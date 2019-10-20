# ssti

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

- 