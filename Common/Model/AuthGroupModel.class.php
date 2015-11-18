<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-16 15:11:12
 * @Last Modified time: 2015-01-16 15:31:44
 */
// 后台用户组模型
namespace Common\Model;
use Think\Model;
class AuthGroupModel extends Model{
	// 自动验证
	protected $_validate = array(
		array('title','require','角色名不能为空'),
		array('title','2,100','角色名2-100字符','','length'),
		);

	// 自动完成
	protected $_auto = array(
		array('status',1)
		);

}