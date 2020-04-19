<?php

namespace Model;

class BookAddrModel extends Model {

    /**
     * 添加图书馆藏信息
     *
     * @param array $param 图书馆藏新增信息
     * @return integer 受影响的行数
     */
    public function addBookAddr($param) {
        return $this->insert($param);
    }

    /**
     * 根据馆藏Id删除图书馆藏信息
     *
     * @param integer $id 馆藏Id
     * @return integer 受影响的行数
     */
    public function delBookAddr($id) {
        $ans = $this->delete($id);
        return $ans;
    }

    /**
     * 修改图书馆藏信息
     *
     * @param array $param 图书馆藏修改信息
     * @return integer 返回受影响的行数
     */
    public function updateBookAddr($param) {
        return $this->update($param);
    }

    /**
     * 根据馆藏Id查询图书馆藏信息
     *
     * @param integer $id 馆藏id
     * @return array 单条馆藏记录
     */
    public function getBookAddrById($id) {
        $info = $this->find($id);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据图书借阅条形码查询图书馆藏信息
     *
     * @param string $barcode 借阅条形码
     * @return array 单条馆藏记录
     */
    public function getBookAddrByBarcode($barcode) {
        //条件数组
        $cond = array(
            'barcode' => $barcode
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info[0];
        }
        return array();
    }

    /**
     * 根据图书Id(外键)查询图书馆藏信息
     *
     * @param integer $bookid 图书Id(外键)
     * @return array 包含结果集中所有行的馆藏信息数组
     */
    public function getBookAddrByBookid($bookid) {
        //条件数组
        $cond = array(
            'bookid' => $bookid
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据借阅条形码查询馆藏id
     *
     * @param integer $id 图书id
     * @return string 馆藏id
     */
    public function getBookAddrIdByBarcode($barcode) {
        $sql = "SELECT id FROM `t_book_addr` WHERE barcode={$barcode}";
        return $this->fetchColumn($sql);
    }

    /**
     * 根据借阅条形码查询图书id
     *
     * @param integer $id 图书id
     * @return string 图书名
     */
    public function getBookIdByBarcode($barcode) {
        $sql = "SELECT bookid FROM `t_book_addr` WHERE barcode={$barcode}";
        return $this->fetchColumn($sql);
    }

    /**
     * 通过借阅条形码使指定图书借阅次数+1
     *
     * @param integer $barcode 借阅条形码
     * @return integer 返回受影响的行数
     */
    public function incrLendingTimesByBarcode($barcode) {
        $sql = "UPDATE `t_book_addr` SET `lendingtimes`=`lendingtimes`+1 WHERE barcode={$barcode}";
        return $this->exec($sql);
    }

    /**
     * 通过条形码更改指定图书的借阅状态
     *
     * @param integer $barcode 借阅条形码
     * @return integer 返回受影响的行数
     */
    public function updateLendingStatusByBarcode($barcode) {
        $sql = "UPDATE `t_book_addr` SET lendingstatus=1-lendingstatus WHERE barcode={$barcode}";
        return $this->exec($sql);
    }


}