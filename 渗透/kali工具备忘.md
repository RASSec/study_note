# kali工具

## wfuzz

`wfuzz -c -w /usr/share/wfuzz/wordlist/general/common.txt  --hc 404 http://website.com/secret.php?FUZZ=something`

`COMMAND ==>  wfuzz -c -w /usr/share/seclists//usr/share/seclists/Discovery/DNS --hc 404 --hw 617 -u website.com -H "HOST: FUZZ.website.com"`

`COMMAND  ==> wfuzz -c -w /usr/share/seclists//usr/share/seclists/Discovery/DNS --hc 404 --hw 7873 -u hnpsec.com -H "HOST: FUZZ.hnpsec.com"`

()WORKING WITH FILTERS:                                                                               |   

(i) If we want to filter words then we used switch --hw (words_lenth. In above example --hw 12)        |
(ii) To filter lenth then we used --hl(In above above example this would be --hl 7)
(iii) For chars we used --hh (In above example this would br --hh 206)                                 |
(iv) For response code we use --hc. And always we attach --hc 404. Because this is common for all

## dirb

`dirb url -x .php`

`dirb url -x /contents.txt`

## nmap

•ICMP扫描：nmap  -sP 192.168.1.100-254

•尝试检测目标操作系统：-O

•SYN扫描：-sS

•操作系统版本检测：-sV

•AWD常用命令

	nmap -sS -p 1337 172.16.0.0/24

-sP ：进行ping扫描

打印出对ping扫描做出响应的主机,不做进一步测试(如端口扫描或者操作系统探测)： 

下面去扫描10.0.3.0/24这个网段的的主机

-sn:  Ping Scan - disable port scan  #ping探测扫描主机， 不进行端口扫描 （测试过对方主机把icmp包都丢弃掉，依然能检测到对方开机状态）

-sA

nmap 10.0.1.161 -sA （发送tcp的ack包进行探测，可以探测主机是否存活）

-D 

 nmap -sT 192.168.177.144 -D 192.168.177.34,192.168.177.56 

 这个例子中`-D`后面的`IP`地址是虚假的`IP`地址，它会和原始`IP`地址一同出现在目标机器的网络日志文件中，这会迷惑对方的网络管理员，让他们以为这三个`IP`都是伪造的。但不能添加太多虚假IP地址，不然会影响扫描结果。因此，只要使用一定数量的地址就行。 

### script list

 https://nmap.org/book/nse-scripts-list.html 

### script help

`nmap --script-help filename`

## 爆破神器：hydra



```
hydra -L user.txt  -P /usr/share/wordlists/fuzzDicts-master/passwordDict/top6000.txt 10.10.10.175 smb
```

