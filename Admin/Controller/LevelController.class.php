<?php
/**
 * @Author: Administrator
 * @Date:   2015-01-15 01:20:05
 * @Last Modified by:   Administrator
 * @Last Modified time: 2015-01-16 12:10:34
 */
// 经验等级控制器
namespace Admin\Controller;
use Common\Controller\AuthController;

class LevelController extends AuthController{
		public function index()
		{
			$this->lv = C('USER_LV');
			$this->display();
		}

}