<?php
namespace app\api\controller;
use app\common\controller\Api;
use app\common\model\File as FileModel;
use  app\common\model\Qualification as QualificationModel;
use  app\common\model\QualificationTest as QualificationTest;
use  think\Db;
use  think\Request;
 /*
 * 资格认证
 * User: stay
 * Date: 2019/11/11
 * Time: 14:34
 */ 
Class  Qualification extends Api{
      // protected $noNeedLogin = ['index'];  //后期删除  cookie 判断登录
      /*
        *资格认证
        *提交表单
       */
     public  function  index(Request $request){
        //post  数据
        $data = $request->post();
        $userid = $request->header('userid');
        $data['user_id'] = $userid;
        $data['create_time'] = time();
        $status   = QualificationModel::field('id,status,class')->where('user_id','=',$userid)->where('status','=','1')->order('id  desc')->find();
        //确认是否在报考
        if($status && $status['status'] == '0'){
          return  $this->error('报考进行中',$data = null, 30006);
         }if($status && $status['status'] == '2'){
           return  $this->error('审核不通过,重新填写',$data = null, 30005);
         }
        //验证  =》判断条件
        $validate = new \app\api\validate\Qualification();
        if(!$validate->check($data)){
            $error = explode('|',$validate->getError());
            return  $this->error($error[0],$data = null, $error[1]);
         }
         //class 判断报考等级
         if($status && $data['class'] >= $status['class']  ){
          return  $this->error('报考等级过低或者考过',$data = null, 10008);  
          }
         //添加数据
         $res = QualificationModel::create($data);
         if($res){
           $list['list']  = array();
           return  $this->success($msg = '成功回调',$list, $code = 20006, $type = 'json');
         }else{
             return  $this->error('参数不对',$data = null, 10001);
         }
        } //end
        //重新提交
        public  function  re_test(Request $request){     
            //post  数据
           $data = $request->post();
           $userid = $request->header('userid');
           $data['user_id'] = $userid;
           $data['create_time'] = time();
           $qua  = QualificationModel::field('id,class')->where('user_id','=',$userid)->where('status','=','1')->order('id  desc')->find();
           $validate = new \app\api\validate\Qualification();
           //判断条件
           if(!$validate->check($data)){
              $error = explode('|',$validate->getError());
              return  $this->error($error[0],$data = null, $error[1]);
           } 
           //class 判断报考等级
           if($qua &&  $data['class'] >= $qua['class']){
            return  $this->error('报考等级过低或者考过',$data = null, 10008);  
           }
           $qualification =   new  QualificationModel;
           $qua1 = QualificationModel::field('id,class')->where('user_id','=',$userid)->where('status','=','2')->order('id  desc')->find();
           if($qua1){
            return  $this->error('逻辑错误',$data = null, 70001);
           }
           $res = $qualification->allowField(true)->save($data,['id' => $qua1['id']]);
           // return  json($res);
            if($res){
           return  $this->success($msg = '成功回调',$data = null, $code = 20006, $type = 'json');
            }else{
              return  $this->error('修改不成功',$data = null, 10001);
            }
        }  
        /*
          *权限判断
          *验证是报考 还是 重新填写   还是在审核中  
        */
         public  function  code(Request $request){
           $userid = $request->header('userid');
           $data['user_id'] = $userid;
           $qua  = QualificationModel::field('id,status,class')->where('user_id','=',$userid)->order('id  desc')->find();
           // return json($qua);
           if($qua && $qua['status'] == 2){
           return  $this->error('重新填写，不允许报考',$data = null, 30005);
           }else if($qua && $qua['status'] == '0'){
            return  $this->error('审核中，不允许报考',$data = null, 30006);
           }
            return  $this->error('报考入口',$data = null, 20006);
          }
         /*
         *审核结果
         */
       public  function audit(Request $request){
           $userid = $request->header('userid');
           $qualification = QualificationModel::where('user_id','=',$userid)->field('id,status')->order('id  desc')->find(); 
           if($qualification['status'] == '0' ){
              return  $this->success($msg = '审核中',$data = null, $code = 30006, $type = 'json');
           }else if($qualification['status'] == '1' ){
              return  $this->success($msg = '审核通过',$data = null, $code = 20006, $type = 'json');
           }else if($qualification['status'] == '2' ){
            //这里需要 审核什么不成功 order();
            $msg =DB::name('qualification_msg')->where('qualification_id','=',$qualification['id'])->order('id  desc')->find();
            $data['msg'] =$msg;
              return  $this->success($msg = '审核不通过',$data, $code = 30005, $type = 'json');
           }
         }
         /*
           成绩回调  more
          */
        public  function  results(Request $request){
           $userid = $request->header('userid');
           if(!$userid){
                return  $this->error('参数不对',$data = null, 100001);
           }
           $res = QualificationTest::where('user_id','=',$userid)->select();
           $data['list'] = $res;
           return  $this->success($msg = '回调成功',$data, $code = 20006, $type = 'json');
       }

}
