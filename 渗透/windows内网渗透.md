# windows内网渗透

## 查看系统信息



### 查看开放端口



netstat -an

我们拿其中一行来解释吧：

Proto Local Address     Foreign Address    State

TCP  Eagle:2929       219.137.227.10:4899  ESTABLISHED

 

协议（Proto ）：TCP ，指是传输层通讯协议
本地机器名（Local Address ）：Eagle ，俗称计算机名了，安装系统时设置的，可以在“我的电脑”属性中修改，本地打开并用于连接的端口：2929 ） 
远程机器名（Foreign Address ）： 219.137.227.10
远程端口： 4899 
状态：ESTABLISHED 

 

**状态列表**

LISTEN  ：在监听状态中。  
ESTABLISHED ：已建立联机的联机情况。 
TIME_WAIT ：该联机在目前已经是等待的状态。 



### 查看自己内网ip

ping ceye的dns服务



