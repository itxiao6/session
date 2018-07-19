<?php
namespace Itxiao6\Session\Tools;
/**
 * Swoole 相关
 * Class Swoole
 * @package Itxiao6\Session\Tools
 */
class Swoole
{
    /**
     * 请求
     * @var null|\swoole_http_request
     */
    protected $request = null;
    /**
     * 响应句柄
     * @var null|\swoole_http_response
     */
    protected $response = null;

    /**
     * 设置请求
     * @param null $request
     * @return $this|null
     */
    public function request($request = null)
    {
        if($request === null){
            $this -> request = $request;
            return $this;
        }
        return $request;
    }

    /**
     * 设置响应句柄
     * @param $response
     * @return $this|null|\swoole_http_response
     */
    public function response($response)
    {
        if($response === null){
            $this -> response = $response;
            return $this;
        }
        return $this -> response;
    }
    /**
     * 获取客户端cookie数据
     * @param string $name
     * @return mixed
     */
    public function getCookie($name = '')
    {
        $path = explode('.',$name);
        $value = $this -> request -> cookie;
        foreach ($path as $item){
            if($item == ''){
                break;
            }
            $value = isset($value[$item])?$value[$item]:null;
        }
        return $value;
    }

    /**
     * 设置cookie
     * @param $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return bool
     */
    public function setCookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        return $this -> response -> cookie(...func_get_args());
    }
}