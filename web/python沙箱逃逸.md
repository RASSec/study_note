# python 沙箱逃逸

在xman个人排位赛的时候第一次见到这种题

## 推荐网站

[https://www.k0rz3n.com/2018/05/04/Python%20%E6%B2%99%E7%9B%92%E9%80%83%E9%80%B8%E5%A4%87%E5%BF%98/](https://www.k0rz3n.com/2018/05/04/Python 沙盒逃逸备忘/)

## 关键函数

### getattr

`getattr`(*object*, *name*[, *default*])

返回对象命名属性的值。*name* 必须是字符串。如果该字符串是对象的属性之一，则返回该属性的值。例如， `getattr(x, 'foobar')` 等同于 `x.foobar`。如果指定的属性不存在，且提供了 *default* 值，则返回它，否则触发 [`AttributeError`](https://docs.python.org/zh-cn/3/library/exceptions.html#AttributeError)。

获得对象属性的值相当于拷贝对象属性的副本，如果这个对象的属性的值是一个函数呢？

fun=getattr(os,'system')则fun()=system()

