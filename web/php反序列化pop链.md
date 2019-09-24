# php反序列化

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

## php原生类反序列化

### phar

https://paper.seebug.org/680/

#### 原理

部分文件操作函数读取phar文件会反序列化phar文件中的meta-data 部分的序列化内容

#### phar文件结构

- a **stub**
  可以理解为一个标志，格式为`xxx<?php xxx; __HALT_COMPILER();?>`，前面内容不限，但必须以`__HALT_COMPILER();?>`来结尾，否则phar扩展将无法识别这个文件为phar文件。

- a **manifest** describing the contents
  phar文件本质上是一种压缩文件，其中每个被压缩文件的权限,属性等信息都放在这部分。这部分还会以**序列化**的形式存储用户自定义的meta-data，这是上述攻击手法最核心的地方。

- the file **contents**
  被压缩文件的内容。

- [optional] a **signature** for verifying Phar integrity (phar file format only)

  签名，放在文件末尾

#### 影响函数

```php
fileatime|filectime|file_exists|file_get_contents|file_put_contents|file|filegroup|fopen|fileinode|filemtime|fileowner|fileperms|is_dir|is_executable|is_file|is_link|is_readable|is_writable|is_writeable|parse_ini_file|copy|unlink|stat|readfile|md5_file|filesize|mime_content_type
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