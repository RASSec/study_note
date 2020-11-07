# linux提权

## 文章

 https://cloud.tencent.com/developer/article/1544037 



## 工具



linux-exploit-suggester-2

### Linux Exploit Suggester1/2

### 配置检查

#### LinEnum



#### Linuxprivchecker





#### Unix-Privesc-checker



## 爆破密码

### /etc/shadow

```
john ./shadow
```



## 运维人员留下的信息

运维人员会定期做巡检，采集配置文件、CPU利用率等重要信息备份工作，一般借助 expect 处理交互的命令，可以将交互过程如：ssh登录，ftp登录等写在一个脚本上，使之自动化完成，大大提高系统管理人员的工作效率，所以就有了expect，通过递归查找存在expect字段的文件，就有概率能获取密码信息。