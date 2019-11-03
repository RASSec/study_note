# web缓存欺骗攻击

真不知道大佬们是怎么发现的。

## 原文

 https://omergil.blogspot.com/2017/02/web-cache-deception-attack.html 

## what is cache

 [https://zh.wikipedia.org/wiki/Web%E7%BC%93%E5%AD%98](https://zh.wikipedia.org/wiki/Web缓存) 

 https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Caching_FAQ 

>**Web缓存**（或**HTTP缓存**）是用于临时存储（[缓存](https://zh.wikipedia.org/wiki/缓存)）[Web文档](https://zh.wikipedia.org/wiki/網頁)（如[HTML页面](https://zh.wikipedia.org/wiki/網頁)和[图像](https://zh.wikipedia.org/wiki/数字图像)），以减少[服务器](https://zh.wikipedia.org/wiki/带宽_(计算机))延迟的一种[信息技术](https://zh.wikipedia.org/wiki/信息技术)。Web缓存系统会保存下通过这套系统的文档的副本；如果满足某些条件，则可以由缓存满足后续请求。[[1\]](https://zh.wikipedia.org/wiki/Web缓存#cite_note-1) Web缓存[系统](https://zh.wikipedia.org/wiki/系統)既可以指[设备](https://zh.wikipedia.org/w/index.php?title=服务器设备&action=edit&redlink=1)，也可以指计算机程序。 
>
> 缓存的种类有很多,其大致可归为两类：私有与共享缓存。共享缓存存储的响应能够被多个用户使用。私有缓存只能用于单独用户。本文将主要介绍浏览器与代理缓存，除此之外还有网关缓存、CDN、反向代理缓存和负载均衡器等部署在服务器上的缓存方式，为站点和 web 应用提供更好的稳定性、性能和扩展性。 

网站通常会缓存静态文件如`.css,.js,.png`等文件来加快网站访问，减少服务器负荷



### 与cache相关的一些http头字段

####  Cache-control

HTTP/1.1定义的 [`Cache-Control`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Cache-Control) 头用来区分对缓存机制的支持情况， 请求头和响应头都支持这个属性。通过它提供的不同的值来定义缓存策略。



##### 客户端 max-age=0的含义

 If a user agent sends a request with `Cache-Control: max-age=0` (aka. "end-to-end revalidation"), then each cache along the way will revalidate its cache entry (eg. with the `If-Not-Modified` header) all the way to the origin server. If the reply is then 304 (Not Modified), the cached entity can be used. 



##### 服务端 max-age=0和no-cache的区别

 I believe `max-age=0` simply tells caches (and user agents) the response is stale from the get-go and so they **SHOULD** revalidate the response (eg. with the `If-Not-Modified` header) before using a cached copy, whereas, `no-cache` tells them they **MUST** revalidate before using a cached copy. From [14.9.1 What is Cacheable](http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9.1): 



##### 禁止进行缓存

缓存中不得存储任何关于客户端请求和服务端响应的内容。每次由客户端发起的请求都会下载完整的响应内容。

```html
Cache-Control: no-store
```

##### 强制确认缓存

如下头部定义，此方式下，每次有请求发出时，缓存会将此请求发到服务器（译者注：该请求应该会带有与本地缓存相关的验证字段），服务器端会验证请求中所描述的缓存是否过期，若未过期（注：实际就是返回304），则缓存才使用本地缓存副本。

```html
Cache-Control: no-cache
```

##### 私有缓存和公共缓存

"public" 指令表示该响应可以被任何中间人（译者注：比如中间代理、CDN等）缓存。若指定了"public"，则一些通常不被中间人缓存的页面（译者注：因为默认是private）（比如 带有HTTP验证信息（帐号密码）的页面 或 某些特定状态码的页面），将会被其缓存。

而 "private" 则表示该响应是专用于某单个用户的，中间人不能缓存此响应，该响应只能应用于浏览器私有缓存中。

```html
Cache-Control: private
Cache-Control: public
```

##### 缓存过期机制

过期机制中，最重要的指令是 "`max-age=`"，表示资源能够被缓存（保持新鲜）的最大时间。相对[Expires](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Expires)而言，max-age是距离请求发起的时间的秒数。针对应用中那些不会改变的文件，通常可以手动设置一定的时长以保证缓存有效，例如图片、css、js等静态资源。

详情看下文关于[缓存有效性](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Caching_FAQ#Freshness)的内容。

```html
Cache-Control: max-age=31536000
```

##### 缓存验证确认

当使用了 "`must-revalidate`" 指令，那就意味着缓存在考虑使用一个陈旧的资源时，必须先验证它的状态，已过期的缓存将不被使用。详情看下文关于[缓存校验](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Caching_FAQ#Cache_validation)的内容。

```html
Cache-Control: must-revalidate
```

####  `Pragma` 头

 [`Pragma`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Pragma) 是HTTP/1.0标准中定义的一个header属性，请求中包含Pragma的效果跟在头信息中定义Cache-Control: no-cache相同，但是HTTP的响应头没有明确定义这个属性，所以它不能拿来完全替代HTTP/1.1中定义的Cache-control头。通常定义Pragma以向后兼容基于HTTP/1.0的客户端。 

#### ETags

作为缓存的一种强校验器，[`ETag`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/ETag) 响应头是一个对用户代理(User Agent, 下面简称UA)不透明（译者注：UA 无需理解，只需要按规定使用即可）的值。对于像浏览器这样的HTTP UA，不知道ETag代表什么，不能预测它的值是多少。如果资源请求的响应头里含有ETag, 客户端可以在后续的请求的头中带上 [`If-None-Match`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/If-None-Match) 头来验证缓存。

[`Last-Modified`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Last-Modified) 响应头可以作为一种弱校验器。说它弱是因为它只能精确到一秒。如果响应头里含有这个信息，客户端可以在后续的请求中带上 [`If-Modified-Since`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/If-Modified-Since) 来验证缓存。

当向服务端发起缓存校验的请求时，服务端会返回 [`200`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Status/200) ok表示返回正常的结果或者 [`304`](https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Status/304) Not Modified(不返回body)表示浏览器可以使用本地缓存文件。304的响应头也可以同时更新缓存文档的过期时间。



## 原理

代理服务器根据扩展名来缓存文件,而服务器却可以解析类似`http://www.444.com/index.php/aa.css`

而用户下次访问`http://www.444.com/index.php/aa.css`将直接从代理服务器返回上次的缓存内容

### 例子

![undefined](http://ww1.sinaimg.cn/large/006pWR9agy1g8jmbsn8jzj30hs07i0tq.jpg)

### 解释

1. 浏览器访问 http://www.444.com/index.php/aa.css
2. 服务器返回http://www.444.com/index.php的内容
3. 请求经过代理
4. 代理通过.css后缀将这个请求缓存

## 利用条件

1. web缓存功能根据扩展名缓存,而无视http cache头
2. 服务器会将`/index.php/a.css`解析成`/index.php`