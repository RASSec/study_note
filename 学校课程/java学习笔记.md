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



##### abstract

![image-20200219092253425](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219092253425.png)



![image-20200219092320783](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219092320783.png)



![image-20200219092352406](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219092352406.png)



##### static

注意:static方法只能调用同一个类里的静态方法,只能访问同一个类里的静态成员



##### final

用来表示数据不可修改或者方法不可重载,或对象不可继承



##### interface

接口中成员方法的默认方式是:`public abstract`

属性的默认方式`public static final`

用`implements`来声明类实现某接口

![image-20200219094731718](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219094731718.png)





![image-20200219094745207](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219094745207.png)



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

如果子类没有显示的调用父类调用方法,那么java会自己调用父类无参构造方法



#### instanceof

`p instanceof Circle`

如果p是Circle(或其子类)的实例对象,则为true



### java中面向对象特性

1. 继承和多态
2. 封装性

![image-20200219091909021](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219091909021.png)





### java参数传递

Java方法的参数传递机制实际上非常清晰: Java的参数传递为值传递。也就是说，当我们传递一个参数时，方法将获得该参数的一个拷贝。

基本类型变量的值传递，意味着变量本身被复制，并传递给Java方法。Java方法对变量的修改不会影响到原变量。

引用的值传递，意味着对象的地址被复制，并传递给Java方法。Java方法根据该引用的访问将会影响对象。

 

在这里有另一个值得一提的情况: 我们在方法内部使用new创建对象，并将该对象的引用返回。如果该返回被一个引用接收，由于对象的引用不为0，对象依然存在，不会被垃圾回收。

![image-20200219102049098](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200219102049098.png)







#### 增强for语句



```
for(type var1 : arr)
{

}
```

#### 游长时参表

![image-20200224084624042](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224084624042.png)



#### this关键字

![image-20200224085043635](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224085043635.png)



#### 数组

![image-20200224090448056](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224090448056.png)



#### 字符串

![image-20200224091401867](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224091401867.png)



![image-20200224091552784](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224091552784.png)



![image-20200224091956141](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224091956141.png)



![image-20200224092014645](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092014645.png)



![image-20200224092122136](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092122136.png)



![image-20200224092131792](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092131792.png)



![image-20200224092201913](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092201913.png)



![image-20200224092229865](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092229865.png)



![image-20200224092327853](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092327853.png)



![image-20200224092337407](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092337407.png)



![image-20200224092424229](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092424229.png)



#### StringBuffer

![image-20200224092453422](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092453422.png)

![image-20200224092528330](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224092528330.png)







#### ==运算符

![image-20200224091731590](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224091731590.png)





