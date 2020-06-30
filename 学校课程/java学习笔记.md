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

#### 内部类





![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427205105.png)

#### 匿名内部类

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221030.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221123.png)



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



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524162916.png)



#### ==运算符

![image-20200224091731590](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200224091731590.png)



## java一些api介绍

### scaner

```java
Scanner reader=new Scanner(System.in);　
      //next.Byte(),nextDouble(),nextFloat,nextInt(),nextLine(),nextLong(),nextShot()　

```





### iterator

 ![image-20200315134726937](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315134726937.png)

![image-20200315162656923](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315162656923.png)



### java集合框架Collection

![image-20200315160006554](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315160006554.png)

![image-20200315160121149](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315160121149.png)

![image-20200315160138753](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315160138753.png)



![image-20200315160157828](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315160157828.png)

#### 构造函数

![image-20200315160953130](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315160953130.png)

#### 遍历函数

![image-20200315161035116](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161035116.png)

![image-20200315161045216](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161045216.png)

#### 公有方法



![image-20200315161150355](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161150355.png)



![image-20200315161310527](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161310527.png)



### List

![image-20200315161339271](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161339271.png)

![image-20200315161514320](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161514320.png)



#### listIterator

![image-20200315161533568](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161533568.png)

![image-20200315161556174](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161556174.png)

#### subList

![image-20200315161645440](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161645440.png)

![image-20200315162757479](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315162757479.png)

#### 子类

![image-20200315161707733](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161707733.png)

##### ArrayList

可变大小的List,是由数组实现

![image-20200315161756283](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315161756283.png)

ArrayList 没有同步化



##### LinkedList

由双向链表实现

![image-20200315162405378](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315162405378.png)

##### Stack

![image-20200315163532693](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315163532693.png)

![image-20200315163541840](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315163541840.png)



### Arrays

![image-20200315160351304](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315160351304.png)

![image-20200315162913533](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315162913533.png)

### 向量 vector

vector是同步的

```java
Vector<String> obj=new Vector<String>();
```



```java
修改元素:
public E set(int index,E element)
public void setElementAt(E obj,int index)
删除元素:
public void clear()
public void removeAllElements()
public E remove(int index);
public void removeElementAt(int index);
访问元素:
public Iterator<E> iterator();


```



![image-20200315163324032](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315163324032.png)





### 哈希表类

![image-20200304115622514](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200304115622514.png)



### queue

#### PriorityQueue

![image-20200309100139702](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309100139702.png)



### set

![image-20200309100209361](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309100209361.png)



![image-20200309100217729](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309100217729.png)



#### HashSet

相较于TreeSet,HashSet更快

#### TreeSet

相较于HashSet,TreeSet可以有序的排列元素,一般来说把元素添加到HashSet再把集合转化为TreeSet进行有序遍历更快



### Map



![image-20200309100511725](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309100511725.png)



![image-20200309100523860](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309100523860.png)



#### Hashtable

Hashtable在多线程间同步

key和value都不能为空



#### ProPerties

只能操作字符串,保存字符串的键值对,具有持久性(提供方法写入/读入到文件流)

#### HashMap

HashMap与HashTable相似,但是HashMap允许空值/键,且它在多线程间不同步



#### TreeMap

能够实现有序排列,与HashMap的关系和HashSet和TreeSet关系相似,二者本来也是由HashMap和TreeMap实现的



## Collections提供的算法

![image-20200309103024807](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309103024807.png)

### Sort

![image-20200309103044540](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200309103044540.png)



### Collections.reverseOrder()

提供与类相反的排序顺序的Comparator

对集合进行逆序排序



### Comparator.compare()和Comparable.compareTo()

前者是一种比较器,后者是类中比较的方法,前者数据与比较方法分离,后者数据与比较方法在一起

### binarySearch

在使用前,要调用sort让数组有序

### Shuffle

打乱数组的顺序



## 泛型



### 类型参数的定义格式



![image-20200315154632585](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315154632585.png)



### 泛型方法

```java
public static < E > void printArray(E[] inputArray)
{
    for(E element:inputArray)
        System.out.printf("%s\n",element);
}
```

![image-20200315135737917](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315135737917.png)

![image-20200315140109196](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315140109196.png)





### 泛型接口



![image-20200315154506866](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315154506866.png)



![image-20200315151807957](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315151807957.png)

![image-20200315151930013](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315151930013.png)

![image-20200315152040361](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315152040361.png)



### 泛型类



![image-20200315154455864](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315154455864.png)



![image-20200315152351094](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315152351094.png)

![image-20200315152518836](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315152518836.png)



### 通配符型实参

![image-20200315153901042](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315153901042.png)

## 枚举

![image-20200315154912022](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315154912022.png)

![image-20200315155211129](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200315155211129.png)

 ```java
// 定义一个星期的枚举类
public enum WeekEnum {
    
    // 因为已经定义了带参数的构造器，所以在列出枚举值时必须传入对应的参数
    SUNDAY("星期日"), MONDAY("星期一"), TUESDAY("星期二"), WEDNESDAY("星期三"), 
    THURSDAY("星期四"), FRIDAY("星期五"), SATURDAY("星期六");

    // 定义一个 private 修饰的实例变量
    private String date;

    // 定义一个带参数的构造器，枚举类的构造器只能使用 private 修饰
    private WeekEnum(String date) {
        this.date = date;
    }
    
    // 定义 get set 方法
    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }
    
    // 重写 toString() 方法
    @Override
    public String toString(){
        return date;
    }
}
 ```



## java中的异常处理

### 异常的分类

- 受检异常：再编译时就能被java编译器检查到的
- 非受检异常：不能再编译时检查到



### java中异常类的继承结构

![image-20200427093149715](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427093149715.png)



### 如何处理异常

#### 捕获异常

![image-20200427093836880](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427093836880.png)

![image-20200427093941671](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427093941671.png)

![image-20200427094053689](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427094053689.png)

##### 捕获异常的顺序

![image-20200427094224136](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427094224136.png)

##### 同时捕获多个异常

```java
try{
    
}catch( Type1 | Type2 | Type3 e){
    //jdk7开始允许同时处理多个异常
}
```

##### 带资源的try语句

![image-20200427095230394](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427095230394.png)

![image-20200427095301708](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427095301708.png)



#### 声明抛弃异常

##### throws

![image-20200427100257521](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427100257521.png)

![image-20200427100309286](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427100309286.png)



##### throw

![image-20200427100415659](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427100415659.png)

#### 自定义异常类

![image-20200427100507651](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427100507651.png)



```java
class TestException extends Exception{
    public TestException(String msg){
        super(msg);
    }
}
```



#### 链式异常

![image-20200427101337115](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427101337115.png)

## 程序设计模式

### 单体程序设计模式

#### 单体模式的特点

![image-20200427104309640](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427104309640.png)

#### 单体模式的好处

![image-20200427104334905](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427104334905.png)

#### 单体模式示例



![image-20200427104401404](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427104401404.png)

![image-20200427104416317](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427104416317.png)



## 文件与流

流分为字符流和字节流，前者处理文本文件，后者处理二进制文件，通常类名包含Stream

![image-20200427124936099](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427124936099.png)



### File类

![image-20200427125059742](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125059742.png)

#### 常用的构造函数

```java
public File(String name);
public File(String pathToName,String name);
public File(File directory,String name);
public File(URI uri);
```



#### 常用方法



![image-20200427125311071](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125311071.png)

![image-20200427125322786](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125322786.png)

![image-20200427125334079](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125334079.png)

![image-20200427125349770](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125349770.png)

### ”流“类

![image-20200427125451245](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125451245.png)

![image-20200427125503521](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125503521.png)

![image-20200427125520551](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125520551.png)

#### InputStream

![image-20200427125544288](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125544288.png)

![image-20200427125558176](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125558176.png)

![image-20200427125608273](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125608273.png)

#### OutputStream

![image-20200427125633568](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125633568.png)

![image-20200427125646988](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125646988.png)



#### InputStream和OutputStream 子类

![image-20200427125740145](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427125740145.png)



### 处理文件

#### 一般过程

![image-20200427130110734](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427130110734.png)

![image-20200427130123546](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427130123546.png)

![image-20200427130136193](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200427130136193.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427135535.png)

### PrintStream



### Formatter

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427140306.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427140424.png)

### Scanner

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427140529.png)

### DataInputStream / DataOutputStream

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427142123.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427142136.png)

### 带缓存的数据流

BufferedInputStream / BufferedOutputStream

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427142506.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427142541.png)



### RandomAccessFile

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144328.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144349.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144424.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144504.png)

#### 示例

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144533.png)



### 基于字符流的文件操作



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143609.png)

#### 类Reader和Writer中的成员方法

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143658.png)

#### FileReader

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143738.png)



#### FileWriter

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143759.png)

#### BufferedReader



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143824.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143924.png)



#### LineNumberReader

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427143948.png)

#### BufferdWriter

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144016.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144037.png)

#### PrintWriter

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144122.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427144151.png)







## java序列化

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427140633.png)



### 写

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427141639.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427141747.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427141826.png)

### 读

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427141903.png)

### 示例

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427141937.png)



## Swing GUI设计

### GUI类介绍

AWT,Swing,SWT

AWT:依赖操作系统，显示的界面和操作系统有关

Swing:忽略操作系统，在任何平台都实现一样的外观，支持插件

SWT:Swing+AWT

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427145403.png)

重量级：和操作系统关联大



### GUI构成

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427145911.png)

### Swing类

紫色为Swing类

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150109.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150209.png)



### Swing组件分类

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150259.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150339.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150407.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150450.png)



### 程序框架

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150551.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150609.png)



#### 根面板结构

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150643.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150709.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150757.png)



### JFrame

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427150847.png)

#### 构造JFrame

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427151002.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427151212.png)

#### setLayout

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427155013.png)

#### getClass

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427155211.png)

### 多文档界面

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428105429.png)



#### JDesktopPane

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428105702.png)



#### JInternalFrame

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428105722.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428105748.png)





### 标记GUI组件



#### JLabel

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427151330.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427151345.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427154914.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427155127.png)

#### 按钮

##### JButton

用户单击按钮，产生ActionEvent事件

```java
button_test=new JButton("Exit");
add(button_test);
button_test.addActionListener(this);
```



##### JCheckBox 复选框

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427211803.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427215157.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427215323.png)







###### 示例

```java
package gui_test;
import java.awt.FlowLayout;
import java.awt.Font;
import java.awt.event.ItemEvent;
import java.awt.event.ItemListener;

import javax.swing.*;
public class GuiTest extends JFrame{

	/**
	 * 
	 */
	private JCheckBox bold;
	private JCheckBox italy;
	private JTextField testField;
	public GuiTest() {
		super("复选框测试");
		setLayout(new FlowLayout());
		testField = new JTextField("This is a test",20);
		testField.setFont(new Font(null, 0, 20));
		add(testField);
		bold = new JCheckBox("粗体");
		italy = new JCheckBox("斜体");
		bold.addItemListener(new FontStyleChanger());
		italy.addItemListener(new FontStyleChanger());
		add(italy);
		add(bold);
		setVisible(true);
		setSize(500, 300);
		
	}
	public static void main(String args[]) {
		new GuiTest();
	}
	public class FontStyleChanger implements ItemListener{

		@Override
		public void itemStateChanged(ItemEvent e) {
			// TODO Auto-generated method stub
			int fontstyle=0;
			fontstyle = fontstyle | (bold.isSelected()?Font.BOLD:fontstyle);
			fontstyle = fontstyle | (italy.isSelected()?Font.ITALIC:fontstyle);
			testField.setFont(new Font(null, fontstyle, 20));
		}
		
	}

}

```



##### JRadioButton 单选框

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427215358.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427215447.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427220211.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427220229.png)





```java
JRadioButton  plainJRadioButton = new JRadioButton("plain");
JRadioButton BoldJRadioButton = new JRadioButton("bold");
add(plainJRadioButton);
add(BoldJRadioButton);
radioGroup = new ButtonGroup();
radioGroup.add(plainJRadioButton);
radioGroup.add(BoldJRadioButton);
    
```



#### JComboBox 组框

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427220347.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427220416.png)

JComboBox会产生ItemEvent，没被选中和被选中的条目都会产生事件



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427220813.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427220828.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221205.png)





#### JList

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221345.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221416.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221706.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221751.png)

##### 为JList添加滚动条

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221931.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427221947.png)

##### 其他方法

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427222535.png)





#### JTextField

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427204150.png)

```java
textField.setEditable(false);
testField.setFont(new Font(null, Font.Bold, 20));
```



#### JPasswordField

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427204223.png)

```java
passwordField.getPassword();
```

#### JTextArea

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427225017.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427225046.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427225118.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427225632.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230000.png)

#### JTabbedPane

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231400.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231615.png)





#### JSlider

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427234951.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235005.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235049.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235059.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235112.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235453.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235506.png)





```java
JSlider.getValue();
```



#### 菜单

##### 普通菜单



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235559.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235651.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235734.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235802.png)

 ![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427235847.png)

菜单栏是所有菜单和菜单项的根



```java
import javax.swing.BoxLayout;
import javax.swing.JFrame;
import javax.swing.JMenu;
import javax.swing.JMenuBar;

public class ChatRoomGUI extends JFrame{

	public ChatRoomGUI() {
		super("局域网聊天工具");
		getContentPane().setLayout(
			    new BoxLayout(getContentPane(), BoxLayout.X_AXIS)
			);
		setSize(1000,700);
		JMenuBar menubar = new JMenuBar();
		//add menu
		JMenu tool = new JMenu("工具");
		JMenu setting = new JMenu("设置");
		menubar.add(tool);
		menubar.add(setting);
		
		
		
		
		//add 
		setJMenuBar(menubar);
	}
	public static void main(String args[]) {
		JFrame gui = new ChatRoomGUI();
		gui.setVisible(true);
	}

}

```





##### JPopupMenu

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428000941.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428001121.png)

通过添加鼠标事件来弹出菜单

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428105018.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428105101.png)



##### 其他



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428000735.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428000810.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428000829.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428000846.png)





##### 示例

```java
import java.awt.Color;
import java.awt.Font;
import java.awt.BorderLayout;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.awt.event.ItemListener;
import java.awt.event.ItemEvent;
import javax.swing.JFrame;
import javax.swing.JRadioButtonMenuItem;
import javax.swing.JCheckBoxMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JLabel;
import javax.swing.SwingConstants;
import javax.swing.ButtonGroup;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JMenuBar;

public class MenuFrame extends JFrame 
{
   private final Color colorValues[] = 
      { Color.BLACK, Color.BLUE, Color.RED, Color.GREEN };   
   private JRadioButtonMenuItem colorItems[]; // color menu items
   private JRadioButtonMenuItem fonts[]; // font menu items
   private JCheckBoxMenuItem styleItems[]; // font style menu items
   private JLabel displayJLabel; // displays sample text
   private ButtonGroup fontButtonGroup; // manages font menu items
   private ButtonGroup colorButtonGroup; // manages color menu items
   private int style; // used to create style for font

   // no-argument constructor set up GUI
   public MenuFrame()
   {
      super( "Using JMenus" );     
      JMenu fileMenu = new JMenu( "File" ); // create file menu
      fileMenu.setMnemonic( 'F' ); // set mnemonic to F

      // create About... menu item
      JMenuItem aboutItem = new JMenuItem( "About..." );
      aboutItem.setMnemonic( 'A' ); // set mnemonic to A
      fileMenu.add( aboutItem ); // add about item to file menu
      aboutItem.addActionListener(

         new ActionListener() // anonymous inner class
         {  
            // display message dialog when user selects About...
            public void actionPerformed( ActionEvent event )
            {
               JOptionPane.showMessageDialog( MenuFrame.this,
                  "This is an example\nof using menus",
                  "About", JOptionPane.PLAIN_MESSAGE );
            } // end method actionPerformed
         } // end anonymous inner class
      ); // end call to addActionListener
 
      JMenuItem exitItem = new JMenuItem( "Exit" ); // create exit item
      exitItem.setMnemonic( 'x' ); // set mnemonic to x
      fileMenu.add( exitItem ); // add exit item to file menu
      exitItem.addActionListener(

         new ActionListener() // anonymous inner class
         {  
            // terminate application when user clicks exitItem
            public void actionPerformed( ActionEvent event )
            {
               System.exit( 0 ); // exit application
            } // end method actionPerformed
         } // end anonymous inner class
      ); // end call to addActionListener

      JMenuBar bar = new JMenuBar(); // create menu bar
      setJMenuBar( bar ); // add menu bar to application
      bar.add( fileMenu ); // add file menu to menu bar

      JMenu formatMenu = new JMenu( "Format" ); // create format menu
      formatMenu.setMnemonic( 'r' ); // set mnemonic to r

      // array listing string colors
      String colors[] = { "Black", "Blue", "Red", "Green" };

      JMenu colorMenu = new JMenu( "Color" ); // create color menu
      colorMenu.setMnemonic( 'C' ); // set mnemonic to C

      // create radio button menu items for colors
      colorItems = new JRadioButtonMenuItem[ colors.length ];
      colorButtonGroup = new ButtonGroup(); // manages colors
      ItemHandler itemHandler = new ItemHandler(); // handler for colors

      // create color radio button menu items
      for ( int count = 0; count < colors.length; count++ ) 
      {
         colorItems[ count ] = 
            new JRadioButtonMenuItem( colors[ count ] ); // create item
         colorMenu.add( colorItems[ count ] ); // add item to color menu
         colorButtonGroup.add( colorItems[ count ] ); // add to group
         colorItems[ count ].addActionListener( itemHandler );
      } // end for

      colorItems[ 0 ].setSelected( true ); // select first Color item

      formatMenu.add( colorMenu ); // add color menu to format menu
      formatMenu.addSeparator(); // add separator in menu

      // array listing font names
      String fontNames[] = { "Serif", "Monospaced", "SansSerif" };
      JMenu fontMenu = new JMenu( "Font" ); // create font menu
      fontMenu.setMnemonic( 'n' ); // set mnemonic to n

      // create radio button menu items for font names
      fonts = new JRadioButtonMenuItem[ fontNames.length ];
      fontButtonGroup = new ButtonGroup(); // manages font names

      // create Font radio button menu items
      for ( int count = 0; count < fonts.length; count++ ) 
      {
         fonts[ count ] = new JRadioButtonMenuItem( fontNames[ count ] );
         fontMenu.add( fonts[ count ] ); // add font to font menu
         fontButtonGroup.add( fonts[ count ] ); // add to button group
         fonts[ count ].addActionListener( itemHandler ); // add handler
      } // end for

      fonts[ 0 ].setSelected( true ); // select first Font menu item
      fontMenu.addSeparator(); // add separator bar to font menu

      String styleNames[] = { "Bold", "Italic" }; // names of styles
      styleItems = new JCheckBoxMenuItem[ styleNames.length ];
      StyleHandler styleHandler = new StyleHandler(); // style handler

      // create style checkbox menu items
      for ( int count = 0; count < styleNames.length; count++ ) 
      {
         styleItems[ count ] = 
            new JCheckBoxMenuItem( styleNames[ count ] ); // for style
         fontMenu.add( styleItems[ count ] ); // add to font menu
         styleItems[ count ].addItemListener( styleHandler ); // handler
      } // end for

      formatMenu.add( fontMenu ); // add Font menu to Format menu
      bar.add( formatMenu ); // add Format menu to menu bar
     
      // set up label to display text
      displayJLabel = new JLabel( "Sample Text", SwingConstants.CENTER );
      displayJLabel.setForeground( colorValues[ 0 ] );
      displayJLabel.setFont( new Font( "Serif", Font.PLAIN, 72 ) );

      getContentPane().setBackground( Color.CYAN ); // set background
      add( displayJLabel, BorderLayout.CENTER ); // add displayJLabel
   } // end MenuFrame constructor

   // inner class to handle action events from menu items
   private class ItemHandler implements ActionListener 
   {
      // process color and font selections
      public void actionPerformed( ActionEvent event )
      {
         // process color selection
         for ( int count = 0; count < colorItems.length; count++ )
         {
            //if ( colorItems[ count ].isSelected() ) 
        	 if ( event.getSource()==colorItems[ count ] )
            {
               displayJLabel.setForeground( colorValues[ count ] );
               break;
            } // end if
         } // end for

         // process font selection
         for ( int count = 0; count < fonts.length; count++ )
         {
            if ( event.getSource() == fonts[ count ] ) 
            {
               displayJLabel.setFont( 
                  new Font( fonts[ count ].getText(), style, 72 ) );
            } // end if
         } // end for

         repaint(); // redraw application
      } // end method actionPerformed
   } // end class ItemHandler

   // inner class to handle item events from check box menu items
   private class StyleHandler implements ItemListener 
   {
      // process font style selections
      public void itemStateChanged( ItemEvent e )
      {
         style = 0; // initialize style

         // check for bold selection
         if ( styleItems[ 0 ].isSelected() )
            style += Font.BOLD; // add bold to style

         // check for italic selection
         if ( styleItems[ 1 ].isSelected() )
            style += Font.ITALIC; // add italic to style

         displayJLabel.setFont( 
            new Font( displayJLabel.getFont().getName(), style, 72 ) );
         repaint(); // redraw application
      } // end method itemStateChanged
   } // end class StyleHandler
} // end class MenuFrame

```





### SwingContants

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427155247.png)



### GUI事件

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427155949.png)

#### 事件处理机制

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427160038.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427160119.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427160203.png)



#### 示例

```java
package gui_test;
import java.awt.FlowLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.*;
public class GuiTest extends JFrame implements ActionListener{

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	private JButton button_test;
	public GuiTest() {
		// TODO Auto-generated constructor stub
		super("Gui Test by ccreater");
		setLayout(new FlowLayout());
		setSize(300, 100);
		button_test=new JButton("Exit");
		add(button_test);
		button_test.addActionListener(this);
	}
	public static void main(String args[]) {
		(new GuiTest()).setVisible(true);;
	}
	@Override
	public void actionPerformed(ActionEvent e) {
		dispose();
		
	}

}

```



#### ActionEvent



method:

```java
public function getSource();//返回事件源
public funciont getActionCommand();//获取用户在产生事件的文本框中输入的文本
```

#### 鼠标事件

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428001213.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428001230.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428001306.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428001335.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428001448.png)

```java
import java.awt.Color;
import java.awt.BorderLayout;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.awt.event.MouseEvent;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;

public class MouseTrackerFrame extends JFrame
{
   private JPanel mousePanel; // panel in which mouse events will occur
   private JLabel statusBar; // label that displays event information
   private JLabel m;

   // MouseTrackerFrame constructor sets up GUI and 
   // registers mouse event handlers
   public MouseTrackerFrame()
   {
      super( "Demonstrating Mouse Events" );

      mousePanel = new JPanel(); // create panel
      
      mousePanel.setBackground( Color.WHITE ); // set background color
      add( mousePanel, BorderLayout.CENTER ); // add panel to JFrame
      
      m=new JLabel("***!!***");
      mousePanel.setLayout(null);
      mousePanel.add(m,null);

      statusBar = new JLabel( "Mouse outside JPanel" ); 
      add( statusBar, BorderLayout.SOUTH ); // add label to JFrame

      // create and register listener for mouse and mouse motion events
      MouseHandler handler = new MouseHandler(); 
      mousePanel.addMouseListener( handler ); 
      mousePanel.addMouseMotionListener( handler ); 
   } // end MouseTrackerFrame constructor

   private class MouseHandler implements MouseListener, 
      MouseMotionListener 
   {
      // MouseListener event handlers
      // handle event when mouse released immediately after press
      public void mouseClicked( MouseEvent event )
      {
         statusBar.setText( String.format( "Clicked at [%d, %d]", 
            event.getX(), event.getY() ) );
      } // end method mouseClicked

      // handle event when mouse pressed
      public void mousePressed( MouseEvent event )
      {
         statusBar.setText( String.format( "Pressed at [%d, %d]", 
            event.getX(), event.getY() ) );
      } // end method mousePressed

      // handle event when mouse released after dragging
      public void mouseReleased( MouseEvent event )
      {
         statusBar.setText( String.format( "Released at [%d, %d]", 
            event.getX(), event.getY() ) );
      } // end method mouseReleased

      // handle event when mouse enters area
      public void mouseEntered( MouseEvent event )
      {
         statusBar.setText( String.format( "Mouse entered at [%d, %d]", 
            event.getX(), event.getY() ) );
         mousePanel.setBackground( Color.GREEN );
      } // end method mouseEntered

      // handle event when mouse exits area
      public void mouseExited( MouseEvent event )
      {
         statusBar.setText( "Mouse outside JPanel" );
         mousePanel.setBackground( Color.WHITE );
      } // end method mouseExited

      // MouseMotionListener event handlers
      // handle event when user drags mouse with button pressed
      public void mouseDragged( MouseEvent event )
      {
         statusBar.setText( String.format( "Dragged at [%d, %d]", 
            event.getX(), event.getY() ) );
      } // end method mouseDragged

      // handle event when user moves mouse
      public void mouseMoved( MouseEvent event )
      {
         statusBar.setText( String.format( "Moved at [%d, %d]", 
            event.getX(), event.getY() ) );
         m.setBounds(event.getX(), event.getY(), 40, 10);
         repaint();
      } // end method mouseMoved
   } // end inner class MouseHandler
} // end class MouseTrackerFrame

```



#### 键盘事件





### UIManager

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428110454.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428110511.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428110538.png)





### 适配器类

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428104714.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200428104746.png)





### JOptionPane

#### 基本对话框类型

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427205250.png)

#### 调用对话框



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427205333.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427211313.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427211330.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427211357.png)





#### 示例

```java
package gui_test;
import javax.swing.*;
public class GuiTest{

	/**
	 * 
	 */
	public GuiTest() {
		String firstNum = JOptionPane.showInputDialog(null, "请输入第一个数字", "输入框", JOptionPane.INFORMATION_MESSAGE);
		int first = Integer.parseInt(firstNum);
		String secondNum = JOptionPane.showInputDialog(null, "请输入第二个数字", "输入框", JOptionPane.INFORMATION_MESSAGE);
		int second = Integer.parseInt(secondNum);
		JOptionPane.showMessageDialog(null, "结果是" + String.valueOf(first + second), "计算结果", JOptionPane.INFORMATION_MESSAGE);
		Object[] options = {"继续","退出"};
		int response = JOptionPane.showOptionDialog(null, "继续或者退出？", "是否退出", JOptionPane.DEFAULT_OPTION, JOptionPane.INFORMATION_MESSAGE, null, options, "1");
		switch (response) {
		case 0:
			new GuiTest();
			break;
		case 1:
			System.exit(0);
			break;

		default:
			break;
		}
	}
	public static void main(String args[]) {
		new GuiTest();
	}

}

```





```java
JOptionPane.showMessageDialog( null, string )
```



### Box



#### 构建方法

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427225723.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230049.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230124.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230656.png)

#### 添加透明(占位)组件

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230903.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230927.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231001.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231102.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231145.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231203.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231222.png)







![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230810.png)





### 布局

#### FlowLayout

流水布局，从左到右，从上到下，依次填充

#### CardLayout

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427203202.png)

##### 方法

```java
public function pre();
public function next();
```



#### GridLayout 网格式布局



#### BoxLayout 镶式布局

水平分割

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427230038.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231322.png)





#### BorderLayout 边界式布局



#### GridBagLayout 网格包布局管理器

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231805.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231820.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231854.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427231922.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427232012.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427232041.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427233409.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427233500.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427234104.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427234200.png)

##### 示例

```java
import java.awt.GridBagLayout;
import java.awt.GridBagConstraints;
import java.awt.Component;
import javax.swing.JFrame;
import javax.swing.JTextArea;
import javax.swing.JTextField;
import javax.swing.JButton;
import javax.swing.JComboBox;

public class GridBagFrame extends JFrame 
{ 
   private GridBagLayout layout; // layout of this framed
   private GridBagConstraints constraints; // constraints of this layout
    
   // set up GUI
   public GridBagFrame()
   {
      super( "GridBagLayout" );
      layout = new GridBagLayout();
      setLayout( layout ); // set frame layout
      constraints = new GridBagConstraints(); // instantiate constraints

      // create GUI components
      JTextArea textArea1 = new JTextArea( "TextArea1", 5, 10 );
      JTextArea textArea2 = new JTextArea( "TextArea2", 2, 2 );

      String[] names = { "Iron", "Steel", "Brass" };
      JComboBox comboBox = new JComboBox( names );

      JTextField textField = new JTextField( "TextField" );
      JButton button1 = new JButton( "Button 1" );
      JButton button2 = new JButton( "Button 2" );
      JButton button3 = new JButton( "Button 3" );

      // weightx and weighty for textArea1 are both 0: the default
      // anchor for all components is CENTER: the default
      constraints.fill = GridBagConstraints.BOTH;
      addComponent( textArea1, 0, 0, 1, 3 );    
       
      // weightx and weighty for button1 are both 0: the default
      constraints.fill = GridBagConstraints.HORIZONTAL;
      addComponent( button1, 0, 1, 2, 1 );
      
      // weightx and weighty for comboBox are both 0: the default
      // fill is HORIZONTAL
      addComponent( comboBox, 2, 1, 2, 1 );             

      // button2
      constraints.weightx = 1000;  // can grow wider
      constraints.weighty = 1;     // can grow taller
      constraints.fill = GridBagConstraints.BOTH;
      addComponent( button2, 1, 1, 1, 1 );
       
      // fill is BOTH for button3
      constraints.weightx = 0;
      constraints.weighty = 0;    
      addComponent( button3, 1, 2, 1, 1 );
       
      // weightx and weighty for textField are both 0, fill is BOTH
      addComponent( textField, 3, 0, 2, 1 );

      // weightx and weighty for textArea2 are both 0, fill is BOTH
      addComponent( textArea2, 3, 2, 1, 1 );
   } // end GridBagFrame constructor

   // method to set constraints on 
   private void addComponent( Component component,
      int row, int column, int width, int height )
   {
      constraints.gridx = column; // set gridx
      constraints.gridy = row; // set gridy
      constraints.gridwidth = width; // set gridwidth
      constraints.gridheight = height; // set gridheight
      layout.setConstraints( component, constraints ); // set constraints
      add( component ); // add component
   } // end method addComponent
} // end class GridBagFrame
```





### 为组件添加边框: Border



### 多线程



![image-20200524135234483](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200524135234483.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524135259.png)

因此为了保证GUI不卡顿，我们需要将耗时的操作放到一个个单独的线程中

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524145226.png)

#### SwingUtilites

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524145653.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524145738.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524145937.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524150112.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524150202.png)

#### SwingWorker

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524150244.png)





### 杂

#### 添加背景

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200427222059.png)

## 多线程编程

### 线程的概念

#### 概念

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522163310.png)

![image-20200522163409565](C:\Users\蔡建斌\AppData\Roaming\Typora\typora-user-images\image-20200522163409565.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522163425.png)







#### java线程的优先级

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522164138.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522164233.png)







### 多线程的实现







### 线程的生命周期



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522163841.png)



### 线程的创建，调度



#### 创建并执行线程

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522164534.png)

 ![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522190424.png)



##### 通过Thread来实现多线程

```java
import java.util.Random;

class ThreadTest extends Thread {
	private static Random generator = new Random();
	private int sleeptime;
	public ThreadTest(String n) {// n：线程名字
		// TODO Auto-generated constructor stub
		super(n);
	}
	public void run() {
		for(int i=0;i<5;i++) {
			try {
				sleeptime=generator.nextInt(20);
				Thread.sleep(sleeptime);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			System.out.print(getName()+"\t");
		}
	}

}
public class ThreadRun {

	public ThreadRun() {
		// TODO Auto-generated constructor stub
	}
	public static void main(String args[]) {
		new ThreadTest("A").start();
		new ThreadTest("B").start();
	}

}


```



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522191526.png)



##### 通过Runnable接口创建线程

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522191726.png)

```java
import java.util.Random;

public class ThreadTest implements Runnable{
	private static Random generator = new Random();
	private int sleeptime;
	private String name;
	public ThreadTest(String n) {// n：线程名字
		// TODO Auto-generated constructor stub
		name=n;
	}
	public void run() {
		for(int i=0;i<5;i++) {
			try {
				sleeptime=generator.nextInt(20);
				Thread.sleep(sleeptime);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			System.out.print(name+"\t");
		}
	}

}


public class ThreadRun {

	public ThreadRun() {
		// TODO Auto-generated constructor stub
	}
	public static void main(String args[]) {
		new Thread(new ThreadTest("A")).start();
		new Thread(new ThreadTest("B")).start();
	}

}

```



#### 后台线程

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522192440.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522192559.png)



#### 线程组

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522192755.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522192818.png)

- Thread(ThreadGroup group,Runnable target)：group属于的线程组，target为新线程
- Thread(ThreadGroup group,Runnable target,String name)：group属于的线程组，target为新线程，name：线程名
- Thread(ThreadGroup group,String name)：新线程名为name，属于group线程组

ThreadGroup类
（1）构造方法

- ThreadGroup(String name)：以指定线程组名字来创建新线程组
- ThreadGroup(ThreadGroup parent,String name)：以指定的名字、指定的父线程组来创建一个新线程组。
（2）常用操作方法

- int activeCount()：获取线程组中活动线程的数量
- interrupt()：中断线程组中所有线程
- isDaemon()：是否为后台线程组
- setDaemon(boolean daemon)：设置为后台线程组
- setMaxPriority(int pri)：设置线程组的最高优先级



#### 线程池

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522193532.png)

##### 创建固定大小的线程池

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522193628.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522194458.png)





```java
import java.util.Random;

public class ThreadTest implements Runnable{
	private static Random generator = new Random();
	private int sleeptime;
	private String name;
	public ThreadTest(String n) {// n：线程名字
		// TODO Auto-generated constructor stub
		name=n;
	}
	public void run() {
		for(int i=0;i<5;i++) {
			try {
				sleeptime=generator.nextInt(20);
				Thread.sleep(sleeptime);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			System.out.print(name+"\t");
		}
	}

}
import java.awt.datatransfer.FlavorTable;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class ThreadRun {

	public ThreadRun() {
		// TODO Auto-generated constructor stub
	}
	public static void main(String args[]) {
		ExecutorService pool = Executors.newFixedThreadPool(10);
		for(int i=0;i<15;i++) {
			Runnable runnable = new ThreadTest(String.valueOf(i));
			pool.execute(runnable);
		}
		pool.shutdown();//禁止向进程池中继续添加进程
		while(!pool.isTerminated()) {};//进程是否都结束了
		System.out.println("program ends");
	}
		

}

```



##### 单任务线程池

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522194607.png)

##### 可变大小线程池

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522195426.png)

只要往里面丢线程就可以了

```java
public class ThreadRun {

	public ThreadRun() {
		// TODO Auto-generated constructor stub
	}
	public static void main(String args[]) {
		ExecutorService pool = Executors.newCachedThreadPool();
		for(int i=0;i<15;i++) {
			Runnable runnable = new ThreadTest(String.valueOf(i));
			pool.execute(runnable);
		}
		pool.shutdown();//禁止向进程池中继续添加进程
		while(!pool.isTerminated()) {};//进程是否都结束了
		System.out.println("program ends");
	}
		

}
```



##### 延迟线程池

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522195840.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522195904.png)

##### 自定义线程池

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200112.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200158.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200239.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200255.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200322.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200513.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200608.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200656.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522200725.png)



#### 线程的返回值

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522201004.png)



```java
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

class MyCallable implements Callable<String> {

	
	public String name;
	
	public MyCallable(String n) {
		name = n;
		// TODO Auto-generated constructor stub
	}
	@Override
	public String call() throws Exception {
		// TODO Auto-generated method stub
		return name+"任务返回的内容";
	}
	
}
public class CallableTest {

	public CallableTest() {
		// TODO Auto-generated constructor stub
		ExecutorService pool = Executors.newFixedThreadPool(5);
		MyCallable task1= new MyCallable("A");
		MyCallable task2= new MyCallable("B");
		Future<String> f1=pool.submit(task1);
		Future<String> f2=pool.submit(task2);
		pool.shutdown();
		while(!f1.isDone()) {};
		while(!f2.isDone()) {};
		try {
			System.out.println(f1.get());
			System.out.println(f2.get());
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ExecutionException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}
	public static void main(String args[]) {
		new CallableTest();
		
	}

}

```



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522202505.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522202520.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522202548.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200522202605.png)





### 同步线程

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524095537.png)

 

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100030.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100050.png)



#### synchronized关键字

##### 同步方法

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100121.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100210.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100238.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100256.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100329.png)



##### 同步块

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100415.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524100636.png)



#### wait/notify/notifyAll



 

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524101519.png)



可以设置等待秒数

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524101550.png)

#### 直接使用封装好的类

ArrayBlockingQueue

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524102648.png)

#### Lock,Condition接口

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524133246.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524133402.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524133430.png)

#### Lock,Conditoin,Synchronized

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524134104.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524134157.png)



## 网络编程



### GUI

#### JEditorPane

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524151557.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524151617.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524151700.png)

### IP地址相关

#### 表示---InetAddress

 ![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524151805.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524151817.png)



### Socket

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524151935.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524152010.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524152112.png)



#### 流套接字(TCP):Socket和ServerSocket

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524152318.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524152541.png)





#### 数据报套接字：DategramSocket

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524152407.png)





### 建立服务器

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524155640.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524155654.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524155808.png)

可以和其他数据处理流套接

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524155913.png)



```java
import java.io.IOException;
import java.io.OutputStream;
import java.io.PrintStream;
import java.net.ServerSocket;
import java.net.Socket;

public class Server {

	public Server() {
		// TODO Auto-generated constructor stub
	}
	public static void main(String args[]) {
		ServerSocket serverSocket = null;
		try {
			serverSocket = new ServerSocket(12345);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			System.exit(0);
		}
		System.out.println("Server start!");
		while(true) {
			try {
				Socket connection=serverSocket.accept();
				System.out.println("Received request from "+ 
						connection.getInetAddress()+":"+connection.getPort());
				OutputStream outputStream = connection.getOutputStream();
				PrintStream pout = new PrintStream(outputStream);
				pout.print("你好！连接服务器成功\n");
				//outputStream.flush();
				outputStream.close();
				connection.close();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
				break;
				
			}
			
		}
		try {
			serverSocket.close();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

}

```







### 建立客户端

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524160010.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524160033.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524160052.png)





```java
import java.io.IOException;
import java.io.InputStream;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.Scanner;

public class Cilent {

	public Cilent() {
		// TODO Auto-generated constructor stub
	}
	public static void main(String args[]) {
		try {
			Socket con = new Socket("127.0.0.1", 12345);
			InputStream in= con.getInputStream();
			Scanner scanner = new Scanner(in);
			while(scanner.hasNextLine()) {
				System.out.print(scanner.nextLine());;
				
			}
			scanner.close();
			in.close();
			con.close();
		} catch (UnknownHostException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}
}

```

### 无连接的客户/服务端编程

#### 服务端

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524163628.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524163739.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524163802.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524163903.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524163922.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524163952.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524164019.png)

#### 客户端

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524170315.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524170408.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524170432.png)

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200524171227.png)



