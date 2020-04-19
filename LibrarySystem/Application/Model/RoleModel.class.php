<?php

namespace Model;

class RoleModel extends Model {

    /**
     * 添加角色信息
     *
     * @param array $param 角色新增信息
     * @return integer 受影响的行数
     */
    public function addRole($param) {
        return $this->insert($param);
    }

    /**
     * 根据角色ID查询指定角色
     *
     * @param integer $id 角色id
     * @return array 单条用户记录
     */
    public function getRoleById($id) {
        $info = $this->find($id);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 修改角色信息
     *
     * @param array $param 角色修改信息
     * @return integer 返回受影响的行数
     */
    public function editRole($param) {
        return $this->update($param);
    }

    /**
     * 展示全部角色信息
     *
     * @return array 包含结果集中所有行的角色信息数组
     */
    public function getAllRoleInfo() {
        $info = $this->select();
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据角色Id删除角色信息
     *
     * @param integer $id 角色Id
     * @return integer 受影响的行数
     */
    public function delRole($id) {
        $info = $this->delete($id);
        return $info;
    }

    /**
     * 通过角色ID获取该角色的允许借书数
     *
     * @param integer $id 角色Id
     * @return integer 允许借书数
     */
    public function getBorrownumsById($id) {
        $sql = "SELECT `borrownums` FROM t_role WHERE id ={$id}";
        return $this->fetchColumn($sql);
    }

    /**
     * 通过角色ID获取该角色的允许借书天数
     *
     * @param integer $id 角色Id
     * @return integer 允许借书天数
     */
    public function getBorrowdaysById($id) {
        $sql = "SELECT `borrowdays` FROM t_role WHERE id ={$id}";
        return $this->fetchColumn($sql);
    }

}