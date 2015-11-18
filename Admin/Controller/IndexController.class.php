<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-13 00:41:15
 * @Last Modified time: 2015-01-17 13:36:09
 */
// 后台首页控制器
namespace Admin\Controller;
use Common\Controller\AuthController;
class IndexController extends AuthController{
		public function index()
		{
			$weeks = array('日','一','二','三','四','五','六');
			$this->assign('week',$weeks[date('w')]);
			$this->display();
		}

		public function copy()
		{
			$info = M()->query('select version()');
			$mysql = $info? $info[0]['version()']: @mysql_get_server_info();
			$this->data = M('admin')->where(array('uid'=>session('uid')))->field('loginip,logintime')->find();
			$this->assign('mysql',$mysql);
			$this->display();
		}
}