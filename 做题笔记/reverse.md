# reverse

## rois

### passwd

题目给了一个汇编代码

```assembly
.intel_syntax noprefix
.bits 32
	
.global main	; int main(int argc, char **argv)
input_data=ebp-0x4
input_len=ebp-0xc
main:
	push   ebp;
	mov    ebp,esp;
	sub    esp,0x10;函数调用
	mov    DWORD PTR [input_len],0x0;int a=0
	mov    eax,DWORD PTR [ebp+0xc];eax=argv
	mov    eax,DWORD PTR [eax+0x4];argv;eax=argv[1];
	mov    DWORD PTR [input_data],eax;char * c=argv[1],input data pointer
	jmp    part_b
part_a:
	add    DWORD PTR [input_len],0x1
	add    DWORD PTR [input_data],0x1
part_b:	
	mov    eax,DWORD PTR [input_data]
	movzx  eax,BYTE PTR [eax]
	test   al,al
	jne    part_a;ax!=0
	mov    DWORD PTR [ebp-0x8],0x0;int b=0;
	jmp    part_d

part_c:	
	mov    eax,DWORD PTR [ebp+0xc];eax=argv
	add    eax,0x4;eax=argv[1]
	mov    edx,DWORD PTR [eax];
	mov    eax,DWORD PTR [ebp-0x8]
	add    eax,edx
	mov    DWORD PTR [input_data],eax
	mov    eax,DWORD PTR [input_data]
	movzx  eax,BYTE PTR [eax]
	xor    eax,0x9d
	mov    edx,eax
	mov    eax,DWORD PTR [input_data]
	mov    BYTE PTR [eax],dl
	mov    eax,DWORD PTR [input_data];eax=p
	movzx  eax,WORD PTR [eax];注意这里是word
	ror    ax,0x5
	mov    edx,eax
	mov    eax,DWORD PTR [input_data]
	mov    WORD PTR [eax],dx
	mov    eax,DWORD PTR [input_data]
	mov    eax,DWORD PTR [eax];这里是dword
	rol    eax,0xb
	mov    edx,eax
	mov    eax,DWORD PTR [input_data]
	mov    DWORD PTR [eax],edx
	add    DWORD PTR [ebp-0x8],0x1
part_d:	
	mov    eax,DWORD PTR [input_len]
	sub    eax,0x3;c_input_len-=3;
	cmp    eax,DWORD PTR [ebp-0x8]
	jg     part_c
	mov    eax,DWORD PTR [ebp+0xc]
	mov    eax,DWORD PTR [eax+0x4]
	mov    DWORD PTR [input_data],eax
	mov    DWORD PTR [ebp-0x10],0x14890ba
	;input_data=ebp-0x4
	;b=ebp-0x8
	;input_len=ebp-0xc
	;pp=0x14890ba;
	jmp    part_f
part_e:	
	mov    eax,DWORD PTR [input_data]
	movzx  edx,BYTE PTR [eax]
	mov    eax,DWORD PTR [ebp-0x10]
	movzx  eax,BYTE PTR [eax]
	cmp    dl,al
	je     part_k
	mov    eax,0x0
	jmp    fun_leave
part_k:	
	add    DWORD PTR [input_data],0x1
	add    DWORD PTR [ebp-0x10],0x1
part_f:	
	mov    eax,DWORD PTR [ebp-0x10]
	movzx  eax,BYTE PTR [eax]
	test   al,al
	jne    part_e
	mov    eax,DWORD PTR [ebp+0xc]
	add    eax,0x4
	mov    eax,DWORD PTR [eax]
	mov    edx,DWORD PTR [ebp-0x10]
	mov    ecx,0x14890ba
	sub    edx,ecx
	add    eax,edx
	movzx  eax,BYTE PTR [eax]
	test   al,al
	je     part_g
	mov    eax,0x0			; LOGIN_FAILED
	jmp    fun_leave
part_g:	
	mov    eax,0x1			; LOGIN_SUCCESS
fun_leave:	
;leave
;在16位汇编下相当于:
;mov sp,bp
;pop bp

;在32位汇编下相当于:
;mov esp,ebp
;pop ebp
	leave
	ret



014890BA:  9a da 72 86 fe 32 af fe  0a 72 27 ff c7 7e 92 ff   
014890CA:  96 83 96 ff 96 7f 67 ff  0e 9f 9f 2e 6a 17 67 ce   
014890DA:  fe 8e 9f ff 3f 87 87 27  ef db c3 df 00

```

慢吞吞的把他给转成c代码

```c
char data[]={154, 218, 114, 134, 254, 50, 175, 254, 10, 114, 39, 255, 199, 126, 146, 255, 150, 131, 150, 255, 150, 127, 103, 255, 14, 159, 159, 46, 106, 23, 103, 206, 254, 142, 159, 255, 63, 135, 135, 39, 239, 219, 195, 223, 0};
int main(int argc,char * argv[])
{
    int len=0;
    int b;
    char * p=argv[1];
    char * pp;
    while(!(*p))
    {
        len++;
        p++;
    }
    b=0;
    int temp;
    for(temp=len-3;temp>b;)
    {
        p=argv[1]+b;
        temp_n=(*p)^0x9d;
        *p=(char)temp_n;
        *((short *)p)=(*(short *)p)>>5;//循环右移
        int n=*((int *)p)<<0xb;//循环左移
        *((int *)p)=n;
        b++;
    }
    p=argv[1];
    pp=data;
    for(;!*pp;)
    {
        if(*p==*pp)
        {
            p++;
            pp++;
        }else
        {
            return 0;//login_failed
        }
    }
    
    if((*(pp-data+arvg[1]))==0)
        return 1;//login_success
       
}
```



c里面的<<和>>不是循环左移和右移,要自己实现

这里要注意的是,逆向的时候,由于对某个字符加密的时候也会影响到后面的字符,所以要从后面开始



最后的解密脚本

```c
#include<stdio.h>
void ror(unsigned char * p,unsigned int cnt);
void rol(unsigned char * p,unsigned int cnt);
int main(int argc,char *argv[])
{
	unsigned char data[]={154, 218, 114, 134, 254, 50, 175, 254, 10, 114, 39, 255, 199, 126, 146, 255, 150, 131, 150, 255, 150, 127, 103, 255, 14, 159, 159, 46, 106, 23, 103, 206, 254, 142, 159, 255, 63, 135, 135, 39, 239, 219, 195, 223, 0};
	
	int len=sizeof(data)/sizeof(char);	
	char a;
//	printf("%d",len);
	for(int i=len-5;i>=0;i--)
	{
		//scanf("%c",&a);
		unsigned char * t_data=data+i;
		ror(t_data,0xb);
		rol(t_data,0x5);
		*t_data=*t_data^0x9d;
		printf("%x\n",*t_data);
	}
	printf("finished\n");
	for(int i=0;i<len-3;i++)
	{
		printf("%s\n",data);
	}


}
void ror(unsigned char * p,unsigned int cnt)
{
	unsigned int int_mask=((1<<32)-1);
	unsigned int data=(*((int *)p)) & int_mask;
	unsigned int high_mask=(1<<cnt)-1;
	unsigned int result=((high_mask&data)<<(32-cnt))|(data>>cnt);
	printf("%x %x %x %x %x\n",int_mask,(*p),(*(p+1)),(*(p+2)),(*(p+3)));
	*p=(unsigned char)(result&((1<<8)-1));
	*(p+1)=(unsigned char)((result&(((1<<8)-1)<<8))>>8);
	*(p+2)=(unsigned char)((result&(((1<<8)-1)<<16))>>16);
	*(p+3)=(unsigned char)((result&(((1<<8)-1)<<24))>>24);
	printf("%x %x %x %x\n",(*p),(*(p+1)),(*(p+2)),(*(p+3)));
	
}
void rol(unsigned char * p,unsigned int cnt)
{
	unsigned int int_mask=(1<<16)-1;
	unsigned int data=(*((int *)p)) & int_mask;
	unsigned int high_mask=((1<<cnt)-1)<<(16-cnt);
	unsigned int result=((high_mask&data)>>(16-cnt))|(data<<cnt);
	printf("%x %x\n",(unsigned int)(*p),(unsigned int)(*(p+1)));
	*p=(unsigned char)((result&((1<<8)-1)));
	*(p+1)=(unsigned char)((result&(((1<<8)-1)<<8))>>8);
	printf("%x %x\n",(unsigned int)(*p),(unsigned int)(*(p+1)));
	
}
// 01 23 45 67
// 00000001 00100011 01000101 01100111
//00000 000 00001001
//00011 01000101 01100111
//00011010 00101011 0011100 00000000
```

