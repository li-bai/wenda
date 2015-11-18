<?php 
namespace Custom;
class Tree
{

  private $id;
  private $pid;
  private $son;

  public function __construct($id='cid',$pid='pid',$son='son')
  {
      $this->id = $id;
      $this->pid = $pid;
      $this->son = $son;
  }

  //组合一维数组
  public function oneTree ($cate, $pid = 0, $level = 0) {
    $arr = array();
    foreach ($cate as $k => $v) {
        if ($v[$this->pid] == $pid) {
            $v['on'] = $this->is_have($cate,$v['cid']);
            $cid = $this->getLast($cate,$pid);
            $v['level'] = $level + 1;
            if($level>=1){
                if($v['cid']!=$cid){
                $v['html']  = str_repeat('│&nbsp;&nbsp;&nbsp;&nbsp;',$level-1).'├─';
              }else{
                $v['html']  = str_repeat('│&nbsp;&nbsp;&nbsp;&nbsp;',$level-1).'└─';
              }
            }else{
              $v['html'] = '';
            }
            $arr[] = $v;
            $arr = array_merge($arr, $this->oneTree($cate, $v[$this->id], $level + 1));
        }
    }
    return $arr;
  }

  public function getLast($arr,$id)
  {
    foreach ($arr as $k => $v) {
      if($v['pid']==$id){
        $cid = $v['cid'];
      }
    }
    return $cid;
  }

  public function is_have($arr,$id)
  {
    foreach ($arr as $v) {
      if($v['pid']==$id)
        return true;
    }
    return false;
  }

  //组合多维数组
  public function moreArr ($cate) {
     return genTree($cate,$this->id,$this->pid,$this->son);
  }


  //传递一个子分类ID返回所有的父级分类  
  public function getParents ($cate, $id) {
      $arr = array();
      foreach ($cate as $v) {
        if ($v[$this->cid] == $id) {
            $arr[] = $v;
            $arr = array_merge($this->getParents($cate, $v[$this->pid]), $arr); 
        }
      }
      return $arr;
  }


  //传递一个父级分类ID返回所有子分类ID
  public function getSonid ($cate, $pid) {
      $arr = array();
      foreach ($cate as $v) {
        if ($v[$this->pid] == $pid) {
            $arr[] = $v[$this->id];
            $arr = array_merge($arr, $this->getSonid($cate, $v[$this->id]));
        }
      }
      return $arr;
  }

  //传递一个父级分类ID返回所有子分类
  public function getSon ($cate, $pid) {
      $arr = array();
      foreach ($cate as $v) {
        if ($v[$this->pid] == $pid) {
            $arr[] = $v;
            $arr = array_merge($arr, $this->getSon($cate, $v[$this->id]));
        }
      }
      return $arr;
  }
}


 ?>

