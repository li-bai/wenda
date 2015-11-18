<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-13 01:25:11
 * @Last Modified time: 2015-02-26 19:43:03
 */
// 后台问题控制器
namespace Admin\Controller;
use Common\Controller\AuthController;
class AskController extends AuthController{
		public $db;
		public function _initialize()
		{
			parent::_initialize();
			$this->db = M('ask');
		}


		public function index()
		{
			$s = I('get.s',0,'intval');
			$where = array();
			switch ($s) {
				case '1':
					// 待解决
					$where['solve'] = 0;
					break;
				case '2':
					// 已解决
					$where['solve'] = 1;
					break;
				case '3':
					// 0回答
					$where['answer'] = 0;
					break;
			}
			$count = $this->db->where($where)->count();
			$page = $this->page($count,5);
			$data = $this->db->where($where)->order('asid desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
			$this->assign('data',$data);
			$this->display();
		}

		//删除问题
		public function del()
		{
			$asid = I('get.asid',0,'intval');
			// 查找所有回答 
			$data = D('AskRelation')->relation(true)->find($asid);
			// 查找是否有回答
			$db = M('user');
			if($son = $data['son']){
				foreach ($son as $v) {
					// 查找回答是否采纳
					if($v['accept']==1){
						// 扣除悬赏 采纳扣金币 回答扣金币
						$db->where(array('uid'=>$v['uid']))->setDec('point',$data['reward']+C('ACCEPT_DEL_ANSWER')+C('ANSWER_DEL_POINT'));
					}else{
						$db->where(array('uid'=>$v['uid']))->setDec('point',C('ANSWER_DEL_POINT'));
					}
				}
			}
			// 扣除提问者金币
			$db->where(array('uid'=>$data['uid']))->setDec('point',C('ASK_DEL_POINT'));
			// 删除 问题 级回答数据
			$data = D('AskRelation')->relation(true)->delete($asid);
			$this->success('删除成功');
		}
}