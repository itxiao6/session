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
     * 获取session 数据
     * @param $session_id
     */
    public function get($session_id)
    {

    }

    /**
     * 设置session数据
     * @param $session_id
     * @param $data
     */
    public function set($session_id,$data)
    {

    }
    /**
     * Redis 存储器
     * @param string $host
     * @param int $port
     * @param null $pwd
     */
    public function __construct($host='127.0.0.1',$port = 6379,$pwd = null)
    {
        ini_set("session.save_handler", "redis");
        ini_set("session.save_path", "tcp://{$host}:{$port}".(($pwd!==null)?"?auth={$pwd}":''));
        /**
         * 启动session
         */
        session_start();
    }
}