<?php
namespace Itxiao6\Session;

/**
 * Class Session
 * @package Itxiao6\Session
 */
class Session
{
    /**
     * Session实例
     * @var array
     */
    protected $example = [];
    /**
     * session 构造器配置
     * @var null|array
     */
    protected $session_config = [
        'driver'=>'Local',
        'session_name'=>'PHPSESSION',
        'session_path'=>'/',
        // 默认一周的有效期
        'session_expire'=>0,
        'interfaces'=>[
            'Local'=>\Itxiao6\Session\Storage\Local::class,
            'Redis'=>\Itxiao6\Session\Storage\Redis::class,
            'Mysql'=>\Itxiao6\Session\Storage\Mysql::class
        ],
    ];
    /**
     * 是否已经启动session
     * @var bool
     */
    protected $is_start = false;
    /**
     * 请求
     * @var null | Request
     */
    protected $request = null;
    /**
     * 响应
     * @var null | Response
     */
    protected $response = null;

    /**
     * 构造方法
     */
    protected function __construct($request,$response)
    {
        # 设置请求
        $this -> request = $request;
        # 设置响应
        $this -> response = $response;
        # 判断是否已经设置了 session 有效期
        if($this -> config('session_expire') < 1){
            $this -> config('session_expire',604800);
        }
        # 获取请求内的session id
        $session_id = isset($request -> RawRequest() -> cookie[$this -> config('session_name')])?$request -> RawRequest() -> cookie[$this -> config('session_name')]:false;
        # 判断是否需要重置 session id
        if(in_array($session_id,[null,false,''])){
            $this -> session_id(self::getARandLetter(30));
        }else{
            $this -> session_id($session_id);
        }
    }

    /**
     * 获取接口
     * @param $request
     * @param $response
     * @return static
     */
    public static function getInterface($request,$response)
    {
        return new static($request,$response);
    }

    /**
     * 启动会话
     * @return mixed
     * @throws \Exception
     */
    public function start()
    {
        /**
         * 判断session id 是否存在
         */
        if($this -> config('session_id') === null){
            $this -> reset_session_id();
        }
        /**
         * 实例化存储器
         */
        if($this -> is_start){
            throw new \Exception('会话已经启动');
        }
        /**
         * 判断有效期是否小于当前时间
         */
        if($this -> expire() < time()){
            $this -> expire($this -> expire());
        }
        /**
         * 获取接口实例类
         */
        $class = $this -> config('interfaces')[$this -> config('driver')];
        /**
         * 设置session_id 到cookie
         */
        $this -> response -> RawResponse() -> cookie($this -> session_name(),$this -> session_id(),$this -> expire(),$this -> path());
        /**
         * 实例化接口 并且返回实例
         */
        return $this -> example = new $class($this,...func_get_args());
    }

    /**
     * 重置session_id
     */
    public function reset_session_id()
    {
        # 获取session id 并且 更改配置
        $this -> session_id(self::getARandLetter(30));
    }

    /**
     * cookie 路径设置或 获取
     * @param null|string $val
     * @return Session|mixed
     */
    public function path($val = null)
    {
        if($val === null){
            return $this -> config('session_path');
        }else{
            return $this -> config('session_path',$val);
        }
    }
    /**
     * 设置或获取 过期时间
     * @param null|int $time
     * @return Session|mixed
     */
    public function expire($time = null)
    {
        if($time === null){
            return $this -> config('session_expire');
        }else{
            return $this -> config('session_expire',time()+$time);
        }
    }
    /**
     * 获取随机字符串
     * @param int $number
     * @return bool|string
     */
    protected static function getARandLetter($number = 1)
    {
        # 判断长度是否为0
        if ($number == 0){return false;}
        # 如果小于零取正值
        $number = $number < 0 ? - $number : $number;
        $letterArr = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' );
        $returnStr ='';
        for($i= 0; $i < $number; $i ++) {
            $returnStr .= $letterArr [rand ( 0, 51 )];
        }
        return $returnStr;
    }

    /**
     * 设置配置
     * @param null|string $name
     * @param null|mixed $value
     * @return $this|mixed
     */
    protected function config($name = null,$value=null)
    {
        if($name === null && $value === null){
            return $this -> session_config;
        }
        if(is_string($name) && $value === null){
            return isset($this -> session_config[$name])?$this -> session_config[$name]:null;
        }
        if(is_array($name) && count($name) > 0 && $value === null){
            foreach ($name as $key=>$item){
                $this -> config($key,$item);
            }
        }else{
            $this -> session_config[$name] = $value;
        }
        return $this;
    }

    /**
     * 使用的驱动
     * @param $driverName
     * @return Session
     */
    public function driver($driverName,$driverClass = null)
    {
        if($driverClass === null){
            return $this -> config('driver');
        }else{
            return $this -> config('driver',$driverName);
        }
    }

    /**
     * 存储接口
     * @param $key
     * @param null $value
     * @return $this
     */
    public function interface($key = null,$value = null)
    {
        if(is_string($key) && $value === null){
            return $this -> config('interfaces')[$key];
        }else if($key === null && $value === null){
            return $this -> config('interfaces');
        }
        if(is_array($key) && count($key) > 0 && $value==null){
            foreach ($key as $k=>$val){
                $this -> config('interfaces',array_merge($this -> config('interfaces'),[$k=>$val]));
            }
        }else{
            return $this -> config('interfaces',array_merge($this -> config('interfaces'),[$key=>$value]));
        }
    }

    /**
     * 设置session 名称
     * @param null $name
     * @return $this|Session|mixed
     */
    public function session_name($name = null)
    {
        if($name === null){
            return $this -> config('session_name');
        }
        return $this -> config('session_name',$name);
    }

    /**
     * 设置session id
     * @param $id
     * @return $this|Session|mixed
     */
    public function session_id($id = null)
    {
        if($id === null){
            return $this -> config('session_id');
        }
        return $this -> config('session_id',$id);
    }
}