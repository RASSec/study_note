# Crypto做题笔记

## 攻防世界

### 你猜猜

##### 解题思路



题目提供文件

haha.txt

```
504B03040A0001080000626D0A49F4B5091F1E0000001200000008000000666C61672E7478746C9F170D35D0A45826A03E161FB96870EDDFC7C89A11862F9199B4CD78E7504B01023F000A0001080000626D0A49F4B5091F1E00000012000000080024000000000000002000000000000000666C61672E7478740A0020000000000001001800AF150210CAF2D1015CAEAA05CAF2D1015CAEAA05CAF2D101504B050600000000010001005A000000440000000000
```

看别人的wp知道了504B0304是Zip的文件头

HxD新建文件，将`haha.txt`中的数据copy进去，命名为`1.zip`

压缩文件需要密码直接爆破(工具:ziperello)

##### 收获

zip的文件头:504B0304

工具:HxD,ziperello(破解压缩文件密码)

### 告诉你个秘密

### 思路

题目给你两个字符串

>636A56355279427363446C4A49454A7154534230526D6843
>56445A31614342354E326C4B4946467A5769426961453067

尝试看成hex编码,试一下

得到

>cjV5RyBscDlJIEJqTSB0RmhC
>
>VDZ1aCB5N2lKIFFzWiBiaE0g

尝试base64decode

>r5yG lp9I BjM tFhB
>T6uh y7iJ QsZ bhM 

到这边就是我会做的全部了

试了一堆最后百度发现

你妈是看键盘

> 在键盘上`r5yg`中间的字母是`t`,同理，将其他的也找出来，得到
>
> TONGYUAN



### **Easy-one**


  做这题的时候我没有事先进行分析，一直以为密钥是CENSORED

看了别人的wp才发现这题的密钥不是CENSORED。





msg001.enc是msg001的加密文件,已知过程直接逆向出密钥。

贴上代码

```c
//获取密钥
#define false 0
#define true 1
int main() {
	FILE* input  = fopen("msg001", "rb");
	FILE* output = fopen("msg001.enc", "rb");
	if (!input || !output) {
		printf("Error\n");
		return 0;
	}
	int c, p, t = 0;
	char k[30]="";
	int i = 0;
	while ((p = fgetc(input)) != EOF &&(c= fgetc(output))!=EOF  ) {
		char ch;
		//(p + (k[i % strlen(k)] ^ t) + i*i) & 0xff;
		int ok=false;
		while(1)
		{
			for(ch=32;ch<127;ch++)
			{
				printf("未加密:%d,测试加密:%d,正确加密:%d,猜测k:%d\n",p,(p + (ch ^ t) + i*i) & 0xff,c,ch);
				if((p + (ch ^ t) + i*i) == c)
				{
					ok=true;
					break;
				}
			}
			if(ok)
				break;
			else c+=256;
		
		}
		
			
		k[i]=ch;
		t = p;
		i++;
	}
	k[i+1]=0;
	printf("%s",k);
	return 0;
}


//解密
int main() {

	FILE* input  = fopen("msg002.enc", "rb");
	char k[] = "VeryLongKeyYouWillNeverGuess";
	char c, p, t = 0;
	int i = 0;
	while ((p = fgetc(input)) != EOF) {
		 
		//(p + (k[i % strlen(k)] ^ t) + i*i) & 0xff;
		c = (p-i*i-(k[i % strlen(k)] ^ t));
		t = c;
		i++;
		printf("%c",c);
	}
	return 0;
}
```