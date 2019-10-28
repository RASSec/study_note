# web杂项



## 编码报错

做题时可以试试%80(ascii:128)来试一下是否报错

### django

%80会报错

## 伪造IP的几种方法

 Client-Ip: 127.0.0.1

 X-Forwarded-For: 127.0.0.1

 Host: 127.0.0.1

 Referer: www.google.com

## 杂

### 使用别的端口访问

```SHELL
 curl --local-port 51 http://web.jarvisoj.com:32770/
```



### chr(0)字符截断

## 文件上传

### 客户端绕过

burp抓包修改

### 服务端绕过

#### content-type字段校验



```php
`<?php        if($_FILES['userfile']['type'] != "image/gif")  #这里对上传的文件类型进行判断，如果不是image/gif类型便返回错误。                {                    echo "Sorry, we only allow uploading GIF images";                 exit;                 }         $uploaddir = 'uploads/';         $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);         if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))             {                 echo "File is valid, and was successfully uploaded.\n";                } else {                     echo "File uploading failed.\n";    }     ?>`
```

　　可以看到代码对上传文件的文件类型进行了判断，如果不是图片类型，返回错误。

直接burp抓包修改content-type

#### 文件头校验

　可以通过自己写正则匹配，判断文件头内容是否符合要求，这里举几个常见的文件头对应关系：
（1） .JPEG;.JPE;.JPG，”JPGGraphic File”
（2） .gif，”GIF 89A”
（3） .zip，”Zip Compressed”
（4） .doc;.xls;.xlt;.ppt;.apr，”MS Compound Document v1 or Lotus Approach APRfile”

#### 文件后缀名黑名单绕过

绕过方法：

1. 找黑名单扩展名的漏网之鱼 - 比如 asa 和 cer 之类
2. 可能存在大小写绕过漏洞 - 比如 aSp 和 pHp 之类

文件名后缀白名单绕过

##### chr(00)字符截断绕过

1. 1.php%00.jpg(url网址中)
2. 1.php .jpg用burp修改chr(20)为chr(00)

## 文件读取

- 当前程序的配置文件

  - wp-config.php  （WordPress）

  - .env （Laravel）

- 当前程序的源代码 / 字节码

  - 源码审计，寻找RCE漏洞

- /proc/self

  - /proc/version  当前操作系统内核版本，可帮助判断是否可用DirtyCow等提权，也可以根据发行版推测各种配置的存放位置。

  -  /proc/self/cmdline  读取当前进程的执行命令行，对于PHP以外的题目有效，可以读到生效的配置。 

  - /proc/self/environ  读取当前环境变量，某些情况下无法生效

  - /proc/self/maps 读取当前进程的内存分布信息，可以读到某些so的存放位置，如果有任意写也可以以此为参考写当前进程内存 /proc/self/mem
- /proc/\d* 读到有权限读的进程的信息
  - 尤其注意/proc/1/，可分辨其是否是Docker启动，如是，可读到Docker启动参数。
- /etc/ 或 /usr/local/etc
  - /etc/passwd 系统用户信息
  - /etc/shadow （仅root）可读到密码，后可在本地hashcat暴力破解。
- Nginx、Apache、PHP、MySQL配置等
  - /var 或 /usr/local/var
  - /var/log 下的各种日志
  - /var/run 下……嗯没什么好读的
  - /dev/zero  可以DoS服务器
  - /home/xxx/.bash_history 服务器运维人员操作日志
- 其他协议
  - SSRF
    - http / https / ftp (file_get_contents)
    - \\  (Windows下的UNC路径，访问SMB共享)
    - gopher:// (Java)
  - 列目录
    - netdoc:// (Java)

- PHP:

  - file_get_contents / file / fopen + fread / show_source / readfile 

  - require / include (文件包含)

- Python:

  - open(‘xxxxxxxxxx’).read()

- Nodejs:

  - require('fs').readFile


## 框架

### Django 

1. %80编码报错
2. debug模式打开可以看看有啥东西
3. xxx.xxx.xxx?xxx=@/opt/api/database.sqlite3(当 **CURLOPT_SAFE_UPLOAD** 为 true 时，PHP 可以通过在参数中注入 **@** 来读取文件。)



### javascript库---jQuery

#### getJSON()的特性

https://stackoverflow.com/questions/29022794/is-getjson-safe-to-call-on-untrusted-url

> If the URL includes the string `"callback=?"` (or similar, as defined by the server-side API), the request is treated as JSONP instead.





## perl

### perl文件包含漏洞

应用条件:perl 5,6

问题代码

```perl
use strict;
use warnings;
use CGI;
 
my $cgi= CGI->new;
if ( $cgi->upload( 'file' ) )
{
my $file= $cgi->param( 'file' );
while ( <$file> ) { print "$_"; } }
```

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5s8gm8ve5j30rc0m00wn.jpg)

param()返回name=file的所有参数,但是只有第一个值才能传给$file变量,当$file="ARGV"时,perl会调用open()访问url的参数

可以通过 /bin/bash%20-c%20......来getshell

`/cgi-bin/file.pl?/bin/bash%20-c%20ls| `





## http走私

在复杂网络环境下，不同的服务器对RFC标准实现的方式不同，程度不同。这样一来，对同一个HTTP请求，不同的服务器可能会产生不同的处理结果，这样就产生了了安全风险。

### cl-cl

### te-te

### te-cl

### cl-te



## dns rebinding

对多次dns解析中，不同的dns解析结果，来绕过一些特定的限制:如同源策略,限制访问ip为内网地址等

工具:`https://lock.cmpxchg8b.com/rebinder.html`

