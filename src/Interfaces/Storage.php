<?php
namespace Itxiao6\Session\Interfaces;
/**
 * 存储接口
 * Interface Storage
 * @package Itxiao6\Session\Interfaces
 */
interface Storage
{
    /**
     * 获取session 数据
     * @param $session_id
     * @return mixed
     */
    public function get($session_id);

    /**
     * 写入session
     * @param $session_id
     * @param $data
     * @return mixed
     */
    public function set($session_id,$data);
}