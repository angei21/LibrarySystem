<?php

namespace Controller\Admin;

use Model\UserModel;

class LoginController extends BaseController {

    //用户登录
    public function loginAction() {
        //第二步：执行登陆逻辑
        if (!empty($_POST)) {
            $model = new UserModel('t_user');
            if ($info = $model->getUserBySnoAndPwd($_POST['sno'], $_POST['pwd'])) {
                //将用户信息保存到会话
                $info['pwd'] = null;
                $_SESSION['user'] = $info;
                //页面跳转
                if ($info['roleid'] == 2) {//如果是2代表身份是管理员
                    $this->success('index.php?p=Admin&c=Page&a=adminPanel');
                } else {//其他身份转至读者界面
                    $this->success('index.php?p=Admin&c=Page&a=userPanel');
                }
            } else {
                $this->error('index.php?p=Admin&c=Login&a=login', '账号或密码不匹配，请重新登陆！');
            }
        }
        //第一步：显示登陆界面
        require __VIEW__ . 'login.html';
    }

    //用户注册
    public function registerAction() {
        //第二步：执行注册逻辑
        if (!empty($_POST)) {
            $_POST['pwd'] = md5(md5($_POST['pwd']) . $GLOBALS['config']['app']['key']);
            $model = new UserModel('t_user');
            if ($model->insert($_POST)) {
                $this->success('index.php?p=Admin&c=Login&a=login','注册成功',1);
            } else {
                $this->error('index.php?p=Admin&c=Login&a=register', '注册失败，请重新注册！');
            }
        }
        //第一步：显示注册界面
        require __VIEW__ . 'register.html';
    }


    //注销用户
    public function logoutAction() {
        session_destroy();
        $this->success('index.php?p=Admin&c=Login&a=login');
    }

}
