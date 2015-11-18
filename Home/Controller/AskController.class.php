<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-09 14:03:38
 * @Last Modified time: 2015-01-09 18:06:21
 */
// 前台用户提问控制器

namespace Home\Controller;
use Common\Controller\UserController;


class AskController extends UserController{
		public function index()
		{
			
			$this->get_asknum();
			// 获得当前用户金币
			$point = M('user')->where(array('uid'=>I('session.uid')))->getField('point');
			$this->point = $point;
			if(IS_POST){
				$model = D('Ask');
				if(!$model->create()) $this->error($model->getError());
				$model->add_data();
				$this->success('提问成功');
			}
			$this->display();
		}

		/**
		 * 异步获得分类
		 */
		public function cate_ajax()
		{
			if(!IS_AJAX) $this->error('页面不存在');
			$cid = I('post.pid');
			// 缓存里的分类
			if($cid) {
				if(!S('category')) D('Category')->up_category();
				// 格式化分类
				$data = array();
				foreach (S('category') as $v) {
					if($v['pid'] == $cid){
						$data[]=$v;
					}
				}
				$this->ajaxReturn($data);
			}
			

		}
}
