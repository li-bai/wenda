<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-15 17:30:13
 * @Last Modified time: 2015-01-26 16:17:39
 */
// 权限控制 控制器
/*
 DROP TABLE IF EXISTS `hd_auth_rule`;
CREATE TABLE `hd_auth_rule` (  
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,  
    `name` char(80) NOT NULL DEFAULT '',  
    `title` char(20) NOT NULL DEFAULT '',  
    `type` tinyint(1) NOT NULL DEFAULT '1',    
    `status` tinyint(1) NOT NULL DEFAULT '1',  
    `condition` char(100) NOT NULL DEFAULT '',  # 规则附件条件,满足附加条件的规则,才认为是有效的规则
    PRIMARY KEY (`id`),  
    UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- hd_auth_group 用户组表， 
-- id：主键， title:用户组中文名称， rules：用户组拥有的规则id， 多个规则","隔开，status 状态：为1正常，为0禁用
-- ----------------------------
 DROP TABLE IF EXISTS `hd_auth_group`;
CREATE TABLE `hd_auth_group` ( 
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, 
    `title` char(100) NOT NULL DEFAULT '', 
    `status` tinyint(1) NOT NULL DEFAULT '1', 
    `rules` char(80) NOT NULL DEFAULT '', 
    PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- ----------------------------
-- hd_auth_group_access 用户组明细表
-- uid:用户id，group_id：用户组id
-- ----------------------------
DROP TABLE IF EXISTS `hd_auth_group_access`;
CREATE TABLE `hd_auth_group_access` (  
    `uid` mediumint(8) unsigned NOT NULL,  
    `group_id` mediumint(8) unsigned NOT NULL, 
    UNIQUE KEY `uid_group_id` (`uid`,`group_id`),  
    KEY `uid` (`uid`), 
    KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 */
namespace Common\Controller;
use  Think\Controller;
class AuthController extends Controller{
	public function _initialize()
	{
        // 判断是否有session
        if(!session('username') || !session('uid')){
            $this->error('非法访问',U('Admin/Login/index'));
        }
        // 判断是否为超级管理员
        if(in_array(session('uid'),C('ADMIN_ID'))){
            return true;
        }
        $act = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
        // 设置不需要验证的页面
        if(in_array($act, C('NO_AUTH'))){
            return true;
        }
		$auth = new \Think\Auth();
		if(!$auth->check($act,session('uid'))){
			$this->error('没有权限',U('Index/copy'));
		}
	}


    /**
     * 分页配置
     */
    protected function page($count,$num)
    {
        $page = new \Think\Page($count,$num);
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('first','第一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        return $page;
    }

    /**
     * 修改配置项
     * @return [type] [description]
     */
    public function editConfig()
    {

        $path = CONF_PATH . 'user.php';
        $config = include $path;
        // 修改配置项
        if(IS_POST){
            // 查看是否存在 UWER_LV
            $tmp = array();
            if(I('post.lv1')){
               $arr = I('post.');
               foreach ($arr as $k => $v) {
                   if(substr($k, 0,2) == 'lv'){
                       $tmp['USER_LV'][strtoupper($k)]=$v;
                   }else{
                       $tmp[$k]=$v;
                   }
               } 
            }else{
                $tmp = I('post.');
            }
            $new = array_merge($config,$tmp);
            $str = "<?php \r\n return ". var_export($new,true). ";\r\n?>";
            if(file_put_contents($path, $str)){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }

    }


    
}