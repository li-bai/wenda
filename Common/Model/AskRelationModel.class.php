<?php
/**
 * @Author: Administrator
 * @Date:   2015-01-14 00:12:55
 * @Last Modified by:   Administrator
 * @Last Modified time: 2015-01-14 00:24:53
 */
namespace Common\Model;
use Think\Model\RelationModel;
class AskRelationModel extends RelationModel{
	protected $tableName="ask";
	protected $_link = array(
		'Answer'  =>  array(
			'mapping_type' =>self::HAS_MANY,
		 	'mapping_name'=>'son',
 			'foreign_key' => 'asid',
    		'relation_key' => 'asid',
			)
		);
}