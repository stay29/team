<?php

namespace app\common\model;
use think\Model;
/**
 * 邮箱验证码
 */
class QualificationTest Extends Model
{
  protected  $name = 'qualification_test';

   public function getClassAttr($value)
    {
        $class = ['1'=>'一级','2'=>'二级','3'=>'三级','4'=>'四级'];
        return $class[$value];
    }
   public function getCreateTimeAttr($value)
    {
        return date('Y-m-d',$value);
    }
}
