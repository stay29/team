<?php
namespace app\api\controller;
use app\common\controller\Api;
use  think\Db;
use  think\Request;
 /*
 * 分会详情
 * User: stay
 * Date: 2019/11/12
 * Time: 9:31
 */ 
Class  Branch extends Api{
        /* 数据表结构
       create table piano_branch(
              `id` int(11) unsigned not null auto_increment comment '资格认证id',
              `president` varchar(255)   not null default '' comment '会长单位',
              `vice_president ` varchar(255)   not null default '' comment '副会长单位',
              `secretary` varchar(255)   not null default '' comment '秘书长单位',
              `vice_secretary` varchar(255)   not null default '' comment '副秘书长单位',
              `director` varchar(255)   not null default '' comment '理事单位',
              
              `province`   int(11)    comment '省',
              `city`  int(11)   not null default '0' comment '市',
              `area`   int(11)    comment '县区',
              `type`   int(11)    comment '类型',
              `is_detele` int(11) comment '软删除',
              `create_time` int(11) comment '添加时间',
              PRIMARY KEY (`id`)
        )engine=innodb auto_increment=1 default charset=utf8;

}
