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
     * 读取session 数据
     * @param $session_id
     * @return mixed
     */
    public function get($session_id)
    {
//        TODO 获取session内容
    }

    /**
     * 写入session
     * @param $session_id
     * @param $data
     * @return bool|int
     */
    public function set($session_id,$data)
    {
//        TODO 写入session
    }
    /**
     * 垃圾回收
     * @return bool
     * @throws Exception
     */
    public function gc()
    {
//        TODO 文件垃圾回收机制
    }
    /**
     * 销毁session
     * @param $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
//        TODO 销毁session
    }
    protected function connection($host,$port,$pwd)
    {
        $redis = new \Redis();
        $redis -> connect($host,$port);
        return $redis;
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
        if($redis != null){
            $redis = $this -> connection($host,$port,$pwd);
        }
        # 设置连接
        $this -> redis = $redis;
    }
}