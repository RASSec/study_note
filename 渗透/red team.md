# read team



## 渗透思路



### 总体思路

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9eytss3fij30rl0cg780.jpg)



### 信息收集方向



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ez4odsw8j30c209saar.jpg)



### 拿到webshell后的进一步利用

#### 与msf meterpreter或cobalt strike建立连接









#### cs通过sock代理与msf进行联动

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezad7uywj30zv0jlqlz.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ez9wwwu0j30nm0dgtcz.jpg)





![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ez9m26ufj30yf0h7qim.jpg)





### 拿到高权限

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezb83347j30ym0iltm6.jpg)



### 配置代理

#### frps来设置代理



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezh8l2l6j30y20fu7bs.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezhso48cj30zq0j6jxw.jpg)



#### 利用reGeorg设置简易代理

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9eziiwll6j31060k2wq1.jpg)



### 钓鱼

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f32sonucj310e0jetim.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f33it624j315b0jxwns.jpg)





### 抓取用户密码

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f34q9rkij315o0md7cq.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f35awg2yj30zv0jrgu7.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f35v3owrj30zz0k7ti2.jpg)







### 横向移动

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f380tekkj30xx0kk78o.jpg)

#### kerberos

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3adlzsoj31040k0ah9.jpg)



#### spn



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f39ryzjsj30yr0k1n8l.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3b3hkfzj30x50hs44h.jpg)



#### pth

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3d5r77wj30zp0ghq8k.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3dhetntj31050jogx0.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3dykib4j30zx0jx12v.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3efivrnj30zb0kbwpw.jpg)



#### wmi利用

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3ete1dij30zw0jvh1o.jpg)







#### 计划任务启动

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3gfbo1oj314n0nhwqa.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3gqr4z9j30nq036gmv.jpg)







#### ssp

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3f4tza0j31020kdn7r.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3hbn79oj30p102n3z8.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3hstjakj311j0nvwnv.jpg)

### 域管权限维持



#### 金票利用

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3inso9tj30zi0h5gxh.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3k12g5oj315c0nek61.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3khnd1yj315a0nlwss.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3kyf0oaj314r0njn5w.jpg)





![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3lc8nb5j316i0n2an9.jpg)



### 隧道隐藏



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3mnnb3qj30yk0jqgug.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3n0q4gxj318w0n94dp.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f3ndfiyzj31940nr7nd.jpg)





### 上传马被杀

cs生成payload,网络收集



云沙箱检测



## 工具使用

### metasploit

#### 数据库准备

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezdnvp15j30zo0ixgzb.jpg)





![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezefctuyj30x90i4af1.jpg)





#### 常见漏洞扫描命令

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9ezfbmrzjj310h0k11fq.jpg)





## 杂



### 什么是域

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f36xb3vrj312e0k1gs1.jpg)



![image-20191129182958955](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20191129182958955.png)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9f37meyk0j31060k1dn4.jpg)

