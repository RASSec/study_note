# cpp

## 函数

### ios::sync_with_stdio(false)

>在C++中的输入和输出有两种方式，一种是scanf和printf，另一种是cin和cout，在#include<bits/stdc++.h>这个万能头文件下，这两种方式是可以互换的
>C++和C很相似，很多大佬都用C++写C，但是在后面的一种方式中cin和cout的输入和输出效率比第一种低，原来而cin，cout之所以效率低，是因为先把要输出的东西存入缓冲区，再输出，导致效率降低，而这段语句可以来打消iostream的输入 输出缓存，可以节省许多时间，使效率与scanf与printf相差无几，还有应注意的是scanf与printf使用的头文件应是stdio.h而不是 iostream。
>在学校的OJ上后面的时间复杂度要求很低，有好多时候TLE不是因为代码的问题，对于初学C++的人来说根本不知道ios::sync_with_stdio(false);这个东西。

### cin.tie(0)

>　　虽然C++有cin函数，但看别人的程序，大多数人都用C的scanf来读入，其实是为了加快读写速度，难道C++还不如C吗！？其实cin效率之所以低，不是比C低级，是因为先把要输出的东西存入缓冲区，再输出，导致效率降低，而且是C++为了兼容C而采取的保守措施。
>
>　　先讲一个cin中的函数——tie，证明cin和scanf绑定是同一个的流。



## c和c++中关于printf和cout的特性



```c++
#include<iostream>
#include <queue>
#include<stdio.h> 
using namespace std;
int i=0;
int update(){i++;return i;};
int main()
{
	printf("----------------------------test printf----------------------------\n");
	printf("before:%d\n",i);
	printf("update():%d i:%d\n",update(),i);
	printf("later:%d\n",i);
	
	printf("before:%d\n",i);
	printf("i:%d update():%d \n",i,update());
	printf("later:%d\n",i);
	
	printf("update1:%d update2:%d update3:%d",update(),update(),update());
	printf("\n\n\n\n");
	
	cout<<"---------------------------- test cout ----------------------------\n";
	i=0;
	cout<<"before:"<<i<<endl;
	cout<<"update():"<<update()<<" i:"<<i<<endl;
	cout<<"later:"<<i<<endl;
	
	cout<<"before:"<<i<<endl;
	cout<<"i:"<<i<<" update():"<<update()<<endl;
	cout<<"later:"<<i<<endl;
	
	cout<<"update1:"<<update()<<" update2:"<<update()<<" update3:"<<update()<<endl;

}
```



