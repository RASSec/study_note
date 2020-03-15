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

