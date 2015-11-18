<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-10 15:18:59
 * @Last Modified time: 2015-01-10 22:40:43
 */
// 回答模块
namespace Common\Model;
use Think\Model;

class AnswerModel extends Model{
	protected $tableName = 'answer';

	/**
	 * 自动验证
	 * @var array
	 */
	protected $_validate = array(
		array('content','require','内容不能为空'),
		array('content','1,250','内容长度不能超过250字符',1,'length')
		);

	protected $_auto = array(
		array('time','time',3,'function'),
		array('uid','_uid',3,'callback'),
		array('answer','answer+1',1)
		);

	public function _uid()
	{
		return session('uid');
	}


	/**
	 * 添加回答数据
	 */
	public function add_data()
	{	
		if($this->add()){
			// 改变USER数据中的回答数 经验
			$data = array(
				'answer'=>array('exp','answer+1'),
				'point'=>array('exp','point+'.C('ANSEER_POINT')),
				'exp'=>array('exp','exp+'.C('ANSWER_EXP'))
				);
			M('user')->where(array('uid'=>session('uid')))->save($data);
			// 改变当前问题的回答数
			M('ask')->where(array('asid'=>I('post.asid')))->setInc('answer',1);
			return true;
		}
		return false;
	}

}