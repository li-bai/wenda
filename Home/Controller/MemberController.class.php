<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-11 23:00:43
 * @Last Modified time: 2015-02-15 16:17:25
 */
// 个人中心控制器
namespace Home\Controller;
use Common\Controller\UserController;

class MemberController extends UserController{
		private $uid;
		public function _initialize()
		{
			$this->uid = I('get.uid',0,'intval');
			if(!$this->uid){$this->error('非法');}
			$this->_left();
			$this->get_asknum();
		}

		public function index()
		{
			// 用户提问数据
			$this->askdata = D('AskView')->where(array('uid'=>$this->uid))->order('time desc')->limit(5)->select();

			// 用户回答数据
			$this->answerdata = D('AnswerView')->where(array('uid'=>$this->uid))->order('time desc')->limit(5)->select();
			$this->display();
		}


		/**
		 * 左侧用户信息
		 */
		private function _left(){
			// 获得用户信息
			if($userinfo = M('user')->where(array('uid'=>$this->uid))->field('uid,username,ask,answer,accept,point,exp,face')->find())
			{
				$userinfo['sex'] = $this->uid == session('uid')?'我':'TA';
				$userinfo['lv'] = $this->exp_lv($userinfo['exp']);
				// 距离下级多少经验
				$lv = C('USER_LV');
				$userinfo['next'] = $userinfo['lv']<20?$lv['LV'.($userinfo['lv']+1)] - $userinfo['exp']:0;
				$userinfo['accnum'] = $this->accept_num($userinfo['accept'],$userinfo['answer']);
				$this->assign('left',$userinfo);
			}else{
				$this->error('用户不存在');
			}
		}

		/**
		 * 我的提问
		 */
		public function myask()
		{
			$s = I('get.s',0,'intval');
			$where = array(
				'solve'=>$s,
				'uid'=>$this->uid
				);
			$model = D('AskView');
			$count = $model->where($where)->count();
			$page = $this->page($count,10);
			$data = $model->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
			$this->assign('data',$data);
			$this->display();
		}

		/**
		 * 我的回答
		 */
		public function myanswer()
		{
			$s = I('get.s',0,'intval');
			$where['uid'] = $this->uid;
			if($s == 1) $where['accept'] = 1;
			$model = D('AnswerView');
			$count = $model->where($where)->count();
			// 分页
			$page = $this->page($count,10);
			$data = $model->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
			$this->assign('data',$data);
			$this->display();
		}

		/**
		 * 我的等级
		 */
		public function mylevel()
		{
			$this->assign('lv',C('USER_LV'));
			$this->display();
		}

		/**
		 * 我的金币
		 */
		public function mygolb()
		{
			$this->display();
		}

		/**
		 * 我的头像
		 */
		public function myface()
		{

			// 判断是否为当前用户
			if(!session('user') || !session('uid')){
				$this->error('您还没有登陆');
			}
			if(session('uid')!=$this->uid){
					$this->error('非法操作');
			}
			if (IS_POST) {
				// 删除原头像文件
				$face = M('user')->where(array('uid'=>$this->uid))->getField('face');
				if(is_file($face)){
					unlink($face);
				}
            //头像文件
            $file = $_POST['img_face'];
            $dst_image = imagecreatetruecolor(250, 250);
            $fileInfo = getimagesize($file);
            switch ($fileInfo[2]) {
                case 1://gif
                    $src_image = imagecreatefromgif($file);
                    break;
                case 2://jpeg
                    $src_image = imagecreatefromjpeg($file);
                    break;
                case 3://png
                    $src_image = imagecreatefrompng($file);
                    break;
            }
            //裁切图片
            $dst_x = $dst_y = 0;
            $dst_w = $dst_h = 250;
            $src_x = $_POST['x1'];
            $src_y = $_POST['y1'];
            $src_w = $_POST['w'];
            $src_h = $_POST['h'];
            imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
            switch ($fileInfo[2]) {
                case 1:
                    imagegif($dst_image, $file, 100);
                    break;
                case 2:
                    imagejpeg($dst_image, $file, 100);
                    break;
                case 3:
                    imagepng($dst_image, $file, 100);
                    break;
            }
            if (is_file($file) && getimagesize($file)) {
               // 组合新目录 
               $newpath = dirname(dirname($file));
               $newfile = str_replace('\\', '/',$newpath.'/'.basename($file));
               rename($file, $newfile);
               // 删除原文件夹
               deldir(dirname($file));
               M("user")->save(array('uid' => session('uid'), 'face' => $newfile));
               $this->success('保存成功');
            }
        } else {
            $this->display();
        }
		}


		/**
		 * uploadify 头像上传处理
		 */
		public function setFace()
		{
			C('FACE_CONFIG.savePath','Face/'.$this->uid.'/');
			$upload = new \Think\Upload(C('FACE_CONFIG'));// 实例化上传类
         $images = $upload->upload();
         //判断是否有图
         if(!empty($images)){
         	foreach ($images as $file) {
         		$url = $file['savepath'].$file['savename'];
         	}
         	// 缩略图
         	$thumb = new \Think\Image();
         	$thumb ->open($upload->rootPath.$url);
         	$mini = $thumb->thumb(250, 250,\Think\Image::IMAGE_THUMB_NORTHWEST)->save($upload->rootPath.$url);
         	$this->new_ajax(1,$upload->rootPath.$url);
         }else{
            $this->new_ajax(0,$upload->getError());
         }
		}
		

		/**
		 * 分页配置
		 */
		private function page($count,$num)
		{
			$page = new \Think\Page($count,$num);
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$page->setConfig('first','第一页');
			$page->setConfig('last','尾页');
			$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
			return $page;
		}
}