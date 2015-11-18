<?php
/**
 * @Author: Zhao Yang[873777808@qq.com]
 * @Date:   2015-01-06 15:13:56
 * @Last Modified time: 2015-01-27 13:11:51
 */
function P($var)
{
	header('Content-type:text/html; charset=utf8');
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

// 无限级分类
function genTree($items, $id = 'id', $pid = 'pid', $son = 'son') 
{
		$tree = array();
		//格式化的树
		$tmpMap = array();
		//临时扁平数据
		foreach ($items as $item) {
			$tmpMap[$item[$id]] = $item;
		}
		foreach ($items as $item) {
			if (isset($tmpMap[$item[$pid]])) {
				$tmpMap[$item[$pid]][$son][$item[$id]] = &$tmpMap[$item[$id]];
			} else {
				$tree[$item[$id]] = &$tmpMap[$item[$id]];
			}
		}
		return $tree;
}

/**
 * 查询所有父级
 * @param  [type] $arr [description]
 * @param  [type] $id  [description]
 * @param  [type] $pid [description]
 * @return [type]      [description]
 */
function familytree($arr,$id,$idname='id',$pidname = 'pid') {
    $tree = array();
    foreach($arr as $v) {
        if($v[$idname] == $id) {// 判断要不要找父栏目
            if($v[$pidname] > 0) { // parnet>0,说明有父栏目
                $tree = array_merge($tree,familytree($arr,$v[$pidname],$idname,$pidname));
            }
            $tree[] = $v; // 以找到上地为例
        }
    }
    return $tree;
}

// 查询子级
function soncate($arr,$id,$pidname='pid'){
	$tmp = array();
	foreach ($arr as $key => $value) {
		if($value[$pidname] == $id)
			$tmp[]=$value;
	}
	return $tmp?$tmp:soncate($arr,$arr[$id][$pidname]);
}

/**
 * 获得子孙集ID
 * @param  [type] &$cate [description]
 * @param  [type] $pid   [description]
 * @return [type]        [description]
 */
function getChild(&$cate , $pid){
		$arr = array();
		foreach ($cate as $k => $v) {
		   if ($v['pid'] == $pid) {
		       $arr[] = $v['cid'];
		       unset($cate[$k]);
		       $arr = array_merge(getChild($cate,$v['cid']),$arr);
		   }
		}
		return $arr;
}

/**
 * 删除文件夹目录
 * @param  [type] $dirname [description]
 * @return [type]          [description]
 */
function deldir($dirname){
	if(!is_dir($dirname))return;
	//$dirname=rtrim(str_replace('\\', '/', $dirname),'/').'/';
	foreach (glob($dirname.'\*') as  $file) {
		is_dir($file)?deldir($file):unlink($file);
	}
	rmdir($dirname);
}

/**
 * 格式化数据为一维数组添加 level
 * @param  [type]  $categorys [description]
 * @param  integer $catId     [description]
 * @param  integer $level     [description]
 * @return [type]             [description]
 */
function getSubs($categorys,$catId=0,$level=0){
    $subs=array();  
    foreach($categorys as $item){  
        if($item['pid']==$catId){
            $item['level']=$level;
            $subs[]=$item;
            $subs=array_merge($subs,getSubs($categorys,$item['cid'],$level+1));
        }
              
    }  
    return $subs;  
} 
