<?php
return array(
    //连接数据库的配置
    'database' => array(
        'type' => 'mysql',              //数据库类别
        'host' => '127.0.0.1',          //主机地址
        'port' => '3306',               //端口号
        'dbname' => 'librarysystem',    //数据库名
        'charset' => 'utf8',            //字符集
        'user' => 'root',               //用户名
        'pwd' => 'root',                //密码
    ),
    //应用程序配置
    'app' => array(
        'dp' => 'Admin',                 //默认平台
        'dc' => 'login',                  //默认控制器
        'da' => 'login',                  //默认方法

        'key' => 'library'               //存储用户密码字段时，使用md5加密的秘钥
    ),
);
