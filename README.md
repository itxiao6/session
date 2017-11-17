# session
##### 基于Cookie 重写了PHP自带的 SESSION 机制，存储介质支持:本地模式、Mysql、Redis php运行模式支持:LAMP、LNMP、SWOOLE
## 1.引入入口
```php
use \Itxiao6\Session\Session;
```
## 2.启动会话
### 1.本地存储方式(默认)
```php
// 设置存储介质
Session::set_driver('Local'); // 默认为 Local
// 启动会话
Session::session_start(__DIR__.'/session');
```
### 2.MySql存储介质
```php
// 设置存储介质
Session::set_driver('Mysql'); // 默认为 Local
# 获取pdo 实例
$pdo = new \PDO('mysql:host=localhost;dbname=dbname', 'dbname', 'password');
// 启动会话
Session::session_start($pdo);
```
### 3.Redis存储介质
```php
Session::set_driver('Redis'); // 默认为 Local
$redis = new \Redis();
$redis -> connect('127.0.0.1',6379);
// 启动会话
Session::session_start($redis);
```
### 4.使用Session
```php
# 设置值
Session::set('name','戒尺');
Session::save();
# 获取值
var_dump(Session::get());
```
#### 更多存储机制可以无限扩展和替换
###### 备注
```text
1.存储器必须继承:Itxiao6\Session\Interfaces\Storage 接口
2.Session::session_start();的参数会传递到存储器的构造方法内,所有开发存储器只需要在构造方法获取连接 即可 
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

#### 附录1
  SWOOLE 模式使用方式 操作和 驱动和上文使用方法一样，唯一的区别就是 需要调用
```php
   // 创建http server
  $http = new swoole_http_server('0.0.0.0', 80, SWOOLE_BASE);
  // 监听request 事件
  $http->on('request', function(swoole_http_request $req, swoole_http_response $res){
    // 设置请求
    Session::set_request($req);
    // 设置响应
    Session::set_response($res);
    // 启动会话
    Session::session_start(__DIR__.'/session');
    // 输出Hello World
    $res->write("hello world");
    // 结束请求
    $res->end();
  });
```

 

