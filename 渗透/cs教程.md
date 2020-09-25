#  **Cobaltstrike**

## 教程

[i春秋公众号cs教程]( https://mp.weixin.qq.com/s?__biz=MzUzNTkyODI0OA==&mid=2247494718&idx=1&sn=4f523ce9d5ef8ef3be6677f36f5cabcd&chksm=fafca0e9cd8b29ffd4e7bab0f23682e8bec6e15ecf6a3b42f15fad22156ea664969b6a0080b6&mpshare=1&scene=23&srcid=&sharer_sharetime=1578371303504&sharer_shareid=eee8b250e780dfa6f3a463559ee39c8f#rd )



## 启动

```
teamserver 8.8.8.8 123456
./cobaltstrike
```

## 模块

### payload

- Payload Generator


   生成多种语言后门

- Windows Dropper

  这个是一个Windows程序的捆绑器，它可以实现把后门捆绑于其他程序之上，比如扫雷游戏，某些带有诱惑性的可执行文件...

- Windows Excutable/Windows Excutable(s)
  顾名思义
  Windows Excutable带有生成出的是stageless版本（无状态Windows后门木马），下面简单说下这个无状态木马的使用方法。一般使用无状态木马的网络环境是这样的。

  

  ![img](https://mmbiz.qpic.cn/mmbiz_png/Go7NSXrKWd7gH9LSoUA1BlkwZ6evdP81cwsFdqXrJjs9qHxyddxzLbNTouCjwqMSrjXkbWicBu5qS5v7enanPoA/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

  

  首先你需要让一台主机作为中转器，配合无状态木马使用。

  

  ![img](https://mmbiz.qpic.cn/mmbiz_png/Go7NSXrKWd7gH9LSoUA1BlkwZ6evdP81XhTbicyiavuibdXguyibYBxxIFGphQQF5M7PPknLathxNQ8wnAm7rAA7hw/640?wx_fmt=png&tp=webp&wxfrom=5&wx_lazy=1&wx_co=1)

  

  这里选择中转器的监听器生成木马。需要注意的是如果开启了防火墙会产生一个Windows安全警报，因此最好提前用cmd添加一个防火墙放行规则或关闭防火墙，随后便可将无状态的木马上传到内网的其他机器运行后返回一个会话。

### beacon

#### 心跳时间

 在Cobalt Strike中它的心跳默认是60s（即sleep时间为60s，每一分钟目标主机与teamserver通信一次）， 这会让我们执行命令或进行其他操作响应很慢。 

在beacon下执行`sleep 5`来修改

#### 部分beacon命令

```shell
Beacon Commands
    Command                   Description
    -------                   -----------
    browserpivot              注入受害者浏览器进程
    bypassuac                 绕过UAC
    cancel                    取消正在进行的下载
    cd                        切换目录
    checkin                   强制让被控端回连一次
    clear                     清除beacon内部的任务队列
    connect                   Connect to a Beacon peer over TCP
    covertvpn                 部署Covert VPN客户端
    cp                        复制文件
    dcsync                    从DC中提取密码哈希
    desktop                   远程VNC
    dllinject                 反射DLL注入进程
    dllload                   使用LoadLibrary将DLL加载到进程中
    download                  下载文件
    downloads                 列出正在进行的文件下载
    drives                    列出目标盘符
    elevate                   尝试提权
    execute                   在目标上执行程序(无输出)
    execute-assembly          在目标上内存中执行本地.NET程序
    exit                      退出beacon
    getprivs                  Enable system privileges on current token
    getsystem                 尝试获取SYSTEM权限
    getuid                    获取用户ID
    hashdump                  转储密码哈希值
    help                      帮助
    inject                    在特定进程中生成会话
    jobkill                   杀死一个后台任务
    jobs                      列出后台任务
    kerberos_ccache_use       从ccache文件中导入票据应用于此会话
    kerberos_ticket_purge     清除当前会话的票据
    kerberos_ticket_use       从ticket文件中导入票据应用于此会话
    keylogger                 键盘记录
    kill                      结束进程
    link                      Connect to a Beacon peer over a named pipe
    logonpasswords            使用mimikatz转储凭据和哈希值
    ls                        列出文件
    make_token                创建令牌以传递凭据
    mimikatz                  运行mimikatz
    mkdir                     创建一个目录
    mode dns                  使用DNS A作为通信通道(仅限DNS beacon)
    mode dns-txt              使用DNS TXT作为通信通道(仅限D beacon)
    mode dns6                 使用DNS AAAA作为通信通道(仅限DNS beacon)
    mode http                 使用HTTP作为通信通道
    mv                        移动文件
    net                       net命令
    note                      备注      
    portscan                  进行端口扫描
    powerpick                 通过Unmanaged PowerShell执行命令
    powershell                通过powershell.exe执行命令
    powershell-import         导入powershell脚本
    ppid                      Set parent PID for spawned post-ex jobs
    ps                        显示进程列表
    p**ec                    Use a service to spawn a session on a host
    p**ec_psh                Use PowerShell to spawn a session on a host
    psinject                  在特定进程中执行PowerShell命令
    pth                       使用Mimikatz进行传递哈希
    pwd                       当前目录位置
    reg                       Query the registry
    rev2self                  恢复原始令牌
    rm                        删除文件或文件夹
    rportfwd                  端口转发
    run                       在目标上执行程序(返回输出)
    runas                     以另一个用户权限执行程序
    runasadmin                在高权限下执行程序
    runu                      Execute a program under another PID
    screenshot                屏幕截图
    setenv                    设置环境变量
    shell                     cmd执行命令
    shinject                  将shellcode注入进程
    shspawn                   生成进程并将shellcode注入其中
    sleep                     设置睡眠延迟时间
    socks                     启动SOCKS4代理
    socks stop                停止SOCKS4
    spawn                     Spawn a session 
    spawnas                   Spawn a session as another user
    spawnto                   Set executable to spawn processes into
    spawnu                    Spawn a session under another PID
    ssh                       使用ssh连接远程主机
    ssh-key                   使用密钥连接远程主机
    steal_token               从进程中窃取令牌
    timestomp                 将一个文件时间戳应用到另一个文件
    unlink                    Disconnect from parent Beacon
    upload                    上传文件
    wdigest                   使用mimikatz转储明文凭据
    winrm                     使用WinRM在主机上生成会话
    wmi                       使用WMI在主机上生成会话
    argue                      进程参数欺骗
```

#### beacon监听器使用

- Http Beacon&tcp Beacon

- smb beacon
   派生一个SMB Beacon方法：在Listner生成SMB Beacon>目标主机>右键> spawn as>选中对应的Listener>上线 
  当前是连接状态，你可以Beacon上用link <ip>命令链接它或者unlink <ip>命令断开它。
- dns beacon
  配置A记录指向服务器ip -->ns记录都指向A记录域名.如果返回的IP地址与你的服务器IP地址对应是正确的，那我们就可以开始配置dns beacon的监听器了。Host那里最好填域名（A记录解析那个），不要填服务器的IP地址。

- ssh beacon
  `Beacon命令: ssh [target:port] [user] [pass] ` 或 `ssh [target:port] [user] [path/to/key.pem]`



### 脚本加载

#### 汇总

```
elevate.cna https://github.com/rsmudge/ElevateKit/  增加五种提权方式
ProcessTree.cna 让ps命令可以显示父子关系并显示颜色
CVE-2018-4878.cna
ArtifactPayloadGenerator.cna  创建多种类型的payload。生成的文件在cs目录下的opt\cobaltstrike\ 
AVQuery.cna 查询目标所安装的所有杀毒软件
RedTeamRepo.cna   提示一下常用的渗透命令
ProcessColor.cna 显示带有颜色的进程列表（不同颜色有不同含义）
EDR.cna 检查有无终端安全产品
logvis.cna  显示Beacon命令日志
ProcessMonitor.cna 记录一段时间内程序启动的情况
SMBPayloadGenerator.cna  生成基于SMB的payload
Persistence/Persistence_Menu.cna  脚本功能：持久化控制集合
Eternalblue.cna
https://github.com/harleyQu1nn/AggressorScripts
https://github.com/bluscreenofjeff/AggressorScripts
https://github.com/michalkoczwara/
https://github.com/vysec/Aggressor-VYSEC
https://github.com/killswitch-GUI/CobaltStrike-ToolKit
https://github.com/ramen0x3f/AggressorScripts
https://github.com/rasta-mouse/Aggressor-Script
https://github.com/Und3rf10w/Aggressor-scripts
https://github.com/001SPARTaN/aggressor_scripts
https://github.com/gaudard/scripts/tree/master/red-team/aggressor
https://github.com/branthale/CobaltStrikeCNA
https://github.com/threatexpress/persistence-aggressor-script
https://github.com/FortyNorthSecurity/AggressorAssessor
```

