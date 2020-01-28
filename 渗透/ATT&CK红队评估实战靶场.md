# ATT&CK红队评估实战靶场

  

## 网络拓扑图

![](http://vulnstack.qiyuanxuetang.net/media/vuln/screenshot/2019/10/20/%E7%BB%98%E5%9B%BE2.png)



## web服务

### 信息收集

#### nmap

kali的ip是192.168.233.128

```
Nmap scan report for 192.168.233.1
Host is up (0.00022s latency).
MAC Address: 00:50:56:C0:00:01 (VMware)
Nmap scan report for 192.168.233.129
Host is up (0.00013s latency).
MAC Address: 00:0C:29:A7:C1:B2 (VMware)
Nmap scan report for 192.168.233.254
Host is up (0.00019s latency).
MAC Address: 00:50:56:ED:2D:97 (VMware)
Nmap scan report for 192.168.233.128

```

靶机的ip为:192.168.233.129

```
Not shown: 998 filtered ports
PORT     STATE SERVICE
80/tcp   open  http
3306/tcp open  mysql
MAC Address: 00:0C:29:A7:C1:B2 (VMware)
```

开方80,3306端口

### web服务

对web服务进行扫描发现

```
[13:54:20] 200 -    4KB - /phpMyadmin/
[13:54:20] 200 -   71KB - /phpinfo.php
```



### mysql

对mysql密码进行爆破发现密码是:root

尝试写shell

拿到root权限:)

## 域成员



发现机子在192.168.52.143网段





### 设置代理

```
autoroute
use auxiliary/server/socks4a
set srvhost 127.0.0.
run
```



### 信息收集

#### nmap

```
Nmap scan report for 192.168.52.138
Host is up (0.00s latency).
MAC Address: 00:0C:29:3F:5D:A9 (VMware)
Nmap scan report for 192.168.52.141
Host is up (0.00s latency).
MAC Address: 00:0C:29:6D:39:34 (VMware)
Nmap scan report for www.qiyuanxuetang.net (192.168.52.143)
```





192.168.52.138

```
53/tcp    open  domain
80/tcp    open  http
88/tcp    open  kerberos-sec
135/tcp   open  msrpc
139/tcp   open  netbios-ssn
389/tcp   open  ldap
445/tcp   open  microsoft-ds
464/tcp   open  kpasswd5
593/tcp   open  http-rpc-epmap
636/tcp   open  ldapssl
3268/tcp  open  globalcatLDAP
3269/tcp  open  globalcatLDAPssl
49154/tcp open  unknown
49155/tcp open  unknown
49157/tcp open  unknown
49158/tcp open  unknown

```



### 杂

```
/ODA6NmU6NmY6NmU6Njk6NjMtQWRtaW5pc3RyYXRvcgaa/a.png

```





### 192.168.52.138

```
Nmap scan report for 192.168.52.138
Host is up (0.029s latency).
Not shown: 983 filtered ports
PORT      STATE SERVICE      VERSION
53/tcp    open  domain       Microsoft DNS 6.1.7601 (1DB1446A) (Windows Server 2008 R2 SP1)
80/tcp    open  http         Microsoft IIS httpd 7.5
88/tcp    open  kerberos-sec Microsoft Windows Kerberos (server time: 2020-01-06 05:38:02Z)
135/tcp   open  msrpc        Microsoft Windows RPC
139/tcp   open  netbios-ssn  Microsoft Windows netbios-ssn
389/tcp   open  ldap         Microsoft Windows Active Directory LDAP (Domain: god.org, Site: Default-First-Site-Name)
445/tcp   open  microsoft-ds Microsoft Windows Server 2008 R2 - 2012 microsoft-ds (workgroup: GOD)
464/tcp   open  kpasswd5?
593/tcp   open  ncacn_http   Microsoft Windows RPC over HTTP 1.0
636/tcp   open  tcpwrapped
3268/tcp  open  ldap         Microsoft Windows Active Directory LDAP (Domain: god.org, Site: Default-First-Site-Name)
3269/tcp  open  tcpwrapped
49154/tcp open  msrpc        Microsoft Windows RPC
49155/tcp open  msrpc        Microsoft Windows RPC
49157/tcp open  ncacn_http   Microsoft Windows RPC over HTTP 1.0
49158/tcp open  msrpc        Microsoft Windows RPC
49167/tcp open  msrpc        Microsoft Windows RPC
MAC Address: 00:0C:29:3F:5D:A9 (VMware)
Warning: OSScan results may be unreliable because we could not find at least 1 open and 1 closed port
Device type: general purpose
Running: Microsoft Windows Vista|2008|7
OS CPE: cpe:/o:microsoft:windows_vista::- cpe:/o:microsoft:windows_vista::sp1 cpe:/o:microsoft:windows_server_2008::sp1 cpe:/o:microsoft:windows_7
OS details: Microsoft Windows Vista SP0 or SP1, Windows Server 2008 SP1, or Windows 7
Network Distance: 1 hop
Service Info: Host: OWA; OS: Windows; CPE: cpe:/o:microsoft:windows_server_2008:r2:sp1, cpe:/o:microsoft:windows


```







```
[20:29:13] 301 -  159B  - /aspnet_client  ->  http://192.168.52.138/aspnet_client/
[20:33:05] 403 -    2KB - /Trace.axd

```

### 192.168.52.141



ftp服务可以匿名登陆,不过不能列出目录



```
PORT     STATE SERVICE         VERSION
21/tcp   open  ftp             Microsoft ftpd
135/tcp  open  msrpc           Microsoft Windows RPC
139/tcp  open  netbios-ssn     Microsoft Windows netbios-ssn
445/tcp  open  microsoft-ds    Microsoft Windows 2003 or 2008 microsoft-ds
777/tcp  open  multiling-http?
1025/tcp open  NFS-or-IIS?
1026/tcp open  msrpc           Microsoft Windows RPC
1029/tcp open  msrpc           Microsoft Windows RPC
1030/tcp open  msrpc           Microsoft Windows RPC
6002/tcp open  http            SafeNet Sentinel Protection Server 7.3
7001/tcp open  afs3-callback?
7002/tcp open  http            SafeNet Sentinel Keys License Monitor httpd 1.0 (Java Console)
8099/tcp open  http            Microsoft IIS httpd
1 service unrecognized despite returning data. If you know the service/version, please submit the following fingerprint at https://nmap.org/cgi-bin/submit.cgi?new-service :
SF-Port777-TCP:V=7.80%I=7%D=1/6%Time=5E12C69D%P=i686-pc-windows-windows%r(
SF:TerminalServerCookie,A,"\x01\0\t\xe0\x06\x01\0\t\xe0\x06")%r(Kerberos,5
SF:,"\x01\0\t\xe0\x06")%r(SMBProgNeg,5,"\x01\0\t\xe0\x06")%r(TerminalServe
SF:r,A,"\x01\0\t\xe0\x06\x01\0\t\xe0\x06")%r(WMSRequest,5,"\x01\0\t\xe0\x0
SF:6");
MAC Address: 00:0C:29:6D:39:34 (VMware)
Device type: general purpose
Running: Microsoft Windows XP|2003
OS CPE: cpe:/o:microsoft:windows_xp::sp2:professional cpe:/o:microsoft:windows_server_2003
OS details: Microsoft Windows XP Professional SP2 or Windows Server 2003
Network Distance: 1 hop
Service Info: OS: Windows; CPE: cpe:/o:microsoft:windows, cpe:/o:microsoft:windows_server_2003

```

