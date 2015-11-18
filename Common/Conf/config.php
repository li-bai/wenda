<?php
return array(
	'DB_TYPE'   => 'mysqli', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'wenda', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'root', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'hd_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	// 'DB_TYPE'   => 'mysqli', // 数据库类型
	// 'DB_HOST'   => '10.0.16.16', // 服务器地址
	// 'DB_NAME'   => '87869808-409121_mysq_6xcs6f36', // 数据库名
	// 'DB_USER'   => '46wdI8oi', // 用户名
	// 'DB_PWD'    => 'K972M8u7GK3x', // 密码
	// 'DB_PORT'   => 4066, // 端口
	// 'DB_PREFIX' => 'hd_', // 数据库表前缀 
	// 'DB_CHARSET'=> 'utf8', // 字符集

	// 自动加载的配置项
	'LOAD_EXT_CONFIG' => 'user',

	'TMPL_ACTION_ERROR'     =>  APP_PATH.'Public/err/success.html', 
	// 默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   =>  APP_PATH.'Public/err/success.html',
	'SHOW_PAGE_TRACE' =>false, 

	// 自动加载的标签
	'TAGLIB_PRE_LOAD'     => 'Custom\\Test',


	// 权限控制
	'AUTH_CONFIG' => array(
		'AUTH_USER' => 'hd_admin'
		),

	'ADMIN_ID' => array(1), // 不需要认证的用户UID
	// 不需要验证的方法
	'NO_AUTH' => array(
		'Admin/Index/index',
		'Admin/Index/welcome'
	),

	// 上传头像配置
  'FACE_CONFIG' => array (
		'maxSize' => 3145728,
		'rootPath' => 'Public/',
		'savePath' => 'Face/',
		'exts' => 
		    array (
		      0 => 'jpg',
		      1 => 'gif',
		      2 => 'png',
		      3 => 'jpeg',
		    ),
			'subName' => '',
	),
);