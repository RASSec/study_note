# linux提权记录

## lxd提权

前提：用户属于lxd用户组

https://www.freebuf.com/articles/system/216803.html



## 浏览记录提权

通过用户的浏览记录获得关键服务的密码从而提权



## 日志信息

adm用户组通常可以查看所有日志

其中/var/log/audit存在着用户的输入记录，查找用户输入方式为：

`cd /var/log/audit&&cat * | grep data=`



## sudo

对每一个用户一定要去查看有没有sudo的权限:`sudo -l`