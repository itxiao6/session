<?php
namespace Itxiao6\Session\Tools;
/**
 * 存储
 * Class Storage
 * @package Itxiao6\Session\Tools
 */
class Storage
{
    /**
     * 驱动
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $driver;
    /**
     * 配置
     * @var Config
     */
    protected $config;

    /**
     * 实例化存储器
     * Storage constructor.
     * @param \Doctrine\Common\Cache\Cache $driver
     * @param Config $config
     */
    public function __construct(\Doctrine\Common\Cache\Cache $driver,Config $config)
    {
        $this -> driver = $driver;
        $this -> config = $config;
    }

    public function rand($session_id):string
    {
        return $this -> driver -> fetch($this -> config -> get('session_storage_prefix').$session_id);
    }
    public function clear($session_id):bool
    {
        return $this -> driver -> save($this -> config -> get('session_storage_prefix').$session_id,null);
    }
    public function delete($session_id):bool
    {
        return $this -> driver -> delete($this -> config -> get('session_storage_prefix').$session_id);
    }
    public function write($session_id,$data):bool
    {
        return$this -> driver -> save($this -> config -> get('session_storage_prefix').$session_id,serialize($data),$this -> config -> get('session_expire'));
    }
}