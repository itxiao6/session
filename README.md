# session
##### 基于Cookie 重写了PHP自带的 SESSION 机制，存储介质支持:本地模式、Mysql、Redis php运行模式支持:LAMP、LNMP、SWOOLE
## 1.引入入口
```php
use \Itxiao6\Session\Session;
```
## 2.启动会话
### 1.本地存储方式(默认)
```php
// 设置使用的存储介质
Session::driver(); // 默认为 Local
// 启动会话
Session::getInterface() -> start(__DIR__.'/SessionFile/');
```
### 2.MySql存储介质
```php
$pdo = new \PDO("mysql:host=127.0.0.1;dbname=dbname",'username','passwd');
$session = Session::getInterface() -> driver('MySql') -> start($pdo,'session_table');
```
### 3.Redis存储介质
```php
$redis = new \Redis();
$redis -> connect('127.0.0.1',6379);
# 启动会话
$session = Session::getInterface() -> driver('Redis') -> start($redis);
```
### 4.使用Session
```php
# 设置值
$session -> set('name','戒尺');
# 获取值
var_dump($session -> get('name'));
```
#### 更多存储机制可以无限扩展和替换
###### 备注
```text
1.存储器必须继承:Itxiao6\Session\Interfaces\Storage 接口
2.Session::interface('DriverName',Driver::class) -> driver('DriverName');的参数会传递到存储器的构造方法内,所有开发存储器只需要在构造方法获取连接 即可 
```

```php
# 设置新的存储器
Session::getInterface() -> interface('Memcache',Driver::class);
$memcache = Memcache();
$memcache -> connect('127.0.0.1', 11211); 
use \Itxiao6\Session\Session;
// 设置存储介质 并启动会话
Session::getInterface() -> interface('Memcache',Driver::class) -> driver('Memcache') -> start($memcache);
```
#### 附录1
  SWOOLE 模式使用方式 操作和 驱动和上文使用方法一样，唯一的区别就是 调用 Session::getInterface()的时候需要传入 $request 和 $response
```php
   // 创建http server
  $http = new swoole_http_server('0.0.0.0', 80, SWOOLE_BASE);
  // 监听request 事件
  $http->on('request', function(swoole_http_request $request, swoole_http_response $response){
    # 启动会话
    $session = Session::getInterface($request,$response) -> start(__DIR__.'/'.'SessionFile/');
    # 设置值
    $session -> set('name','戒尺');
    // 获取值 输出Hello World session 内的name值
    $res->write("hello world:".$session -> get('name'));
    // 结束请求
    $res->end();
  });
```

 

