<?php
namespace Itxiao6\Session\Tools;

/**
 * 配置
 * Class Config
 * @package Itxiao6\Session\Tools
 */
class Config
{
    /**
     * 会话id类型 1 字符（数字+字母）
     */
    CONST TYPE_STR = 1;
    /**
     * 会话id 2 数字（纯数字）
     */
    CONST TYPE_NUMBER = 2;
    /**
     * 配置
     * @var array
     */
    protected $data = [
        'session_name'=>'PHPSESSION',
        'session_path'=>'/',
        'session_id_length'=>32,
        'session_id_type'=>1,
        'session_storage_prefix'=>'itxiao6_session_',
        // 默认一周的有效期
        'session_expire'=>null,
    ];

    /**
     * 实例化配置
     * Config constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this -> data = array_merge($this -> data,$config);
    }

    /**
     * 获取配置
     * @param string $name
     * @return array|mixed|null
     */
    public function get($name = '')
    {
        $path = explode('.',$name);
        $value = $this -> data;
        foreach ($path as $item){
            if($item == ''){
                break;
            }
            $value = isset($value[$item])?$value[$item]:null;
        }
        return $value;
    }

    /**
     * 修改配置
     * @param $name
     * @param $value
     */
    public function set($name,$value)
    {
        $this -> data[$name] = $value;
    }
}