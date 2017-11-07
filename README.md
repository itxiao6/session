# session
#####基于Cookie 重写了PHP自带的 SESSION 机制，存储介质支持:本地模式、Mysql、Redis php运行模式支持:LAMP、LNMP、SWOOLE
# 启动会话
###1.本地存储方式(默认)
```php
use \Itxiao6\Session\Session;
// 设置存储介质
Session::set_driver('Local'); // 默认为Local
// 启动会话
Session::session_start(ROOT_PATH.'runtime/session');
```
###2.MySql存储介质
```php
# 获取PDO 实例
$pdo = new \PDO($dns, $user, $pwd);
use \Itxiao6\Session\Session;
// 设置存储介质
Session::set_driver('Mysql');
// 启动会话
Session::session_start($pdo);
```
###3.Redis存储介质
```php
$redis = Redis();
$redis -> connect('127.0.0.1', 6379); 
use \Itxiao6\Session\Session;
// 设置存储介质
Session::set_driver('Redis');
// 启动会话
Session::session_start($redis);
```
####更多存储机制可以无限扩展和替换
######备注
```text
    1.存储器必须继承:Itxiao6\Session\Interfaces\Storage 接口
    2.Session::session_start();的参数会传递到存储器的构造方法内,所有开发存储器只需要在构造方法活动连接 即可 
```

```php
# 设置新的存储器
Session::set_interface('Memcache',/MyNameSpace/MyMemcache::class);
$memcache = Memcache();
$memcache -> connect('127.0.0.1', 11211); 
use \Itxiao6\Session\Session;
// 设置存储介质
Session::set_driver('Memcache');
// 启动会话
Session::session_start($memcache);
```
