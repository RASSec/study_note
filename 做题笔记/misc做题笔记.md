# misc做题笔记

## xman练习赛

### 小小的pdf

下载附件，就发现里面有两张图

用hxd看了一下都是FFD8开头发现都是jpg格式

到这边我就卡住了

然后谷歌搜索有关pdf的misc题，发现什么移动图片获得flag

,但我没有pdf编辑器,于是我就想能不能直接把第三张图片的数据拷出来。搜了一下发现真的有3个jpg头

拷贝出来完事。

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5p5z5nccuj30fh0433z7.jpg)

### 神奇的modbus

下载用wireshark打开

filter:mbtcp(被这个搞了好久)

保存

follow the tcp stream

得到flag:sctf{Easy_Mdbus}

### mysql

#### 做法1

使用工具undrop-for-innodb

```shell
git clone https://github.com/twindb/undrop-for-innodb.git
sudo apt-get install flex bison

```

> dictionary目录。存放字典sql脚本，用于恢复表结构的几张核心字典表的DDL语句
> sakila目录。测试schema
> stream_parser。可执行文件，用于扫描文件或者磁盘设备，目的是找出符合innodb格式的数据页，按照index_id进行组织
> c_parser。可执行文件，用于解析innodb数据页，获取行记录
> sys_parser。可执行文件，通过字典表记录恢复目标表的表结构

https://www.anquanke.com/post/id/85170

https://www.xctf.org.cn/library/details/e2773814ba1c89e8ee1edcb9b556d50301c16670/

#### 做法2



打开ib_logfile0 查找flag出来了

### Cephalopod

#### 流量包提取文件方法

- wireshark

wrieshark自带有文件分离功能。

- tcpxtract

`tcpxtract -f XXX.pcap`

- foremost
```shell
foremost -i XXX.pcap
```

- Network miner

下载地址：https://sourceforge.net/projects/networkminer/

#### 过程

wireshark follow the tcp stream

发现了png的头和尾，复制粘贴，得到flag

### Erik-Baleog-and-Olaf

下载图片,因为之前有看别人做，所以我知道里面有一个二维码。

直接用stegosolve查看，但是二维码不清晰

卡在这边好久，想过各种修改图片

后来别人告诉我文件里有一个图片的网址，下载下来，用stegosolve两个图片相减

得到flag:flag{#justdiffit}

### Miscellaneous

还行吧，记录下做法

获得一个加密压缩包，解密发现是文件名:

一个压缩包套另一个不同名的压缩包，所以写了个程序

```python
import zipfile
zipname='73168.zip'
while zipname[:-4].isdigit():
    with zipfile.ZipFile(zipname) as myzip:
        zipname=myzip.namelist()[0]
        myzip.extract(zipname,pwd=bytes(zipname[:-4],encoding='ascii'))
        print('解压'+zipname+'成功')

```

最后一个压缩包里面是音频文件,用ARCHPR解密

百度音频隐写教程一个一个试过去

最后是用Audacity看频谱图

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5q6pwup95j30qi051ae1.jpg)

### 神奇的压缩文件

超大脑洞

知识点:利用ntfs文件系统的特性隐藏文件(工具ntfs  streams info)

一个ascii有7位且ascii以0作为开头0(ascii)(ascii).....

获得一个无限解压的rar 解压看发现一个网址(tykje.com)，结果发现访问不了

到这边我直接不会，看别人的wp 发现hello和XDSEC中间有重复的tab和空格换成0和1,()得到flag:lctf{6d3677dd}