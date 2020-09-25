import string
import requests
import random


burp0_url = "http://139.199.182.61:19999/index.php?method=send"
burp0_cookies = {"PHPSESSID": "ab5fgepp50bnaenppkju4md4qs"}
burp0_headers = {"Cache-Control": "max-age=0", "Origin": "http://139.199.182.61:19999", "Upgrade-Insecure-Requests": "1", "Content-Type": "application/x-www-form-urlencoded", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9", "Referer": "http://139.199.182.61:19999/index.php", "Accept-Language": "zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7", "Connection": "close"}


allow_list=[]
disable_list=[]
for i in string.printable:
    burp0_data = {"message": "aaa%s" % i}
    res=requests.post(burp0_url, headers=burp0_headers, cookies=burp0_cookies, data=burp0_data)

    if "参数不合法" not in res.text:
        allow_list.append(i)
    else :
        disable_list.append(i)


print("allow:",allow_list)
print("disable:",disable_list)