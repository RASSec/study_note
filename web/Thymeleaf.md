# Thymeleaf

Thymeleaf是一个java模板引擎，它在独立和Web应用程序中处理六种模板(HTML，CSS，TEXT，JAVASCRIPT，CSS和RAW)。

## 环境搭建





## 标准表达式语法

大多数Thymeleaf属性允许将它们的值设置为或包含表达式，由于它们使用的方言，我们将其称为标准表达式。这些表达式可以有五种类型:

- `${...}` : 变量表达式。
- `*{...}` : 选择表达式。
- `#{...}` : 消息 (i18n) 表达式。
- `@{...}` : 链接 (URL) 表达式。
- `~{...}` : 片段表达式。

### 变量表达式

`${session.user.name}`输出变量的值

### 选择表达式

选择表达式就像变量表达式一样，它们不是整个上下文变量映射上执行，而是在先前选择的对象。 它们看起来像这样:

```jsp
*{customer.name}

```

它们所作用的对象由`th:object`属性指定:

```jsp
<div th:object="${book}">
  ...
  <span th:text="*{title}">...</span>
  ...
</div>

```

所以这相当于:

```java
{
  // th:object="${book}"
  final Book selection = (Book) context.getVariable("book");
  // th:text="*{title}"
  output(selection.getTitle());
}
```





## 表达式预处理

`#{selection.__${sel.code}__}`

`${sel.code}`Thymelead首先预处理${sel.code}。然后，它使用结果(在本例中是一个存储值ALL)作为稍后计算的真实表达式的一部分(#{selection.ALL})。



## 全局变量



### Basci

```
#ctx: the context object.
#vars: the context variables.
#locale: the context locale.
#request: (only in Web Contexts) the HttpServletRequest object.
#response: (only in Web Contexts) the HttpServletResponse object.
#session: (only in Web Contexts) the HttpSession object.
#servletContext: (only in Web Contexts) the ServletContext object.
```

### Utility

```
#execInfo: information about the template being processed.
#messages: methods for obtaining externalized messages inside variables expressions, in the same way as they would be obtained using #{…} syntax.
#uris: methods for escaping parts of URLs/URIs
#conversions: methods for executing the configured conversion service (if any).
#dates: methods for java.util.Date objects: formatting, component extraction, etc.
#calendars: analogous to #dates, but for java.util.Calendar objects.
#numbers: methods for formatting numeric objects.
#strings: methods for String objects: contains, startsWith, prepending/appending, etc.
#objects: methods for objects in general.
#bools: methods for boolean evaluation.
#arrays: methods for arrays.
#lists: methods for lists.
#sets: methods for sets.
#maps: methods for maps.
#aggregates: methods for creating aggregates on arrays or collections.
#ids: methods for dealing with id attributes that might be repeated (for example, as a result of an iteration).
```

## payload收集

```
[[*{__${#strings.replace(param.foo[0],param.bbb[0],param.t[0])}__}]]
```

