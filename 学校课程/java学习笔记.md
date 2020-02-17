# java学习笔记

## 初识java

### java程序的执行过程

![image-20200217093155532](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200217093155532.png)



### java程序的基本结构

1. java程序由一个或多个独立的类组成,其中必须有一个公有类,而且源代码文件必须与这个公有类的名字相同
2. java类有多个方法,公有类中的main方法作为程序的入口

hello.java

```java
public class hello
{
    public static void main(String [] args)
    {
        System.out.println("hello world");
    }
}
```



### java的一些知识

#### 标识符

相较于c,java允许了`$`作为标识符的组成



#### java关键字

![image-20200217094043842](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200217094043842.png)



#### 运算符

```
>>带符号右移
<<左移
>>> 不带符号右移

```



#### 定义变量

格式:`<修饰符><数据类型><名称>(=<初值>)`

修饰符:

```
static  定义类变量，区分实例变量
final 用来声明常量，值只能用不能改
transient 定义暂时性变量，串行化时不能保存
volatile 定义共享变量，用于多线程共享。

```



#### 定义方法

除构造函数没有返回类型,其他都有返回类型

格式:`[access] type methodname(para) {}`



#### 构造方法

构造方法不会继承

![image-20200217095455962](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200217095455962.png)



#### instanceof

`p instanceof Circle`

如果p是Circle(或其子类)的实例对象,则为true