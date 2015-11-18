<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-06 17:36:51
 * @Last Modified time: 2015-02-02 17:39:08
 */
// 前台用户注册控制器

namespace Home\Controller;
use Common\Controller\UserController;

class RegisterController extends UserController{
		public function index()
		{
			if(IS_POST){
				// 判断是否已经登陆
				if(session('user') && session('uid')){
					$this->error('您已登陆,请不要重复注册');
				}
				if(!C('RES_ON')) $this->error('网站已暂停注册');
				$model = D('User');
				if(!$model->create($_POST,1)) $this->error($model->getError());
				$model->add_data();
				$this->success('注册成功');
			}
		}

		// 用户AJAX验证
		public function user_ajax()
		{
			if(!IS_AJAX) $this->error('页面不存在');
			// 用户名是否存在
			$on = M('user')->where(array('username'=>I('post.username')))->getField('uid');
			if($on){
				$this->ajaxReturn(0);
			}else{
				$this->ajaxReturn(1);
			}
		}

		// 验证码AJAX验证
		public function code_ajax()
		{
			$verify = new \Think\Verify();
			$code = I('post.verify');
			$this->ajaxReturn($verify->check($code));
		}


		// 显示验证码
		public function code()
		{
			$code = new \Think\Verify(C('CODE_CONFIG'));
			$code->entry();
		}
}