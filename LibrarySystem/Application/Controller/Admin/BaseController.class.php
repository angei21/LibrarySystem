<?php


namespace Controller\Admin;

use Core\Controller;

//后台基础控制器
class BaseController extends Controller {

    public function __construct() {
        parent::__construct();//调用父类函数完成初始化session的操作
        $this->checkLogin();
    }

    //验证用户是否登录，防止翻墙
    private function checkLogin() {
        if (CONTROLLER_NAME == 'Login') {//登录控制器不需要验证
            return;
        }
        if (empty($_SESSION['user'])) {
            $this->error('index.php?p=Admin&c=Login&a=login', '您还没有登录！');
        }
    }

    //验证读者的身份
    public function checkUser() {
        if ($_SESSION['user']['roleid'] == 2) {
            $this->error('index.php?p=Admin&c=Login&a=login', '权限校验失败，请先进行登陆！');
        }
        return true;
    }

    //验证管理员的身份(管理员身份识别id = 2)
    public function checkAdmin() {
        if ($_SESSION['user']['roleid'] != 2) {
            $this->error('index.php?p=Admin&c=Login&a=login', '权限校验失败，请先进行登陆！');
        }
        return true;
    }
}
