<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-19 14:37:22
 * @Last Modified time: 2015-01-19 14:47:49
 */
// 搜索控制器
namespace Home\Controller;
use Common\Controller\UserController;

class SearchController extends UserController{
		public function index()
		{
			$this->get_asknum();
			$this->display();
		}
}