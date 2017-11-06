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
     * 返回序列化过的数据
     */
    public function get_data()
    {
        return $this -> data;
    }
}