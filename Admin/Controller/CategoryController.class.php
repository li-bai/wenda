<?php
/**
 * @Author: Administrator
 * @Date:   2015-01-14 14:37:48
 * @Last Modified by:   Administrator
 * @Last Modified time: 2015-01-31 02:30:55
 */
//分类控制器
namespace Admin\Controller;
use Common\Controller\AuthController;
class CategoryController extends AuthController{
		public $db;
		public $model;

		public function _initialize()
		{
			parent::_initialize();
			$this->db = M('category');
			$this->model = D('Category');
		}

		public function index()
		{
			if(!S('category')) D('Category')->up_category();
			$tree = new \Custom\Tree();
			// $tre = $tree->Tree(S('category'),'cid');
			// p($tre);
			// die;
			$data = $tree->oneTree(S('category'));
			$this->assign('data',$data);
			$this->display();
		}

		/**
		 * 添加分类
		 */
		public function add_cate()
		{
			if(!S('category')) D('Category')->up_category();
			$tree = new \Custom\Tree();
			$data = $tree->oneTree(S('category'));
			$this->assign('data',$data);
			if(IS_POST){
				if(!I('post.title')) $this->error('分类名称不能为空');
				// 添加子分类
				$cate['pid'] = I('post.pid',0,'intval');
				$cate['title'] = I('post.title');
				$this->db->add($cate);
				$this->model->up_category();
				$this->success('添加成功',U('index'));

			}
			$this->display();			
		}

		/**
		 * 添加主分类
		 */
		public function add_top_cate()
		{
			if(IS_POST){
				if(!$title = I('post.title')) $this->error('分类名称不能为空');
				$data['pid']=0;
				$data['title']=$title;
				$this->db->add($data);
				$this->model->up_category();
				$this->success('添加成功',U('index'));
			}
			$this->display();
		}

		/**
		 * 修改分类
		 */
		public function edit()
		{
			$cid = I('get.cid',0,'intval');
			$cate = S('category');
			$this->assign('cate',$cate[$cid]['title']);
			if(IS_POST){
				if(!$title = I('post.title')) $this->error('分类名称不能为空');
				$data['title'] = $title;
				$data['cid'] = $cid;
				$this->db->save($data);
				// 更新缓存
				$this->model->up_category();
				$this->success('修改成功',U('index'));
			}	
			$this->display();
		}

		/**
		 * 删除动作
		 */
		public function del()
		{
			$cid = I('get.cid',0,'intval');
			if($cid){
				// 查找当前分类下所有子集ID
				$data = S('category');
				$son = getChild($data,$cid);
				if($son){
					$ids = join(',',$son).','.$cid;
				}else{
					$ids = $cid;
				}
				// 删除分类
				$this->db->delete($ids);
				$this->model->up_category();
				$this->success('删除成功',U('index'));
			}
		}

}