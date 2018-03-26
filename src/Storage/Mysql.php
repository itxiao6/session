<?php
namespace Itxiao6\Session\Storage;
use Itxiao6\Session\Interfaces\Storage;
use Exception;
use PDO;
/**
 * Mysql 存储器
 * Class Mysql
 * @package Itxiao6\Session\Storage
 */
class Mysql implements Storage
{

    /**
     * pdo 链接
     * @var bool | object
     */
    protected $connect = false;

    /**
     * 表名
     * @var string
     */
    protected $table = 'session';
    /**
     * 会话数据
     * @var mixed
     */
    protected $data = null;
    /**
     * session 实例
     * @var null | object
     */
    protected $session = null;

    /**
     * 建表语句
     * @var string
     */
    protected $creatTable = "
        CREATE TABLE %s (
          skey char(32) CHARACTER SET ascii NOT NULL,
          data text COLLATE utf8mb4_bin,
          expire int(11) NOT NULL,
          PRIMARY KEY (skey),
          KEY index_session_expire (expire) USING BTREE
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
    ";

    /**
     * Mysql 存储器
     * Mysql constructor.
     * @param $session
     * @param null $pdo
     * @param null $table_name
     */
    public function __construct($session,$pdo=null,$table_name = null)
    {
        /**
         * 获取session 实例
         */
        $this -> session = $session;
        /**
         * 判断是否自定义了表
         */
        if($table_name!=null){
            $this -> table = $table_name;
        }
        /**
         * 设置PDO
         */
        $this -> connect = $pdo;
        /**
         * 查询当前表
         */
        $result = $pdo -> query("SHOW TABLES LIKE '". $this -> table."'");
        /**
         * 解析结果集
         */
        $row = $result -> fetchAll();
        /**
         * 判断表名是否存在
         */
        if(count($row) < 1){
            /**
             * 建表
             */
            $this -> connect -> query(sprintf($this -> creatTable,$this -> table));
        }
    }

    /**
     * 获取session 数据
     * @param null $name
     * @return bool|mixed|null
     */
    public function get($name=null)
    {
        /**
         * 判断是否要获取数据
         */
        if($this -> data === null){
            try {
                /**
                 * 定义sql语句
                 */
                $sql = "SELECT count(*) AS 'count' FROM ".$this -> table
                    ." WHERE skey = ? and expire > ?";
                /**
                 * 预编译sql
                 */
                $stmt = $this -> connect -> prepare($sql);
                /**
                 * 执行sql
                 */
                $stmt->execute([$this -> session_id(), time()]);
                /**
                 * 解析结果集
                 */
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                /**
                 * 判断数据是否存在
                 */
                if ($data['count'] = 0) {
                    return null;
                }
                /**
                 * 定义sql 语句
                 */
                $sql = "SELECT data FROM {$this ->table} WHERE skey = ? and expire > ?";
                /**
                 * 预编译sql
                 */
                $stmt = $this -> connect -> prepare($sql);
                /**
                 * 执行sql
                 */
                $stmt->execute([$this -> session_id(), time()]);
                /**
                 * 解析结果集
                 */
                $data = $stmt -> fetch(PDO::FETCH_ASSOC);
                /**
                 * 判断是否要返回数据
                 */
                if($data != false){
                    $this -> data = unserialize($data['data']);
                }else{
                    $this -> data = null;
                }
            } catch (Exception $e) {
                $this -> data = null;
            }
        }
        /**
         * 判断是否过期
         */
        if((!isset($this -> data['expire'])) || $this -> data['expire'] <= time()){
            $this -> destroy();
            return false;
        }
        /**
         * 判断是否要取全部的数据
         */
        if($name === null){
            return isset($this -> data['data'])?$this -> data['data']:null;
        }
        /**
         * 返回要获取的数据
         */
        return isset($this -> data['data'][$name])?$this -> data['data'][$name]:null;
    }

    /**
     * 设置session数据
     * @param $key
     * @param null $data
     * @return bool|mixed
     */
    public function set($key,$data = null)
    {
        /**
         * 判断是否要写入键=>值
         */
        if(is_string($key)){
            $this -> data = ['data'=>[$key=>$data],'expire'=>$this -> expire()];
        }
        /**
         * 判断是否要直接写入一个数据
         */
        if(is_array($key)){
            $this -> data = ['data'=>$key,'expire'=>$this -> expire()];
        }
        try {
            /**
             * 定义Sql 语句
             */
            $sql = "INSERT INTO {$this -> table} (skey,data,expire) values (?, ?, ?) "
                . "ON DUPLICATE KEY UPDATE data = ?, expire = ?";
            /**
             * 预编译sql
             */
            $stmt = $this -> connect -> prepare($sql);
            /**
             * 执行sql
             */
            return (Bool) $stmt -> execute([$this -> session_id(), serialize($this -> data), $this -> expire(), serialize($this -> data), $this -> expire()]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 销毁session
     * @return bool|mixed
     */
    public function destroy()
    {
        try {
            /**
             * 定义sql 语句
             */
            $sql = "DELETE FROM {$this -> table} where skey = ?";
            /**
             * 预编译sql 语句
             */
            $stmt = $this -> connect -> prepare($sql);
            /**
             * 执行sql 语句
             */
            $stmt->execute([$this -> session_id()]);
            /**
             * 返回结果
             */
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 垃圾回收
     * @return bool
     * @throws Exception
     */
    public function gc()
    {
        try {
            /**
             * 定义sql 语句
             */
            $sql = "DELETE FROM {$this -> table} WHERE expire < ?";
            /**
             * 执行sql 语句
             */
            $stmt = $this -> connect -> prepare($sql);
            /**
             * 执行sql语句
             */
            $stmt->execute([time()]);
            /**
             * 返回结果
             */
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * 调用不存在的方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this -> session -> $name(...$arguments);
    }
}