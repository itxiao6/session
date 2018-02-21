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
     * session 实例
     * @var null|object
     */
    protected $session = null;
    /**
     * session 数据
     * @var null
     */
    protected $data = null;
    /**
     * 读取session 数据
     * @param null $name
     * @return bool|mixed|null
     */
    public function get($name=null)
    {
        /**
         * 判断是否要获取数据
         */
        if($this -> data === null){
            # 判断session文件是否存在
            if($this -> redis -> exists("session_".$this -> session_id())){
                # 获取session 数据
                $this -> data = unserialize($this -> redis -> get("session_".$this -> session_id()));
            }else{
                return null;
            }
        }
        /**
         * 判断是否过期
         */
        if($this -> data['expire'] <= time()){
            $this -> destroy();
            return false;
        }
        /**
         * 判断是否要取全部的数据
         */
        if($name === null){
            return $data['data'];
        }
        /**
         * 返回要获取的数据
         */
        return isset($this -> data['data'][$name])?$this -> data['data'][$name]:null;
    }

    /**
     * 写入session
     * @param $data
     * @return bool|int|mixed
     */
    public function set($key,$data = null)
    {
        /**
         * 判断session目录是否存在
         */
        if(!is_dir($this -> path)){
            mkdir($this -> path,'0777',true);
        }
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
        return $this -> redis -> set("session_".$this -> session_id(),serialize($this -> data),$this -> expire());
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
    public function destroy()
    {
        return $this -> redis -> delete("session_".$this -> session_id());
    }

    /**
     * 实例化存储器
     * Redis constructor.
     * @param $session
     * @param \Redis $redis
     */
    public function __construct($session,\Redis $redis)
    {
        /**
         * 获取session 实例
         */
        $this -> session = $session;
        /**
         * 获取redis 连接
         */
        $this -> redis = $redis;
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