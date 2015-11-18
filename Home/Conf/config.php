<?php
return array(
	// 前台用户注册验证码配置
	'CODE_CONFIG' => array(
		'length' => 1,			// 长度
		'imageW' => 100,
		'imageH' => 30,
		
		'useCurve'=>false,
		'fontSize'=>16,
		'codeSet'=>'1234567890',
		// 'expire'=>'5'
		),
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
	    '__STATIC__' => __ROOT__ . '/Public/Static',
	    '__IMG__'    => __ROOT__ . '/Public/' .MODULE_NAME. '/Images',
	    '__CSS__'    => __ROOT__ . '/Public/' .MODULE_NAME. '/Css',
	    '__JS__'     => __ROOT__ . '/Public/' .MODULE_NAME. '/Js',
	),
	
);