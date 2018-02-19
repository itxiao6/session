<?php
namespace Itxiao6\Session\Tools;
use \Itxiao6\Session\Session;

/**
 * Cookie 操作类
 * Class Cookie
 * @package Itxiao6\Session\Tools
 */
class Cookie
{
    /**
     * 请求
     * @var
     */
    protected $request;
    /**
     * 响应
     * @var
     */
    protected $response;
    /**
     * 获取cookie 操作实例
     * @param Request $request
     * @param Response $response
     */
    public static function getInterface(Request $request,Response $response)
    {
        return new static($request,$response);
    }

    /**
     * 构造方法
     * Cookie constructor.
     * @param Request $request
     * @param Response $response
     */
    protected function __construct(Request $request,Response $response)
    {
        $this -> request = $request;
        $this -> response = $response;
    }

    /**
     * 设置cookie
     */
    public function set_cookie()
    {
        if(PHP_SAPI === 'cli'){
            # TODO swoole 设置cookie
        }else{
            return setcookie(...func_get_args());
        }
    }

    /**
     * 获取cookie
     * @param null $name
     * @return mixed
     */
    public function get_cookie($name = null)
    {
        if(PHP_SAPI === 'cli'){
            # TODO swoole 获取cookie
        }else{
            return ($name===null)?$_COOKIE:(isset($_COOKIE[$name])?$_COOKIE[$name]:null);
        }
    }
}