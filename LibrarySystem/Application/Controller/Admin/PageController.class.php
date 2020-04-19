<?php

namespace Controller\Admin;

class PageController extends BaseController {

    //跳转至用户主页面
    public function userPanelAction() {
        if ($this->checkUser()) {
            $info = $_SESSION['user'];
            require __VIEW__ . 'user_panel.html';
        }
    }

    //跳转至管理员主页面
    public function adminPanelAction() {
        if($this->checkAdmin()){
            $info = $_SESSION['user'];
            require __VIEW__ . 'admin_panel.html';
        }
    }

}