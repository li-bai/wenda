<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-11 17:10:38
 * @Last Modified time: 2015-01-11 17:18:45
 */
// 提问 类别视图模型
namespace Common\Model;
use Think\Model\ViewModel;

class AskViewModel extends ViewModel{
	public $viewFields = array(
		'Ask'=>array('*'),
		'Category'=>array(
			'title',
			'_on'=>'Ask.cid=Category.cid'
		),
	);
}