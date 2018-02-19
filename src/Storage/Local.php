<?php
namespace Itxiao6\Session\Storage;
use Itxiao6\Session\Interfaces\Storage;
use Itxiao6\Session\Tools\Session;

/**
 * Local 存储
 * Class Local
 * @package Itxiao6\Session\Storage
 */
class Local implements Storage
{
    /**
     * 定义session 存储路径
     * @var string
     */
    protected $path = '/tmp/';

    /**
     * session 入口对象
     * @var null|object
     */
    protected $session = null;
    /**
     * session 数据
     * @var mixed
     */
    protected $data = null;

    /**
     * 读取session 数据
     * @param null $name
     * @return bool|mixed|null
     */
    public function get($name=null)
    {
        /**
         * 判断是否要获取数据
         */
        if($this -> data === null){
            # 判断session文件是否存在
            if(file_exists(preg_replace('!\/$!','',$this -> path).'/'.$this -> session_id())){
                # 获取session 数据
                $this -> data = unserialize(file_get_contents(preg_replace('!\/$!','',$this -> path).'/'.$this -> session_id()));
            }else{
                return null;
            }
        }
        /**
         * 判断是否过期
         */
        if($this -> data['expire'] <= time()){
            $this -> destroy();
            return false;
        }
        /**
         * 判断是否要取全部的数据
         */
        if($name === null){
            return $data['data'];
        }
        /**
         * 返回要获取的数据
         */
        return isset($this -> data['data'][$name])?$this -> data['data'][$name]:null;
    }

    /**
     * 写入session
     * @param $data
     * @return bool|int|mixed
     */
    public function set($key,$data = null)
    {
        /**
         * 判断session目录是否存在
         */
        if(!is_dir($this -> path)){
            mkdir($this -> path,'0777',true);
        }
        /**
         * 判断是否要写入键=>值
         */
        if(is_string($key)){
            $this -> data = ['data'=>[$key=>$data],'expire'=>$this -> expire()];

        }
        /**
         * 判断是否要直接写入一个数据
         */
        if(is_array($key)){
            $this -> data = ['data'=>$key,'expire'=>$this -> expire()];
        }
        return file_put_contents(
            preg_replace('!\/$!','',$this -> path).'/'.$this -> session_id(),
            serialize($this -> data));
    }

    /**
     * 垃圾回收
     * @return mixed|void
     */
    public function gc(){
//        TODO 文件垃圾回收机制
    }

    /**
     * 销毁session
     * @return bool|mixed
     */
    public function destroy()
    {
        return unlink(preg_replace('!\/$!','',$this -> path).'/'.$this -> session -> session_id());
    }

    /**
     * 调用不存在的方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this -> session -> $name(...$arguments);
    }

    /**
     * 实例化存储器
     * @param $path
     */
    public function __construct($session,$path = null)
    {
        /**
         * 获取入口对象
         */
        $this -> session = $session;
        /**
         * 设置本地存储驱动要设置的参数
         */
        $this -> path = ($path===null)?$this -> path:$path;
    }
}