<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use  think\Db;
class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {  
      echo  'this is index';
      $content = Db::name('news')->field('description')->where('id = 16')->find();
      echo '<html><div style="width:500px;"'.$content['description'].'</div></html>';
      exit;
        // return $this->view->fetch();
    }

    public function news()
    {
        $newslist = [];
        return jsonp(['newslist' => $newslist, 'new' => count($newslist), 'url' => 'https://www.fastadmin.net?ref=news']);
    }

}
