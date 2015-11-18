<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-09 18:51:21
 * @Last Modified time: 2015-01-18 17:26:14
 */
// 问题操作控制器
namespace Home\Controller;
use Common\Controller\UserController;

class ShowController extends UserController{
		public function index()
		{
			$cid = I('get.cid',0,'intval');
			$asid = I('get.asid',0,'intval');
			$db = M('ask');
			$info = $db->where(array('asid'=>$asid,'cid'=>$cid))->find();
			if($info){
				// 分类 与总提问数
				$this->get_asknum();
				$this->comFun();
				// 获得分类数据
				if(!S('category')) D('category')->up_category();
				$category = S('category');
				$this->list = familytree($category,$cid,'cid','pid');

				// 与用户表关联(提问)
				$ask = $db->alias('a')->join('__USER__ b ON a.uid =b.uid')->where(array('asid'=>$asid))->find();
				$ask['lv'] = $this->exp_lv($ask['exp']);
				$this->ask = $ask;

				// 用户表与答案表关联 (获得当前问题所有回答)
				$field = array('anid','content','asid','username','a.uid','face','time','a.accept','exp','b.accept','b.answer');

				// 设置分页
				$model = M('answer');

				// 满意回答
				$satisfy = $model->field($field)->alias('a')->join('__USER__ b ON a.uid =b.uid')->where(array('asid'=>$asid,'a.accept'=>1))->find();
				if($satisfy){
					$satisfy['lv'] = $this->exp_lv($satisfy['exp']);
					$satisfy['accnum']= $this->accept_num($satisfy['accept'],$satisfy['answer']);
					$this->assign('satisfy',$satisfy);
				}
				$count = $model->field($field)->alias('a')->join('__USER__ b ON a.uid =b.uid')->where(array('asid'=>$asid,'a.accept'=>0))->count(); 
				$page =  new \Think\Page($count,2); //分页
				$page->setConfig('prev','上一页');
				$page->setConfig('next','下一页');
				$page->setConfig('first','第一页');
				$page->setConfig('last','尾页');
				$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
				$answer = $model->field($field)->alias('a')->join('__USER__ b ON a.uid =b.uid')->where(array('asid'=>$asid,'a.accept'=>0))->limit($page->firstRow.','.$page->listRows)->select();
				$show = $page->show();
				$this->assign('num',$count);
				$this->assign('page',$show);
				$this->assign('answer',$answer);

				// 待解决的相关问题
				
				// 当前 分类所有子集分类ID
				$where = array('asid'=>array('neq',$asid),'solve'=>0);
				$sonid = getChild($category,$cid);
				// 如果有子集
				if(!empty($sonid)){
					$where['cid'] = array('in',$sonid);
				}else{
					$where['cid']= $cid;
				}
				$rand = $db->where($where)->order('RAND()')->find();
				$this->assign('rand',$rand);
				$this->display();
			}else{
				$this->error('非法');
			}
		}

		/**
		 * 回答表单处理
		 */
		public function answer()
		{
			if(IS_POST){
				// 判断问题是否可以添加回答
				$where = array(
					'solve'=>0,
					'asid'=>I('post.asid',0,'intval'),
					'uid'=>array('neq',session('uid'))
					);
				if(M('ask')->where($where)->find()){
					$model = D('Answer');
					if(!$model->create()) $this->error($model->getError());
					$model->add_data();
					$this->success('回答成功');
				}
			}
		}

		/**
		 * 采纳回答
		 */
		public function accept()
		{
			// 修改答案为采纳
			$anid = I('get.anid',0,'intval');
			$asid = I('get.asid',0,'intval');

			// 判断问题 是否存在
			$where = array(
				'asid'=>$asid,
				'solve'=>0,
				'uid'=>array('eq',session('uid'))
				);
			$ask = M('ask');
			if($askinfo = $ask->where($where)->find()){
				
				// 判断回答是否存在
				$answerwhere = array(
					'asid'=>$asid,
					'accept'=>array('neq',1),
					'anid'=>$anid,
					'uid'=>array('neq',session('uid'))
					);
				$answer = M('answer');
				if($answerinfo = $answer->where($answerwhere)->find()){
					// 更新回答为 采纳
					$answer->where(array('anid'=>$anid))->setField('accept',1);

					// 更新问题为 解决
					$ask->where(array('asid'=>$asid))->setField('solve',1);

					// 更新提问者经验 金币 
					$user = M('user');
					$askdata = array(
						'exp'=>array('exp','exp+'.C('SOLVE_EXP')),
						'point'=>array('exp','point+'.C('ASK_ACCEPT_POINT')),
						);
					$user->where(array('uid'=>session('uid')))->save($askdata);

					// 更新 回答者分数经验 金币 采纳数
					$data = array(
						'accept'=>array('exp','accept+1'),
						'exp'=>array('exp','exp+'.C('SOLVE_EXP')),
						'point'=>array('exp','point+'.$askinfo['reward']+C('ANSWER_ACCEPT_POINT')
						));
					$user->where(array('uid'=>$answerinfo['uid']))->save($data);
					$this->success('采纳成功');
				}
			}else{
				$this->error('非法');
			}
		}
}