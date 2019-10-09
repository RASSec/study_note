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



