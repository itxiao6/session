<?php
namespace Itxiao6\Session;

/**
 * Class Session
 * @package Itxiao6\Session
 */
class Session
{
    /**
     * 存储接口
     * @var array
     */
    protected static $interfaces = [
        'Local'=>\Itxiao6\Session\Storage\Local::class,
        'Redis'=>\Itxiao6\Session\Storage\Redis::class,
        'Mysql'=>\Itxiao6\Session\Storage\Mysql::class
    ];
    /**
     * 使用驱动
     * @var bool | string
     */
    protected static $driver = 'Local';
    /**
     * 存储驱动实例
     * @var null | object
     */
    protected static $storage = null;
    /**
     * Session实例
     * @var null | object
     */
    protected static $example = null;
    /**
     * Session 名称
     * @var string
     */
    protected static $name = 'Minkernel';
    /**
     * 请求实例 (swoole 模式下才用的到)
     * @var null | object
     */
    protected static $request = null;
    /**
     * 响应实例 (swoole 模式下才用的到)
     * @var null | object
     */
    protected static $response = null;

    /**
     * 装饰者模式
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        # 判断是否已经启动了Session
        if(self::$storage === null){
            return false;
        }
        # 判断是否已经实例是否启动
        if(self::$example === null){
            self::$example = new \Itxiao6\Session\Tools\Session(self::$storage);
        }
        # 返回工具类返回的结果
        return self::$example -> $name(...$arguments);
    }

    /**
     * 启动session 回话
     * @return bool
     */
    public static function session_start()
    {
        /**
         * 实例化存储器
         */
        self::$storage = new self::$interfaces[self::$driver](...func_get_args());
    }

    /**
     * 设置使用的驱动
     * @param $type
     */
    public static function set_driver($type)
    {
        self::$driver = $type;
    }

    /**
     * 获取驱动
     * @return bool|string
     */
    public static function get_driver()
    {
        return self::$driver;
    }

    /**
     * 获取存储接口
     * @param null | string $key
     * @return array|mixed
     */
    public static function get_interface($key = null)
    {
        if(self::$key!=null){
            return self::$interfaces[$key];
        }else{
            return self::$interfaces;
        }
    }

    /**
     * 设置存储接口
     * @param null | array | string $key
     * @param null | value $value
     */
    public static function set_interface($key = null,$value = null)
    {
        if(is_array($key) && $value==null){
            foreach ($key as $key=>$val){
                self::$interfaces[$key] = $val;
            }
        }else{
            self::$interfaces[$key] = $value;
        }
    }

    /**
     * 设置存储实例
     * @param $object
     */
    public static function set_storage($object)
    {
        self::$storage = $object;
    }

    /**
     * 获取存储实例
     * @return null|object
     */
    public static function get_storage()
    {
        return self::$storage;
    }

    /**
     * 获取session 操作实例
     * @return null|object
     */
    public static function get_example()
    {
        return self::$example;
    }

    /**
     * 设置session 名称
     * @param $name
     */
    public static function set_session_name($name)
    {
        self::$name = $name;
    }

    /**
     * 获取session 名称
     * @return string
     */
    public static function get_session_name()
    {
        return self::$name;
    }

    /**
     * 设置请求 (swoole 模式下才用的到)
     * @param $request
     * @return mixed
     */
    public static function set_request($request)
    {
        return self::$request = $request;
    }

    /**
     * 获取请求 (swoole 模式下才用的到)
     * @return null|object
     */
    public static function get_request()
    {
        return self::$request;
    }

    /**
     * 设置响应 (swoole 模式下才用的到)
     * @param $response
     * @return mixed
     */
    public static function set_response($response)
    {
        return self::$response = $response;
    }

    /**
     * 获取响应 (swoole 模式下才用的到)
     * @return null|object
     */
    public static function get_response()
    {
        return self::$response;
    }
}