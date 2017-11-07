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
    public function gc(){
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

    /**
     * 实例化存储器
     * @param $path
     */
    public function __construct($host='127.0.0.1',$port = 6379,$pwd = null)
    {
//        TODO 获取Redis 连接池 或进行创建新的 Redis 链接
    }
}