# fork 炸弹

## 原理

不断调用自己来创建新的进程,并让自己在后台运行

最经典的fork炸弹

`b(){ b|b& };b`

## Linux 资源限制

### 文章

https://jin-yang.github.io/post/linux-resource-limit-introduce.html

https://www.vpsee.com/2010/09/limit-linux-user-process/

### 总结

#### ulimit

设置当前shell和子shell的限制

#### 配置文件

在/etc/security/limits.conf添加记录

` *      hard    nproc   200`

`用户  hard/soft nproc 进程数`

