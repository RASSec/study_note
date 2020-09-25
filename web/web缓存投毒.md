# web缓存投毒

## 原理

对于某个http头/get参数/cookie参数(unkeyed input)，CDN不会因其改变而向服务器发起请求，而是缓存它，但是http response却根据它改变







## 常见 Unkeyed Input

http header:

```
X-Forwarded-Host
X-Forwarded-Proto
X-Original-URL
```

cookie:

```
lang
```





get:

```
lang
```



## 工具

Burp 扩展：Param Miner



## 教程

 https://portswigger.net/web-security/web-cache-poisoning 

