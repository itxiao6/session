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
class Mysql implements Storage,\SessionHandlerInterface
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
     * @param null $pdo
     * @param string $dns
     * @param string $user
     * @param string $password
     * @param string $table_name
     */
    public function __construct($pdo=null,$dns = '',$user = '',$password = '',$table_name = '')
    {
        /**
         * 判断是否自定义了表
         */
        if($table_name!=''){
            $this -> table = $table_name;
        }
        if($pdo != null){
            /**
             * 直接设置PDO
             */
            $this -> connect = $pdo;
        }else{
            /**
             * 创建PDO 连接
             */
            $this -> connection($dns,$user,$password);
        }
        /**
         * 查询当前表
         */
        $result = $pdo->query("SHOW TABLES LIKE '". $this -> table."'");
        /**
         * 解析结果集
         */
        $row = $result->fetchAll();
        /**
         * 判断表名是否存在
         */
        if(count($row) < 1){
            /**
             * 建表
             */
            $this -> connect -> query(sprintf($this -> creatTable,$this -> table));
        }
        session_save_path(ROOT_PATH.'runtime/session/');
        session_set_save_handler(
            [&$this,'open'],
            [&$this,'close'],
            [&$this,'read'],
            [&$this,'write'],
            [&$this,'destroy'],
            [&$this,'gc']);
        /**
         * 启动session
         */
        session_start();
    }

    /**
     * 创建PDO连接
     * @param $dns
     * @param $user
     * @param $pwd
     * @throws Exception
     */
    public function connection($dns,$user,$pwd) {
        try {
            $this -> connect = new PDO($dns, $user, $pwd, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ));
        } catch (Exception $e) {
            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * 读取session
     * @param $sessionId
     * @return string
     * @throws Exception
     */
    public function read($sessionId) {
        try {
            /**
             * 获取当前时间
             */
            $time = time();
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
            $stmt->execute([$sessionId, $time]);
            /**
             * 解析结果集
             */
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            /**
             * 判断数据是否存在
             */
            if ($data['count'] = 0) {
                return '';
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
            $stmt->execute([$sessionId, $time]);
            /**
             * 解析结果集
             */
            $data = $stmt -> fetch(PDO::FETCH_ASSOC);
            /**
             * 判断是否要返回数据
             */
            if($data == false){
                return '';
            }else{
                return $data['data'];
            }
        } catch (Exception $e) {
            return '';
//            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * 写入session
     * @param $sessionId
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function write($sessionId, $data) {
        try {
            /**
             * 获取过期时间
             */
            $expire = time() + get_cfg_var('session.gc_maxlifetime');
            /**
             * 定义Sql 语句
             */
            $sql = "INSERT INTO {$this -> table} (skey,data,expire) values (?, ?, ?) "
                . "ON DUPLICATE KEY UPDATE data = ?, expire = ?";
            /**
             * 预编译sql
             */
            $stmt = $this -> connect ->prepare($sql);
            /**
             * 执行sql
             */
            return (Bool) $stmt -> execute([$sessionId, $data, $expire, $data, $expire]);
        } catch (Exception $e) {
            return false;
//            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * 销毁session
     * @param $sessionId
     * @return bool
     * @throws Exception
     */
    public function destroy($sessionId) {
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
            $stmt->execute(array($sessionId));
            /**
             * 返回结果
             */
            return true;
        } catch (Exception $e) {
            return false;
//            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * gc 函数
     * @param $lifetime
     * @return bool
     * @throws Exception
     */
    public function gc($lifetime) {
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
//            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * id 函数
     * @return bool
     * @throws Exception
     */
    function id() {
        if (filter_input(INPUT_GET, session_name()) == '' and
            filter_input(INPUT_COOKIE, session_name()) == '') {
            try {
                /**
                 * 定义sql 并执行 语句
                 */
                $stmt = $this -> connect -> query('SELECT uuid() AS uuid');
                /**
                 * 解析结果集
                 */
                $data = $stmt -> fetch(PDO::FETCH_ASSOC);
                /**
                 * 替换session_id 的-
                 */
                $data = str_replace('-', '', $data['uuid']);
                /**
                 * 修改session id
                 */
                session_id($data);
                /**
                 * 返回结果
                 */
                return true;
            } catch (Exception $e) {
                throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
            }

        }
    }

    /**
     * 打开session
     * @param $savePath
     * @param $sessionName
     * @return bool
     */
    public function open($savePath, $sessionName) {
        return true;
    }

    /**
     * 关闭session
     * @return bool
     */
    public function close() {
        return true;
    }

}