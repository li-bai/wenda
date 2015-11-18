<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-16 17:07:57
 * @Last Modified time: 2015-01-17 22:20:32
 */
// 后台用户权限节点表

namespace Common\Model;
use Think\Model;

class AuthRuleModel extends Model{
	// 自动验证
	protected $_validate = array(
		array('title','require','权限名称不能为空'),
		array('name','require','权限URL不能为空'),
		array('name','','权限URL已经存在！',0,'unique',1), 
		);
}