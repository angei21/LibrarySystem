<?php


namespace Model;

class BookModel extends Model {

    /**
     * 添加图书信息
     *
     * @param array $param 图书新增信息
     * @return integer 受影响的行数
     */
    public function addBook($param) {
        return $this->insert($param);
    }

    /**
     * 修改图书信息
     *
     * @param array $param 图书修改信息
     * @return integer 返回受影响的行数
     */
    public function editBook($param) {
        return $this->update($param);
    }

    /**
     * 根据图书id删除图书信息
     *
     * @param integer $id 图书id
     * @return integer 返回受影响的行数
     */
    public function delBookById($id){
        return $this->delete($id);
    }

    /**
     * 展示全部图书信息
     *
     * @return array 包含结果集中所有行的角色信息数组
     */
    public function getAllBookInfo() {
        $info = $this->select();
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据图书ID查询指定图书
     *
     * @param integer $id 图书id
     * @return array 单条用户记录
     */
    public function getBookById($id) {
        //通过条件数组查询用户
        $info = $this->find($id);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 通过图书名模糊查询图书信息
     *
     * @param string $name 图书名关键字
     * @return array 包含结果集中所有行的图书信息数组
     */
    public function getBookByBookName($name) {
        //条件数组
        $cond = array(
            'bookname' => array('like', $name)
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 通过标准书号(ISBN)查询
     *
     * @param string $isbn 标准书号
     * @return array 包含结果集中所有行的图书信息数组
     */
    public function getBookByIsbn($isbn) {
        //条件数组
        $cond = array(
            'isbn' => $isbn
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据索引号查询指定图书
     *
     * @param string $localIndex 索引号
     * @return array 包含结果集中所有行的图书信息数组
     */
    public function getBookByLocalIndex($localIndex) {
        //条件数组
        $cond = array(
            'localindex' => $localIndex
        );
        //通过条件数组查询用户
        $info = $this->select($cond);
        if (!empty($info)) {
            return $info;
        }
        return array();
    }

    /**
     * 根据图书ID查询图书名
     *
     * @param integer $id 图书id
     * @return string 图书名
     */
    public function getBookNameById($id) {
        $sql = "SELECT `bookname` FROM t_book WHERE `id`={$id}";
        return $this->fetchColumn($sql);
    }

}