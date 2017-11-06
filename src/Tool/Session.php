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
        if(Cookie::get_cookie(\Itxiao6\Session\Session::get_session_name()) == null ||
            Cookie::get_cookie(\Itxiao6\Session\Session::get_session_name()) == ''){
            # 重新生成session_id
            $this -> session_id = self::getARandLetter(20);
            # 写入Cookie
            Cookie::set_cookie(\Itxiao6\Session\Session::get_session_name(),$this -> session_id,time()+3600,'/');
        }
        # 获取session 内容 并创建数据对象
        $this -> session_data = Data::create($this -> session_storage -> get($this -> session_id));

    }

    /**
     * 获取随机字符串
     * @param int $number
     * @return bool|string
     */
    protected static function getARandLetter($number = 1) {
        # 判断长度是否为0
        if ($number == 0){return false;}
        # 如果小于零取正值
        $number = $number < 0 ? - $number : $number;
        $letterArr = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' );
        $returnStr ='';
        for($i= 0; $i < $number; $i ++) {
            $returnStr .= $letterArr [rand ( 0, 51 )];
        }
        return $returnStr;
    }

    /**
     * 设置值
     * @return mixed
     */
    public function set()
    {
        return $this -> session_data -> set(...func_get_args());
    }

    /**
     * 获取值
     */
    public function get()
    {
        return $this -> session_data -> get(...func_get_args());
    }

    /**
     * 保存session 数据
     * @return mixed
     */
    public function save()
    {
        return $this -> session_storage -> set($this -> session_id,$this -> session_data -> all());
    }

}