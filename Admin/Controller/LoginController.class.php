<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-15 16:04:43
 * @Last Modified time: 2015-01-17 16:54:51
 */
//后台用户登陆控制器
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
		public function index()
		{
			// 判断是否登陆
			if(session('username') && session('uid')){
				redirect(U('Admin/Index/index'));
			}
			if(IS_POST){
				$code = new \Think\Verify();
				if(!$code->check(I('post.verify'))) $this->error('验证码错误',__ACTION__);
				$db = M('admin');
				$username = I('post.username');
				$pwd = I('post.passwd');
				if(!$username) $this->error('请填写用户名');
				if(!pwd) $this->error('请填写密码');
				$where = array(
					'username' => $username,
					'pwd' => md5($pwd)
					);
				if($info = $db->where($where)->field('lock,uid')->find()){
					// 判断用户是否锁定
					if($info['lock']==1) $this->error('用户已锁定,请联系管理员');
					// 更新用户IP TIME;
					$db->where(array('uid'=>$info['uid']))->setField(array('logintime'=>time(),'loginip'=>get_client_ip()));
					session('username',$username);
					session('uid',$info['uid']);
					redirect(U('Admin/Index/index'));
				}

			}
			$this->display();
		}

		public function code()
		{
			$code = new \Think\Verify(C('CODE'));
			$code->entry();
		}

		/**
		 * 退出
		 */
		public function out()
		{
			session('username',null);
			session('uid',null);
			$this->success('退出成功',U('Login/index'));
		}

}