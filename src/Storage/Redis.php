<?php
namespace Itxiao6\Session\Storage;

/**
 * Redis 存储
 * Class Redis
 * @package Itxiao6\Session\Storage
 */
class Redis implements Storage
{
    /**
     * Redis 存储器
     * Redis constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct($host='127.0.0.1',$port = 6379)
    {
        ini_set("session.save_handler", "redis");
        ini_set("session.save_path", "tcp://{$host}:{$port}");
    }
}