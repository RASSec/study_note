# php

## 编写马

- 在服务端生成访问自身某个文件的网址

  ```php
  <?php
  $shellpath=$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_ADDR"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
  $current_file=end(explode("/",$shellpath));
  $shellpath=str_replace($current_file,"test.php",$shellpath);
  print($shellpath);
  ?>
  ```

  

- file_get_contents设置超时

  ```php
  $opts = array(   
      $_SERVER["REQUEST_SCHEME"]=>array(   
        'method'=>"GET",
        'timeout'=>3,//单位秒  
       )   
    ); 
  file_get_contents("http://www.4399.com",false,stream_context_create($opts));
  ```

  

- 判断木马是否上传成功

```php
<?php
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if(strstr($errstr,"failed to open stream")!==FALSE)
        print("上传木马失败");
    else print("errno<br/>".$errno."errstr<br/>".$errstr."errfile<br/>".$errfile."errline<br/>".$errline);

}
$opts = array(   
    $_SERVER["REQUEST_SCHEME"]=>array(   
      'method'=>"GET",
      'timeout'=>3,//单位秒  
     )   
  ); 
file_get_contents($shellpath,false,stream_context_create($opts));

    ?>
```



## ob_start,ob_end_flush,ob_get_contents 获取 显示内容

```php
<?php
ob_start();
?>


12312312312
<?php
$s=ob_get_contents();
ob_end_flush();
?>
```



### 获取所有内置类



```php
 <?php
$classes = get_declared_classes();
foreach ($classes as $class) {
    $methods = get_class_methods($class);
    foreach ($methods as $method) {
        if (in_array($method, array(
            '__destruct',
            '__toString',
            '__wakeup',
            '__call',
            '__callStatic',
            '__get',
            '__set',
            '__isset',
            '__unset',
            '__invoke',
            '__set_state'
        ))) {
            print $class . '::' . $method . "\n";
        }
    }
} 
```



## xdebug配置

```
[xdebug]
zend_extension = D:\phpStudy\PHPTutorial\php\php-7.2.1-nts\ext\php_xdebug-2.8.0-7.2-vc15-nts.dll
xdebug.profiler_output_dir="D:\phpStudy\PHPTutorial\tmp\xdebug"
xdebug.trace_output_dir="D:\phpStudy\PHPTutorial\tmp\xdebug"
xdebug.profiler_append = 0
xdebug.profiler_enable = 1
xdebug.profiler_enable_trigger = 0  
xdebug.profiler_output_name = "cache.out.%t-%s"  
xdebug.remote_handler = "dbgp"  
xdebug.remote_host = "127.0.0.1"
xdebug.remote_enable = 1
xdebug.remote_autostart = 1

xdebug.auto_trace=1
;是否允许 ;xdebug跟踪函数参数，默认值为0
xdebug.collect_params=1
;是否允许 ;xdebug跟踪函数返回值，默认值为0
xdebug.collect_return=1

;用于zend studio远程调试的应用层通信协议
xdebug.idekey = PHPSTORM
xdebug.remote_port = 9000
```



##  开启报错
```
ini_set('display_errors','1');
error_reporting(E_ALL);

```

