<?php

namespace app\admin\model;

use think\Model;


class Qualification extends Model
{

    

    

    // 表名
    protected $name = 'qualification';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'sex_text',
        'certificate_type_text',
        'music_school_text',
        'create_time_text'
    ];
    

    
    public function getSexList()
    {
        return ['男' => __('男'), '女' => __('女')];
    }

    public function getCertificateTypeList()
    {
        return ['2' => __('Certificate_type 2'), '3' => __('Certificate_type 3')];
    }

    public function getMusicSchoolList()
    {
        return ['4' => __('Music_school 4'), '5' => __('Music_school 5'), '6' => __('Music_school 6')];
    }


    public function getSexTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['sex']) ? $data['sex'] : '');
        $list = $this->getSexList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCertificateTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['certificate_type']) ? $data['certificate_type'] : '');
        $list = $this->getCertificateTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getMusicSchoolTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['music_school']) ? $data['music_school'] : '');
        $list = $this->getMusicSchoolList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['create_time']) ? $data['create_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCreateTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
