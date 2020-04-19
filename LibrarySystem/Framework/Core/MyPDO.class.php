<?php

namespace Core;

/**
 * @name MyPDO
 * @desc PDO工具类
 * @author wei_wang
 */
class MyPDO {

    private $type;      //数据库类别
    private $host;      //主机地址
    private $port;      //端口号
    private $dbname;    //数据库名
    private $charset;   //字符集
    private $user;      //用户名
    private $pwd;       //密码
    private $pdo;       //保存PDO对象
    private static $instance;

    private function __construct($param) {
        $this->initParam($param);
        $this->initPDO();
        $this->initException();
    }

    private function __clone() {
    }


    /**
     * 对外提供pdo实例
     *
     * @param $param 连接数据库的相关参数
     * @return pdo实例
     */
    public static function getInstance($param = array()) {
        if (!self::$instance instanceof self)
            self::$instance = new self($param);
        return self::$instance;
    }

    //初始化参数
    private function initParam($param) {
        $this->type = (!empty($param['type'])) ? $param['type'] : $GLOBALS['config']['database']['type'];
        $this->host = (!empty($param['host'])) ? $param['host'] : $GLOBALS['config']['database']['host'];
        $this->port = (!empty($param['port'])) ? $param['port'] : $GLOBALS['config']['database']['port'];
        $this->dbname = (!empty($param['dbname'])) ? $param['dbname'] : $GLOBALS['config']['database']['dbname'];
        $this->charset = (!empty($param['charset'])) ? $param['charset'] : $GLOBALS['config']['database']['charset'];
        $this->user = (!empty($param['user'])) ? $param['user'] : $GLOBALS['config']['database']['user'];
        $this->pwd = (!empty($param['pwd'])) ? $param['pwd'] : $GLOBALS['config']['database']['pwd'];
    }

    //初始化PDO
    private function initPDO() {
        try {
            $dsn = "{$this->type}:host={$this->host};port={$this->port};dbname={$this->dbname};charset={$this->charset}";
            $this->pdo = new \PDO($dsn, $this->user, $this->pwd);
        } catch (\PDOException $ex) {
            $this->showException($ex);
            exit;
        }
    }

    //显示异常信息
    private function showException($ex, $sql = '') {
        if ($sql != '') {
            echo 'SQL语句执行失败<br>';
            echo '错误的SQL语句是：' . $sql, '<br>';
        }
        echo '错误编号：' . $ex->getCode(), '<br>';
        echo '错误行号：' . $ex->getLine(), '<br>';
        echo '错误文件：' . $ex->getFile(), '<br>';
        echo '错误信息：' . $ex->getMessage(), '<br>';
    }

    //设置异常模式
    private function initException() {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    //执行增、删、改操作
    public function exec($sql) {
        try {
            return $this->pdo->exec($sql);
        } catch (\PDOException $ex) {
            $this->showException($ex, $sql);
            exit;
        }
    }

    //获取自动增长的编号
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    //判断匹配的类型
    private function fetchType($type) {
        switch ($type) {
            case 'num':
                return \PDO::FETCH_NUM;
            case 'both':
                return \PDO::FETCH_BOTH;
            case 'obj':
                return \PDO::FETCH_OBJ;
            default:
                return \PDO::FETCH_ASSOC;
        }
    }

    //获取所有数据 ，返回二维数组
    public function fetchAll($sql, $type = 'assoc') {
        try {
            $stmt = $this->pdo->query($sql);  //获取PDOStatement对象
            $type = $this->fetchType($type); //获取匹配方法
            return $stmt->fetchAll($type);
        } catch (\Exception $ex) {
            $this->showException($ex, $sql);
        }
    }

    //获取一维数组
    public function fetchRow($sql, $type = 'assoc') {
        try {
            $stmt = $this->pdo->query($sql);  //获取PDOStatement对象
            $type = $this->fetchType($type); //获取匹配方法
            return $stmt->fetch($type);
        } catch (\Exception $ex) {
            $this->showException($ex, $sql);
            exit;
        }
    }

    //返回单个域值
    public function fetchColumn($sql) {
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchColumn();
        } catch (\Exception $ex) {
            $this->showException($ex, $sql);
            exit;
        }
    }

}
