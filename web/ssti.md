# ssti

## 思考方法 

### 检测

#### 直接将输入当成模板进行渲染

如

```python
reander_string(s);
```



#### 嵌入已有模板中

`reander_string("{{'hello,'+'"+s+"'}}");`

### 确认模板引擎



![image.png](http://ww1.sinaimg.cn/large/006pWR9aly1g8odw94ktrj31360mxjtz.jpg)

红色代表命令未执行,绿色代表命令成功执行

在twig中,{{7*'7'}}=>49

而在jinja2中{{7*'7'}}=>7777777




### 利用

#### 阅读文档

前两部都是为了确定阅读什么文档,文档中有利于我们接下来利用的提示。

在阅读文档的时候留意以下部分

-  'For Template Authors' 部分的基本语法
-  'Security Considerations' - 开发者通常没有阅读这一部分 ，在这个部分可能会包含一些对利用有用的提示
-  内置的方法,函数,过滤器和变量
- 留意默认加载的扩展和插件



#### 探索

- 记录下阅读文档时发现的可利用点。
- 接下来就是爆破获得sandbox中默认包含的对象方法和变量(作者推荐fuzzDB?)。
- 很多模板默认包含类似self的变量,可以尝试找一找。
- 开发者提供的对象通常具有敏感信息

#### 攻击

冲



## 一些有用的东东

`__dict__和dir()`获得 模块对象名称空间的变量,函数,类等等

### python 魔术方法文档

 https://pyzh.readthedocs.io/en/latest/python-magic-methods-guide.html#id1 









## jiaja2

http://docs.jinkan.org/docs/jinja2/templates.html



### 前置知识

#### 过滤器

变量可以通过 **过滤器** 修改。过滤器与变量用管道符号（ `|` ）分割，并且也 可以用圆括号传递可选参数。多个过滤器可以链式调用，前一个过滤器的输出会被作为 后一个过滤器的输入。

过滤器清单:http://docs.jinkan.org/docs/jinja2/templates.html#builtin-filters





### 一些绕过

- 禁用{{,则可以利用{%

- 禁用__,用

  ```
  {{''|attr('_'+'_class_'+'_')}}
  ```

  来绕过




