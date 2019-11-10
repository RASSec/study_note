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

