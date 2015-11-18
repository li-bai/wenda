<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-06 14:54:22
 * @Last Modified time: 2015-01-06 16:04:32
 */
// 分类模型
namespace Common\Model;
use Think\Model;
class CategoryModel extends Model{
	public $tableName = 'category';


	// 获取所有分类
	public function up_category()
	{
		$data = $this->select();
		$top = array();
		$all = array();
		foreach ($data as $v) {
			if($v['pid']==0){
				$top[$v['cid']] = $v;
			}
			$all[$v['cid']] = $v;
		}
		// 设置缓存
		S('topcategory',$top,0);
		S('category',$all,0);
	}
}