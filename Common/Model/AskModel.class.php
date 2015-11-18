<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-09 17:54:40
 * @Last Modified time: 2015-02-02 00:20:16
 */

// 前台用户提问控制器
namespace Common\Model;
use Think\Model;

class AskModel extends Model{
	protected $tablName = 'ask';

	// 自动验证
	protected $_validate = array(
		array('content','require','内容不能为空'),
		array('cid','require','问题分类不能为空')
		);

	// 自动完成
	protected $_auto = array(
		array('time','time',1,'function'), // 提问时间
		array('uid','_uid',1,'callback') // 用户ID
		);

	/**
	 * 自动完成用户UID
	 * @return [type] [description]
	 */
	public function _uid()
	{
		return session('uid');
	}

	/**
	 * 添加数据
	 */
	public function add_data()
	{
		if($this->add()){
			// 用户增加经验
			$data['exp'] = array('exp','exp+'.C('ASK_EXP'));
			// 提问数增加 
			$data['ask'] = array('exp','ask+1');
			// 减去悬赏金币
			$data['point'] = array('exp','point-'.I('post.reward'));
			M('user')->where(array('uid'=>session('uid')))->save($data);
			return true;
		}
	}

	
}