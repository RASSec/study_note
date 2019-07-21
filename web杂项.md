# web杂项

## 伪造IP的几种方法

 Client-Ip: 127.0.0.1

 X-Forwarded-For: 127.0.0.1

 Host: 127.0.0.1

 Referer: www.google.com

## 杂
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