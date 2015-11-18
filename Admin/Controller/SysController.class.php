<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-15 13:03:23
 * @Last Modified time: 2015-01-17 01:25:41
 */

// 系统 信息控制器
namespace Admin\Controller;
use Common\Controller\AuthController;
class SysController extends AuthController{
		/**
		 * 网站配置
		 */
		public function webconfig()
		{
			$this->display();
		}

}