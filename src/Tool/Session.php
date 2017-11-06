<?php
namespace Itxiao6\Session\Tool;
use Itxiao6\Session\Interfaces\Storage;
/**
 * Session 操作类
 * Class Session
 * @package Itxiao6\Session\Tool
 */
class Session
{
    /**
     * Session ID
     * @var string
     */
    protected $session_id = '';

    /**
     * 存储实例
     * @var null | object
     */
    protected $session_storage = null;

    /**
     * session 数据
     * @var null | array | object
     */
    protected $session_data = null;

    /**
     * Session 构造器
     * @param $storage
     */
    public function __construct($storage)
    {
        # 判断存储实例是否集成了存储接口
        if(!($storage instanceof Storage)){
            return false;
        }
        # 保存session 存储实例
        $this -> session_storage = $storage;
        # 获取当前session_id
        if(Cookie::get_cookie(\Itxiao6\Session\Session::get_session_name())){
            # 重新生成session_id
//            TODO 生成session_id 规则
        }
        # 获取session 内容 并创建数据对象
        $this -> session_data = Data::create($this -> session_storage -> get($this -> session_id));

    }

    /**
     * 保存session 数据
     * @return mixed
     */
    public function save()
    {
        return $this -> session_storage -> set($this -> session_id,$this -> session_data -> get());
    }

}