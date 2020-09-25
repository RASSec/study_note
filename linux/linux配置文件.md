# linux配置文件

## /etc/passwd

这个文件对所有用户都是可读的。

```
　　root:x:0:0:root:/root:/bin/bash

　　bin:x:1:1:bin:/bin:/sbin/nologin

　　daemon:x:2:2:daemon:/sbin:/sbin/nologin

　　desktop:x:80:80:desktop:/var/lib/menu/kde:/sbin/nologin

　　mengqc:x:500:500:mengqc:/home/mengqc:/bin/bash
```

/etc/passwd中一行记录对应着一个用户，每行记录又被冒号(:)分隔为7个字段，其格式和具体含义如下：
　　**用户名**:**口令**:**用户标识号**:**组标识号**:**注释性描述**:**主目录**:**登录Shell**

处于安全考虑现在口令一般保存在/etc/shadow中,而这里的口令填x



## limits.conf和sysctl.conf

系统资源限制

`limits.conf` 和 `sysctl.conf` 区别在于，前者只针对用户，而后者是针对整个系统参数配置的。

注意，在改变资源限制的时候需要保证如下的几个准则：

- 进程的软限制需要小于等于硬限制；
- 普通用户只能缩小硬限制，而且不可逆；只有超级用户可以扩大限制。

## /etc/profile

 此文件为系统的每个用户设置环境信息,当用户第一次登录时,该文件被执行。是系统全局针对终端环境的设置，它是login时最先被系统加载的，是它调用了/etc/bashrc，以及/etc/profile.d目录下的*.sh文件，如果有一个软件包，系统上只安装一份，供所有开发者使用，建议在/etc/profile.d下创建一个新的xxx.sh，配置环境变量。

## ~/.bashrc,  /etc/bash.bashrc

rc=run command



是用户相关的终端（shell）的环境设置，通常打开一个新终端时，默认会load里面的设置，在这里的设置不影响其它人。如果一个服务器多个开发者使用，大家都需要有自己的sdk安装和设置，那么最好就是设置它。



## /usr/lib , /usr/local/lib

动态链接库查找目录

## /etc/apt/sources.list

 软件仓库 源

## /etc/rc.local

开机自启动文件(debain)