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
     * 实例化存储器
     * @param $path
     */
    public function __construct($path = null)
    {
        $this -> path = ($path===null)?$this -> path:$path;
    }
}