<?php
namespace Itxiao6\Session\Tool;
/**
 * session 数据类
 * Class Data
 * @package Itxiao6\Session\Tool
 */
class Data
{
    /**
     * 数据
     * @var null | array | object
     */
    protected $data = null;

    /**
     * 创建一个数据类
     */
    public static function create()
    {
        return new self(func_get_args());
    }

    /**
     * 数据类构造方法
     * @param null $data
     */
    protected function __construct($data = null)
    {
        $this -> data = $data;
    }

    /**
     * 设置值
     */
    public function set()
    {
//        TODO 设置session 数据
    }

    /**
     * 获取数据
     */
    public function get()
    {
//        TODO 获取session 数据
    }
    /**
     * 返回所有数据
     */
    public function all()
    {
        return $this -> data;
    }
}