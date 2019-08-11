# upload 通关笔记

## 第四关

- 特殊字符+\x00:在1.php和.jpg中间加入特殊字符0xff

文件无法上传`<b>Notice</b>:  Undefined index: upload_file in <b>D:\xampp\htdocs\upload-lab-master\Pass-04\index.php</b> on line <b>19</b><br />`

- 超长文件名，`Undefined index: upload_file in <b>D:\xampp\htdocs\upload-lab-master\Pass-04\index.php`
  会不会是apache拦截的

- apache文件后缀名解析:
  失败（https://blog.csdn.net/mgxcool/article/details/73028488）

  [https://medium.com/@ledaiye/%E8%A7%A3%E6%9E%90%E6%BC%8F%E6%B4%9E-dcd1c0433fbc](https://medium.com/@ledaiye/解析漏洞-dcd1c0433fbc)

- 百度发现是.htaccess文件利用
  [.htaccess文件利用](https://skysec.top/2017/09/06/有趣的-htaccess/)

## 第五关

没有将输入大小写同步

PhP绕过

## 第六关

阅读代码发现没有删除后缀名尾部空格

所以可以用.php+空格绕过黑名单检测

多余的空格会被windows系统自动忽略(linux不会)

## 第七关

说来惭愧，这几题都在看别人写的wp

看这题代码发现代码没有去除收尾的dot

在windows下最后的点会自动被忽略而linux不会

也就是说向windows上的服务器上传.php.文件可以得到.php文件

## 第八关

是不是我太蠢了？？？？

只删除一次空格

.php..绕过

第二种方法:

.php::$DATA后缀名绕过

知识点：

- NTFS ADS特性

|        上传的文件名         |         系统结果         |
| :-------------------------: | :----------------------: |
|       test.php:a.jpg        | 生成test.php，但是无内容 |
|       test.php::$DATA       |   生成test.php，有内容   |
| test.php::$INDEX_ALLOCATION |    生成test.php文件夹    |
|     test.php::$DATA.jpg     |    生成0.jpg，有内容     |
|  test.php::$DATA\test.jpg   |   生成aaa.jpg，有内容    |

[脑洞大开 - NTFS交换数据流ADS](https://veritas501.space/2017/03/04/脑洞大开 - NTFS交换数据流ADS/)

## 第九关

```PHP
        $file_name = trim($_FILES['upload_file']['name']);
        $file_name = deldot($file_name);//删除文件名末尾的点
        $file_ext = strrchr($file_name, '.');
        $file_ext = strtolower($file_ext); //转换为小写
        $file_ext = str_ireplace('::$DATA', '', $file_ext);//去除字符串::$DATA
        $file_ext = trim($file_ext); //首尾去空
```

后缀.php. .绕过

。。。我又看了wp

## 第十关

双写绕过，我终于混出头了

## 第十一关

*00截断*在*php* 5.3.4中*php*修复了0字符

我的环境php版本为5.6,所以做不了喽

## 第十二关

同第十一关

## 第十三关

这里有个坑，不能直接复制图片头到burp的文本框,

还是用hxd改好了再上传

## 第十四关，十五关

没啥区别就不写了



## 第十六关

看源代码发现有二次渲染，整个人都不好了。。但是如果认真分析会发现有逻辑漏洞。。可是我没有。。。想屠我狗头

在扩展名和content-type校验过后就会保存文件并且如果文件头不符合的话就不会经过二次渲染

