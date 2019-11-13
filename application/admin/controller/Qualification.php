<?php
namespace app\admin\controller;
use app\common\controller\Backend;
use  think\Request;
/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Qualification extends Backend
{
    
    /**
     * Qualification模型对象
     * @var \app\admin\model\Qualification
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Qualification;
        $this->view->assign("sexList", $this->model->getSexList());
        $this->view->assign("certificateTypeList", $this->model->getCertificateTypeList());
        $this->view->assign("musicSchoolList", $this->model->getMusicSchoolList());
    }
     //审核=》审核 成绩 
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
     public  function  details(Request  $request){
        // {"dialog":"1","ids":"12"}
          $ids  =  $request->param('ids');
          // return json($a);
        // return $this->fetch('qualification/add.html');
            return $this->view->fetch('qualification/add');
     }
       public  function  audit(){
        echo 'audit';
     }
     public  function  results(){
        echo 'results';
     }
}
