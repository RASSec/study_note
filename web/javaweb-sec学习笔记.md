# javaweb-sec学习笔记

https://javasec.org/

## java基础

### ClassLoader



#### 什么是ClassLoader

ClassLoader是加载Java类文件的类。Java类初始化的时候会调用`java.lang.ClassLoader`加载类字节码（就是那个class类）

ClassLoader类的核心方法：

1. `loadClass`(加载指定的Java类)
2. `findClass`(查找指定的Java类)
3. `findLoadedClass`(查找JVM已经加载过的类)
4. `defineClass`(定义一个Java类)
5. `resolveClass`(链接指定的Java类)

ClassLoader的子类

在JVM类加载器中最顶层的是`Bootstrap ClassLoader(引导类加载器)`、`Extension ClassLoader(扩展类加载器)`、`App ClassLoader(系统类加载器)`，`AppClassLoader`是默认的类加载器，如果类加载时我们不指定类加载器的情况下，默认会使用`AppClassLoader`加载类，`ClassLoader.getSystemClassLoader()`返回的系统类加载器也是`AppClassLoader`。

值得注意的是某些时候我们获取一个类的类加载器时候可能会返回一个`null`值，如:`java.io.File.class.getClassLoader()`将返回一个`null`对象，因为`java.io.File`类在JVM初始化的时候会被`Bootstrap ClassLoader(引导类加载器)`加载(该类加载器实现于JVM层，采用C++编写)，我们在尝试获取被`Bootstrap ClassLoader`类加载器所加载的类的`ClassLoader`时候都会返回`null`。



#### 使用javap查看java类字节码

```
javap -help
用法: javap <options> <classes>
其中, 可能的选项包括:
  -help  --help  -?        输出此用法消息
  -version                 版本信息
  -v  -verbose             输出附加信息
  -l                       输出行号和本地变量表
  -public                  仅显示公共类和成员
  -protected               显示受保护的/公共类和成员
  -package                 显示程序包/受保护的/公共类
                           和成员 (默认)
  -p  -private             显示所有类和成员
  -c                       对代码进行反汇编
  -s                       输出内部类型签名
  -sysinfo                 显示正在处理的类的
                           系统信息 (路径, 大小, 日期, MD5 散列)
  -constants               显示最终常量
  -classpath <path>        指定查找用户类文件的位置
  -cp <path>               指定查找用户类文件的位置
  -bootclasspath <path>    覆盖引导类文件的位置
```

通常通过`javap -c -p -l xxx.class`来查看



#### java类的加载方式

Java类加载方式分为`显式`和`隐式`,`显式`即我们通常使用`Java反射`或者`ClassLoader`来动态加载一个类对象，而`隐式`指的是`类名.方法名()`或`new`类实例。`显式`类加载方式也可以理解为类动态加载，我们可以自定义类加载器去加载任意的类。

**常用的类动态加载方式：**

```java
// 反射加载TestHelloWorld示例
Class.forName("com.anbai.sec.classloader.TestHelloWorld");

// ClassLoader加载TestHelloWorld示例
this.getClass().getClassLoader().loadClass("com.anbai.sec.classloader.TestHelloWorld");
```

`Class.forName("类名")`默认会初始化被加载类的静态属性和方法，如果不希望初始化类可以使用`Class.forName("类名", 是否初始化类, 类加载器)`，而`ClassLoader.loadClass`默认不会初始化类方法。



#### ClassLoader类加载流程

`ClassLoader`加载`com.anbai.sec.classloader.TestHelloWorld`类重要流程如下：

1. `ClassLoader`会调用`public Class<?> loadClass(String name)`方法加载`com.anbai.sec.classloader.TestHelloWorld`类。
2. 调用`findLoadedClass`方法检查`TestHelloWorld`类是否已经初始化，如果JVM已初始化过该类则直接返回类对象。
3. 如果创建当前`ClassLoader`时传入了父类加载器(`new ClassLoader(父类加载器)`)就使用父类加载器加载`TestHelloWorld`类，否则使用JVM的`Bootstrap ClassLoader`加载。
4. 如果上一步无法加载`TestHelloWorld`类，那么调用自身的`findClass`方法尝试加载`TestHelloWorld`类。
5. 如果当前的`ClassLoader`没有重写了`findClass`方法，那么直接返回类加载失败异常。如果当前类重写了`findClass`方法并通过传入的`com.anbai.sec.classloader.TestHelloWorld`类名找到了对应的类字节码，那么应该调用`defineClass`方法去JVM中注册该类。
6. 如果调用loadClass的时候传入的`resolve`参数为true，那么还需要调用`resolveClass`方法链接类,默认为false。
7. 返回一个被JVM加载后的`java.lang.Class`类对象。

#### 自定义ClassLoader

```java

import sun.misc.PerfCounter;

import java.lang.ClassLoader;
import java.io.*;
import java.lang.reflect.Method;
import java.util.ArrayList;

public class HelloWorldClassLoader extends ClassLoader {
    private static String class_name = "HelloWorld";
    private byte[] class_bytes;
    public HelloWorldClassLoader(String class_name) throws IOException {
        FileInputStream fileInputStream = new FileInputStream(class_name+".class");
        int chr = 0;
        ArrayList<Byte> class_bytes_array = new ArrayList<Byte>();

        while((chr = fileInputStream.read())!=-1){
            class_bytes_array.add(Byte.valueOf((byte) chr));
        }
        int i = 0;
        class_bytes = new byte[class_bytes_array.size()];
        for(Object b : class_bytes_array.toArray()){
            class_bytes[i++] = ((Byte)b).byteValue();
        }

    }

    protected Class<?> loadClass(String name, boolean resolve)
            throws ClassNotFoundException
    {
        synchronized (getClassLoadingLock(name)) {
            if(!name.equals(class_name)){
                return super.loadClass(name,resolve);
            }
            Class<?> c = findLoadedClass(name);
            if (c == null) {
                long t0 = System.nanoTime();
                if (c == null) {
                    long t1 = System.nanoTime();
                    c = findClass(name);

                    PerfCounter.getParentDelegationTime().addTime(t1 - t0);
                    PerfCounter.getFindClassTime().addElapsedTimeFrom(t1);
                    PerfCounter.getFindClasses().increment();
                }
            }
            if (resolve) {
                resolveClass(c);
            }
            return c;
        }
    }

    @Override
    public Class<?> findClass(String name) throws ClassNotFoundException {
        if (name.equals(class_name)) {
            System.out.println("My Class Loader");
            return defineClass(class_name, class_bytes,0, class_bytes.length);
        }

        return null;
    }
    public static void main(String[] args) throws IOException {
        HelloWorldClassLoader loader = new HelloWorldClassLoader(class_name);

        try {
            Class testClass = loader.loadClass(class_name);

            Object testInstance = testClass.newInstance();

            Method method = testInstance.getClass().getMethod("hello");

            String str = (String) method.invoke(testInstance);

            System.out.println(str);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


}

```



#### URLClassLoader

```java
import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.net.URL;
import java.net.URLClassLoader;

public class TestUrlClassLoader {

    public static void main(String[] args) {
        try {
            // 定义远程加载的jar路径
            URL url = new URL("http://127.0.0.1:8000/cmd.class");

            // 创建URLClassLoader对象，并加载远程jar包
            URLClassLoader ucl = new URLClassLoader(new URL[]{url});

            // 定义需要执行的系统命令
            String cmd = "cmd.exe /C dir";

            // 通过URLClassLoader加载远程jar包中的CMD类
            Class cmdClass = ucl.loadClass("cmd");

            // 调用CMD类中的exec方法，等价于: Process process = CMD.exec("whoami");
            Process process = (Process) cmdClass.getMethod("exec", String.class).invoke(null, cmd);

            // 获取命令执行结果的输入流
            InputStream in   = process.getInputStream();
            ByteArrayOutputStream baos = new ByteArrayOutputStream();
            byte[]                b    = new byte[1024];
            int                   a    = -1;

            // 读取命令执行结果
            while ((a = in.read(b)) != -1) {
                baos.write(b, 0, a);
            }

            // 输出命令执行结果
            System.out.println(baos.toString());
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}
```



### Java反射机制

#### 获取Class对象

Java反射操作的是`java.lang.Class`对象，所以我们需要先想办法获取到Class对象，通常我们有如下几种方式获取一个类的Class对象：

1. `类名.class`，如:`com.anbai.sec.classloader.TestHelloWorld.class`。
2. `Class.forName("com.anbai.sec.classloader.TestHelloWorld")`。
3. `classLoader.loadClass("com.anbai.sec.classloader.TestHelloWorld");`
4. `classLoader.findClass("");`

获取数组类型的Class对象需要特殊注意,需要使用Java类型的描述符方式，如下：

```java
Class<?> doubleArray = Class.forName("[D");//相当于double[].class
Class<?> cStringArray = Class.forName("[[Ljava.lang.String;");// 相当于String[][].class
```



通过以上任意一种方式就可以获取类的Class对象了，**反射调用内部类的时候需要使用`$`来代替`.`**,如`com.anbai.Test`类有一个叫做`Hello`的内部类，那么调用的时候就应该将类名写成：`com.anbai.Test$Hello`。



#### 反射创建类实例

`runtimeClass1.getDeclaredConstructor`和`runtimeClass1.getConstructor`都可以获取到类构造方法，区别在于后者无法获取到私有方法，所以一般在获取某个类的构造方法时候我们会使用前者去获取构造方法。如果构造方法有一个或多个参数的情况下我们应该在获取构造方法时候传入对应的参数类型数组，如：`clazz.getDeclaredConstructor(String.class, String.class)`。

如果我们想获取类的所有构造方法可以使用：`clazz.getDeclaredConstructors`来获取一个`Constructor`数组。

获取到`Constructor`以后我们可以通过`constructor.newInstance()`来创建类实例,同理如果有参数的情况下我们应该传入对应的参数值，如:`constructor.newInstance("admin", "123456")`。当我们没有访问构造方法权限时我们应该调用`constructor.setAccessible(true)`修改访问权限就可以成功的创建出类实例了。

```java
Constructor constructor = runtimeClass1.getDeclaredConstructor();
constructor.setAccessible(true);

// 创建Runtime类示例，等价于 Runtime rt = new Runtime();
Object runtimeInstance = constructor.newInstance();
```



#### 反射调用类方法



`Class`对象提供了一个获取某个类的所有的成员方法的方法，也可以通过方法名和方法参数类型来获取指定成员方法。

**获取当前类所有的成员方法：**

```java
Method[] methods = clazz.getDeclaredMethods()
```

**获取当前类指定的成员方法：**

```java
Method method = clazz.getDeclaredMethod("方法名");
Method method = clazz.getDeclaredMethod("方法名", 参数类型如String.class，多个参数用","号隔开);
```

`getMethod`和`getDeclaredMethod`都能够获取到类成员方法，区别在于`getMethod`只能获取到`当前类和父类`的所有有权限的方法(如：`public`)，而`getDeclaredMethod`能获取到当前类的所有成员方法(不包含父类)。

**反射调用方法**

获取到`java.lang.reflect.Method`对象以后我们可以通过`Method`的`invoke`方法来调用类方法。

**调用类方法代码片段：**

```java
method.invoke(方法实例对象, 方法参数值，多个参数值用","隔开);
```

`method.invoke`的第一个参数必须是类实例对象，如果调用的是`static`方法那么第一个参数值可以传`null`，因为在java中调用静态方法是不需要有类实例的，因为可以直接`类名.方法名(参数)`的方式调用。

`method.invoke`的第二个参数不是必须的，如果当前调用的方法没有参数，那么第二个参数可以不传，如果有参数那么就必须严格的`依次传入对应的参数类型`。



#### 反射访问成员变量

Java反射不但可以获取类所有的成员变量名称，还可以无视权限修饰符实现修改对应的值。

**获取当前类的所有成员变量：**

```java
Field fields = clazz.getDeclaredFields();
```

**获取当前类指定的成员变量：**

```java
Field field  = clazz.getDeclaredField("变量名");
```

`getField`和`getDeclaredField`的区别同`getMethod`和`getDeclaredMethod`。



**获取成员变量值：**

```java
Object obj = field.get(类实例对象);
```

**修改成员变量值：**

```java
field.set(类实例对象, 修改后的值);
```

##### 修改final成员变量值

当我们没有修改的成员变量权限时可以使用: `field.setAccessible(true)`的方式修改为访问成员变量访问权限。

如果我们需要修改被`final`关键字修饰的成员变量，那么我们需要先修改方法

```java
Field modifiers = field.getClass().getDeclaredField("modifiers");

// 设置modifiers修改权限
modifiers.setAccessible(true);

// 修改成员变量的Field对象的modifiers值
modifiers.setInt(field, field.getModifiers() & ~Modifier.FINAL);

// 修改成员变量值
field.set(类实例对象, 修改后的值);
```



###  sun.misc.Unsafe

`sun.misc.Unsafe`是Java底层API(`仅限Java内部使用,反射可调用`)提供的一个神奇的Java类，`Unsafe`提供了非常底层的`内存、CAS、线程调度、类、对象`等操作、`Unsafe`正如它的名字一样它提供的几乎所有的方法都是不安全的

`Unsafe`是Java内部API，外部是禁止调用的，在编译Java类时如果检测到引用了`Unsafe`类也会有禁止使用的警告：`Unsafe是内部专用 API, 可能会在未来发行版中删除`。

#### 反射获取Unsafe

```java
Field theUnsafeField = Unsafe.class.getDeclaredField("theUnsafe");

// 反射设置theUnsafe访问权限
theUnsafeField.setAccessible(true);

// 反射获取theUnsafe成员变量值
Unsafe unsafe = (Unsafe) theUnsafeField.get(null);
```



用反射创建`Unsafe`类实例的方式去获取`Unsafe`对象：

```java
// 获取Unsafe无参构造方法
Constructor constructor = Unsafe.class.getDeclaredConstructor();

// 修改构造方法访问权限
constructor.setAccessible(true);

// 反射创建Unsafe类实例，等价于 Unsafe unsafe1 = new Unsafe();
Unsafe unsafe1 = (Unsafe) constructor.newInstance();
```



####  allocateInstance无视构造方法创建类实例

假设我们有一个叫`com.anbai.sec.unsafe.UnSafeTest`的类，因为某种原因我们不能直接通过反射的方式去创建`UnSafeTest`类实例，那么这个时候使用`Unsafe`的`allocateInstance`方法就可以绕过这个限制了。



**UnSafeTest代码片段：**

```java
public class UnSafeTest {

   private UnSafeTest() {
      // 假设RASP在这个构造方法中插入了Hook代码，我们可以利用Unsafe来创建类实例
      System.out.println("init...");
   }

}
```

**使用Unsafe创建UnSafeTest对象：**

```java
// 使用Unsafe创建UnSafeTest类实例
UnSafeTest test = (UnSafeTest) unsafe1.allocateInstance(UnSafeTest.class);
```



#### defineClass直接调用JVM创建类对象



如果`ClassLoader`被限制的情况下我们还可以使用`Unsafe`的`defineClass`方法来实现同样的功能。

`Unsafe`提供了一个通过传入类名、类字节码的方式就可以定义类的`defineClass`方法：

```
public native Class defineClass(String var1, byte[] var2, int var3, int var4);
public native Class<?> defineClass(String var1, byte[] var2, int var3, int var4, ClassLoader var5, ProtectionDomain var6);
```

**使用Unsafe创建TestHelloWorld对象：**

```java
// 使用Unsafe向JVM中注册com.anbai.sec.classloader.TestHelloWorld类
Class helloWorldClass = unsafe1.defineClass(TEST_CLASS_NAME, TEST_CLASS_BYTES, 0, TEST_CLASS_BYTES.length);
```

或调用需要传入类加载器和保护域的方法：

```java
// 获取系统的类加载器
ClassLoader classLoader = ClassLoader.getSystemClassLoader();

// 创建默认的保护域
ProtectionDomain domain = new ProtectionDomain(
    new CodeSource(null, (Certificate[]) null), null, classLoader, null
);

// 使用Unsafe向JVM中注册com.anbai.sec.classloader.TestHelloWorld类
Class helloWorldClass = unsafe1.defineClass(
    TEST_CLASS_NAME, TEST_CLASS_BYTES, 0, TEST_CLASS_BYTES.length, classLoader, domain
);
```

`Unsafe`还可以通过`defineAnonymousClass`方法创建内部类，这里不再多做测试。

**注意：**

这个实例仅适用于`Java 8`以前的版本如果在`Java 8`中应该使用应该调用需要传类加载器和保护域的那个方法。`Java 11`开始`Unsafe`类已经把`defineClass`方法移除了(`defineAnonymousClass`方法还在)，虽然可以使用`java.lang.invoke.MethodHandles.Lookup.defineClass`来代替，但是`MethodHandles`只是间接的调用了`ClassLoader`的`defineClass`，所以一切也就回到了`ClassLoader`。



### Java文件系统

#### 基础知识

众所周知Java是一个跨平台的语言，不同的操作系统有着完全不一样的文件系统和特性。JDK会根据不同的操作系统(`AIX,Linux,MacOSX,Solaris,Unix,Windows`)编译成不同的版本。

在Java语言中对文件的任何操作最终都是通过`JNI`调用`C语言`函数实现的。Java为了能够实现跨操作系统对文件进行操作抽象了一个叫做FileSystem的对象出来，不同的操作系统只需要实现起抽象出来的文件操作方法即可实现跨平台的文件操作了。

同的操作系统有不一样的文件系统,例如`Windows`和`Unix`就是两种不一样的文件系统： `java.io.UnixFileSystem`、`java.io.WinNTFileSystem`。

Java只不过是实现了对文件操作的封装而已，最终读写文件的实现都是通过调用native方法实现的。

不过需要特别注意一下几点：

1. 并不是所有的文件操作都在`java.io.FileSystem`中定义,文件的读取最终调用的是`java.io.FileInputStream#read0、readBytes`、`java.io.RandomAccessFile#read0、readBytes`,而写文件调用的是`java.io.FileOutputStream#writeBytes`、`java.io.RandomAccessFile#write0`。
2. Java有两类文件系统API！一个是基于`阻塞模式的IO`的文件系统，另一是JDK7+基于`NIO.2`的文件系统。



Java 7提出了一个基于NIO的文件系统，这个NIO文件系统和阻塞IO文件系统两者是完全独立的。`java.nio.file.spi.FileSystemProvider`对文件的封装和`java.io.FileSystem`同理。

NIO的文件操作在不同的系统的最终实现类也是不一样的，比如Mac的实现类是: `sun.nio.fs.UnixNativeDispatcher`,而Windows的实现类是`sun.nio.fs.WindowsNativeDispatcher`。

合理的利用NIO文件系统这一特性我们可以绕过某些只是防御了`java.io.FileSystem`的`WAF`/`RASP`



Java内置的文件读取方式大概就是这三种方式，其他的文件读取API可以说都是对这几种方式的封装而已：

FileInputStream，FileOutputStream，RandomAccessFile，FileSystemProvider

####  Java 文件名空字节截断漏洞

究其根本是Java在调用文件系统(C实现)读写文件时导致的漏洞，并不是Java本身的安全问题。

2013年9月10日发布的`Java SE 7 Update 40`修复了空字节截断这个历史遗留问题。此次更新在`java.io.File`类中添加了一个`isInvalid`方法，专门检测文件名中是否包含了空字节。

受空字节截断影响的JDK版本范围:`JDK<1.7.40`

JDK1.6虽然JDK7修复之后发布了数十个版本，但是并没有任何一个版本修复过这个问题



```java
public class FileNullBytes {

    public static void main(String[] args) {
        try {
            String           fileName = "/tmp/null-bytes.txt\u0000.jpg";
            FileOutputStream fos      = new FileOutputStream(new File(fileName));
            fos.write("Test".getBytes());
            fos.flush();
            fos.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

}
```

最终创建：/tmp/null-bytes.txt



###  Java本地命令执行

####  Runtime命令执行调用链

`Runtime.exec(xxx)`调用链如下:

```java
java.lang.UNIXProcess.<init>(UNIXProcess.java:247)
java.lang.ProcessImpl.start(ProcessImpl.java:134)
java.lang.ProcessBuilder.start(ProcessBuilder.java:1029)
java.lang.Runtime.exec(Runtime.java:620)
java.lang.Runtime.exec(Runtime.java:450)
java.lang.Runtime.exec(Runtime.java:347)
org.apache.jsp.runtime_002dexec2_jsp._jspService(runtime_002dexec2_jsp.java:118)
```

通过观察整个调用链我们可以清楚的看到`exec`方法并不是命令执行的最终点，执行逻辑大致是：

1. `Runtime.exec(xxx)`
2. `java.lang.ProcessBuilder.start()`
3. `new java.lang.UNIXProcess(xxx)`
4. `UNIXProcess`构造方法中调用了`forkAndExec(xxx)` native方法。
5. `forkAndExec`调用操作系统级别`fork`->`exec`(*nix)/`CreateProcess`(Windows)执行命令并返回`fork`/`CreateProcess`的`PID`。

有了以上的调用链分析我们就可以深刻的理解到Java本地命令执行的深入逻辑了，切记`Runtime`和`ProcessBuilder`并不是程序的最终执行点!从而绕过RASP

####  反射Runtime命令执行



####  ProcessBuilder命令执行



####  UNIXProcess/ProcessImpl



####  反射UNIXProcess/ProcessImpl执行本地命令





####  forkAndExec命令执行-Unsafe+反射+Native方法调用



####  JNI命令执行





### JDBC

#### JDBC连接过程

Java通过`java.sql.DriverManager`来管理所有数据库的驱动注册，所以如果想要建立数据库连接需要先在`java.sql.DriverManager`中注册对应的驱动类，然后调用`getConnection`方法才能连接上数据库。

JDBC定义了一个叫`java.sql.Driver`的接口类负责实现对数据库的连接，所有的数据库驱动包都必须实现这个接口才能够完成数据库的连接操作。`java.sql.DriverManager.getConnection(xx)`其实就是间接的调用了`java.sql.Driver`类的`connect`方法实现数据库连接的。数据库连接成功后会返回一个叫做`java.sql.Connection`的数据库连接对象，一切对数据库的查询操作都将依赖于这个`Connection`对象。

JDBC连接数据库的一般步骤:

1. 注册驱动，`Class.forName("数据库驱动的类名")`。
2. 获取连接，`DriverManager.getConnection(xxx)`。

#### JDBC数据库配置存储位置

1. 传统的Web应用

传统的Web应用的数据库配置信息一般都是存放在`WEB-INF`目录下的`*.properties`、`*.yml`、`*.xml`中的,如果是`Spring Boot`项目的话一般都会存储在jar包中的`src/main/resources/`目录下。常见的存储数据库配置信息的文件路径如：`WEB-INF/applicationContext.xml`、`WEB-INF/hibernate.cfg.xml`、`WEB-INF/jdbc/jdbc.properties`



2. DataSource

定义一个`DataSource Bean`用于配置和初始化数据源对象，在这个对象里，或者xxx.properties中

SpringBoot的数据库配置在application.properties或application.yml中

#### 为什么需要Class.forName

**很多人不理解为什么第一步必须是`Class.forName(CLASS_NAME);// 注册JDBC驱动类`，因为他们永远不会跟进驱动包去一探究竟。**

实际上这一步是利用了Java反射+类加载机制往`DriverManager`中注册了驱动包！

![image-20191208225820692](https://javasec.org/images/image-20191208225820692.png)



#### 为什么Class.forName又可以省去

这里又利用了Java的一大特性:`Java SPI(Service Provider Interface)`，因为`DriverManager`在初始化的时候会调用`java.util.ServiceLoader`类提供的SPI机制，Java会自动扫描jar包中的`META-INF/services`目录下的文件，并且还会自动的`Class.forName(文件中定义的类)`，这也就解释了为什么不需要`Class.forName`也能够成功连接数据库的原因了。

**Mysql驱动包示例:**

![image-20191208232329364](https://javasec.org/images/image-20191208232329364.png)



#### DataSource

在真实的Java项目中通常不会使用原生的`JDBC`的`DriverManager`去连接数据库，而是使用数据源(`javax.sql.DataSource`)来代替`DriverManager`管理数据库的连接。一般情况下在Web服务启动时候会预先定义好数据源，有了数据源程序就不再需要编写任何数据库连接相关的代码了，直接引用`DataSource`对象即可获取数据库连接了。

常见的数据源有：`DBCP`、`C3P0`、`Druid`、`Mybatis DataSource`，他们都实现于`javax.sql.DataSource`接口。



在Spring MVC中我们可以自由的选择第三方数据源，通常我们会定义一个`DataSource Bean`用于配置和初始化数据源对象，然后在Spring中就可以通过Bean注入的方式获取数据源对象了。

**在基于XML配置的SpringMVC中配置数据源:**

```xml
<bean id="dataSource" class="com.alibaba.druid.pool.DruidDataSource" init-method="init" destroy-method="close">
        <property name="url" value="${jdbc.url}"/>
        <property name="username" value="${jdbc.username}"/>
        <property name="password" value="${jdbc.password}"/>
        ....
        />
```

Spring的`property-placeholder`制定了一个`properties`文件，使用`${jdbc.username}`其实会自动自定义的properties配置文件中的配置信息。

```xml
<context:property-placeholder location="classpath:/config/jdbc.properties"/>
```

`jdbc.properties`内容：

```java
jdbc.driver=com.mysql.jdbc.Driver
jdbc.url=jdbc:mysql://localhost:3306/mysql?autoReconnect=true&zeroDateTimeBehavior=round&useUnicode=true&characterEncoding=UTF-8&useOldAliasMetadataBehavior=true&useOldAliasMetadataBehavior=true&useSSL=false
jdbc.username=root
jdbc.password=root
```

在Spring中我们只需要通过引用这个Bean就可以获取到数据源了，比如在Spring JDBC中通过注入数据源(`ref="dataSource"`)就可以获取到上面定义的`dataSource`。

```xml
<!-- jdbcTemplate Spring JDBC 模版 -->
<bean id="jdbcTemplate" class="org.springframework.jdbc.core.JdbcTemplate" abstract="false" lazy-init="false">
  <property name="dataSource" ref="dataSource"/>
</bean>
```

**SpringBoot配置数据源：**

在SpringBoot中只需要在`application.properties`或`application.yml`中定义`spring.datasource.xxx`即可完成DataSource配置。

```java
spring.datasource.url=jdbc:mysql://localhost:3306/mysql?autoReconnect=true&zeroDateTimeBehavior=round&useUnicode=true&characterEncoding=UTF-8&useOldAliasMetadataBehavior=true&useOldAliasMetadataBehavior=true&useSSL=false
spring.datasource.username=root
spring.datasource.password=root
spring.datasource.type=com.alibaba.druid.pool.DruidDataSource
spring.datasource.driver-class-name=com.mysql.jdbc.Driver
```



#### Spring 数据源 Hack

https://javasec.org/javase/JDBC/DataSource.html#spring-%E6%95%B0%E6%8D%AE%E6%BA%90hack

通过注册好的数据源直接操纵数据库





### URLConnection

在java中，Java抽象出来了一个`URLConnection`类，它用来表示应用程序以及与URL建立通信连接的所有类的超类，通过`URL`类中的`openConnection`方法获取到`URLConnection`的类对象。

Java中URLConnection支持的协议可以在`sun.net.www.protocol`看到。

`gopher`实际在jdk8版本以后被阉割了

java中默认对(http|https)做了一些事情，比如:

- 默认启用了透明NTLM认证
- 默认跟随跳转

关于NTLM认证的过程这边不在复述，大家可以看该文章[《Ghidra 从 XXE 到 RCE》](https://xlab.tencent.com/cn/2019/03/18/ghidra-from-xxe-to-rce/) 默认跟随跳转这其中有一个坑点，就是

![follow_redirect.jpg](https://javasec.org/images/follow_redirect.jpg)

它会对跟随跳转的url进行协议判断，所以Java的SSRF漏洞利用方式整体比较有限。





### JNI

见java_jni笔记


```java
package com.anbai.sec.cmd;

import java.io.File;
import java.lang.reflect.Method;

/**
 * Creator: yz
 * Date: 2019/12/8
 */
public class CommandExecutionTest {

    private static final String COMMAND_CLASS_NAME = "com.anbai.sec.cmd.CommandExecution";

    /**
     * JDK1.5编译的com.anbai.sec.cmd.CommandExecution类字节码,
     * 只有一个public static native String exec(String cmd);的方法
     */
    private static final byte[] COMMAND_CLASS_BYTES = new byte[]{
            -54, -2, -70, -66, 0, 0, 0, 49, 0, 15, 10, 0, 3, 0, 12, 7, 0, 13, 7, 0, 14, 1,
            0, 6, 60, 105, 110, 105, 116, 62, 1, 0, 3, 40, 41, 86, 1, 0, 4, 67, 111, 100,
            101, 1, 0, 15, 76, 105, 110, 101, 78, 117, 109, 98, 101, 114, 84, 97, 98, 108,
            101, 1, 0, 4, 101, 120, 101, 99, 1, 0, 38, 40, 76, 106, 97, 118, 97, 47, 108, 97,
            110, 103, 47, 83, 116, 114, 105, 110, 103, 59, 41, 76, 106, 97, 118, 97, 47, 108,
            97, 110, 103, 47, 83, 116, 114, 105, 110, 103, 59, 1, 0, 10, 83, 111, 117, 114,
            99, 101, 70, 105, 108, 101, 1, 0, 21, 67, 111, 109, 109, 97, 110, 100, 69, 120,
            101, 99, 117, 116, 105, 111, 110, 46, 106, 97, 118, 97, 12, 0, 4, 0, 5, 1, 0, 34,
            99, 111, 109, 47, 97, 110, 98, 97, 105, 47, 115, 101, 99, 47, 99, 109, 100, 47, 67,
            111, 109, 109, 97, 110, 100, 69, 120, 101, 99, 117, 116, 105, 111, 110, 1, 0, 16,
            106, 97, 118, 97, 47, 108, 97, 110, 103, 47, 79, 98, 106, 101, 99, 116, 0, 33, 0,
            2, 0, 3, 0, 0, 0, 0, 0, 2, 0, 1, 0, 4, 0, 5, 0, 1, 0, 6, 0, 0, 0, 29, 0, 1, 0, 1,
            0, 0, 0, 5, 42, -73, 0, 1, -79, 0, 0, 0, 1, 0, 7, 0, 0, 0, 6, 0, 1, 0, 0, 0, 7, 1,
            9, 0, 8, 0, 9, 0, 0, 0, 1, 0, 10, 0, 0, 0, 2, 0, 11
    };

    public static void main(String[] args) {
        String cmd = "ifconfig";// 定于需要执行的cmd

        try {
            ClassLoader loader = new ClassLoader(CommandExecutionTest.class.getClassLoader()) {
                @Override
                protected Class<?> findClass(String name) throws ClassNotFoundException {
                    try {
                        return super.findClass(name);
                    } catch (ClassNotFoundException e) {
                        return defineClass(COMMAND_CLASS_NAME, COMMAND_CLASS_BYTES, 0, COMMAND_CLASS_BYTES.length);
                    }
                }
            };

            // 测试时候换成自己编译好的lib路径
            File libPath = new File("/Users/yz/IdeaProjects/javaweb-sec/javaweb-sec-source/javase/src/main/java/com/anbai/sec/cmd/libcmd.jnilib");

            // load命令执行类
            Class commandClass = loader.loadClass("com.anbai.sec.cmd.CommandExecution");

            // 可以用System.load也加载lib也可以用反射ClassLoader加载,如果loadLibrary0
            // 也被拦截了可以换java.lang.ClassLoader$NativeLibrary类的load方法。
//            System.load("/Users/yz/IdeaProjects/javaweb-sec/javaweb-sec-source/javase/src/main/java/com/anbai/sec/cmd/libcmd.jnilib/libcmd.jnilib");
            Method loadLibrary0Method = ClassLoader.class.getDeclaredMethod("loadLibrary0", Class.class, File.class);
            loadLibrary0Method.setAccessible(true);
            loadLibrary0Method.invoke(loader, commandClass, libPath);

            String content = (String) commandClass.getMethod("exec", String.class).invoke(null, cmd);
            System.out.println(content);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

}
```





### Java Agent机制

`JDK1.5`开始，`Java`新增了`Instrumentation(Java Agent API)`和`JVMTI(JVM Tool Interface)`功能，允许`JVM`在加载某个`class文件`之前对其字节码进行修改，同时也支持对已加载的`class(类字节码)`进行重新加载(`Retransform`)。

利用`Java Agent`这一特性衍生出了`APM(Application Performance Management，应用性能管理)`、`RASP(Runtime application self-protection，运行时应用自我保护)`、`IAST(Interactive Application Security Testing，交互式应用程序安全测试)`等相关产品，它们都无一例外的使用了`Instrumentation/JVMTI`的`API`来实现动态修改`Java类字节码`并插入监控或检测代码。

**`Java Agent`有两种运行模式：**

1. 启动`Java程序`时添加`-javaagent(Instrumentation API实现方式)`或`-agentpath/-agentlib(JVMTI的实现方式)`参数，如`java -javaagent:/data/XXX.jar LingXeTest`。
2. `JDK1.6`新增了`attach(附加方式)`方式，可以对运行中的`Java进程`附加`Agent`。

这两种运行方式的最大区别在于第一种方式只能在程序启动时指定`Agent`文件，而`attach`方式可以在`Java程序`运行后根据`进程ID`动态注入`Agent`到`JVM`。



Java Agent和普通的Java类并没有任何区别，普通的Java程序中规定了`main`方法为程序入口，而Java Agent则将`premain`（Agent模式）和`agentmain`（Attach模式）作为了Agent程序的入口，两则所接受的参数是完全一致的，如下：

```java
public static void premain(String args, Instrumentation inst) {}
public static void agentmain(String args, Instrumentation inst) {}
```

Java Agent还限制了我们必须以jar包的形式运行或加载，我们必须将编写好的Agent程序打包成一个jar文件。除此之外，Java Agent还强制要求了所有的jar文件中必须包含`/META-INF/MANIFEST.MF`文件，且该文件中必须定义好`Premain-Class`（Agent模式）或`Agent-Class:`（Agent模式）配置，如：

```java
Premain-Class: com.anbai.sec.agent.CrackLicenseAgent
Agent-Class: com.anbai.sec.agent.CrackLicenseAgent
```

**如果我们需要修改已经被JVM加载过的类的字节码，那么还需要设置在`MANIFEST.MF`中添加`Can-Retransform-Classes: true`或`Can-Redefine-Classes: true`。**



#### Instrumentation

`java.lang.instrument.Instrumentation`是监测运行在`JVM`程序的`Java API`，利用`Instrumentation`我们可以实现如下功能：

1. 动态添加或移除自定义的`ClassFileTransformer`（`addTransformer/removeTransformer`），JVM会在类加载时调用Agent中注册的`ClassFileTransformer`；
2. 动态修改`classpath`（`appendToBootstrapClassLoaderSearch`、`appendToSystemClassLoaderSearch`），将Agent程序添加到`BootstrapClassLoader`和`SystemClassLoaderSearch`（对应的是`ClassLoader类的getSystemClassLoader方法`，默认是`sun.misc.Launcher$AppClassLoader`）中搜索；
3. 动态获取所有`JVM`已加载的类(`getAllLoadedClasses`)；
4. 动态获取某个类加载器已实例化的所有类(`getInitiatedClasses`)。
5. 重定义某个已加载的类的字节码(`redefineClasses`)。
6. 动态设置`JNI`前缀(`setNativeMethodPrefix`)，可以实现Hook native方法。
7. 重新加载某个已经被JVM加载过的类字节码`retransformClasses`)。

**`Instrumentation`类方法如下：**

![07EC4F97-CD49-41E6-95CE-FEB000325E33](https://javasec.org/images/07EC4F97-CD49-41E6-95CE-FEB000325E33.png)





#### ClassFileTransformer

`java.lang.instrument.ClassFileTransformer`是一个转换类文件的代理接口，我们可以在获取到`Instrumentation`对象后通过`addTransformer`方法添加自定义类文件转换器。

示例中我们使用了`addTransformer`注册了一个我们自定义的`Transformer`到`Java Agent`，当有新的类被`JVM`加载时`JVM`会自动回调用我们自定义的`Transformer`类的`transform`方法，传入该类的`transform`信息(`类名、类加载器、类字节码`等)，我们可以根据传入的类信息决定是否需要修改类字节码，修改完字节码后我们将新的类字节码返回给`JVM`，`JVM`会验证类和相应的修改是否合法，如果符合类加载要求`JVM`会加载我们修改后的类字节码。

**重写`transform`方法需要注意以下事项：**

1. `ClassLoader`如果是被`Bootstrap ClassLoader(引导类加载器)`所加载那么`loader`参数的值是空。
2. 修改类字节码时需要特别注意插入的代码在对应的`ClassLoader`中可以正确的获取到，否则会报`ClassNotFoundException`，比如修改`java.io.FileInputStream(该类由Bootstrap ClassLoader加载)`时插入了我们检测代码，那么我们将必须保证`FileInputStream`能够获取到我们的检测代码类。
3. `JVM`类名的书写方式路径方式：`java/lang/String`而不是我们常用的类名方式：`java.lang.String`。
4. 类字节必须符合`JVM`校验要求，如果无法验证类字节码会导致`JVM`崩溃或者`VerifyError(类验证错误)`。
5. 如果修改的是`retransform`类(修改已被`JVM`加载的类)，修改后的类字节码不得`新增方法`、`修改方法参数`、`类成员变量`。
6. `addTransformer`时如果没有传入`retransform`参数(默认是`false`)就算`MANIFEST.MF`中配置了`Can-Redefine-Classes: true`而且手动调用了`retransformClasses`方法也一样无法`retransform`。
7. 卸载`transform`时需要使用创建时的`Instrumentation`实例。



####  Agent 实现破解License示例



## java web

### Servlet

`Servlet`是在 `Java Web`容器中运行的`小程序`,通常我们用`Servlet`来处理一些较为复杂的服务器端的业务逻辑。`Servlet`是`Java EE`的核心,也是所有的MVC框架的实现的根本！

#### 基于Web.xml配置

`Servlet3.0` 之前的版本都需要在`web.xml` 中配置`servlet标签`，`servlet标签`是由`servlet`和`servlet-mapping`标签组成的,两者之间通过在`servlet`和`servlet-mapping`标签中同样的`servlet-name`名称来实现关联的。

#### Servlet的定义

定义一个 Servlet 很简单，只需要继承`javax.servlet.http.HttpServlet`类并重写`doXXX`(如`doGet、doPost`)方法或者`service`方法就可以了，其中需要注意的是重写`HttpServlet`类的`service`方法可以获取到上述七种Http请求方法的请求。

**javax.servlet.http.HttpServlet：**

在写`Servlet`之前我们先了解下`HttpServlet`,`javax.servlet.http.HttpServlet`类继承于`javax.servlet.GenericServlet`，而`GenericServlet`又实现了`javax.servlet.Servlet`和`javax.servlet.ServletConfig`。`javax.servlet.Servlet`接口中只定义了`servlet`基础生命周期方法：`init(初始化)`、`getServletConfig(配置)`、`service(服务)`、`destroy(销毁)`,而`HttpServlet`不仅实现了`servlet`的生命周期并通过封装`service`方法抽象出了`doGet/doPost/doDelete/doHead/doPut/doOptions/doTrace`方法用于处理来自客户端的不一样的请求方式，我们的Servlet只需要重写其中的请求方法或者重写`service`方法即可实现`servlet`请求处理。

**javax.servlet.http.HttpServlet类:**

![img](https://javasec.org/images/14.png)

#### **TestServlet示例代码:**

```java
package com.anbai.sec.servlet;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;

/**
 * Creator: yz
 * Date: 2019/12/14
 */
// 如果使用注解方式请取消@WebServlet注释并注释掉web.xml中TestServlet相关配置
//@WebServlet(name = "TestServlet", urlPatterns = {"/TestServlet"})
public class TestServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
        doPost(request, response);
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
        PrintWriter out = response.getWriter();
        out.println("Hello World~");
        out.flush();
        out.close();
    }

}
```

**请求`TestServlet`示例:**

![image-20191214150238924](https://javasec.org/images/image-20191214150238924.png)

#### Servlet Web.xml配置

定义好了Servlet类以后我们需要在`web.xml`中配置servlet标签才能生效。

**基于配置实现的Servlet：**

![image-20191214142745856](https://javasec.org/images/image-20191214142745856.png)

#### Servlet 3.0 基于注解方式配置

**基于注解的Servlet:**

值得注意的是在 Servlet 3.0 之后( Tomcat7+)可以使用注解方式配置 Servlet 了,在任意的Java类添加`javax.servlet.annotation.WebServlet`注解即可。

基于注解的方式配置Servlet实质上是对基于`web.xml`方式配置的简化，极大的简化了Servlet的配置方式，但是也提升了对Servlet配置管理的难度，因为我们不得不去查找所有包含了`@WebServlet`注解的类来寻找Servlet的定义，而不再只是查看`web.xml`中的`servlet`标签配置。

![15](https://javasec.org/images/15.png)

#### Servlet 3.0 特性

1. 新增动态注册`Servlet`、`Filter` 和`Listener`的API(`addServlet`、`addFilter`、`addListener`)。
2. 新增`@WebServlet`、`@WebFilter`、`@WebInitParam`、`@WebListener`、`@MultipartConfig`注解。
3. 文件上传支持，`request.getParts()`。
4. `非阻塞 IO`，添加`异步 IO`。
5. 可插拔性(`web-fragment.xml`、`ServletContainerInitializer`)。



### JSP基础

现代的MVC框架(如：`Spring MVC 5.x`)已经完全抛弃了`JSP`技术，采用了`模板引擎(如：Freemark)`或者`RESTful`的方式来实现与客户端的交互工作,或许某一天`JSP`技术也将会随着产品研发的迭代而彻底消失。

#### JSP 三大指令

1. `<%@ page ... %>` 定义网页依赖属性，比如脚本语言、error页面、缓存需求等等
2. `<%@ include ... %>` 包含其他文件（静态包含）
3. `<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>` 引入标签库的定义

#### JSP 表达式(EL)

`EL表达式`(`Expression Language`)语言,常用于在jsp页面中获取请求中的值，如获取在Servlet中设置的`Attribute`:`${名称}`。使用EL表达式可以实现命令执行，我们将会在后续EL表达式章节中详细讲解。

#### JSP 标准标签库(JSTL)

JSP标准标签库（JSTL）是一个JSP标签集合，它封装了JSP应用的通用核心功能。

JSTL支持通用的、结构化的任务，比如迭代，条件判断，XML文档操作，国际化标签，SQL标签。 除了这些，它还提供了一个框架来使用集成JSTL的自定义标签。

#### JSP 九大对象

从本质上说 JSP 就是一个Servlet，JSP 引擎在调用 JSP 对应的 jspServlet 时，会传递或创建 9 个与 web 开发相关的对象供 jspServlet 使用。 JSP 技术的设计者为便于开发人员在编写 JSP 页面时获得这些 web 对象的引用，特意定义了 9 个相应的变量，开发人员在JSP页面中通过这些变量就可以快速获得这 9 大对象的引用。

如下：

| 变量名      | 类型                | 作用                                        |
| ----------- | ------------------- | ------------------------------------------- |
| pageContext | PageContext         | 当前页面共享数据，还可以获取其他8个内置对象 |
| request     | HttpServletRequest  | 客户端请求对象，包含了所有客户端请求信息    |
| session     | HttpSession         | 请求会话                                    |
| application | ServletContext      | 全局对象，所有用户间共享数据                |
| response    | HttpServletResponse | 响应对象，主要用于服务器端设置响应信息      |
| page        | Object              | 当前Servlet对象,`this`                      |
| out         | JspWriter           | 输出对象，数据输出到页面上                  |
| config      | ServletConfig       | Servlet的配置对象                           |
| exception   | Throwable           | 异常对象                                    |

### JSP,Servlet之间的关系

JSP、JSPX 文件是可以直接被 Java 容器直接解析的动态脚本， jsp 和其他脚本语言无异，不但可以用于页面数据展示，也可以用来处理后端业务逻辑。

从本质上说 JSP 就是一个`Servlet` ，因为 jsp 文件最终会被编译成 class 文件，而这个 class 文件实际上就是一个特殊的`Servlet` 。

JSP文件会被编译成一个java类文件，如`index.jsp`在Tomcat中`Jasper`编译后会生成`index_jsp.java`和`index_jsp.class`两个文件。而`index_jsp.java` 继承于`HttpJspBase`类，`HttpJspBase`是一个实现了`HttpJspPage`接口并继承了`HttpServlet`的标准的`Servlet`，`__jspService`方法其实是`HttpJspPage`接口方法，类似于`Servlet`中的`service`方法，这里的`__jspService`方法其实就是`HttpJspBase`的`service`方法调用。

![img](https://javasec.org/images/17.png)



### Filter



`javax.servlet.Filter`是`Servlet2.3`新增的一个特性,主要用于过滤URL请求，通过Filter我们可以实现URL请求资源权限验证、用户登陆检测等功能。

Filter是一个接口，实现一个Filter只需要重写`init`、`doFilter`、`destroy`方法即可，其中过滤逻辑都在`doFilter`方法中实现。

`Filter`的配置类似于`Servlet`，由`<filter>`和`<filter-mapping>`两组标签组成，如果Servlet版本大于3.0同样可以使用注解的方式配置Filter。

**基于注解实现的Filter示例:**

![18](https://javasec.org/images/18.png)





## 容器安全

### tomcat

#### manager和host-manager

Tomcat在默认情况下提供了一些管理后台，不同的管理后台提供了不同的功能，这些管理后台使用了**Basic认证**的方式进行权限校验，如果暴露在互联网上，将存在遭到暴力破解的安全风险。

其中主要包含两种：**Manager**以及**Host Manager**。

如果得到了manager的权限，那么我们可以部署我们的应用，也就是直接getshell



- /manager/html/：提供HTML格式的管理页面
- /manager/status/：提供服务器状态（Server Status）页面
- /manager/jmxproxy/：提供JMX proxy 接口
- /manager/text/：提供纯文本页面



如果想要使用这些功能，则需要在`$CATALINA_BASE/conf/tomcat-users.xml`中配置相关的用户信息，包括用户名、密码、用户角色，来对使用这些功能的用户进行身份鉴别和权限验证。

在 manager 项目中的web.xml中我们可以看到能够使用的这些角色：

- manager-gui：能够访问`/manager/html/`的管理界面。
- manager-script：能够访问 `/manager/text/` 以及`/manager/status/`界面。
- manager-jmx：能够访问`/manager/jmxproxy/` 以及`/manager/status/`界面。
- manager-status：能够访问 `/manager/status/`的Server Status界面。



#### Tomcat-AJP

AJP（Apache JServer Protocol) 协议最初是由 Gal Shachor 设计。对于Web服务器与Servlet容器通信来讲，最主要目的是：

- 提高性能（主要是速度）。
- 添加对SSL的支持。

目前Tomcat中使用的版本均为AJP1.3，简称为ajp13。ajp13协议是面向数据包的。出于对性能的考虑，选择了以二进制格式传输，而不是更易读的纯文本。

在Tomcat的`server.xml` 中默认配置了两种连接器：

![image-20200925172240547](https://javasec.org/images/image-20200925172240547.png)

一种是使用的HTTP Connector，监听8080端口，还有一个AJP Connector，监听了8009端口。在Tomcat中这个协议的监听的一直都是默认开启的。



在 `AjpProcessor` 的 `prepareRequest()` 中，恶意攻击者可通过控制请求内容，为request对象任意的设置属性。

在`switch/case` 判断中,当`attributeCode=10` 时，将调用 `request.setAttribute` 方法存入。

![image-20201013155520803](https://javasec.org/images/image-20201013155520803.png)







https://javasec.org/java-web-container/Tomcat/TomcatAJP.html



## 代码审计

