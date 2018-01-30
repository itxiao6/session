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
    protected $interfaces = [
        'Local'=>\Itxiao6\Session\Storage\Local::class,
        'Redis'=>\Itxiao6\Session\Storage\Redis::class,
        'Mysql'=>\Itxiao6\Session\Storage\Mysql::class
    ];
    /**
     * 存储驱动实例
     * @var null | object
     */
    protected $storage = null;
    /**
     * 使用驱动
     * @var bool | string
     */
    protected $driver = 'Local';
    /**
     * Session实例
     * @var null | object
     */
    protected $example = null;
    /**
     * Session 名称
     * @var string
     */
    protected $name = 'Minkernel';
    /**
     * 请求实例 (swoole 模式下才用的到)
     * @var null | object
     */
    protected $request = null;
    /**
     * 响应实例 (swoole 模式下才用的到)
     * @var null | object
     */
    protected $response = null;

    /**
     * 构造方法
     */
    protected function __construct()
    {

    }

    /**
     * 获取接口
     */
    public static function getInterface()
    {
        return new static(...func_get_args());
    }

    /**
     * 启动session 回话
     * @return $this
     */
    public function session_start()
    {
        /**
         * 实例化存储器
         */
        $this -> storage = new $this -> interfaces[$this -> driver](...func_get_args());
        return $this;
    }

    /**
     * 设置使用的驱动
     * @param $type
     */
    public function set_driver($type)
    {
        $this -> driver = $type;
    }

    /**
     * 获取驱动
     * @return bool|string
     */
    public function get_driver()
    {
        return $this -> driver;
    }

    /**
     * 获取存储接口
     * @param null $key
     * @return array|mixed
     */
    public function get_interface($key = null)
    {
        if(self::$key!=null){
            return $this -> interfaces[$key];
        }else{
            return $this -> interfaces;
        }
    }

    /**
     * 设置存储接口
     * @param null|array $key
     * @param null|string $value
     * @return $this
     */
    public function set_interface($key = null,$value = null)
    {
        if(is_array($key) && $value==null){
            foreach ($key as $key=>$val){
                $this -> interfaces[$key] = $val;
            }
        }else{
            $this -> interfaces[$key] = $value;
        }
        return $this;
    }

    /**
     * 设置存储实例
     * @param $object
     * @return $this
     */
    public function set_storage($object)
    {
        $this -> storage = $object;
        return $this;
    }

    /**
     * 获取存储实例
     * @return null|object
     */
    public function get_storage()
    {
        return $this -> storage;
    }

    /**
     * 获取session 操作实例
     * @return null|object
     */
    public function get_example()
    {
        return $this -> example;
    }

    /**
     * 设置session 名称
     * @param $name
     * @return $this
     */
    public function set_session_name($name)
    {
        $this -> name = $name;
        return $this;
    }

    /**
     * 获取session 名称
     * @return string
     */
    public function get_session_name()
    {
        return $this -> name;
    }

    /**
     * 设置请求 (swoole 模式下才用的到)
     * @param $request
     * @return $this
     */
    public function set_request($request)
    {
        $this -> request = $request;
        return $this;
    }

    /**
     * 获取请求 (swoole 模式下才用的到)
     * @return null|object
     */
    public function get_request()
    {
        return $this -> request;
    }

    /**
     * 设置响应 (swoole 模式下才用的到)
     * @param $response
     * @return $this
     */
    public function set_response($response)
    {
        $this -> response = $response;
        return $this;
    }

    /**
     * 获取响应 (swoole 模式下才用的到)
     * @return null|object
     */
    public function get_response()
    {
        return $this -> response;
    }
}