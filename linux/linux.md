# linux

## 查看linux版本

```shell
 cat /proc/version 
uname -a
uname -r
lsb_release -a
```



## 将后台重新恢复到tty

`reptyr pid`





## 命令行快捷键

- Ctrl + L 用于清理终端的内容，就是清屏的作用。其实 clear 命令也有同样效果，但是你不觉得 Ctrl + L 的按键比输入 clear 这五个字母更快速吗？
- Ctrl + D 给终端传递 EOF （End Of File，文件结束符），在运行程序时很有用。有些程序我们需要在接收到 EOF 输入时结束，那么这个快捷键就可以派上用场了。比如我们之前演示过，退出 root 用户身份，就可以用 Ctrl + D。如果你在命令行提示符后什么也不输入的情况下直接按下这组快捷键，那么就会关闭当前的终端；
- Ctrl + A 光标跳到一行命令的开头。一般来说，Home 键有相同的效果；
- Ctrl + E 光标跳到一行命令的结尾。一般来说，End 键有相同的效果；。
- Ctrl + U 删除所有在光标左侧的命令字符；
- Ctrl + K 删除所有在光标右侧的命令字符；
- Ctrl + W 删除光标左侧的一个“单词”，这里的“单词”指的是用空格隔开的一个字符串。例如 -a 就是一个“单词”；
- Ctrl + Y 粘贴用 Ctrl + U、 Ctrl + K 或 Ctrl + W “删除”的字符串，有点像“剪切-粘贴”。
-  Ctrl + R ： 用于查找使用过的命令



## 常见目录的解释

- bin：英语 binary 的缩写，表示“二进制文件”（我们知道可执行文件是二进制的）。包含了会被所有用户使用的可执行程序；
- boot：英语 boot 表示“启动”，包含与 Linux 启动密切相关的文件；
- dev：英语 device 的缩写，表示“设备”，包含外设。它里面的子目录，每一个对应一个外设。比如代表我们的光盘驱动器的文件就会出现在这个目录下面；
- etc：etc 有点不能顾名思义了。因为 etc 是法语 et cetera 的缩写，翻成英语就是“and so on”，表示“…等等”，包含系统的配置文件。至于为什么在 /etc 下面存放配置文件， 按照原始的 Unix 说法（Linux 文件结构参考 Unix 的教学实现 MINIX），这下面放的都是一堆零零碎碎的东西， 就叫 etc 好了。哈哈 ，这其实是个历史遗留；
- home：英语 home 表示“家”，用户的私人目录。之前我们提过一些，在这个目录中，我们放置私人的文件，有点类似 Windows 中的 Documents 这个文件夹，也叫“我的文档”。Linux 中的每个用户（除了大管家用户，也就是超级用户 root 外。root 因为太厉害，拥有所有权限，所以比较“任性”，跟普通用户不住在一起）都在 home 目录下有自己的一个私人目录。比如我的用户名是 oscar，那么我的私人目录就是 /home/oscar；如果另一个用户叫 john，那么他的私人目录就是 /home/john；
- lib：英语 library 的缩写，表示“库”，包含被程序所调用的库文件。例如 .so 结尾的文件，在 Windows 下这样的库文件是以 .dll 结尾的；
- media：英语 media 表示“媒体”。当一个可移动的外设（比如 USB 盘、SD 卡、DVD、光盘等等）插入电脑时，Linux 就可以让我们通过 media 的子目录来访问这些外设中的内容。
- mnt：英语 mount 的缩写，表示“挂载”。有点类似 media，但一般用于临时挂载一些装置；
- opt：英语 optional application software package 的缩写，表示“可选的应用软件包”，用于安装多数第三方软件和插件；
- root：英语“根”的意思。超级用户 root 的家目录/主目录。一般用户的家目录是位于 /home 下，不过 root 用户是个例外。之前的课程我们也提到过，root 是整个系统的超级用户，拥有一切权限，初学者请慎用此用户模式；
- sbin：英语 system binary 的缩写，表示“系统二进制文件”。比起 bin 目录多了一个前缀 system，所以包含的是系统级的重要可执行程序；
- srv：英语 service的缩写，表示“服务”。包含一些网络服务启动之后所需要取用的数据；
- tmp：英语 temporary 的缩写，表示“临时的”。普通用户和程序存放临时文件的地方；
- usr：英语 Unix Software Resource 的缩写，表示“Unix 操作系统软件资源”（也是个历史遗留的命名）。这个目录是最庞大的目录之一。有点类似 Windows 中的 C:\Windows 和 C:\Program Files 这两个文件夹的集合。在这里面安装了大部分用户要调用的程序；
- var：英语 variable 的缩写，表示“动态的，可变的”。通常包含程序的数据，比如一些 log（日志）文件，记录电脑中发生了什么事。



## 用screen来分屏

```shell
#启动
screen
#screen中的一切功能都需要在按下Ctrl-a之后,才有用,这里严格区分大小写
? 显示帮助#如这里就需要按下Ctrl-a之后,在按下问好

```

### 常用的组合按键

```
Ctrl + a，松开，再按 c ：创建一个新的虚拟终端。
Ctrl + a，松开，再按 w ：显示当前虚拟终端的列表。
此处的 0$ bash 1-$ bash 2*$ bash 表示此时打开了 3 个虚拟终端，都叫作 bash，编号是 0，1，2。这是因为目前终端的 Shell 是用的 Bash，之后我们第五部分会开始学习 Shell（外壳程序）。

有 *（星号）的那个虚拟终端就是我们目前所在的虚拟终端，也就是第 3 个，编号是 2。

Ctrl + a，松开，再按 A ：重命名当前虚拟终端。修改后的名字，你用 Ctrl + a，松开，再按 w 时就会看到。

Ctrl + a，松开，再按 n ：跳转到下一个虚拟终端。

Ctrl + a，松开，再按 p ：跳转到上一个虚拟终端。

Ctrl + a，松开，再按 Ctrl + a ：跳转到最近刚使用的那个虚拟终端。

Ctrl + a，松开，再按 0 ~ 9 数字键：跳转到第 0 ~ 9 号虚拟终端。

Ctrl + a，松开，再按 "（双引号）：会让你选择跳转到哪个虚拟终端。

Ctrl + a，松开，再按 k ：关闭当前终端。
```



### 分隔屏幕

#### 水平切割

```
Ctrl + a，松开，再按 S ,上下分隔屏幕
注意是大写的 S（是英语 split 的首字母，表示“分割，分离”）。如果这样操作一次，则当前虚拟终端被横向分割为上下两部分。如下图所示：
```

#### 竖直切割



```
Ctrl + a，松开，再按 | ,上下分隔屏幕
```





#### 关闭切割出来的窗口

```
只要 Ctrl + a，松开，再按大写的 X
```

### 终端和screen分离

```
Ctrl + a，松开，再按 d：分离 screen
可以看到 [detached from 2249.pts-0.oscar-laptop]

表示我们的 screen 与实际终端分离（detach 是英语“分离，挣脱”的意思）了。

之后如果你要重回 screen 中，可以输入：
screen -r
就又回到刚才的 screen 的虚拟终端里了。
```



### 恢复对话

网络突然中断,screen是不是结束会话的

我们可以用如下命令来恢复

```shell
screen -x
screen -ls
screen -r xxxx
```

### 常用命令

```bash
screen -d <作业名称> 　#将指定的screen作业离线。
```





## ssh

### 安装

```
apt install openssh-client
apt install openssh-server
```



### 免密登陆

```
ssh-keygen#生成public key (本机运行)
ssh-copy-id user@host
```



编辑 /etc/ssh/sshd_config 

```
RSAAuthentication yes
PubkeyAuthentication yes
AuthorizedKeysFile .ssh/authorized_keys
```



## rsync 增量备份

### 备份到同一台电脑

```
rsync -arv Images/ backups/
```



以上命令，将 Images 目录下的所有文件备份到 backups 目录下。

-arv 参数分别表示：

- -a：保留文件的所有信息，包括权限、修改日期等等。a 是 archive 的缩写，是“归档”的意思；
- -r：递归调用，表示子目录的所有文件也都包括。r 是 recursive 的缩写，是“递归的”的意思；
- -v：冗余模式，输出详细操作信息。v 是 verbose 的缩写，是“冗余的”的意思。

### 删除文件

默认地，rsync 在同步时并不会删除目标目录的文件。例如你的源目录（被同步目录）中删除了一个文件，但是用 rsync 同步时，它并不会删除同步目录中的相同文件。

如果要使 rsync 也同步删除操作。那么可以这么做：

```
rsync -arv --delete Images/ backups/
```

加上 --delete 参数就可以了。delete 是英语“删除”的意思。

### 备份到另一台电脑的目录

例如：

```
rsync -arv --delete Images/ oscar@89.231.45.67:backups/
```



## 关闭登录某个到linux的终端

```
fuser -k /dev/pts/id
```



## sudo

### 添加用户

```bash
usermod -aG sudo username#将用户添加到sudo组中
#or
visudo #修改/etc/sudoers文件

```



## tail



### tail 动态查看新增内容

```shell
tail -f log.txt
```





## 开机自启动

### /etc/rc.local



### systemctl

https://www.cnblogs.com/downey-blog/p/10473939.html



## 设置默认shell

`chsh -s $(which zsh)`