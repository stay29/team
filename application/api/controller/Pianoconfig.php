<?php
namespace app\api\controller;
use app\common\controller\Api;
use app\common\model\File as FileModel;
use  think\Db;
use  think\Request;
/*
 * api  配置文件 
*/ 
Class  Pianoconfig  extends Api{
     protected $noNeedLogin = ['index'];
     //认证资格 一些多选
     public function  index(Request $request){
      $user = array();
      $userid = $request->header('userid');
      $res = DB::name('qualification')->where('user_id','=',$userid)->where('class','=','4')->find();
      if($res){
        $user =$res;
      }
      $data['list'] = array(
               'document_type'=>config('document_type'),
               'piano_major'=>config('piano_major'),
               'class'=>config('piano_class'),
               'user'=>$user
          );
      return  $this->success($msg = '成功回调', $data, $code = 20006, $type = 'json');
    }

}
