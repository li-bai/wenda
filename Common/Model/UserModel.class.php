<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-06 17:32:21
 * @Last Modified time: 2015-02-02 00:20:26
 */
// 前台用户模型
namespace Common\Model;
use Think\Model;

class UserModel extends Model{
		protected $tableName = 'user';

		// 自动验证
// array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
		protected $_validate = array(
			array('username','require','用户名不能为空',1,'',1),
			array('username','','用户名已经存在！',0,'unique',1),
			array('passwd','require','密码不能为空',1),
			array('passwd','6,12','密码格式错误,长度为6-12位',1,'length'),
			array('passwdc','require','密码不能为空',1,'',1),
			array('passwdc','passwd','两次密码不一致',0,'confirm'), 
			array('verify','require','验证码不能为空')
			);

		// 自动完成
		protected $_auto = array(
			array('passwd','md5',3,'function'),
            // 注册奖励20金币
            array('point',20),
            array('exp',1),
			array('restime','time',1,'function'),
			array('logintime','time',3,'function'),
			array('loginip','get_client_ip',3,'function')
			);	

		/**
		 * [add_data 添加数据]
		 */
		public function add_data()
		{

			return $this->add();
		}

		/**
		 * 检测用户登陆
		 */
		public function user_login()
		{
			if (!$username = I('post.user')) {
                $this->error = '帐号不能为空';
                return false;
            }
            if (!$pwd = I('post.pwd')) {
                $this->error = '密码不能为空';
                return false;
            }
            if(!$info = $this->where(array('username'=>$username))->find()){
        		$this->error = '用户不存在';
        		return false;
            }
            if(md5($pwd) != $info['passwd']){
        		$this->error = '用户名或密码错误';
        		return false;
            }
            if($info['lock']){
        		$this->error = '该用户已经锁定,请联系管理员';
        		return false;
            }
            // 修改用户信息(IP,时间,经验)
            $data = array();
            // 每天增加登陆经验
            $old = $info['logintime'];
            $zero = strtotime(date('Y-m-d'));
            if($old < $zero) $data['exp'] = $info['exp'] + C('LOGIN_EXP');
            $data['loginip'] = get_client_ip();
            $data['logintime'] = time();
            // 如果选择下次自动登陆
            if(I('post.auto')==='on') {
            		cookie(session_name(),session_id(),3600*24*7);
            }
            // 更新数据
            $this->where(array('uid'=>$info['uid']))->save($data);
            session('user',$username);
            session('uid',$info['uid']);
            unset($info['passwd']);
            return true;
		}		
}