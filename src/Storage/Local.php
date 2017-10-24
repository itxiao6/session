<?php
namespace Itxiao6\Session\Storage;
use Itxiao6\Session\Interfaces\Storage;

/**
 * Local 存储
 * Class Local
 * @package Itxiao6\Session\Storage
 */
class Local implements Storage
{
    /**
     * 本地存储器
     * @param string $session_dir
     */
    public function __construct($session_dir = '/tmp')
    {
        /**
         * 修改session文件的储存位置
         */
        session_save_path($session_dir);
        /**
         * 启动session
         */
        session_start();
    }
}