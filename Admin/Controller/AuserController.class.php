<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-15 15:44:24
 * @Last Modified time: 2015-01-18 17:48:57
 */
// 后台用户管理控制器
namespace Admin\Controller;
use Common\Controller\AuthController;

class AuserController extends AuthController{
	private $db;
	private $model;
	public function _initialize()
	{
	 	parent::_initialize();
	 	$this->db = M('admin');
	 	$this->model = D('Admin');
	} 

	// 默认显示
	public function index()
	{
		$data = $this->db->field('passwd',true)->select();
		// 获得用户角色
		foreach ($data as &$v) {
			if($this->is_admin($v['uid'])){
				$v['title']='超级管理员';
			}else{
				$id = M('auth_group_access')->where(array('uid'=>$v['uid']))->getField('group_id');
				$v['title'] = M('auth_group')->where(array('id'=>$id))->getField('title');
			}
		}
		$this->assign('data',$data);
		$this->display();
	}

	/**
	 * 锁定用户
	 */
	public function lock()
	{
		$uid = I('get.uid',0,'intval');
		// 判断是否为超级管理员
		if($this->is_admin($uid)) $this->error('不能操作超级管理员');
		// 不能操作自己
		$where = array('uid'=>$uid);
		if($this->is_me($uid)) $this->error('不能操作自己');
		$this->db->where($where)->setField('lock',1);
		$this->success('锁定成功');

	}

	/**
	 * 解锁用户
	 */
	public function unlock()
	{
		$uid = I('get.uid',0,'intval');
		if($this->is_me($uid)) $this->error('不能操作自己');
		$this->db->where(array('uid'=>$uid))->setField('lock',0);
		$this->success('解锁成功');
	}

	/**
	 * 删除用户
	 */
	public function del()
	{
		$uid = I('get.uid',0,'intval');
		if($this->is_admin($uid)) $this->error('不能操作超级管理员');
		if($this->is_me($uid)) $this->error('不能操作自己');
		if($this->db->delete($uid)){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}		
	}

	/**
	 * 添加用户
	 */
	public function adduser()
	{
		if(IS_POST){
			if(!$this->model->create()) $this->error($this->model->getError());
			$this->model->add();
			$this->success('添加成功',U('index'));
		}
		$this->display();
	}

	// 修改用户
	public function edit()
	{
		if(IS_POST){
			if(!$this->model->create()) $this->error($this->model->getError());
			$uid = I('post.uid',0,'intval');
			if($this->model->save()){
				if(in_array($uid, C('ADMIN_ID'))){
					session('username',null);
					session('uid',null);
				}
				$this->success('修改成功',U('index'));
			}else{
				$this->error('修改失败',U('index'));
			}
		}
		$this->display();
	}
	/**
	 * 角色组列表
	 * @return [type] [description]
	 */
	public function group()
	{
		$this->data = M('auth_group')->select();
		$this->display();
	}

	/**
	 * 权限列表
	 */
	public function rule()
	{
		$data = M('auth_rule')->select();
		$this->rule = genTree($data,'id','pid');
		$this->display();
	}

	/**
	 * 分配权限
	 */
	public function setrule()
	{
		$uid = I('get.uid',0,'intval');
		if($uid){
			$data = M('auth_rule')->select();
			$this->rule = genTree($data,'id','pid');
			// 查询当前用户所属用户组是否有旧数据
			$id = M('auth_group_access')->where(array('uid'=>$uid))->getField('group_id');
			$db = M('auth_group');
			$ids = $db->where(array('id'=>$id))->getField('rules');
			// 转换数据为数组
			$idarr = explode(',',$ids);
			$this->assign('idarr',$idarr);
			if(IS_POST){
				// 判断是否有数据
				$rules = I('post.rule');
				if(empty($rules)) $this->error('没有数据被选中');
				// 数组转换为字符串
				$str = join(',',$rules);
				$db->where(array('id'=>$id))->setField('rules',$str);
				$this->success('更新成功');
			}
			$this->display();
		}else{
			$this->error('非法访问');
		}
		
	}

	/**
	 * 修改权限
	 */
	public function editrule()
	{
		$id = I('get.id',0,'intval');
		if($id){
			$db = M('auth_rule');
			$info = $db->where(array('id'=>$id))->find();
			$this->assign('rule',$info);
			if(IS_POST){
				// 判断是否存在
				$where = array('name'=>I('post.name'),'id'=>array('neq',$id));
				if($db->where($where)->find()) $this->error('权限URL已经存在');
				if($db->where(array('id'=>$id))->save(I('post.'))){
					$this->success('更新成功');
				}else{
					$this->error('更新失败');
				}
			}
			$this->display();
		}
	}

	/**
	 * 权限列表
	 */
	public function addrule()
	{
		if(IS_POST){
			$model = D('AuthRule');
			if(!$model->create()) $this->error($model->getError());
			$model->add();
			$this->success('添加成功');
		}
		$this->display();
	}

	/**
	 * 删除权限
	 */
	public function delrule()
	{
		$id = I('get.id',0,'intval');
		if($id){
			M('auth_rule')->delete($id);
			$this->success('删除成功');
		}
	}

	/**
	 * 添加角色
	 */
	public function addgroup()
	{
		if(IS_POST){
			$model = D('AuthGroup');
			if(!$model->create()) $this->error($model->getError());
			$model->add();
			$this->success('添加成功',U('group'));
		}
		$this->display();
	}

	/**
	 * 修改角色
	 */
	public function editgroup()
	{
		$id = I('get.id',0,'intval');
		if($id){
			$db = M('auth_group');
			$info = $db->where(array('id'=>$id))->find();
			$this->assign('info',$info);
			if(IS_POST){
				$db->where(array('id'=>$id))->save(I('post.'));
				$this->success('更新成功');
			}
			$this->display();
		}else{
			$this->error('非法访问');
		}
		
	}

	/**
	 * 判断是否为超级管理员
	 */
	private function is_admin($uid)
	{
		if(in_array($uid, C('ADMIN_ID'))){
			return true;
		}
		return false;
	}

	/**
	 * 判断是否为自己
	 */
	private function is_me($uid)
	{
		if($this->db->where(array('uid'=>$uid))->getField('username') == session('username')){
			return true;
		}
		return false;
	}
}