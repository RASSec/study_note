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

