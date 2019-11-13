<?php
namespace app\api\controller;
use app\common\controller\Api;
use app\common\model\File as FileModel;
use  think\Db;
use  think\Request;
/*
 * 文件管理接口
*/ 
Class  File  extends Api{
protected $noNeedLogin = ['upload'];
public  function  upload(){
    $file = request()->file('image');
    $type_id = request()->param('type');
   // var_dump($file);
    // return json_encode(array('file'=>$file,'type_id'=>$type_id));
     if(!$file || !$type_id){
      return  $this->error(__('无效的参数'),$data = null, $code = 60008, $type = 'json');
    }
    // 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 数据库操作
            $fileinfo =$file->getInfo();
             //参数
            $data =array(
                 'file_size'=>$fileinfo['size'],
                 'file_name'=>$info->getSaveName(),
                 'extension'=>$info->getExtension(),
                 'type_id'=>$type_id,
                 'create_time'=>time(),
                 'file_type'=>'image',
              );
            // $FileModel = new FileModel;
            // $res_file = $FileModel->save($data);
            // return  json($FileModel->file_id);
            $res_file=FileModel::create($data);
            if($res_file->file_id){
                 $data['file_id'] =  $res_file->file_id;
                 $res['list'] =$data;
                 return  $this->success($msg = '插入成功', $res, $code = 20006, $type = 'json');
            }else{
                 //删除文件
                 unlink(ROOT_PATH . 'public' . DS . 'uploads'.$info->getSaveName());
                 return  $this->error(__('插入失败'),$data = null, $code = 200001, $type = 'json');
            }
            return   json($data);

             // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
         }else{
            // 上传失败获取错误信息
            return  $this->error($file->getError(),$data = null, $code =10003, $type = 'json');
        }
    }
}
 
}
