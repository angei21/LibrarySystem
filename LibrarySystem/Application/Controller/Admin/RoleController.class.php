<?php

namespace Controller\Admin;

use \Model\RoleModel;

class RoleController extends BaseController {

    //展示角色基本信息
    public function listAction() {
        if ($this->checkAdmin()) {
            $roleModel = new RoleModel('t_role');
            $list = $roleModel->getAllRoleInfo();
            //加载视图
            require __VIEW__ . 'role_list.html';
        }
    }

    /**
     * 修改角色基本信息
     *
     * @param integer $id 待修改的角色id
     */
    public function editAction() {
        if ($this->checkAdmin()) {
            $roleModel = new RoleModel('t_role');
            if (!empty($_POST)) {
                if ($roleModel->editRole($_POST)) {
                    $this->success('index.php?p=Admin&c=Role&a=list');
                } else {
                    $this->error('index.php?p=Admin&c=Role&a=list', '系统出错，请稍后再试！');
                }
            }
            //加载视图
            $info = $roleModel->getRoleById($_GET['id']);
            require __VIEW__ . 'role_edit.html';
        }
    }

    //添加角色基本信息
    public function addAction() {
        if ($this->checkAdmin()) {
            $roleModel = new RoleModel('t_role');
            if (!empty($_POST)) {
                if ($roleModel->addRole($_POST)) {
                    $this->success('index.php?p=Admin&c=Role&a=list');
                } else {
                    $this->error('index.php?p=Admin&c=Role&a=list', '系统出错，请稍后再试！');
                }
            }
            //加载视图
            require __VIEW__ . 'role_add.html';
        }
    }

    /**
     * 删除角色基本信息
     *
     * @param integer $id 待删除的角色id
     */
    public function delAction() {
        if ($this->checkAdmin()) {
            $roleModel = new RoleModel('t_role');
            if (!empty($_GET)) {
                if ($roleModel->delRole($_GET['id'])) {
                    $this->success('index.php?p=Admin&c=Role&a=list');
                } else {
                    $this->error('index.php?p=Admin&c=Role&a=list', '系统出错，请稍后再试！');
                }
            }
        }
    }
}