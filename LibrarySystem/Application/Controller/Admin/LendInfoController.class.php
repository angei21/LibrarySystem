<?php

namespace Controller\Admin;

use Model\BookAddrModel;
use Model\BookModel;
use Model\UserModel;
use Model\RoleModel;
use Model\LendInfoModel;

//本控制器用于自助借阅机调用(无显示界面)
class LendInfoController extends BaseController {


    /**
     * 自助借阅机执行借书功能的接口
     * 需要参数：
     * @param string $userid 用户ID(扫码登录获取)
     * @param string $barcode 图书借阅条形码(感应识别获取)
     *
     */
    public function borrowBookAction() {
        if ((!empty($_GET['barcode'])) && (!empty($_GET['userid']))) {
            $bookModel = new BookModel('t_book');
            $bookAddrModel = new BookAddrModel('t_book_addr');
            $userModel = new UserModel('t_user');
            $roleModel = new RoleModel('t_role');
            $lendInfoModel = new LendInfoModel('t_lend_info');

            //借阅时，通过感应和扫码分别识别到借阅条形码和用户id
            $barcode = $_GET['barcode'];
            $userId = $_GET['userid'];

            //1.判断用户借阅总书数是否已达到该角色可借阅总书数的上限
            $userRoleId = $userModel->getRoleIdById($userId);
            $allowBorrowNums = $roleModel->getBorrownumsById($userRoleId);//用户可借阅总书数
            $hadBorrowNums = $lendInfoModel->hadBorrowNumsByUserId($userId);//用户已借阅总书数

            if ($allowBorrowNums >= $hadBorrowNums + 1) { //执行借阅流程
                //2.填充借阅记录
                //(1)填充图书馆藏ID
                $data['addrid'] = $bookAddrModel->getBookAddrIdByBarcode($barcode);
                //(2)填充图书名称
                $bookId = $bookAddrModel->getBookIdByBarcode($barcode);
                $data['bookname'] = $bookModel->getBookNameById($bookId);
                //(3)填充用户ID
                $data['userid'] = $userId;
                //(4)填充借阅日期
                $data['lendingdate'] = time();
                //(5)填充应还日期
                $userRoleId = $userModel->getRoleIdById($userId);
                $allowBorrowDays = $roleModel->getBorrowdaysById($userRoleId);
                $data['duedate'] = time() + 86400 * $allowBorrowDays;

                //3.借阅记录入库
                $lendInfoModel->addLendInfo($data);

                //4.同步更新图书的外借次数和外借状态
                $bookAddrModel->incrLendingTimesByBarcode($barcode);
                $bookAddrModel->updateLendingStatusByBarcode($barcode);

                echo 'success!';

            } else { //该角色借阅总书数已达上限
                echo 'Your total borrowing has exceeded the standard!';
            }
        }
    }

    /**
     * 自助借阅机执行还书功能的接口
     * 需要参数：
     * @param string $barcode 图书借阅条形码(感应识别获取)
     *
     */
    public function returnBookAction() {
        if (!empty($_GET['barcode'])) {
            $bookAddrModel = new BookAddrModel('t_book_addr');
            $lendInfoModel = new LendInfoModel('t_lend_info');

            //还书时，通过感应识别到借阅条形码
            $barcode = $_GET['barcode'];

            //通过借阅条形码锁定借阅记录
            $bookAddrId = $bookAddrModel->getBookAddrIdByBarcode($barcode);//通过借阅条形码获取馆藏id
            $lendInfo = $lendInfoModel->getLendInfoByAddrId($bookAddrId);//通过馆藏id获取指定的借阅信息
            //录入实际归还时间
            $lendInfo['returndate'] = time();
            //逾期判定(将实际归还时间与应还时间进行比对，判断是否逾期)
            if ($lendInfo['returndate'] - $lendInfo['duedate'] > 0) {//发生了逾期
                //计算逾期时间，提供给即将触发的逾期执行流程作参考
                $lendInfo['overduedays'] = ($lendInfo['returndate'] - $lendInfo['duedate']) / 86400;
                //【逾期执行流程：例如学生卡缴纳定向的逾期费用等；这里不做处理。】
            }
            //处理完毕，删除此条借阅记录。
            $lendInfoModel->delLendInfoById($lendInfo['id']);
            //更新图书的外借状态
            $bookAddrModel->updateLendingStatusByBarcode($barcode);

            echo 'Completed!';
        }
    }

    /**
     * 根据用户id查询指定用户的借阅情况
     *
     * @param integer $userId 用户id
     */
    public function getLendInfoByUserIdAction() {
        if ($this->checkUser()) {
            $lendInfoModel = new LendInfoModel('t_lend_info');
            $userId = $_SESSION['user']['id'];
            $list = $lendInfoModel->getLendInfoByUserId($userId);
            //调用视图显示
            require __VIEW__ . 'personal_lendinfo_list.html';
        }
    }


}
