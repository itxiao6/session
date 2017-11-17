<?php
namespace Itxiao6\Session\Tools;
/**
 * session 数据类
 * Class Data
 * @package Itxiao6\Session\Tools
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
     * @return Data
     */
    public static function create()
    {
        return new self(...func_get_args());
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
     * @param null $name
     * @param null $value
     * @return null
     */
    public function set($name = null,$value = null)
    {
        return ($value === null)?$this -> data = $name:$this -> data[$name] = $value;
    }

    /**
     * 获取数据
     * @param null $name
     * @return array|mixed|null|object
     */
    public function get($name = null)
    {
        return ($name===null)?$this -> data:$this -> data[$name];
    }

    /**
     * 返回所有数据
     * @return array|null|object
     */
    public function all()
    {
        return $this -> data;
    }
}