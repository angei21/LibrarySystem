<?php

namespace Model;

use Core\MyPDO;

/**
 * @name Model
 * @desc 基础模型类,其他xxxModel均继承此类
 * @author wei_wang
 */
class Model {

    protected $mypdo;   //pdo对象
    private $table;     //表名
    private $pk;        //主键

    public function __construct($table) {
        $this->initMyPDO(); //初始化pdo对象
        $this->initTable($table); //初始化表名
        $this->getPrimaryKey(); //初始化主键
    }

    //连接数据库
    private function initMyPDO() {
        $this->mypdo = MyPDO::getInstance($GLOBALS['config']['database']);
    }

    //获取表名
    private function initTable($table) {
        $this->table = $table;
    }

    //获取主键
    private function getPrimaryKey() {
        $rs = $this->mypdo->fetchAll("desc `{$this->table}`");
        foreach ($rs as $rows) {
            if ($rows['Key'] == 'PRI') {
                $this->pk = $rows['Field'];
                break;
            }
        }
    }

    /**
     * 提供完整数据的通用插入
     *
     * @param array $data pojo类的新增数据集
     * @return integer 受影响的行数
     */
    public function insert($data) {
        $keys = array_keys($data);  //获取所有的字段名
        $keys = array_map(function ($key) { //在所有的字段名上添加反引号
            return "`{$key}`";
        }, $keys);
        $keys = implode(',', $keys);  //字段名用逗号连接起来
        $values = array_values($data); //获取所有的值
        $values = array_map(function ($value) { //所有的值上添加单引号
            return "'{$value}'";
        }, $values);
        $values = implode(',', $values); //值通过逗号连接起来
        $sql = "insert into `{$this->table}` ($keys) values ($values)";
        return $this->mypdo->exec($sql);
    }


    /**
     * 提供完整数据的通用更新
     *
     * @param array $data pojo类的修改数据集
     * @return integer 受影响的行数
     */
    public function update($data) {
        $keys = array_keys($data); //获取所有键
        $index = array_search($this->pk, $keys); //返回主键在数组中的下标
        unset($keys[$index]);  //删除主键
        $keys = array_map(function ($key) use ($data) {
            return "`{$key}`='{$data[$key]}'";
        }, $keys);
        $keys = implode(',', $keys);
        $sql = "update `{$this->table}` set $keys where $this->pk='{$data[$this->pk]}'";
        return $this->mypdo->exec($sql);
    }

    /**
     * 根据主键id删除该条数据
     *
     * @param integer $id 主键
     * @return integer 受影响的行数
     */
    public function delete($id) {
        $sql = "delete from `{$this->table}` where `{$this->pk}`='$id'";
        return $this->mypdo->exec($sql);
    }


    /**
     * 根据条件组查询并返回结果集
     *
     * @param array $cond 条件组
     * @return array 包含结果集中所有行的数组
     */
    public function select($cond = array()) {
        $sql = "select * from `{$this->table}` where 1";
        if (!empty($cond)) {
            foreach ($cond as $k => $v) {
                //如果条件的值是数组类型
                if (is_array($v)) {
                    //$v[0]保存的是符号，$v[1]是值
                    switch ($v[0]) {
                        case 'eq':
                            $op = '=';
                            break;
                        case 'gt':
                            $op = '>';
                            break;
                        case 'lt':
                            $op = '<';
                            break;
                        case 'gte':
                        case 'egt':
                            $op = '>=';
                            break;
                        case 'lte':
                        case 'elt':
                            $op = '<=';
                            break;
                        case 'neq':
                            $op = '<>';
                            break;
                        case 'like':
                            $op = 'LIKE';
                            $v[1] = $v[1] . '%';
                            break;
                    }
                    $sql .= " and `$k` $op '$v[1]'";
                } else {
                    $sql .= " and `$k`='$v'";
                }
            }
        }
        return $this->mypdo->fetchAll($sql);
    }

    /**
     * 根据主键id返回结果
     *
     * @param integer $id 主键id
     * @return array 单条记录
     */
    public function find($id) {
        $sql = "select * from `{$this->table}` where `{$this->pk}`='$id'";
        return $this->mypdo->fetchRow($sql);
    }


    /**
     * 原始SQL查询,返回二维数组
     *
     * @param string $sql sql语句
     * @param string $type 匹配类型
     * @return array 包含结果集中所有行的数组
     */
    public function fetchAll($sql, $type = 'assoc') {
        return $this->mypdo->fetchAll($sql, $type);
    }

    /**
     * 原始SQL查询,返回一维数组
     *
     * @param string $sql sql语句
     * @param string $type 匹配类型
     * @return array 单条记录
     */
    public function fetchRow($sql, $type = 'assoc') {
        return $this->mypdo->fetchRow($sql, $type);
    }

    /**
     * 原始SQL查询,返回单个域值
     *
     * @param string $sql sql语句
     * @return string 单个域值
     */
    public function fetchColumn($sql) {
        return $this->mypdo->fetchColumn($sql);
    }

    /**
     * 原始SQL查询,执行增、删、改操作
     *
     * @param string $sql sql语句
     * @return integer 受影响的行数
     */
    public function exec($sql) {
        return $this->mypdo->exec($sql);
    }

}
