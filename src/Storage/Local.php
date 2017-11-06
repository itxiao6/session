<?php
namespace Itxiao6\Session\Storage;
use Itxiao6\Session\Interfaces\Storage;

/**
 * Local 存储
 * Class Local
 * @package Itxiao6\Session\Storage
 */
class Local implements Storage
{
    /**
     * 定义session 存储路径
     * @var string
     */
    protected $path = '/tmp/';
    /**
     * 读取session 数据
     * @param $session_id
     */
    public function get($session_id)
    {
        return unserialize(file_get_contents(preg_replace('!\/$!','',$this -> path).'/'.$session_id));
    }

    /**
     * 写入session
     * @param $session_id
     * @param $data
     */
    public function set($session_id,$data)
    {
        return file_put_contents(preg_replace('!\/$!','',$this -> path).'/'.$session_id,serialize($data));
    }

    /**
     * 实例化存储器
     * @param $path
     */
    public function __construct($path = null)
    {
        $this -> path = ($path===null)?$this -> path:$path;
    }
}