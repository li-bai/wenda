<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-06 00:09:07
 * @Last Modified time: 2015-04-18 23:57:02
 */
/**
 * 登陆控制器
 */
namespace Home\Controller;
use Common\Controller\UserController;

class LoginController extends UserController{
		
		public function index(){
			if(session('user') && session('uid')){
					$this->error('您已登陆,请不要重复登陆');
			}
			if(!IS_AJAX) $this->error('页面不存在');
				$model = D('User');
				// 检查登陆(用户名是否存在,密码是否正确)
				if(!$model->user_login()) $this->error($model->getError());
				$this->success('登陆成功');
		}

		// 退出 操作
		public function out()
		{
			session('user',null);
			session('uid',null);
			$this->success('退出成功',U('Home/Index/index'));
		}
}