<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-06 16:07:28
 * @Last Modified time: 2015-11-10 02:02:15
 */

// 前台公共控制器
namespace Common\Controller;
use Think\Controller;
class UserController extends Controller{
		public function _initialize()
		{
			if(!C('WEB_ON')){
				header('content-type:text/html;charset=utf-8');
				die('网站升级中,敬请期待');
			}
		}
		
		public function get_asknum()
		{
			// 分配顶级分类数据
			if(!S('topcategory')) D('Category')->up_category();
			$this->assign('topcategory',S('topcategory'));

			// 获得提问总数
			$this->asknum = M('ask')->count();

			// 如果登陆获得个人信息
			if(session('user') && session('uid')){
				$info = M('user')->where(array('uid'=>session('uid')))->find();
				$info['lv'] = $this->exp_lv($info['exp']);
				$info['accnum'] = $this->accept_num($info['accept'],$info['answer']);
				$this->assign('info',$info);
			}
		}

		public function comFun()
		{
			if(!S('ansToday')){$this->ans_today();}
			$this->assign('ansToday',S('ansToday'));

			if(!S('ansHis')){$this->ans_his();}
			$this->assign('ansHis',S('ansHis'));

			if(!S('helpTop')){$this->help_top();}
			$this->assign('helpTop',S('helpTop'));
			
		}

		/**
		 * 获得当日回答问题最多的人
		 */
		public function ans_today()
		{		
				$zero = strtotime(date('Y-m-d'));
				$field = array('a.uid','username','exp','answer','b.accept','face','point','time');
				$data = M('answer')->alias('a')->field($field)->join('__USER__ b ON a.uid=b.uid')->where(array('time'=>array('gt',$zero)))->group('username')->order('count(username) desc')->find();
				if($data){
					$data['lv'] = $this->exp_lv($data['exp']);
					$data['accnum']= $this->accept_num($data['accept'],$data['answer']);
				}
				S('ansToday',$data,60);
		}

		/**
		 * 历史回答最多的人
		 */
		public function ans_his()
		{
			$field = array('a.uid','username','exp','answer','b.accept','face','point','time');
			$data = M('answer')->alias('a')->field($field)->join('__USER__ b ON a.uid=b.uid')->order('answer desc')->find();
			if($data){
				$data['lv'] = $this->exp_lv($data['exp']);
				$data['accnum']= $this->accept_num($data['accept'],$data['answer']);
			}
			S('ansHis',$data,60);
		}
		
		/**
		 * 帮助过最多的人
		 */
		public function help_top()
		{
			$field = array('uid','username','accept');
			$data = M('user')->field($field)->limit(10)->order('accept desc')->select();
			S('helpTop',$data,3600);
		}


		public function new_ajax($status,$message)
		{
			$data = array('message'=>$message);
			if($status){
				$data['status']  = 1;
			}else{
				$data['status'] = 0;
			}
			$this->ajaxReturn($data);
		}

		/**
		 * 经验转换为等级
		 */
		public function exp_lv($exp)
		{
			$lv = C('USER_LV');
			// p($lv);
			for ($i=0; $i < count($lv); $i++) {
				if($exp <= $lv['LV'.$i]){
					return $i;
				}
			}
			if($exp >= $lv['lv20']){
				return 20;
			}
		}

		/**
		 * 采纳率
		 */
		public function accept_num($accept,$total)
		{
			return $total!=0?round(($accept/$total)*100):0;
		}


}