<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-11 00:57:20
 * @Last Modified time: 2015-01-19 12:56:27
 */
// 分类问题控制器
namespace Home\Controller;
use Common\Controller\UserController;

class ListController extends UserController{
		public function index()
		{
			$this->get_asknum();
			// 获得分类数据
			$cid = I('get.cid',0,'intval');
			if(!S('category')) D('category')->up_category();
			$this->list = familytree(S('category'),$cid,'cid','pid');
			$this->soncate = soncate(S('category'),$cid);
			$this->comFun();
			
			// 当前 分类所有子集分类ID
			$where = array();
			if($cid){
				$category = S('category');
				$sonid = getChild($category,$cid);
				// 如果有子集
				if(!empty($sonid)){
					$where['cid'] = array('in',$sonid);
				}else{
					$where['cid']= $cid;
				}
			}

			// 待解决问题列表
			$s = I('get.s',0,'intval');
			$where = array();
			switch ($s) {
				case '1':
					$where['solve'] = 1;
					break;
				case '2':
					$where['solve'] = 0;
					$where['reward'] = array('egt',20);
					break;
				case '3':
					$where['answer'] = 0;
					break;
				default:
					$where['solve'] = 0;
					break;
			}
			// 分页
			$count = M('ask')->where($where)->count();
			$page =  new \Think\Page($count,10); //分页
			$page->setConfig('prev','上一页');
			$page->setConfig('next','下一页');
			$page->setConfig('first','第一页');
			$page->setConfig('last','尾页');
			$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

			$data = D('AskView')->where($where)->limit($page->firstRow.','.$page->listRows)->order('time desc')->select();

			$this->assign('page',$page->show());
			$this->assign('data',$data);
			$this->display();
		}
}