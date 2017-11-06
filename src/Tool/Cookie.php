<?php
namespace Itxiao6\Session\Tool;
use Itxiao6\Route\Bridge\Http;

/**
 * Cookie 操作类
 * Class Cookie
 * @package Itxiao6\Session\Tool
 */
class Cookie
{
    /**
     * 设置cookie
     */
    public static function set_cookie()
    {
        return Http::get_response() -> cookie(...func_get_args());
    }

    /**
     * 获取cookie
     * @param null $name
     * @return mixed
     */
    public static function get_cookie($name = null)
    {
        return ($name===null)?Http::get_request() -> cookie:Http::get_request() -> cookie[$name];
    }
}