<?php
namespace Itxiao6\Session\Tools;

use Itxiao6\Session\SessionManager;

/**
 * 会话实例
 * Class Session
 * @package Itxiao6\Session\Tools
 */
class Session
{
    /**
     * 会话实例
     * @var SessionManager
     */
    protected $manager;
    /**
     * 会话id
     * @var null|string
     */
    protected $session_id = null;
    /**
     * 存储器
     * @var null |Storage
     */
    protected $storage = null;
    /**
     * 会话数据
     * @var mixed
     */
    protected $data = null;
    /**
     * 实例化会话
     * Session constructor.
     * @param SessionManager $manager
     */
    public function __construct(SessionManager $manager)
    {
        /**
         * 存储管理器
         */
        $this -> manager = $manager;
        /**
         * 实例化存储器
         */
        $this -> storage = new Storage($manager -> get_driver(),$this -> manager -> config());
        /**
         * 获取session_id
         */
        $this -> get_session_id();
    }

    /**
     * 获取会话id
     * @return null|string
     */
    public function get_session_id()
    {
        /**
         * 判断客户端是否有session_id
         */
        if(strlen($this -> manager -> http() -> getCookie($this -> manager -> config() -> get('session_name'))) == $this ->manager ->config() -> get('session_id_length')){
            /**
             * 设置session_id
             */
            $this -> set_session_id($this -> manager -> http() -> getCookie($this -> manager -> config() -> get('session_name')));
            /**
             * 返回会话id
             */
            return $this -> session_id;
        }
        /**
         * 生成session_id
         */
        if($this -> manager -> config() -> get('session_id_type') == Config::TYPE_STR){
            $this -> set_session_id(Random::randStr($this -> manager -> config() -> get('session_id_length')));
        }else if($this -> manager -> config() -> get('session_id_type') == Config::TYPE_NUMBER){
            $this -> set_session_id(Random::randNumStr($this -> manager -> config() -> get('session_id_length')));
        }
        /**
         * 设置cookie
         */
        $this -> manager -> http() -> setCookie($this -> manager -> config() -> get('session_name'),$this -> session_id,$this -> manager -> config() -> get('session_expire') + time());
        /**
         * 返回会话 id
         */
        return $this -> session_id;
    }

    /**
     * 设置会话id
     * @param $session_id
     */
    public function set_session_id($session_id)
    {
        $this -> session_id = $session_id;
        $this -> update_data();
    }

    /**
     * 更新会话数据
     */
    protected function update_data()
    {
        $this -> data = unserialize($this -> storage -> rand($this -> session_id));
    }

    /**
     * @param $name
     * @return null
     */
    public function get($name = null)
    {
        if($name === null){return $this -> data;}
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
     * 设置session 指定的数据
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name,$value)
    {
        $this -> data[$name] = $value;
        return $this;
    }

    /**
     * 保存当前数据
     * @return bool
     */
    public function save()
    {
        /**
         * 保存数据
         */
        return $this -> storage -> write($this -> session_id,$this -> data);
    }

    /**
     * 销毁前调用保存方法
     */
    public function __destruct()
    {
        $this -> save();
    }

}