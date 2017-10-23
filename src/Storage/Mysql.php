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
        if(count($row) !=1){
            /**
             * 建表
             */
            $this -> connect -> query(sprintf($this -> creatTable,$this -> table));
        }
        session_module_name('user');
        session_set_save_handler(
            [&$this,'Open'],
            [&$this,'Close'],
            [&$this,'Read'],
            [&$this,'Write'],
            [&$this,'Destroy'],
            [&$this,'Gc']);
        register_shutdown_function('session_write_close');
        $this -> Id();
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
    public function Read($sessionId) {
        try {
            $time = time();
            $sql = "SELECT count(*) AS 'count' FROM ".$this -> table
                ." WHERE skey = ? and expire > ?";
            $stmt = $this -> connect ->prepare($sql);
            $stmt->execute([$sessionId, $time]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data['count'] = 0) {
                return '';
            }

            $sql = "SELECT 'data' FROM ".$this ->table
                . " WHERE 'skey' = ? and 'expire' > ?";
            $stmt = $this -> connect->prepare($sql);
            $stmt->execute([$sessionId, $time]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data['data'];
        } catch (Exception $e) {
            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * 写入session
     * @param $sessionId
     * @param $data
     * @throws Exception
     */
    public function Write($sessionId, $data) {
        try {
            $expire = time() + get_cfg_var('session.gc_maxlifetime');

            $sql = "INSERT INTO '{$this -> table}' ('skey', 'data', 'expire') "
                . "values (?, ?, ?) "
                . "ON DUPLICATE KEY UPDATE data = ?, expire = ?";
            $stmt = $this -> connect ->prepare($sql);
            $stmt->execute([$sessionId, $data, $expire, $data, $expire]);
        } catch (Exception $e) {
            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * 销毁session
     * @param $sessionId
     * @return bool
     * @throws Exception
     */
    public function Destroy($sessionId) {
        try {
            $sql = "DELETE FROM '{$this -> table}' where skey = ?";
            $stmt = $this -> connect -> prepare($sql);
            $stmt->execute(array($sessionId));
            return true;
        } catch (Exception $e) {
            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * gc 函数
     * @param $lifetime
     * @return bool
     * @throws Exception
     */
    public function Gc($lifetime) {
        try {
            $sql = "DELETE FROM '{$this -> table}' WHERE expire < ?";
            $stmt = $this -> connect -> prepare($sql);
            $stmt->execute([time()]);
            $dbh = null;
            return true;
        } catch (Exception $e) {
            throw new Exception($e ->getMessage(),$e->getCode(),$e-> getPrevious());
        }
    }

    /**
     * id 函数
     * @return bool
     * @throws Exception
     */
    function Id() {
        if (filter_input(INPUT_GET, session_name()) == '' and
            filter_input(INPUT_COOKIE, session_name()) == '') {
            try {
                $stmt = $this -> connect -> query('SELECT uuid() AS uuid');
                $data = $stmt -> fetch(PDO::FETCH_ASSOC);
                $data = str_replace('-', '', $data['uuid']);
                session_id($data);
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
    public function Open($savePath, $sessionName) {
        return true;
    }

    /**
     * 关闭session
     * @return bool
     */
    public function Close() {
        return true;
    }

}