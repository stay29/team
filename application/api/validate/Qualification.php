<?php
/**
 * 资格认证
 * User: stay
 * Date: 2019/11/11
 * Time: 14:34
 */
namespace app\api\validate;
use think\Validate;
use think\Db;
class Qualification extends Validate
{
    protected $rule = [
        'province'=>'require|number', //省
        'name'=>'require|max:25', //名字
        'sex'=>'require|in:女,男', //性别
        'birth'=>'require|dateFormat:Y-m-d',//生日
        'certificate_type'=>'require|in:2,3', //类型
        'certificate_number'=> ['/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/', 'require'], //身份证
        'music_school'=>'require|in:4,5,6', //音乐学院
        'class'=>'require|in:1,2,3,4', //音乐学院
        'work_units'=>'require|max:255', //工作单位
        'graduated_college'=>'require|max:255',//毕业学院
        'address'=>'require|max:255',//地址
        'study_resume'=>'require',//学习简历
        'work_resume'=>'require',//工作简历
        'exam_phone'=>'require|number',//考生照片
        'card_phone'=>'require|number',
        'card_the_phone'=>'require|number',
        'user_id'=>'require|number' 
    ];
    protected $message = [
        'province'=>'省份为空|10001', //省
        'name'=>'名字长度不得大于25|10001', //名字
        'sex'=>'性别格式不对|10001', //性别
        'birth'=>'出生年月格不对|10001',//生日
        'certificate_type'=>'类型格式不对|10001', //类型
        'certificate_number'=> '身份证格式不对|10001', //身份证
        'music_school'=>'音乐学院格式不对|10001', //音乐学院
        'class'=>'等级必填|10001', //音乐学院
        'work_units'=>'工作单位必填和超出长度|10001', //工作单位
        'graduated_college'=>'毕业学院必填和超出长度|10001',//毕业学院
        'address'=>'地址超出长度和必填|10001',//地址
        'study_resume'=>'学习简历必填|10001',//学习简历
        'work_resume'=>'工作简历必填|10001',//工作简历
        'exam_phone'=>'照片格式不对|10001',//考生照片
        'card_phone'=>'身份证照片格式不对|10001',
        'card_the_phone'=>'身份证照片格式不对|10001',
        'user_id'=>'头部找不到用户id|10001' 
    ];


    // public  function  sceneAddone()
    // {
    //     return $this->only(['cellphone','password','nickname','account','organization','sex','rid','senfen','manager']);
    // }
    // public function sceneAdd()
    // {
    //      return $this->only(['cellphone','password','repassword'])
    //          ->remove('password','length');
    // }

    // public function sceneEdit()
    // {
    //     return $this->only(['cellphone','password','repassword'])
    //         ->remove('cellphone','unique')
    //         ->remove('password','length');
    // }

    // public function sceneLogin()
    // {
    //      return $this->only(['cellphone','password','remember'])
    //          ->remove('cellphone','max|mobile|unique')
    //          ->remove('password','length');
    // }
}



