<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-15 16:29:07
 * @Last Modified time: 2015-01-18 18:39:55
 */
return array(
	'CODE'=>array(
		'fontttf' => '1.ttf',
		'fontSize' => 15,
		'imageW' => 80,
		'imageH' => 24,
		'length' => 1,
		'useCurve'=>false,
		'useNoise'=>false
		),
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
	    '__STATIC__' => __ROOT__ . '/Public/Static',
	    '__IMG__'    => __ROOT__ . '/Public/' .MODULE_NAME. '/Images',
	    '__CSS__'    => __ROOT__ . '/Public/' .MODULE_NAME. '/Css',
	    '__JS__'     => __ROOT__ . '/Public/' .MODULE_NAME. '/Js',
	),
);