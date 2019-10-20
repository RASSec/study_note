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