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
     * @return mixed
     */
    public function get();

    /**
     * 写入session
     * @param $data
     * @return mixed
     */
    public function set($data);

    /**
     * 销毁session
     * @return mixed
     */
    public function destroy();

    /**
     * 垃圾回收机制
     * @return mixed
     */
    public function gc();
}