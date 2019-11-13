<?php
define('APP_PATH', __DIR__ . '/../application/');
define('FILE_PATH', __DIR__);
// 加载框架引导文件
require __DIR__ . '/../thinkphp/base.php';
// 绑定到admin模块
\think\Route::bind('admin');

// 关闭路由
\think\App::route(false);

// 设置根url
\think\Url::root('');

// 执行应用
\think\App::run()->send();


