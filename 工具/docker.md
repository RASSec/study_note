[TOC]

# Docker使用(命令行)---来自菜鸟教程
## 推荐网站:
https://yeasy.gitbooks.io/docker_practice/



## docker空间清理

**docker system df**命令，类似于Linux上的**df**命令，用于查看Docker的磁盘使用情况:



`docker system prune`

`docker system prune -a`删除所有镜像



## docker配置

### 配置镜像

#### windows  Toolbox  docker

在docker toolbox的命令行中输入以下命令

```shell
docker-machine ssh [machine-name]
#(machine-name一般都是default)
sudo vi /var/lib/boot2docker/profile
#在--label provider=virtualbox的下一行添加--registry-mirror https://etiz1c4o.mirror.aliyuncs.com
sudo /etc/init.d/docker restart

#输入以下命令来查看是否修改成功
docker-machine env default
eval "$(docker-machine env default)"
docker info
```



#### windows images存储位置修改



```shell
docker info #查看存储位置
#Docker Root Dir: /mnt/sda1/var/lib/docker
#由于是放在虚拟机里,我们只需修改,挂载的位置即可
```

#### linux

```
sudo mkdir -p /etc/docker
sudo tee /etc/docker/daemon.json <<-'EOF'
{
  "registry-mirrors": ["https://etiz1c4o.mirror.aliyuncs.com"]
}
EOF
sudo systemctl daemon-reload
sudo systemctl restart docker
```





## Docker的简单操作
### 安装

安装程序地址:http://mirrors.aliyun.com/docker-toolbox/
教程：http://www.runoob.com/docker/windows-docker-install.html
或 https://yeasy.gitbooks.io/docker_practice/install/

### 运行应用程序

打开 Docker Quickstart Terminal

#### docker run ubuntu:15.10 /bin/echo "Hello world"

#### 确认容器有在运行

-  docker ps

	- 输出内容代表的意义

		- NAMES:容器名称
		- CONTAINER ID:容器ID
		- TAG : 容器标签

### 镜像的获取
- docker pull ***
- docker search ***
- 利用dockerfile构建镜像,
   1. cd dockerfile所在目录
   2. docker build -t 镜像的名字 **.** //这里有一个点千万不要漏掉

### 运行交互式的容器

#### docker run -i -t ubuntu:15.10 /bin/bash

root@dc0050c79503:/#

#### 参数解析

- -t:在新容器内指定一个伪终端或终端。
- -i:允许你对容器内的标准输入 (STDIN) 进行交互。
- ubuntu:15.10:NAME为ubuntu,tag为15.10的容器



### 启动容器（后台模式）

#### docker run -d ubuntu:15.10 /bin/sh -c "while true; do echo hello world; sleep 1; done"




#### 查看容器内的标准输出

- docker logs **NAME**/**CONTAINER ID**
- 例子:docker logs **2b1b7a428627**(容器id)

#### 停止容器

- docker stop NAME/CONTAINER ID
- 例子:docker stop amazing_cori

#### 重启应用容器

- restart 

#### 移除应用容器

- docker rm wizardly_chandrasekhar  
- 删除容器时，容器必须是**停止**状态

###  与后台容器交互
docker exec [OPTIONS] CONTAINER COMMAND [ARG...]
- OPTIONS说明：

- -d :分离模式: 在后台运行

- -i :即使没有附加也保持STDIN 打开

- -t :分配一个伪终端

### 查看帮助

docker --help
docker 命令 --help 

## Docker 镜像使用

### 列出镜像列表

- docker images

	- 各个选项说明

		- REPOSITORY：表示镜像的仓库源
		- TAG：镜像的标签
		- IMAGE ID：镜像ID
		- CREATED：镜像创建时间
		- SIZE：镜像大小

	- 同一仓库源可以有多个 TAG，代表这个仓库源的不同个版本，如ubuntu仓库源里，有15.10、14.04等多个不同的版本，我们使用 REPOSITORY:TAG 来定义不同的镜像。

### 获取一个新的镜像

- 当我们在本地主机上使用一个不存在的镜像时 Docker 就会自动下载这个镜像。
- 如果我们想预先下载这个镜像，我们可以使用 docker pull 命令来下载它。

### 查找镜像

- docker search httpd

### 创建镜像

- 从已经创建的容器中更新镜像，并且提交这个镜像
- 使用 Dockerfile 指令来创建一个新的镜像

### 更新镜像

- 更新镜像之前，我们需要使用镜像来创建一个容器。
- 在运行的容器内使用 apt-get update 命令进行更新。
- 在完成操作之后，输入 exit命令来退出这个容器。
- 我们可以通过命令 docker commit来提交容器副本。

	- 各个参数说明：

		- -m:提交的描述信息
		- -a:指定镜像作者
		- e218edb10161：容器ID
		- runoob/ubuntu:v2:指定要创建的目标镜像名

### 构建镜像

- 创建一个 Dockerfile 文件

	- 实例：                                                             
```
runoob@runoob:~$ cat Dockerfile 
FROM    centos:6.7
MAINTAINER      Fisher "fisher@sudops.com"

RUN     /bin/echo 'root:123456' |chpasswd
RUN     useradd runoob
RUN     /bin/echo 'runoob:123456' |chpasswd
RUN     /bin/echo -e "LANG=\"en_US.UTF-8\"" >/etc/default/local
EXPOSE  22
EXPOSE  80
CMD     /usr/sbin/sshd -D
	- 每一个指令都会在镜像上创建一个新的层，每一个指令的前缀都必须是大写的。
	- 第一条FROM，指定使用哪个镜像源
	- RUN 指令告诉docker 在镜像内执行命令，安装了什么。。。
```

- 然后，我们使用 Dockerfile 文件，通过 docker build 命令来构建一个镜像。

	- docker build -t runoob/centos:6.7 .

		- 参数说明：
		- -t ：指定要创建的目标镜像名
			- . ：Dockerfile 文件所在目录，可以指定Dockerfile 的绝对路径

### 将容器commit未镜像

```
docker commit [选项] <容器ID或容器名> [<仓库名>[:<标签>]]
docker commit \
    --author "Tao Wang <twang2218@gmail.com>" \
    --message "修改了默认网页" \
    webserver \
    nginx:v2
```








## 运行一个web应用

### 网络端口的快捷方式

- 查看到容器的端口映射

	- docker ps
	- docker port 可以查看指定 （ID 或者名字）容器的某个确定端口映射到宿主机的端口号

### 查看 WEB 应用程序日志

- docker logs [ID或者名字]

### 步骤

- 第一步:docker pull training/webapp  # 载入镜像
- 第二步:docker run -d -P training/webapp python app.py

	- 参数说明:

		- -d:让容器在后台运行。
		- -P:将容器内部使用的网络端口映射到我们使用的主机上。
		- -p 参数来设置不一样的端口：docker run -d -p 5000:5000 training/webapp python app.py

### 查看WEB应用程序容器的进程

- docker top 

### 检查 WEB 应用程序

- docker inspect

### 访问网站
以一下两张图片为例
![](https://i.imgur.com/l78DC1H.png)

![](https://i.imgur.com/cKJqBXU.png)

访问192.168.99.100:32768

### 进入网站所在容器的交互式终端

docker exec -it webserver bash
webserver: 容器名称
bash:shell程序(一般不用更改)

### 其他命令
- docker diff
具体查看镜像内的历史记录
- docker commit
docker commit [选项] <容器ID或容器名> [<仓库名>[:<标签>]]
Docker 提供了一个 docker commit 命令，可以将容器的存储层保存下来成为镜像。换句话说，就是在原有镜像的基础上，再叠加上容器的存储层，并构成新的镜像。以后我们运行这个新镜像的时候，就会拥有原有容器最后的文件变化。

## Dockerfile的构建

### Dockerfile编写所需要的技能
1. 只有自己会搭建环境，才能写Dockerfile来搭建环境(先自己搭建好，再把搭建过程用到的语句转化为Dockerfile)
2. 对Docker的原理有所了解

### 关于Docker的基本概念


#### Docker 镜像
我们都知道，操作系统分为内核和用户空间。对于 Linux 而言，内核启动后，会挂载 root 文件系统为其提供用户空间支持。而 Docker 镜像（Image），就相当于是一个 root 文件系统。比如官方镜像 ubuntu:18.04 就包含了完整的一套 Ubuntu 18.04 最小系统的 root 文件系统。

Docker 镜像是一个特殊的文件系统，除了提供容器运行时所需的程序、库、资源、配置等文件外，还包含了一些为运行时准备的一些配置参数（如匿名卷、环境变量、用户等）。镜像不包含任何动态数据，其内容在构建之后也不会被改变。

##### 分层存储

因为镜像包含操作系统完整的 root 文件系统，其体积往往是庞大的，因此在 Docker 设计时，就充分利用 Union FS 的技术，将其设计为分层存储的架构。所以严格来说，镜像并非是像一个 ISO 那样的打包文件，镜像只是一个虚拟的概念，其实际体现并非由一个文件组成，而是由一组文件系统组成，或者说，由多层文件系统联合组成。

**镜像构建时，会一层层构建，前一层是后一层的基础。每一层构建完就不会再发生改变，后一层上的任何改变只发生在自己这一层。** 比如，删除前一层文件的操作，实际不是真的删除前一层的文件，而是仅在当前层标记为该文件已删除。在最终容器运行的时候，虽然不会看到这个文件，但是实际上该文件会一直跟随镜像。 **因此，在构建镜像的时候，需要额外小心，每一层尽量只包含该层需要添加的东西，任何额外的东西应该在该层构建结束前清理掉。**

分层存储的特征还使得镜像的复用、定制变的更为容易。甚至可以用之前构建好的镜像作为基础层，然后进一步添加新的层，以定制自己所需的内容，构建新的镜像。

#### Docker 容器

**镜像（Image）和容器（Container）的关系，就像是面向对象程序设计中的 类 和 实例 一样**，镜像是静态的定义，容器是镜像运行时的实体。容器可以被创建、启动、停止、删除、暂停等。

容器的实质是进程，但与直接在宿主执行的进程不同，容器进程运行于属于自己的独立的 命名空间。因此容器可以拥有自己的 root 文件系统、自己的网络配置、自己的进程空间，甚至自己的用户 ID 空间。容器内的进程是运行在一个隔离的环境里，使用起来，就好像是在一个独立于宿主的系统下操作一样。这种特性使得容器封装的应用比直接在宿主运行更加安全。也因为这种隔离的特性，很多人初学 Docker 时常常会混淆容器和虚拟机。

前面讲过镜像使用的是分层存储，容器也是如此。每一个容器运行时，是以镜像为基础层，在其上创建一个当前容器的存储层，我们可以称这个为容器运行时读写而准备的存储层为 容器存储层。

容器存储层的生存周期和容器一样，容器消亡时，容器存储层也随之消亡。因此，任何保存于容器存储层的信息都会随容器删除而丢失。

按照 Docker 最佳实践的要求，容器不应该向其存储层内写入任何数据，容器存储层要保持无状态化。所有的文件写入操作，都应该使用 数据卷（Volume）、或者绑定宿主目录，在这些位置的读写会跳过容器存储层，直接对宿主（或网络存储）发生读写，其性能和稳定性更高。

数据卷的生存周期独立于容器，容器消亡，数据卷不会消亡。因此，使用数据卷后，容器删除或者重新运行之后，数据却不会丢失。

### 利用 commit 理解镜像构成

[看这里](https://yeasy.gitbooks.io/docker_practice/image/commit.html)

### Dockerfile的编写
从刚才的 docker commit 的学习中，我们可以了解到，镜像的定制实际上就是定制每一层所添加的配置、文件。如果我们可以把每一层修改、安装、构建、操作的命令都写入一个脚本，用这个脚本来构建、定制镜像，那么之前提及的无法重复的问题、镜像构建透明性的问题、体积的问题就都会解决。这个脚本就是 Dockerfile。

Dockerfile 是一个文本文件，其内包含了一条条的 指令(Instruction)，每一条指令构建一层，因此每一条指令的内容，就是描述该层应当如何构建。

### 命令

- FROM 指定基础镜像

所谓定制镜像，那一定是以一个镜像为基础，在其上进行定制。就像我们之前运行了一个 nginx 镜像的容器，再进行修改一样，基础镜像是必须指定的。而 FROM 就是指定 基础镜像，因此一个 Dockerfile 中 FROM 是必备的指令，并且必须是第一条指令。
- RUN 
RUN 指令是用来执行命令行命令的。由于命令行的强大能力，RUN 指令在定制镜像时是最常用的指令之一。其格式有两种：

   - shell 格式：RUN <命令>，就像直接在命令行中输入的命令一样。

   - exec 格式：RUN ["可执行文件", "参数1", "参数2"]，这更像是函数调用中的格式。
- COPY 复制文件
  - 格式：

```
COPY [--chown=<user>:<group>] <源路径>... <目标路径>
COPY [--chown=<user>:<group>] ["<源路径1>",... "<目标路径>"]
```

### 详细教程

https://yeasy.gitbooks.io/docker_practice/image/build.html


### 注意事项

- 每一个命令都会构建一个容器

	- 因此在每一个容器中获得所需的东西后删除无用的东西
实例:
```
FROM debian:stretch

RUN buildDeps='gcc libc6-dev make wget' \
    && apt-get update \
    && apt-get install -y $buildDeps \
    && wget -O redis.tar.gz "http://download.redis.io/releases/redis-5.0.3.tar.gz" \
    && mkdir -p /usr/src/redis \
    && tar -xzf redis.tar.gz -C /usr/src/redis --strip-components=1 \
    && make -C /usr/src/redis \
    && make -C /usr/src/redis install \
    && rm -rf /var/lib/apt/lists/* \
    && rm redis.tar.gz \
    && rm -r /usr/src/redis \
    && apt-get purge -y --auto-remove $buildDeps #清除了无用的安装包
```

- 指令中的路径是相对路径，相对于上下文路径
- 一步一步来不要想着可以一下写出来



## docker 网络-端口映射、容器链接、Networking

 https://itbilu.com/linux/docker/Ey5dT-i2G.html 



### 容器链接(link)

 容器的连接（link）系统是除了端口映射外，另一种跟容器中应用交互的方式。该系统会在源容器和接收容器之间创建一个隧道，接收容器可以看到源容器指定的信息。Docker的链接是一个可以将具体的容器连接到一起来进行通信的抽像层。 

`docker run -d -P --name web --link db:db training/webapp python app.py`

#### --link参数格式

`--link`参数的格式为`--link name:alias`，其中：`name`表示要连接的容器的名称，而`alias`表示连接后的别名。

通过`--link`参灵敏，Docker 会在两个互联的容器之间创建了一个安全的隧道，且不用映射它们的端口到宿主主机上。在前面我们启动`db`容器的时，并没有使用`-p`和`-P`参数，从而避免了暴露数据库端口到外部网络上，增加了容器的安全性。



## docker compose

```shell
docker-compose up -d 启动容器，如果镜像不存在则先下载镜像，如果容器没创建则创建容器，如果容器没启动则启动
docker-compose down 停止并移除容器
docker-compose restart 重启服务
```





### 踩过的坑

- : COPY命令中文件夹后面一点要加/(/etc错误，/etc/正确)

### 常用设置

- 设置服务自启动

	- echo '#!/bin/sh' >> /startup.sh &&\
echo '/opt/lampp/lampp start' >> /startup.sh &&\
echo '/bin/bash ' >> /startup.sh && \
CMD ["sh", "/startup.sh"]





## 一些dockers镜像的使用

### mysql

 https://itbilu.com/linux/docker/EyP7QP86M.html 



### php

```
php:<version>-cli
php:<version>-apache
php:<version>-fpm
php:<version>-alpine

```

安装ffi

```
sed -i 's#http://deb.debian.org#https://mirrors.163.com#g' /etc/apt/sources.list && apt update && apt install -y --no-install-recommends libffi-dev && docker-php-ext-install ffi
```



安装imap

```
apt update && apt install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
apt-get install libc-client2007e
docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

```



安装xdebug

```
pecl install xdebug
docker-php-ext-enable xdebug
或
echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
```

