<?php
namespace app\api\controller;
use app\common\controller\Api;
use app\common\library\Sms as Smslib;
use app\common\model\User;
use think\Hook;
/**
 * 手机短信接口
 */
class Sms extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    /**
     * 发送验证码
     *
     * @param string $mobile 手机号
     * @param string $event 事件名称
     */
    public function send()
    {   
        //?后期判断是否登录 
        $mobile = $this->request->request("mobile");
        $event  = $this->request->request("event");
        $event  = $event ? $event : 'register';
        if (!$mobile || !\think\Validate::regex($mobile, "^1\d{10}$")){
            $this->error(__('手机号不正确'),$data = null, $code = 60001);
        }
        $last = Smslib::get($mobile, $event);
        if($last && time() - $last['createtime'] < 60){
            $this->error(__('发送频繁'),$data = null, $code = 60002);
        }
        //一分钟两次 =》一小时5次 =》1天八次（想要增加就需要客服）
        $ipSendTotal = \app\common\model\Sms::where(['ip' => $this->request->ip()])->whereTime('createtime', '-1 hours')->count();
        if ($ipSendTotal >= 5) {
            $this->error(__('发送频繁'),$data = null, $code = 60002);
        }
        if ($event) {
            $userinfo = User::getByMobile($mobile);
            if ($event == 'register' && $userinfo) {
                //已被注册
                $this->error(__('已被注册'),$data = null, $code = 60003);
            } elseif (in_array($event, ['changemobile']) && $userinfo) {
                //被占用
                $this->error(__('已被占用'),$data = null, $code = 60004);
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                //未注册
                $this->error(__('未注册'),$data = null, $code = 60005);
            }
        }
        $code =  mt_rand(1000, 9999);
        $ret = Smslib::send($mobile,$code, $event);  //发送短信
        if ($ret) {
             $data= array(
                    'verification_code'=>$code,
                    'mobile'=>$mobile
                );
            $this->success(__('发送成功'),$data, $code = 60006);
        } else {
            $this->error(__('发送失败'),$data = null, $code = 60007);
        }
    }
    /**
     * 检测验证码
     *
     * @param string $mobile 手机号
     * @param string $event 事件名称
     * @param string $captcha 验证码
     */
    public function check()
    {
        $mobile = $this->request->request("mobile");
        $event = $this->request->request("event");
        $event = $event ? $event : 'register';
        $captcha = $this->request->request("captcha");
        if (!$mobile || !\think\Validate::regex($mobile, "^1\d{10}$")) {
            $this->error(__('手机号不正确'));
        }
        if ($event) {
            $userinfo = User::getByMobile($mobile);
            if ($event == 'register' && $userinfo) {
                //已被注册
                $this->error(__('已被注册'));
            } elseif (in_array($event, ['changemobile']) && $userinfo) {
                //被占用
                $this->error(__('已被占用'));
            } elseif (in_array($event, ['changepwd', 'resetpwd']) && !$userinfo) {
                //未注册
                $this->error(__('未注册'));
            }
        }
        $ret = Smslib::check($mobile, $captcha, $event);
        if ($ret) {
            $this->success(__('成功'));
        } else {
            $this->error(__('验证码不正确'));
        }
    }

  
}
