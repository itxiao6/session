<?php
namespace Itxiao6\Session\Tools;
use Itxiao6\Route\Bridge\Http;

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
            return Http::get_response() -> cookie(...func_get_args());
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
            return ($name===null)?Http::get_request() -> cookie:Http::get_request() -> cookie[$name];
        }else{
            return ($name===null)?$_COOKIE:(isset($_COOKIE[$name])?$_COOKIE[$name]:null);
        }
    }
}