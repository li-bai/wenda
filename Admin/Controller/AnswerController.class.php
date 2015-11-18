<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-13 01:29:16
 * @Last Modified time: 2015-01-18 17:41:48
 */
// 后台回答控制器
namespace Admin\Controller;
use Common\Controller\AuthController;
class AnswerController extends AuthController{
		public $db;
		public function _initialize()
		{
			parent::_initialize();
			$this->db = M('answer');
		}

		// 默认显示
		public function index()
		{
			$s = I('get.s',0,'intval');
			$where = array();
			switch ($s) {
				case '1':
					// 未采纳的回答
					$where['accept'] = 0;
					break;
				case '2':
					// 采纳的回答
					$where['accept'] = 1;
					break;
			}
			
			$count = $this->db->where($where)->count();
			$page = $this->page($count,5);
			$data = $this->db->where($where)->field('anid,content,time')->order('anid desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
			$this->assign('data',$data);
			
			$this->display();
		}
		
		/**
		 * 删除回答
		 */
		public function del()
		{
			$anid = I('get.anid',0,'intval');
			$info = $this->db->where(array('anid'=>$anid))->find();
			$this->db->delete($anid);
			$db = M('user');
			// 判断回答是否采纳
			if($info['accept']==1){
				M('ask')->where(array('asid'=>$info['asid']))->setField('solve',0);
			}
			// 更改用户回答数减一
			$point = C('ANSWER_DEL_POINT')+$info['reward'];
			$userdata = array(
				'point'=>array('exp','point-'.$point),
				'answer'=>array('exp','answer-1')
				);
			$db->where(array('uid'=>$info['uid']))->save($userdata);
			$db->where(array('uid'=>$info['uid']))->setDec('point',C('ANSWER_DEL_POINT'));
		
			$this->success('删除成功');
		}


}