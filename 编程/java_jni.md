

# JNI

Java语言是基于C语言实现的，Java底层的很多API都是通过`JNI(Java Native Interface)`来实现的。通过`JNI`接口`C/C++`和`Java`可以互相调用(存在跨平台问题)。



## 编写JNI代码的步骤

第一步：生成定义

第二步：生成头文件

第三步：编写JNI代码

第四步：编译

## 定义

```java
public class CommandExecution {
    public static native String exec(String cmd);
}
```



## 生成头文件

`javah CommandExecution` 

jdk版本大于10 就用`javac -h CommandExecution`



## 编写JNI代码

https://blog.csdn.net/qq_25722767/article/details/52557235



### C语言与java的数据沟通方式



c语言与java数据类型的对应：

java基本数据类型和c语言的类型是一一对应，无需转换的

![image-20201108142947289](https://raw.githubusercontent.com/Explorersss/photo/master/20201108143426.png)

而引用类型是需要转换的

![image-20201108143031130](https://raw.githubusercontent.com/Explorersss/photo/master/20201108143434.png)



### String类型的处理

java的字符串类型被原生语言当作引用类型来处理，因此如果想在原生语言中使用字符串类型的数据，就必须进行转换。JNI机制提供两种编码方式的的字符串格式，分别是Unicode和UTF编码格式。针对不同的编码格式有不同的转换方法。



**将C风格字符串转换为java字符串**

可以将c风格字符串转换为jni类型的jstring然后返回给java对象使用。如果是Unicode编码则使用NewString,UTF编码则用NewStringUTF函数。比如：

```cpp
env->NewStringUTF("来自C++");
```



**在C语言中使用java字符串**


java字符串在原生语言中被当作引用类型来处理，因此使用之前必须转换为char数组类型。如果是Unicode的编码使用GetStringChars，如果是UTF编码则使用GetStringUTFChars。比如：

```c++
const char *p;
jboolean isCopy = JNI_TRUE;
p = env->GetStringUTFChars(param, &isCopy);
```


在上面由于GetStringUTFChars方法返回一个指向char数组首地址的指针，因此需要定义一个char指针变量。它的第二个参数表示返回的指针指向的是堆中的对象还是字符串副本，true表示指向副本。JNI类型定义了两个常量代表jboolean的真假，分别是

```c++
#define JNI_FALSE   0
#define JNI_TRUE	1
```



**释放字符串**

穿件了字符串指针，使用完毕后应该释放从而避免内存泄漏。释放方法如果是Unicode编码使用releaseStringChars，如果是UTF编码使用releaseStringUTFChars。比如

```c++
env->ReleaseStringUTFChars(javaString, p);
```


javaString指的是java字符串对象，p指的是签名创建的字符指针对象。



### 数组的处理



**创建数组**

通过NewTYPEArray函数就可以在原生语言中创建java数组，type指的是Bool,Int,Float等基本数据类型。



```cpp
jintArray array1 = env->NewIntArray(10);
```


10表示数组的容量。



**访问数组**

通过GetTYPEArrayRegion函数就可以将java数组元素复制到一个C数组中，从而可以在原生语言中对其进行操作。比如：

```cpp
jint nativeArray[10];
env->GetIntArrayRegion(array, 0, 10, nativeArray);//0,10指代数组的范围
```


这样，我们就可以操作nativeArray来访问java数组的元素了。注意这里是复制的方式，虽然访问的内容是一样的，但是他们所代表的对象不是同一个对象，而是java数组的一个副本。



**将c数组复制到java数组**



由于通过GeTYPEArrayRegion函数获取的只是java数组的副本，因此任何对c数组的操作都不会影响java数组，而如果希望将c数组操作后的结果复制会Java数组，就需要使用setTYPEArrayRegion函数。比如：

```cpp
env->SetIntArrayRegion(array, 0, 10, nativeArray);//0,10指代数组的范围
```



**通过指针操作java数组**

由于复制的代价很高，尤其是在数组元素很多的情况下，因此使用指针的方式会更加合理。通过GetTYPEArrayElements函数就可以获取一个指向java数组的指针。比如：

```cpp
jint *pNative;
jboolean isCopy=JNI_TRUE;
pNative=env->GetIntArrayElements(array,&isCopy);
```



这里如何确定长度？

**释放指针对象**

操作完成后，需要通过ReleaseTYPEArrayElements函数释放指针。比如：

```cpp
env->ReleaseIntArrayElements(array,pnative,0);
```

0表示释放模式。总共有三种释放模式：

![image-20201108144834364](https://raw.githubusercontent.com/Explorersss/photo/master/20201108144834.png)

复制回来的意思是，将C数组的内容复制到java数组。



### C语言访问java变量

不仅java可以调用C语言，C语言同样可以调用java对象。在java中，类有两个域，分别是静态域和实例域。一个类可以有多个实例域，但是这多个实例域都对应者一个静态域。而在C语言中获取它们的方法也是不同的。



#### 根据调用对象获取类



要想获取类的对象的实例域或者静态域，就必须知道类。获取当前调用对象的类的方法如下：

```cpp
jclass clazz;
clazz = env->GetObjectClass(jTiss);
```


有了类，我们就可以获取域ID了。

#### 获取域ID

要想获取java对象的实例域或者静态域，除了知道所属的类以外，还必须知道它的域ID。根据实例域和静态域，获取的方法也有所不同。

实例域，比如：

```cpp
jfieldID fieldID;
fieldID = env->GetFieldID(clazz, "instanceString", "Ljava/lang/String;");

```

其中第二个参数表示java类中的实例变量的名称，第三个参数是方法描述符，是Jni特有的一种

静态域，比如：

```cpp
jfieldID fieldID;
fieldID = env->GetStaticFieldID(clazz, "staticString","Ljava/lang/String;");
```


参数同上。但是此方法获取的java对象的静态变量。



#### 获取JAVA变量的值

有了上述的域ID，就可以获取java的实例变量或者静态变量了。

获取实例变量：

```cpp
jstring string;
string = (jstring) env->GetObjectField(jTiss, fieldID);
```

获取静态变量：

```cpp
jstring string;
string = (jstring) env->GetStaticObjectField(clazz, fieldID);
```



### C语言调用java方法

同样的首先我们必须知道所调用的对象的类，接着需要知道方法ID：

获取实例方法ID：

```cpp
jclass clazz = env->GetObjectClass(jThis);
jmethodID methodID = env->GetMethodID(clazz, "getInstanceStringFromJava","()Ljava/lang/String;");
```

其中第二个参数是方法名称。

获取静态方法ID：

```cpp
jclass clazz = env->GetObjectClass(jThis);
jmethodID methodID = env->GetStaticMethodID(clazz,"getStaticStringFromJava", "()Ljava/lang/String;");
```


参数同上。

有了方法ID就可以调用了。

调用实例方法：

```cpp
env->CallObjectMethod(jThis, methodID);
```


调用静态方法：

```cpp
env->CallStaticObjectMethod(clazz, methodID);
```



## 编译(cpp)

### Windows

```
x86_64-w64-mingw32-g++ -I"%JAVA_HOME%\include" -I"%JAVA_HOME%\include\win32" -shared -o hello.dll HelloJNI.c
```



### Linux

```
export JAVA_HOME=/your/java/installed/dir
 gcc -fPIC -I"$JAVA_HOME/include" -I"$JAVA_HOME/include/linux" -shared -o libhello.so HelloJNI.c
 
```





## java加载动态库

```
static {
		System.loadLibrary("cppUtils");
	}
```

