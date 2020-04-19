<?php

namespace Controller\Admin;

use Model\BookAddrModel;
use Model\BookModel;


class BookAddrController extends BaseController {

    /**
     * 添加馆藏信息
     *
     * @param integer $bookid 图书id(外键)
     */
    public function addAction() {
        if ($this->checkAdmin()) {
            if (!empty($_POST)) {
                $BookAddrModel = new BookAddrModel('t_book_addr');
                if ($BookAddrModel->addBookAddr($_POST)) {
                    $this->success('index.php?p=admin&c=BookAddr&a=getAddrByBookId&bookid=' . $_POST['bookid']);
                } else {
                    $this->error('index.php?p=admin&c=BookAddr&a=getAddrByBookId&$bookid=' . $_POST['bookid'], '系统出错，请稍后再试！');
                }
            }
            //加载视图
            $bookid = $_GET['bookid'];
            require __VIEW__ . 'bookaddr_add.html';
        }
    }

    /**
     * 修改馆藏信息
     *
     * @param integer $id 待修改的图书馆藏id
     */
    public function editAction() {
        if ($this->checkAdmin()) {
            $BookAddrModel = new BookAddrModel('t_book_addr');
            if (!empty($_POST)) {
                if ($BookAddrModel->updateBookAddr($_POST)) {
                    $this->success('index.php?p=admin&c=BookAddr&a=getAddrByBookId&bookid=' . $_POST['bookid']);
                } else {
                    $this->error('index.php?p=admin&c=BookAddr&a=getAddrByBookId&$bookid=' . $_POST['bookid'], '系统出错，请稍后再试！');
                }
            }
            //加载视图
            $info = $BookAddrModel->getBookAddrById($_GET['id']);
            require __VIEW__ . 'bookaddr_edit.html';
        }
    }

    /**
     * 删除馆藏信息
     *
     * @param integer $id 待删除的图书馆藏id
     */
    public function delAction() {
        if ($this->checkAdmin()) {
            $BookAddrModel = new BookAddrModel('t_book_addr');
            if (!empty($_GET['id'])) {
                if ($BookAddrModel->delBookAddr($_GET['id'])) {
                    $this->success('index.php?p=admin&c=BookAddr&a=getAddrByBookId&bookid=' . $_GET['bookid']);
                } else {
                    $this->error('index.php?p=admin&c=BookAddr&a=getAddrByBookId&$bookid=' . $_GET['bookid'], '系统出错，请稍后再试！');
                }
            }
        }
    }

    /**
     * 通过图书id查询图书馆藏信息集(用于读者显示)
     *
     * @param string $bookid 图书id(外键)
     */
    public function getBookAddrDetailsAction() {
        if ($this->checkUser()) {
            //图书基本信息
            $bookInfo = array();
            //图书馆藏信息
            $bookaddrList = array();
            if (!empty($_GET['bookid'])) {
                $bookid = $_GET['bookid'];
                //获取图书基本信息
                $bookModel = new BookModel('t_book');
                $bookInfo = $bookModel->getBookById($bookid);
                //通过图书Id(外键)查询图书馆藏信息
                $BookAddrModel = new BookAddrModel('t_book_addr');
                $bookaddrList = $BookAddrModel->getBookAddrByBookid($bookid);
            }
            //加载视图
            require __VIEW__ . 'book_detail_list.html';
        }
    }

    /**
     * 通过图书id查询图书馆藏信息集(用于管理员显示)
     *
     * @param string $bookid 图书id(外键)
     */
    public function getAddrByBookIdAction() {
        if ($this->checkAdmin()) {
            //图书基本信息
            $bookInfo = array();
            //图书馆藏信息
            $bookaddrList = array();
            if (!empty($_GET['bookid'])) {
                $bookid = $_GET['bookid'];
                //获取图书基本信息
                $bookModel = new BookModel('t_book');
                $bookInfo = $bookModel->getBookById($bookid);
                //通过图书Id(外键)查询图书馆藏信息
                $BookAddrModel = new BookAddrModel('t_book_addr');
                $bookaddrList = $BookAddrModel->getBookAddrByBookid($bookid);
            }
            //加载视图
            require __VIEW__ . 'bookaddr_list.html';
        }
    }


}