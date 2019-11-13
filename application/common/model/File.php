<?php

namespace app\common\model;

use think\Model;

/**
 * 文件上传模型
 */
class File extends Model
{
    // 表名
    protected $name = 'upload_file';
    /*获取时间*/
    public  function  getCreateTimeAttr($value,$data){
         return  date('Y-m-d H:i',$value);
    }
    
   

}
