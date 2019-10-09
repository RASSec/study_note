# ssrf

## 绕过小技巧

1.http://baidu.com@www.baidu.com/与http://www.baidu.com/请求时是相同的

2.各种IP地址的进制转换

3.URL跳转绕过：http://www.hackersb.cn/redirect.php?url=http://192.168.0.1/

4.短网址绕过 http://t.cn/RwbLKDx

5.xip.io来绕过：http://xxx.192.168.0.1.xip.io/ == 192.168.0.1 (xxx 任意）

指向任意ip的域名：xip.io(37signals开发实现的定制DNS服务)

6.限制了子网段，可以加 :80 端口绕过。http://tieba.baidu.com/f/commit/share/openShareApi?url=http://10.42.7.78:80

7.探测内网域名，或者将自己的域名解析到内网ip

8.例如 http://10.153.138.81/ts.php , 修复时容易出现的获取host时以/分割来确定host，

但这样可以用 http://abc@10.153.138.81/ 绕过

