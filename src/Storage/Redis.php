<?php
namespace Itxiao6\Session\Storage;
use Itxiao6\Session\Interfaces\Storage;
/**
 * Redis 存储
 * Class Redis
 * @package Itxiao6\Session\Storage
 */
class Redis implements Storage
{
    /**
     * Redis 驱动
     * @var null|\Redis
     */
    protected $redis = null;
    /**
     * 读取session 数据
     * @param $session_id
     * @return mixed
     */
    public function get($session_id)
    {
        return unserialize($this -> redis -> get($session_id));
    }

    /**
     * 写入session
     * @param $session_id
     * @param $data
     * @return bool|int
     */
    public function set($session_id,$data)
    {
        return $this -> redis -> set($session_id,serialize($data),time() + get_cfg_var('session.gc_maxlifetime'));
    }
    /**
     * 垃圾回收
     * @return bool
     * @throws Exception
     */
    public function gc()
    {
        return true;
    }
    /**
     * 销毁session
     * @param $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        return $this -> redis -> delete($session_id);
    }

    /**
     * 连接redis
     * @param $host
     * @param $port
     * @param $pwd
     * @return \Redis
     */
    protected function connection($host,$port,$pwd)
    {
        if(!$this -> redis -> ping()){
            $redis = new \Redis();
            return $redis -> connect($host,$port);
        }else{
            return $this -> redis;
        }
    }

    /**
     * 实例化存储器
     * @param null $redis
     * @param string $host
     * @param int $port
     * @param null $pwd
     */
    public function __construct($redis=null,$host='127.0.0.1',$port = 6379,$pwd = null)
    {
        # 判断是否需要连接
        if($redis != null || (!$redis -> ping())){
            $redis = $this -> connection($host,$port,$pwd);
        }
        # 设置连接
        $this -> redis = $redis;
    }
}