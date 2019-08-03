# DAY2

## 古典密码 
栅栏密码,云隐密码,曲路密码, 凯撒密码, 埃特巴什码,培根密码,仿射密码

## 分组密码
- CBC字节翻转攻击:
nc1=c1[:-2]+str(hex(int(c1[-2:],16)^ord('0')^ord('1'))[2:])
nc=nc1+c2
- CBC选择密文攻击:
这个不太会...
- padding oracle 攻击
- Feistel结构:如果F函数是线性的,可以实现已知明文攻击

## rsa

### 直接模数分解

#### 条件

- N的长度较小，可直接分解：个人计算机256bit以下
- N中p和q选择存在的问题：过于接近 or 差距过大 or 光滑
- p - 1 光滑
- P+1光滑（2017 SECCON very smooth）

#### 攻击方法

- Yafu
- 在factordb上测试一下
- 费马分解和Pollard_rho分解

https://www.freebuf.com/articles/others-articles/166049.html

### 公约数模数分解

- 如果在两个模数中使用了1个相同的素因子，那么可以使用求最
大公约数的方法求出来
-  一般会给两个很难通过其他方法解出的模数
- c1,e1,n1
-  c2,e2,n2
-  gcd(n1,n2)如果不是1的话，那么就可以分解n1和n2

其他的就不懂了。。

## exp



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



