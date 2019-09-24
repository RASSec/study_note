# linux配置文件

## /etc/passwd

这个文件对所有用户都是可读的。

```
　　root:x:0:0:root:/root:/bin/bash

　　bin:x:1:1:bin:/bin:/sbin/nologin

　　daemon:x:2:2:daemon:/sbin:/sbin/nologin

　　desktop:x:80:80:desktop:/var/lib/menu/kde:/sbin/nologin

　　mengqc:x:500:500:mengqc:/home/mengqc:/bin/bash
```

/etc/passwd中一行记录对应着一个用户，每行记录又被冒号(:)分隔为7个字段，其格式和具体含义如下：
　　**用户名**:**口令**:**用户标识号**:**组标识号**:**注释性描述**:**主目录**:**登录Shell**

处于安全考虑现在口令一般保存在/etc/shadow中,而这里的口令填x



