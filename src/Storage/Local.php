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
     * 读取session 数据
     * @param $session_id
     * @return mixed
     */
    public function get($session_id)
    {
        # 判断session文件是否存在
        if(file_exists(preg_replace('!\/$!','',$this -> path).'/'.$session_id)){
            # 获取session 数据
            $data = unserialize(file_get_contents(preg_replace('!\/$!','',$this -> path).'/'.$session_id));
        }else{
            return null;
        }
        # 判断是否过期
        if($data['expire'] <= time()){
            $this -> destroy($session_id);
            return false;
        }
        return $data['data'];
    }

    /**
     * 写入session
     * @param $session_id
     * @param $data
     * @return bool|int
     */
    public function set($session_id,$data)
    {
        return file_put_contents(preg_replace('!\/$!','',$this -> path).'/'.$session_id,serialize(['data'=>$data,'expire'=>Session::get_expire()]));
    }
    /**
     * 垃圾回收
     * @return bool
     * @throws Exception
     */
    public function gc(){
//        TODO 文件垃圾回收机制
    }
    /**
     * 销毁session
     * @param $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        return unlink(preg_replace('!\/$!','',$this -> path).'/'.$session_id);
    }

    /**
     * 实例化存储器
     * @param $path
     */
    public function __construct($path = null)
    {
        $this -> path = ($path===null)?$this -> path:$path;
    }
}