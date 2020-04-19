<?php

namespace Model;

class LendInfoModel extends Model {

    /**
     * 添加借阅信息
     *
     * @param array $param 借阅新增信息
     * @return integer 受影响的行数
     */
    public function addLendInfo($param) {
        return $this->insert($param);
    }

    /**
     * 根据借阅ID(主键)查询指定借阅信息
     *
     * @param integer $id 图书id
     * @return array 单条借阅记录
     */
    public function getLendInfoById($id) {
        $info = $this->find($id);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 通过用户ID查询个人借阅信息集
     *
     * @param integer $userId 用户ID
     * @return array 包含结果集中所有行的个人借阅信息集
     */
    public function getLendInfoByUserId($userId) {
        //条件数组
        $cond = array(
            'userid' => $userId
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 通过图书馆藏ID查询借阅信息
     *
     * @param integer$addrId 图书馆藏ID
     * @return array 单条借阅记录
     */
    public function getLendInfoByAddrId($addrId) {
        //条件数组
        $cond = array(
            'addrid' => $addrId
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info[0];
        }
        return array();
    }

    /**
     * 修改借阅信息
     *
     * @param array $param 图书修改信息
     * @return integer 返回受影响的行数
     */
    public function updateLendInfo($param) {
        return $this->update($param);
    }

    /**
     * 根据借阅Id删除借阅信息。
     * (归还图书后，通过主键删除该条借阅信息)
     *
     * @param integer $id 借阅Id
     * @return integer 受影响的行数
     */
    public function delLendInfoById($id) {
        return $this->delete($id);
    }

    /**
     * 通过用户id查询用户当前总借书数
     *
     * @param integer $userId 用户id
     * @return integer 用户总借书数
     */
    public function hadBorrowNumsByUserId($userId) {
        $sql = "SELECT COUNT(*) FROM `t_lend_info` WHERE `userid`={$userId}";
        return $this->fetchColumn($sql);
    }
}