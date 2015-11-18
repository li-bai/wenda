<?php
namespace Home\Controller;
use Common\Controller\UserController;
class IndexController extends UserController {
    	public function index(){
            $this->ans_today();
    		if(!S('category')) D('Category')->up_category();
    		// 格式化问题分类
            $category = S('category');
    		$this->category = genTree($category,'cid','pid');
    		$this->get_asknum();
    		// 未解决低悬赏问题(5条)
    		$where = array(
    			'solve'=>0,
    			'reward'=>array('lt',20)
    			);
    		$db = M('ask');
    		$this->low = $db->where($where)->order('time desc')->limit(5)->select();

            // 未解决高悬赏问题(5条)
            $where['reward']= array('egt',20);
            $this->tall = $db->where($where)->order('time desc')->limit(10)->select();

            // 当日回答最多的人
            $this->comFun();
        	$this->display();
    	}
}