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

## 第十七关



```php
$is_upload = false;
$msg = null;

if(isset($_POST['submit'])){
    $ext_arr = array('jpg','png','gif');
    $file_name = $_FILES['upload_file']['name'];
    $temp_file = $_FILES['upload_file']['tmp_name'];
    $file_ext = substr($file_name,strrpos($file_name,".")+1);
    $upload_file = UPLOAD_PATH . '/' . $file_name;

    if(move_uploaded_file($temp_file, $upload_file)){
        if(in_array($file_ext,$ext_arr)){
             $img_path = UPLOAD_PATH . '/'. rand(10, 99).date("YmdHis").".".$file_ext;
             rename($upload_file, $img_path);
             $is_upload = true;
        }else{
            $msg = "只允许上传.jpg|.png|.gif类型文件！";
            unlink($upload_file);
        }
    }else{
        $msg = '上传失败！';
    }
}
```

`move_uploaded_file($temp_file, $upload_file)` 这个没有任何防护措施，即使是php也能上传,虽然后面会删除，但只要我们一直生成这个文件并一直访问，就可以访问到

## 第十八关

看别人的wp说是也是在rename那利用条件竞争配合apache解析漏洞。虽然我成功上传18.php.7Z但是没有遇到解析漏洞是我apache版本的问题?

## 第十九关

```php
$is_upload = false;
$msg = null;
if (isset($_POST['submit'])) {
    if (file_exists(UPLOAD_PATH)) {
        $deny_ext = array("php","php5","php4","php3","php2","html","htm","phtml","pht","jsp","jspa","jspx","jsw","jsv","jspf","jtml","asp","aspx","asa","asax","ascx","ashx","asmx","cer","swf","htaccess");

        $file_name = $_POST['save_name'];
        $file_ext = pathinfo($file_name,PATHINFO_EXTENSION);

        if(!in_array($file_ext,$deny_ext)) {
            $temp_file = $_FILES['upload_file']['tmp_name'];
            $img_path = UPLOAD_PATH . '/' .$file_name;
            if (move_uploaded_file($temp_file, $img_path)) { 
                $is_upload = true;
            }else{
                $msg = '上传出错！';
            }
        }else{
            $msg = '禁止保存为该类型文件！';
        }

    } else {
        $msg = UPLOAD_PATH . '文件夹不存在,请手工创建！';
    }
}
```

这一题是黑名单检测后缀名，所以只要构造1.php. 

pathinfo($file_name,PATHINFO_EXTENSION)读到就是空,就可以绕过黑名单,并且在windows环境下.会被自动删除最后会生成1.php文件



这题还有一种做法就是利用move_uploaded_file()%00截断绕过

## 第20关

```php
$is_upload = false;
$msg = null;
if(!empty($_FILES['upload_file'])){
    //检查MIME
    $allow_type = array('image/jpeg','image/png','image/gif');
    if(!in_array($_FILES['upload_file']['type'],$allow_type)){
        $msg = "禁止上传该类型文件!";
    }else{
        //检查文件名
        $file = empty($_POST['save_name']) ? $_FILES['upload_file']['name'] : $_POST['save_name'];
        if (!is_array($file)) {
            $file = explode('.', strtolower($file));
        }

        $ext = end($file);
        $allow_suffix = array('jpg','png','gif');
        if (!in_array($ext, $allow_suffix)) {
            $msg = "禁止上传该后缀文件!";
        }else{
            $file_name = reset($file) . '.' . $file[count($file) - 1];
            $temp_file = $_FILES['upload_file']['tmp_name'];
            $img_path = UPLOAD_PATH . '/' .$file_name;
            if (move_uploaded_file($temp_file, $img_path)) {
                $msg = "文件上传成功！";
                $is_upload = true;
            } else {
                $msg = "文件上传失败！";
            }
        }
    }
}else{
    $msg = "请选择要上传的文件！";
}
```

这题检测的后缀名的位置是 end($file),而文件后缀名却是$file[count($file)-1]，而php的数组不一定非要从0开始也可以从1开始,于是就可以构造

$file[1]=1;$file[2]=php;$file[3]=jpg

$file[count($file)-1]=php

 end($file)=png

并且数组不一定连续

$file[0]=1.php;$file[2]=jpg



还可以利用move_uploaded_file%00截断绕过

