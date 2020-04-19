<?php

namespace Controller\Admin;

use Model\UserModel;
use Model\RoleModel;

class UserController extends BaseController {

    //修改密码
    public function updatePwdAction() {
        if ($this->checkUser()) {
            $UserModel = new UserModel('t_user');
            if (!empty($_POST)) {
                //接收浏览器的参数
                $oldPwd = $_POST['oldPwd'];
                $newPwd_1 = $_POST['newPwd_1'];
                $newPwd_2 = $_POST['newPwd_2'];
                //原密码比对
                $info = $UserModel->getUserById($_SESSION['user']['id']);
                $oldPwd = md5(md5($oldPwd) . $GLOBALS['config']['app']['key']);
                if ($info['pwd'] != $oldPwd) {
                    $this->error('index.php?p=Admin&c=User&a=updatePwd', '原密码不正确！');
                }
                //输入的两次新密码核对
                if ($newPwd_1 != $newPwd_2) {
                    $this->error('index.php?p=Admin&c=User&a=updatePwd', '输入的新密码不一致！');
                }
                //更改密码
                $newPwd_1 = md5(md5($newPwd_1) . $GLOBALS['config']['app']['key']);
                if ($UserModel->updatePwdById($info['id'], $newPwd_1)) {
                    $this->success('index.php?p=Admin&c=Page&a=userPanel');
                } else {
                    $this->error('index.php?p=Admin&c=User&a=updatePwd', '系统出错！');
                }
            }
            //加载视图
            require __VIEW__ . 'change_password.html';
        }
    }

    //用户权限管理（管理员使用）
    public function roleIdManageAction() {
        if($this->checkAdmin()){
            $UserModel = new UserModel('t_user');
            $roleModel = new RoleModel('t_role');
            if ((!empty($_POST['userid'])) && (!empty($_POST['newRoleId']))) {
                if ($UserModel->updateRoleIdById($_POST['userid'], $_POST['newRoleId'])) {
                    $this->success('index.php?p=Admin&c=User&a=roleIdManage', '权限修改成功！', 1);
                } else {
                    $this->error('index.php?p=Admin&c=User&a=roleIdManage', '系统出错！');
                }
            }
            //获取用户集
            $userList = array();
            if (!empty($_POST['searchSno'])) {
                $userList = $UserModel->getUserBySno($_POST['searchSno']);
            } else {
                $userList = $UserModel->getAllUserInfo();
            }
            //获取角色集
            $roleList = $roleModel->getAllRoleInfo();

            //加载视图
            require __VIEW__ . 'role_rights_manage_list.html';
        }
    }

    //判断学号/职工号是否已经存在
    public function checkUserAction() {
        $model = new UserModel('t_user');
        return $model->isExists($_GET['sno']);
    }

}