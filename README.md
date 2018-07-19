# session
##### 重写了PHP自带的 SESSION 机制，存储介质支持:本地模式、Memcache、Redis、Mamcached、Xcache,php运行模式支持:LAMP、LNMP、SWOOLE
## 1.引入入口 && 获取实例
```php
use \Itxiao6\Session\SessionManager;
$session = \Itxiao6\Session\SessionManager::getSessionInterface();
```
## 2.设置驱动
### 1.本地存储方式(默认)
```php
$session -> set_deiver(new \Doctrine\Common\Cache\FilesystemCache(__DIR__.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR));
```
### 2.Redis 驱动
```php
$redis = new \Redis();
$redis->connect('127.0.0.1', 6319);
$cacheDriver = new \Doctrine\Common\Cache\RedisCache();
$cacheDriver->setRedis($redis);

$session -> set_deiver($cacheDriver);
```
### 3.Memcache 驱动
```php
$memcache = new \Memcache();
$memcache->connect('127.0.0.1', 11211);

$cacheDriver = new \Doctrine\Common\Cache\MemcacheCache();
$cacheDriver->setMemcache($memcache);
$session -> set_deiver($cacheDriver);
```
### 4.Memcached 驱动
```php
$memcached = new \Memcached();
$memcached->addServer($cacheConfig['Mamcached']['host'], $cacheConfig['Mamcached']['port']);

$cacheDriver = new \Doctrine\Common\Cache\MemcachedCache();
$cacheDriver->setMemcached($memcached);
$session -> set_deiver($cacheDriver);
```
### 5.Xcache 驱动
```php
$session -> set_deiver(new \Doctrine\Common\Cache\XcacheCache());
```
### 6.传入配置
```php
$session -> set_config(new \Itxiao6\Session\Tools\Config([
    'session_name'=>'PHPSESSION',
    'session_path'=>'/',
    'session_id_length'=>32,
    'session_id_type'=>1,
    'session_storage_prefix'=>'itxiao6_session_',
    // 默认有效期一天
    'session_expire'=>3600*24,
]));
```
### 7.启动会话
```php
try{
    // 启动会话
    $session -> start();
}catch (\Throwable $exception){
    // 打印错误
    var_dump($exception);
}
```
### 8.设置值
```php
$session -> session() -> set('name','戒尺');
```
### 9.设置值
```php
echo $session -> session() -> get('name');
```
#### 附录1
  SWOOLE 模式使用方式 操作和 驱动和上文使用方法一样，唯一的区别就是 步骤使用1的时候调用的"getSessionInterface" 改为"getSwooleSessionInterface" 并且传入 $request 和 $response
```php
   // 创建http server
  $http = new swoole_http_server('0.0.0.0', 80, SWOOLE_BASE);
  // 监听request 事件
  $http->on('request', function(swoole_http_request $request, swoole_http_response $response){
    // 启动会话(步骤一)
    $session = Session::getSwooleSessionInterface($request,$response);
    // 结束请求
    $res->end();
  });
```

 

