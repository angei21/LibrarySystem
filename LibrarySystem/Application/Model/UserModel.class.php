<?php

namespace Model;

class UserModel extends Model {

    /**
     * 新增用户信息
     *
     * @param array $param 用户新增信息
     * @return integer 返回受影响的行数
     */
    public function addUser($param) {
        return $this->insert($param);
    }

    /**
     * 修改用户信息
     *
     * @param array $param 用户修改信息
     * @return integer 返回受影响的行数
     */
    public function updateUser($param) {
        return $this->update($param);
    }

    /**
     * 根据用户ID获取用户的信息
     *
     * @param integer $id 用户id
     * @return array 单条用户记录
     */
    public function getUserById($id) {
        //通过条件数组查询用户
        $info = $this->find($id);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 通过学号/职工号和密码获取用户的信息
     *
     * @param string $name 学号/职工号
     * @param string $pwd 密码
     * @return array 单条用户记录
     */
    public function getUserBySnoAndPwd($name, $pwd) {
        //条件数组
        $cond = array(
            'sno' => $name,
            'pwd' => md5(md5($pwd) . $GLOBALS['config']['app']['key'])
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info[0];
        }
        return array();
    }

    /**
     * 根据用户学号/职工号获取用户的信息
     *
     * @param integer $sno 学号/职工号
     * @return array 包含结果集中所有行的用户信息数组
     */
    public function getUserBySno($sno) {
        //条件数组
        $cond = array(
            'sno' => $sno
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 展示所有用户信息
     *
     * @return array 包含结果集中所有行的用户信息数组
     */
    public function getAllUserInfo() {
        $info = $this->select();
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据用户Id删除用户信息
     *
     * @param integer $id 用户Id
     * @return integer 受影响的行数
     */
    public function delUser($id) {
        $ans = $this->delete($id);
        return $ans;
    }

    /**
     * 通过用户ID获取该用户的角色ID
     *
     * @param integer $id 用户id
     * @return string 角色ID
     */
    public function getRoleIdById($id) {
        $sql = "SELECT `roleid` FROM `t_user` WHERE `id` ={$id}";
        return $this->fetchColumn($sql);
    }

    /**
     * 更改某用户的密码
     *
     * @param integer $id 待更改密码的用户id
     * @param string $newPwd 新密码
     *
     * @return integer 受影响的行数
     */
    public function updatePwdById($id, $newPwd) {
        $sql = "UPDATE `t_user` SET `pwd`='{$newPwd}' WHERE `id` ={$id}";
        return $this->exec($sql);
    }


    /**
     * 通过用户Id更改用户的角色划分
     *
     * @param integer $id 待更改角色划分的用户id
     * @param integer $newRoleId 待更改成的角色划分id
     *
     * @return integer 受影响的行数
     */
    public function updateRoleIdById($id, $newRoleId) {
        $sql = "UPDATE `t_user` SET `roleid`='{$newRoleId}' WHERE `id` ={$id}";
        return $this->exec($sql);
    }

    /**
     * 通过学号判断用户是否存在
     *
     * @param integer $sno 学号
     * @return integer 用户存在返回1，否则返回0
     */
    public function isExists($sno) {
        //条件数组
        $cond = array(
            'sno' => $sno
        );
        $info = $this->select($cond);
        return empty($info) ? 0 : 1;
    }
}