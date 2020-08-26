# misc

## 常见文件头

JPEG (jpg)，文件头：FFD8FF 
PNG (png)，文件头：89504E47 
GIF (gif)，文件头：47494638 

zip,文件头: FFD8(PK)，文件尾:FFD9

rar,文件头,文件尾

## 隐写

### word 文档

### Word 文档

Word 文档可能会隐藏某些信息，遇到 `doc` 文档可以尝试在 `Word 选项`中选择`显示`并打开`隐藏文字`选项。如下所示：
![word](https://howiezhao.github.io/images/word.PNG)
像 Word 文档或 Excel 表格这样的富文本文件，可以直接解压之，查看其中是否包含某些特殊文件。



### pdf

- 用图片覆盖图片

## ZIP

### 明文攻击

 https://ctf-wiki.github.io/ctf-wiki/misc/archive/zip-zh/#_6 

### 伪加密

```
java -jar ZipCenOp.jar r flag.zip
```



### crc

https://github.com/kmyk/zip-crc-cracker



## 音频文件

频谱图隐写

## 区分windows 64位和32位的方法

  "PE..L" (十六进制代码: 504500004C) = 32 bit，"PE..d†" (十六进制代码: 504500006486) = 64 bit， 





## 文件操作



### binwalk

查找该文件中的其他文件

```
binwalk filename
```



### foremost

分割文件

```
foremost filename
```



### filesystem中的文件隐藏

#### 利用fsck恢复

利用fsck恢复

eg.

```bash
mount /tmp/flag /mnt/flag
cd /mnt/flag
ls -al
total 17
drwxr-xr-x 3 root root  1024 Jun 26 08:54 .
drwxr-xr-x 3 root root  4096 Jun 28 11:36 ..
drwx------ 2 root root 12288 Jun 26 08:54 lost+found
cd lost+found
ls -al
total 13
drwx------ 2 root root 12288 Jun 26 08:54 .
drwxr-xr-x 3 root root  1024 Jun 26 08:54 ..
cd /tmp && umount /tmp/flag
fsck /tmp/flag
binwalk /tmp/flag

DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
0             0x0             Linux EXT filesystem, rev 1.0, ext2 filesystem data, UUID=d4d08581-e309-4c51-990b-6472ba24ba24
46080         0xB400          PNG image, 728 x 100, 8-bit/color RGB, non-interlaced
46121         0xB429          Zlib compressed data, default compression

```

#### 利用binwalk



## 图片隐写

### 工具

binwalk,foremost

png隐写检测：zsteg

stegdetect

自己编译出来的stegbreak老是segment fault

建议用[编译好的windows文件](https://web.archive.org/web/20120118060657if_/http://www.outguess.org/stegdetect-0.4.zip)



## 常用工具

captfencoder