# DAY2

## 古典密码 
栅栏密码,云隐密码,曲路密码, 凯撒密码, 埃特巴什码,培根密码,仿射密码

## 分组密码
- CBC字节翻转攻击:
nc1=c1[:-2]+str(hex(int(c1[-2:],16)\^ord('0')\^ord('1'))[2:])
nc=nc1+c2
- CBC选择密文攻击:
这个不太会...
- padding oracle 攻击
- Feistel结构:如果F函数是线性的,可以实现已知明文攻击

## rsa

### 链接

 [https://err0rzz.github.io/2017/11/14/CTF%E4%B8%ADRSA%E5%A5%97%E8%B7%AF/](https://err0rzz.github.io/2017/11/14/CTF中RSA套路/) 



 [https://skysec.top/2018/08/25/RSA%E4%B9%8B%E6%8B%92%E7%BB%9D%E5%A5%97%E8%B7%AF-2/](https://skysec.top/2018/08/25/RSA之拒绝套路-2/) 



 https://en.wikipedia.org/wiki/RSA_(cryptosystem)#Using_the_Chinese_remainder_algorithm 



 https://bbs.pediy.com/thread-254252.htm 



 https://github.com/kur0mi/CTF-RSA 

### 加密原理

#### 名词

 **欧拉函数**![\varphi (n)](https://wikimedia.org/api/rest_v1/media/math/render/svg/f067864064667dd5f8b2508b9cbf983d89788629)是小于或等于*n*的正整数中与*n*[互质](https://zh.wikipedia.org/wiki/互質)的数的数目。 

**模反元素**  如果两个正整数a和n互质，那么一定可以找到整数b，使得 ab-1 被n整除，或者说ab被n除的余数是1。这时，b就叫做a的“模反元素”。  

![](https://gss0.bdstatic.com/-4o3dSag_xI4khGkpoWK1HF6hhy/baike/s%3D220/sign=992ce6a4ecdde711e3d244f497eecef4/960a304e251f95ca9d86c417c0177f3e6709521b.jpg)



**模数** ：N

 **素因子**就是素数因子，也就是质数因子，

比如12=`3*4`=`3*2*2`
3和2是素因子，4就不是素因子。

  **gcd** 是求最大公约数



#### 一些算法知识

##### 欧几里得算法

> 欧几里德算法又称[辗转相除法](https://baike.baidu.com/item/辗转相除法/4625352)，是指用于计算两个[正整数](https://baike.baidu.com/item/正整数/8461335)a，b的[最大公约数](https://baike.baidu.com/item/最大公约数/869308)。应用领域有数学和计算机两个方面。计算公式gcd(a,b) = gcd(b,a mod b)。 



##### 费马小定理



![image.png](https://ws1.sinaimg.cn/large/006pWR9aly1g9cvy3oow6j30rp02odg2.jpg)





#### 公钥与私钥的产生

1. 随机选择两个不同大质数 p 和 q，计算 N=p×q
2. 根据欧拉函数，求得 φ(N)=φ(p)φ(q)=(p−1)(q−1)
3. 选择一个小于 φ(N)的整数 e，使 e 和 φ(N) 互质。并求得 e 关于 φ(N)的模反元素，命名为 d，有![image.png](http://ww1.sinaimg.cn/large/006pWR9aly1g93lugqzw0j308301hjr9.jpg)
4. 将 p 和 q 的记录销毁

此时，(N,e) 是公钥,(N,d) 是私钥。



#### 消息加密

https://ctf-wiki.github.io/ctf-wiki/crypto/asymmetric/rsa/rsa_theory-zh/#_3

![image.png](https://ws1.sinaimg.cn/large/006pWR9aly1g9cuuirl5nj307h01vmwz.jpg)



#### 消息解密

https://ctf-wiki.github.io/ctf-wiki/crypto/asymmetric/rsa/rsa_theory-zh/#_4

![image.png](https://ws1.sinaimg.cn/large/006pWR9aly1g9cuuq4zttj308f028web.jpg)



#### N,e,d,p,q,φ(N)



### 直接模数分解

#### 条件

- N的长度较小，可直接分解：个人计算机256bit以下
- N中p和q选择存在的问题：过于接近 or 差距过大 or 光滑
- p - 1 光滑
- P+1光滑（2017 SECCON very smooth）



### 常用函数

#### gmpy2

```python

from gmpy2 import *
gcd(a,b)# 最大公约数
invert(e, phin)#求模反元素
iroot(n, e)#对n开e次方,返回一个元组,第一个元素是结果,第二个元素是是否能被正好开方
powmod(c, d, N)#c的d次方对N的模
pow(c,d,N)#c的d次方对N的模
```



#### Crypto.Util.number

```python
from Crypto.Util.number import long_to_bytes,bytes_to_long,getPrime,isPrime
```







#### 攻击方法

- Yafu
- 在factordb上测试一下
- 费马分解和Pollard_rho分解

https://www.freebuf.com/articles/others-articles/166049.html







#### 例题jarvisoj xyf

已知e,n,c

用yafu分解(factor(  $n ))得到p,q

```python
import gmpy2
import gmpy
from Crypto.Util.number import *
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5

p=56225103425920179745019828423382255030086226600783237398582720244250840205090747144995470046432814267877822949968612053620215667790366338413979256357713975498764498045710766375614107934719809398451422359883451257033337168560937824719275885709824193760523306327217910106187213556299122895037021898556005848927
q=56225103425920179745019828423382255030086226600783237398582720244250840205090747144995470046432814267877822949968612053620215667790366338413979256357713975498764498045710766375614107934719809398451422359883451257033337168560937824719275885709824193760523306327217910106187213556299122895037021898556005848447
e=65537
ans = 1
phin=(p-1)*(q-1)
d=gmpy2.invert(e,phin)

c=631583911592660652215412683088688785438938386403323323131247534561958531288570612134139288090533619548876156447498627938626419617968918299212863936839701943643735437264304062828205809984533592547599060829451668240569384130130080928292082888526567902695707215660020201392640388518379063244487204881439591813398495285025704285781072987024698133147354238702861803146548057736756003294248791827782280722670457157385205787259979804892966529536902959813675537028879407802365439024711942091123058305460856676910458268097798532901040050506906141547909766093323197363034959926900440420805768716029052885452560625308314284406
N=3161262255255421133292506694323988711204792818702640666084331634444148712428915950639954540974469931426618702044672318134908678730641981414037034058320359158246813987154679178159391832232990193738454116371045928434239936027006539348488316754611586659587677659791620481200732564068367148541242426533823626586574915275209508300120574819113851895932912208783915652764568319771482309338434364094681579135086703127977870534715039005822312878739611630155714313119545610939253355808742646891815442758660278514976431521933763272615653261044607041876212998883732724662410197038419721773290601109065965674129599626151139566369
flag = gmpy2.powmod(c, d, N)
print hex(flag)[2:].decode('hex')
```





### 公约数模数分解

- 如果在两个模数中使用了1个相同的素因子，那么可以使用求最
大公约数的方法求出来
-  一般会给两个很难通过其他方法解出的模数
- c1,e1,n1
-  c2,e2,n2
-  gcd(n1,n2)如果不是1的话，那么就可以分解n1和n2



### 共模攻击

#### 条件

• 识别：两组及以上的RSA加密过程，而且其中两次的m和n都是相
同的，但是e不同，那么就可以使用共模攻击



```
c1 = m^e1 mod N
c2 = m^e2 mod N
r*e1 + s*e2 = 1 mod N #拓展欧几里得算法
c1^r*c2^s = m^(r*e1)*m^(s*e2) mod N
= m^(r*e1+s*e2) mod N 
= m mod N
```



#### jarvis oj xgm

```python
from Crypto.Util.number import long_to_bytes,bytes_to_long,getPrime,isPrime
import primefac
def same_n_sttack(n,e1,e2,c1,c2):
	def egcd(a, b):
		x, lastX = 0, 1
		y, lastY = 1, 0
		while (b != 0):
			q = a // b
			a, b = b, a % b
			x, lastX = lastX - q * x, x
			y, lastY = lastY - q * y, y
		return (lastX, lastY)

	s = egcd(e1, e2)
	s1 = s[0]
	s2 = s[1]
	if s1<0:
		s1 = - s1
		c1 = primefac.modinv(c1, n)
		if c1<0:
			c1+=n
	elif s2<0:
		s2 = - s2	
		c2 = primefac.modinv(c2, n)
		if c2<0:
			c2+=n
	m=(pow(c1,s1,n)*pow(c2,s2,n)) % n
	return m

n1=21660190931013270559487983141966347279666044468572000325628282578595119101840917794617733535995976710097702806131277006786522442555607842485975616689297559583352413160087163656851019769465637856967511819803473940154712516380580146620018921406354668604523723340895843009899397618067679200188650754096242296166060735958270930743173912010852467114047301529983496669250671342730804149428700280401481421735184899965468191802844285699985370238528163505674350380528600143880619512293622576854525700785474101747293316814980311297382429844950643977825771268757304088259531258222093667847468898823367251824316888563269155865061
e1=65537
c1=11623242520063564721509699039034210329314238234068836130756457335142671659158578379060500554276831657322012285562047706736377103534543565179660863796496071187533860896148153856845638989384429658963134915230898572173720454271369543435708994457280819363318783413033774014447450648051500214508699056865320506104733203716242071136228269326451412159760818676814129428252523248822316633339393821052614033884661649376604245744651142959498917235138077366818109892738298251161767344501687113868331134288984466294415889635863660753717476594011236542159800099371872396181448655448842148998667568104710807411358117939831241620315

n2=21660190931013270559487983141966347279666044468572000325628282578595119101840917794617733535995976710097702806131277006786522442555607842485975616689297559583352413160087163656851019769465637856967511819803473940154712516380580146620018921406354668604523723340895843009899397618067679200188650754096242296166060735958270930743173912010852467114047301529983496669250671342730804149428700280401481421735184899965468191802844285699985370238528163505674350380528600143880619512293622576854525700785474101747293316814980311297382429844950643977825771268757304088259531258222093667847468898823367251824316888563269155865061
e2=70001
c2=8180690717251057689732022736872836938270075717486355807317876695012318283159440935866297644561407238807004565510263413544530421072353735781284166685919420305808123063907272925594909852212249704923889776430284878600408776341129645414000647100303326242514023325498519509077311907161849407990649396330146146728447312754091670139159346316264091798623764434932753276554781692238428057951593104821823029665203821775755835076337570281155689527215367647821372680421305939449511621244288104229290161484649056505784641486376741409443450331991557221540050574024894427139331416236263783977068315294198184169154352536388685040531

m=same_n_sttack(n1,e1,e2,c1,c2)

print long_to_bytes(m)

```



### 小指数明文爆破

• 观察加密：
• c=m^e mod n
• 如果e很小，比如e=3，那么如果明文也很小的情况下可能会出现这种
情况：
• m^e < n
• 此时直接对c开e次根号就可以得到m
• 还有一种一般一点的情况：
• k*n<m^3<(k+1)*n，就是说m^e虽然大于n了但是没有超出太多
• 爆破k



```python
import gmpy2
from Crypto.Util.number import long_to_bytes
n=47966708183289639962501363163761864399454241691014467172805658518368423135168025285144721028476297179341434450931955275325060173656301959484440112740411109153032840150659
e=3
c=10968126341413081941567552025256642365567988931403833266852196599058668508079150528128483441934584299102782386592369069626088211004467782012298322278772376088171342152839
#m^e=kn+c
#m = (kn+c) ^(1/e)
for k in range(1000000) :
	rawm=gmpy2.iroot(k*n+c, e)
	try :
		if rawm[1]:
			print long_to_bytes(rawm[0])
	except:
		pass

	
```



### 工具

#### yafu

![image.png](http://ww1.sinaimg.cn/large/006pWR9agy1g93mn8ayd0j30yf0hzgon.jpg)

 直接打开程序，然后输入factor($n)，`$n`为模数，





####  [http://factordb.com](http://factordb.com/)



#### gmpy2

##### 求e关于phin(fn)的模反元素d

```python
gmpy2.invert(e, phin)
```



##### 共模攻击

```python
import gmpy2

e1 = 0x10001
e2 = 0x10003

g,r,s = gmpy2.gcdext(e1,e2)

print r
print s
print r*e1+s*e2
```







##### rsa密文解密

`gmpy2.powmod(c, d, N)`



##### gmpy2.iroot(n, e)

对n开e次方,返回一个元组

第一个元素是结果,第二个元素是是否能被正好开方

#### openssl

##### 查看公钥文件

```
openssl rsa -pubin -in pubkey.pem -text -modulus
```

##### 解密

```
rsautl -decrypt -inkey private.pem -in flag.enc -out flag
```



### exp



#### 模数分解

##### 已知e,p,q求d

```python
d = gmpy2.invert(e, (p-1)*(q-1))
```



##### 已知e,d,n求p,q

```python
import random  
  
def gcd(a, b):  
   if a < b:  
     a, b = b, a  
   while b != 0:  
     temp = a % b  
     a = b  
     b = temp  
   return a  
  
def getpq(n,e,d):  
    p = 1  
    q = 1  
    while p==1 and q==1:  
        k = d * e - 1  
        g = random.randint ( 0 , n )  
        while p==1 and q==1 and k % 2 == 0:  
            k /= 2  
            y = pow(g,k,n)  
            if y!=1 and gcd(y-1,n)>1:  
                p = gcd(y-1,n)  
                q = n/p  
    return p,q  
  
def main():  
 
    n = 0xa66791dc6988168de7ab77419bb7fb0c001c62710270075142942e19a8d8c51d053b3e3782a1de5dc5af4ebe99468170114a1dfe67cdc9a9af55d655620bbab  
    e =  0x10001
    d =  0x123c5b61ba36edb1d3679904199a89ea80c09b9122e1400c09adcf7784676d01d23356a7d44d6bd8bd50e94bfc723fa87d8862b75177691c11d757692df8881
    p,q = getpq(n,e,d)  
    print "p: "+hex(p)
    print "q: "+hex(q)  
  
if __name__ == '__main__':  
    main()
```



#### 已知p,q,dp,dq,c求m



 https://en.wikipedia.org/wiki/RSA_(cryptosystem)#Using_the_Chinese_remainder_algorithm 



```python
import gmpy2
from Crypto.Util.number import long_to_bytes
def dec(dp,dq,p,q,c):
	qinv=gmpy2.invert(q,p)
	m1=pow(c,dp,p)
	m2=pow(c,dq,q)
	h=(qinv*(m1-m2))%p
	return m2+h*q
		
			

p=8637633767257008567099653486541091171320491509433615447539162437911244175885667806398411790524083553445158113502227745206205327690939504032994699902053229 
q=12640674973996472769176047937170883420927050821480010581593137135372473880595613737337630629752577346147039284030082593490776630572584959954205336880228469 
dp=6500795702216834621109042351193261530650043841056252930930949663358625016881832840728066026150264693076109354874099841380454881716097778307268116910582929 
dq=783472263673553449019532580386470672380574033551303889137911760438881683674556098098256795673512201963002175438762767516968043599582527539160811120550041 
c=24722305403887382073567316467649080662631552905960229399079107995602154418176056335800638887527614164073530437657085079676157350205351945222989351316076486573599576041978339872265925062764318536089007310270278526159678937431903862892400747915525118983959970607934142974736675784325993445942031372107342103852

n=p*q
phin=(p-1)*(q-1)

print long_to_bytes(dec(dp,dq,p,q,c))


```



#### 共模攻击

```python
from Crypto.Util.number import long_to_bytes,bytes_to_long,getPrime,isPrime
import primefac
def same_n_sttack(n,e1,e2,c1,c2):
    def egcd(a, b):
        x, lastX = 0, 1
        y, lastY = 1, 0
        while (b != 0):
            q = a // b
            a, b = b, a % b
            x, lastX = lastX - q * x, x
            y, lastY = lastY - q * y, y
        return (lastX, lastY)

    s = egcd(e1, e2)
    s1 = s[0]
    s2 = s[1]
    if s1<0:
        s1 = - s1
        c1 = primefac.modinv(c1, n)
        if c1<0:
            c1+=n
    elif s2<0:
        s2 = - s2
        c2 = primefac.modinv(c2, n)
        if c2<0:
            c2+=n
    m=(pow(c1,s1,n)*pow(c2,s2,n)) % n
    return m
```



#### 小指数明文爆破

```python
import gmpy2

e1 = 0x10001
e2 = 0x10003

g,r,s = gmpy2.gcdext(e1,e2)

print r
print s
print r*e1+s*e2
```



### xfz

```python
import os
import sys

def xor(a,b):
    assert len(a)==len(b)
    c=""
    for i in range(len(a)):
        c+=chr(ord(a[i])^ord(b[i]))
    return c
def round(M,K):
    L=M[0:27]
    R=M[27:54]
    new_l=R
    new_r=xor(xor(R,L),K)
    return new_l+new_r
def fez(m,K):
    for i in K:
        m=round(m,i)
    return m
def nxor(a,b):
    c = ""
    for i in range(int(len(a)/2)):
        num=(int(a[i*2:i*2+2],16))^(int(b[i*2:i*2+2],16))
        c=c+(format(num,'02x'))
    return c
test=("50543fc0bca1bb4f21300f0074990f846a8009febded0b2198324c1b31d2e2563c908dcabbc461f194e70527e03a807e9a478f9a56f7")
feztest=("66bbd551d9847c1a10755987b43f8b214ee9c6ec2949eef01321b0bc42cffce6bdbd604924e5cbd99b7c56cf461561186921087fa1e9")
fezflag=("44fc6f82bdd0dff9aca3e0e82cbb9d6683516524c245494b89c272a83d2b88452ec0bfa0a73ffb42e304fe3748896111b9bdf4171903")
tl=test[0:54]
tr=test[54:108]
ftl=feztest[0:54]
ftr=feztest[54:108]
ffl=fezflag[0:54]
ffr=fezflag[54:108]
x=nxor(ftl,tr)
y=nxor(ftr,nxor(tl,tr))
r=nxor(ffl,x)
l=nxor(ffr,nxor(r,y))
print(l+r)
# K=[]
# for i in range(7):
#     K.append(os.urandom(27))
# m=open("flag","rb").read()
# assert len(m)<54
# m+=os.urandom(54-len(m))
#
# test=os.urandom(54)
# print test.encode("hex")
# print fez(test,K).encode("hex")
# print fez(m,K).encode("hex")
```

### xbitf

```python
from Crypto.Cipher import AES
import os

def aes_cbc(key,iv,m):
    handler=AES.new(key,AES.MODE_CBC,iv)
    return handler.encrypt(m).encode("hex")
def aes_cbc_dec(key,iv,c):
    handler=AES.new(key,AES.MODE_CBC,iv)
    return handler.decrypt(c.decode("hex"))
c='7f405f9651662bd38dd5e1928ba9946a5016db54b471bd56aebe7a3a55a91e90'

c1=c[:int(len(c)/2)]
c2=c[int(len(c)/2):]
nc1=c1[:-2]+str(hex(int(c1[-2:],16)^ord('0')^ord('1'))[2:])
nc=nc1+c2
print(hex(int(c1[-2:],16)^ord('0')^ord('1'))[2:])
```





## 杂

### rabbit加密

`U2FsdGVkX1/+ydnDPowGbjjJXhZxm2MP2AgI` => `Cute_Rabbit`

`U2FsdGVkX1/+ydnDPowGbjjJXhZxm2MP2AgI`(base64decode)=> `Salted__ xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`