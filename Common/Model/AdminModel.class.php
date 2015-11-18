<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-16 12:24:05
 * @Last Modified time: 2015-01-17 16:41:58
 */
// 后台用户模型
namespace Common\Model;
use Think\Model;
class AdminModel extends Model{
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('username','require','用户名不能为空',3),
		array('username','3,20','用户名长度3至20位','','length',3),
		array('username','','用户名已经存在！',0,'unique',3), 
		array('passwd','require','密码不能为空','',3),
		array('passwd','6,20','密码长度为6至20位','','length',3),
		array('passwdc','passwd','两次密码不一致',0,'confirm',3)
		);

	//自动完成
	protected $_auto = array(
		array('passwd','md5',3,'function'),
		array('logintime','time',3,'function'),
		array('loginip','_ip',3,'callback')
		);

	public function _ip()
	{
		return get_client_ip();
	}
}