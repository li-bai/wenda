<?php
/**
 * @Author: Administrator
 * @Date:   2015-01-14 22:23:13
 * @Last Modified by:   Administrator
 * @Last Modified time: 2015-01-16 12:15:29
 */
//金币管理控制器

namespace Admin\Controller;
use Common\Controller\AuthController;

class PointController extends AuthController{
		public function index()
		{
			$this->display();
		}
}