<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-13 01:12:21
 * @Last Modified time: 2015-01-15 23:55:48
 */
// 用户管理控制器
namespace Admin\Controller;
use Common\Controller\AuthController;

class UserController extends AuthController{
		public $db;
		public function _initialize()
		{
			parent::_initialize();
			$this->db = M('user');
		}
		public function index()
		{
			$this->data = $this->db->order('restime desc,logintime desc')->select();
			$this->display();
		}

		/**
		 * 锁定用户
		 * @return [type] [description]
		 */
		public function lock()
		{
			$uid = I('get.uid',0,'intval');
			if($uid){
				$this->db->where(array('uid'=>$uid))->setField('lock',1);
				$this->success('操作成功');
			}
		}

		/**
		 * 解锁用户
		 * @return [type] [description]
		 */
		public function unlock()
		{
			$uid = I('get.uid',0,'intval');
			if($uid){
				$this->db->where(array('uid'=>$uid))->setField('lock',0);
				$this->success('操作成功');
			}
		}
}