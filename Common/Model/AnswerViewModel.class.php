<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-12 00:25:41
 * @Last Modified time: 2015-02-02 12:53:47
 */
// 回答视图模型
namespace Common\Model;
use Think\Model\ViewModel;
class AnswerViewModel extends ViewModel{
		public $viewFields = array(
			'Answer'=>array('*'),
			'Ask'=>array(
				'answer','asid','cid',
				'_type'=>'LEFT',
				'_on'=>'Answer.asid=Ask.asid'
				),
			'Category'=>array(
				'title',
				'_type'=>'LEFT',
				'_on'=>'Ask.cid=Category.cid'
				)
		);
}