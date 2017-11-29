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
     * 设置cookie
     */
    public static function set_cookie()
    {
        if(PHP_SAPI === 'cli'){
            return Session::get_response() -> cookie(...func_get_args());
        }else{
            return setcookie(...func_get_args());
        }
    }

    /**
     * 获取cookie
     * @param null $name
     * @return mixed
     */
    public static function get_cookie($name = null)
    {
        if(PHP_SAPI === 'cli'){
            return ($name===null)?Session::get_request() -> cookie:(isset(Session::get_request() -> cookie[$name])?Session::get_request() -> cookie[$name]:null);
        }else{
            return ($name===null)?$_COOKIE:(isset($_COOKIE[$name])?$_COOKIE[$name]:null);
        }
    }
}