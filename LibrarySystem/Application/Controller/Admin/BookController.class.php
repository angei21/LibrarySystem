<?php

namespace Controller\Admin;

use \Model\BookModel;


class BookController extends BaseController {

    //添加图书
    public function addAction() {
        if ($this->checkAdmin()) {
            if (!empty($_POST)) {
                $bookModel = new BookModel('t_book');
                if ($bookModel->addBook($_POST)) {
                    $this->success('index.php?p=Admin&c=Book&a=list');
                } else {
                    $this->error('index.php?p=Admin&c=Book&a=list', '系统出错，请稍后再试！');
                }
            }
            //加载视图
            require __VIEW__ . 'book_add.html';
        }
    }

    /**
     * 修改图书信息
     *
     * @param integer $id 待修改的图书id
     */
    public function editAction() {
        if ($this->checkAdmin()) {
            $bookModel = new BookModel('t_book');
            if (!empty($_POST)) {
                if ($list = $bookModel->editBook($_POST)) {
                    $this->success('index.php?p=Admin&c=Book&a=list');
                } else {
                    $this->error('index.php?p=Admin&c=Book&a=list', '系统出错，请稍后再试！');
                }
            }
            //加载视图
            $info = $bookModel->getBookById($_GET['id']);
            require __VIEW__ . 'book_edit.html';
        }
    }

    /**
     * 根据图书id删除指定图书的基本信息
     *
     * @param integer $id 待删除的图书id
     */
    public function delAction() {
        if ($this->checkAdmin()) {
            $bookModel = new BookModel('t_book');
            if (!empty($_GET)) {
                if ($bookModel->delBookById($_GET['id'])) {
                    $this->success('index.php?p=Admin&c=Book&a=list');
                } else {
                    $this->error('index.php?p=Admin&c=Book&a=list', '系统出错，请稍后再试！');
                }
            }
        }
    }


    //展示全部图书基本信息
    public function listAction() {
        if ($this->checkAdmin()) {
            $bookModel = new BookModel('t_book');
            $list = $bookModel->getAllBookInfo();
            //加载视图
            require __VIEW__ . 'book_list.html';
        }
    }

    /**
     * 根据书名(模糊查询)/索书号/标准编码检索图书
     *
     * @param string $bookname 图书名
     * @param string $localindex 或：索书号
     * @param string $isbn 或：标准编码
     *
     * @return array 包含结果集中所有行的图书信息数组
     */
    public function searchAction() {
        if ($this->checkUser()) {
            $bookModel = new BookModel('t_book');
            if (!empty($_POST)) {
                $list = array();
                if (!empty($_POST['bookname'])) {//如果是根据书名模糊检索
                    $list = $bookModel->getBookByBookName($_POST['bookname']);
                } elseif (!empty($_POST['localindex'])) {//如果是根据索书号检索
                    $list = $bookModel->getBookByLocalIndex($_POST['localindex']);
                } elseif (!empty($_POST['isbn'])) {//如果是根据标准编码检索
                    $list = $bookModel->getBookByIsbn($_POST['isbn']);
                }
                require __VIEW__ . 'search_list.html';
                return;
            }
            //加载视图
            require __VIEW__ . 'book_search.html';
        }
    }

}