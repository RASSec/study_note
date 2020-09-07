# java反序列化



## 序列化的控制

### **Externalizable**

Externalizable接口继承了Serializable接口，同时添加了两个方法即writeExternal()和readExternal()，两者会在序列化和反序列化的过程中被自动调用，以便于执行一些特殊操作来实现过程控制。

由于Externalizable对象在默认情况下不保存它们的任何字段，所以**transient关键字只能和Serializable对象一起使用**。

### **transient关键字**

当我们正在操作的是一个Serializable对象，则所有序列化操作会自动执行，这时就可应用到transient（瞬时）关键字来逐个字段地关闭序列化，即说明指定字段内容在序列化中是不需要保存或恢复操作的。

### readObject 和 writeObject

通过重写这两个可以有效控制序列化过程

## serialVersionUID

只有序列化对象的serialVersionUID和类定义的serialVersionUID相同才可以反序列化

**具体的序列化过程是这样的**：序列化操作的时候系统会把当前类的serialVersionUID写入到序列化文件中，当反序列化时系统会去检测文件中的serialVersionUID，判断它是否与当前类的serialVersionUID一致，如果一致就说明序列化类的版本与当前类版本是一样的，可以反序列化成功，否则失败。

**serialVersionUID有两种显示的生成方式：**

一是默认的1L，比如：private static final long serialVersionUID = 1L；

二是根据类名、接口名、成员方法及属性等来生成一个64位的哈希字段，

## 反序列化代码

```java
package serialize;

import java.io.*;

public class Main {
    public static void main(String[] args) throws Exception {
        User user=new User();
        user.setName("leixiao");

        byte[] serializeData=serialize(user);
        FileOutputStream fout = new FileOutputStream("user.bin");
        fout.write(serializeData);
        fout.close();
        User user2=(User) unserialize(serializeData);
        System.out.println(user2.getName());
    }
    public static byte[] serialize(final Object obj) throws Exception {
        ByteArrayOutputStream btout = new ByteArrayOutputStream();
        ObjectOutputStream objOut = new ObjectOutputStream(btout);
        objOut.writeObject(obj);
        return btout.toByteArray();
    }
    public static Object unserialize(final byte[] serialized) throws Exception {
        ByteArrayInputStream btin = new ByteArrayInputStream(serialized);
        ObjectInputStream objIn = new ObjectInputStream(btin);
        return objIn.readObject();
    }
}
```



```java
package serialize;

import java.io.Serializable;

public class User implements Serializable{
    private String name;
    public void setName(String name) {
        this.name=name;
    }
    public String getName() {
        return name;
    }
}
```



## java反射

### 获取类对象

假设现在有一个User类

```java
package reflection;

public class User {
    private String name;

    public User(String name) {
        this.name=name;
    }
    public void setName(String name) {
        this.name=name;
    }
    public String getName() {
        return name;
    }
}
```

要获取该类对象一般有三种方法

- class.forName("reflection.User")
- User.class
- new User().getClass()
  最常用的是第一种，通过一个字符串即类的全路径名就可以得到类对象，另外两种方法依赖项太强

### 利用类对象创建对象

与new直接创建对象不同，反射是先拿到类对象，然后通过类对象获取构造器对象，再通过构造器对象创建一个对象

```java
package reflection;

import java.lang.reflect.*;

public class CreateObject {
    public static void main(String[] args) throws Exception {
        Class UserClass=Class.forName("reflection.User");
        Constructor constructor=UserClass.getConstructor(String.class);
        User user=(User) constructor.newInstance("leixiao");

        System.out.println(user.getName());
    }
}
```

| 方法                                               | 说明                                   |
| -------------------------------------------------- | -------------------------------------- |
| getConstructor(Class...<?> parameterTypes)         | 获得该类中与参数类型匹配的公有构造方法 |
| getConstructors()                                  | 获得该类的所有公有构造方法             |
| getDeclaredConstructor(Class...<?> parameterTypes) | 获得该类中与参数类型匹配的构造方法     |
| getDeclaredConstructors()                          | 获得该类所有构造方法                   |

### 通过反射调用方法

```java
package reflection;

import java.lang.reflect.*;

public class CallMethod {
    public static void main(String[] args) throws Exception {
        Class UserClass=Class.forName("reflection.User");

        Constructor constructor=UserClass.getConstructor(String.class);
        User user=(User) constructor.newInstance("leixiao");

        Method method = UserClass.getDeclaredMethod("setName", String.class);
        method.invoke(user, "l3yx");

        System.out.println(user.getName());
    }
}
```

| 方法                                                       | 说明                   |
| ---------------------------------------------------------- | ---------------------- |
| getMethod(String name, Class...<?> parameterTypes)         | 获得该类某个公有的方法 |
| getMethods()                                               | 获得该类所有公有的方法 |
| getDeclaredMethod(String name, Class...<?> parameterTypes) | 获得该类某个方法       |
| getDeclaredMethods()                                       | 获得该类所有方法       |

### 通过反射访问属性

```java
package reflection;

import java.lang.reflect.*;

public class AccessAttribute {
    public static void main(String[] args) throws Exception {
        Class UserClass=Class.forName("reflection.User");

        Constructor constructor=UserClass.getConstructor(String.class);
        User user=(User) constructor.newInstance("leixiao");

        Field field= UserClass.getDeclaredField("name");
        field.setAccessible(true);// name是私有属性，需要先设置可访问
        field.set(user, "l3yx");

        System.out.println(user.getName());
    }
}
```

| 方法                          | 说明                   |
| ----------------------------- | ---------------------- |
| getField(String name)         | 获得某个公有的属性对象 |
| getFields()                   | 获得所有公有的属性对象 |
| getDeclaredField(String name) | 获得某个属性对         |
| getDeclaredFields()           | 获得所有属性对象       |

### 利用java反射执行代码

```java
package reflection;

public class Exec {
    public static void main(String[] args) throws Exception {
        //java.lang.Runtime.getRuntime().exec("calc.exe");

        Class runtimeClass=Class.forName("java.lang.Runtime");
        Object runtime=runtimeClass.getMethod("getRuntime").invoke(null);// getRuntime是静态方法，invoke时不需要传入对象
        runtimeClass.getMethod("exec", String.class).invoke(runtime,"calc.exe");
    }
}
```

以上代码中,利用了Java的反射机制把我们的代码意图都利用字符串的形式进行体现，使得原本应该是字符串的属性，变成了代码执行的逻辑，而这个机制也是后续的漏洞使用的前提



## fastjson

[https://mntn0x.github.io/2020/04/07/Fastjson漏洞复现/#1-2-48前通用不出网payload](https://mntn0x.github.io/2020/04/07/Fastjson漏洞复现/#1-2-48前通用不出网payload)





## JNDI注入



## RMI

### 什么是RMI



Java远程方法调用，即Java RMI（Java Remote Method Invocation）是Java编程语言里，一种用于实现远程过程调用的应用程序编程接口。它使客户机上运行的程序可以调用远程服务器上的对象。远程方法调用特性使Java编程人员能够在网络环境中分布操作。RMI全部的宗旨就是尽可能简化远程接口对象的使用。



### RMI的通信模型

从方法调用角度来看，`RMI`要解决的问题，是让客户端对远程方法的调用可以相当于对本地方法的调用而屏蔽其中关于远程通信的内容，即使在远程上，也和在本地上是一样的。

从客户端-服务器模型来看，客户端程序直接调用服务端，两者之间是通过`JRMP`（ [Java Remote Method Protocol](https://en.wikipedia.org/wiki/Java_Remote_Method_Protocol)）协议通信，这个协议类似于HTTP协议，规定了客户端和服务端通信要满足的规范。

但是实际上，客户端只与代表远程主机中对象的`Stub`对象进行通信，丝毫不知道`Server`的存在。客户端只是调用`Stub`对象中的本地方法，`Stub`对象是一个本地对象，它实现了远程对象向外暴露的接口，也就是说它的方法和远程对象暴露的方法的签名是相同的。客户端认为它是调用远程对象的方法，实际上是调用`Stub`对象中的方法。**可以理解为`Stub`对象是远程对象在本地的一个代理**，当客户端调用方法的时候，`Stub`对象会将调用通过网络传递给远程对象。

从逻辑上来看，数据是在`Client`和`Server`之间横向流动的，但是实际上是从`Client`到`Stub`，然后从`Skeleton`到`Server`这样纵向流动的。



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200903203506.png)



![](https://raw.githubusercontent.com/Explorersss/photo/master/20200903202742.png)








### 重要问题

#### 数据的传递问题

我们都知道在`Java`程序中引用类型（不包括基本类型）的参数传递是按引用传递的，对于在同一个虚拟机中的传递时是没有问题的，因为的参数的引用对应的是同一个内存空间，但是对于分布式系统中，由于对象不再存在于同一个内存空间，虚拟机A的对象引用对于虚拟机B没有任何意义，那么怎么解决这个问题呢？

- 第一种：将引用传递更改为值传递，也就是将对象序列化为字节，然后使用该字节的副本在客户端和服务器之间传递，而且一个虚拟机中对该值的修改不会影响到其他主机中的数据；但是对象的序列化也有一个问题，就是对象的嵌套引用就会造成序列化的嵌套，这必然会导致数据量的激增，因此我们需要有选择进行序列化，在`Java`中一个对象如果能够被序列化，需要满足下面两个条件之一：

  - 是`Java`的基本类型；
  - 实现`java.io.Serializable`接口（`String`类即实现了该接口）；
  - 对于容器类，如果其中的对象是可以序列化的，那么该容器也是可以序列化的；
  - 可序列化的子类也是可以序列化的；

- 第二种：仍然使用引用传递，每当远程主机调用本地主机方法时，该调用还要通过本地主机查询该引用对应的对象，在任何一台机器上的改变都会影响原始主机上的数据，因为这个对象是共享的；

`RMI`中的参数传递和结果返回可以使用的三种机制（取决于数据类型）：

- 简单类型：按值传递，直接传递数据拷贝；
- 远程对象引用（实现了`Remote`接口）：以远程对象的引用传递；
- 远程对象引用（未实现`Remote`接口）：按值传递，通过序列化对象传递副本，本身不允许序列化的对象不允许传递给远程方法；



#### 远程对象的发现问题

在调用远程对象的方法之前需要一个远程对象的引用，如何获得这个远程对象的引用在`RMI`中是一个关键的问题，如果将远程对象的发现类比于`IP`地址的发现可能比较好理解一些。

在我们日常使用网络时，基本上都是通过域名来定位一个网站，但是实际上网络是通过`IP`地址来定位网站的，因此其中就需要一个映射的过程，域名系统（`DNS`）就是为了这个目的出现的，在域名系统中通过域名来查找对应的`IP`地址来访问对应的服务器。那么对应的，`IP`地址在这里就相当于远程对象的引用，而`DNS`则相当于一个**注册表**（Registry）。而域名在RMI中就相当于远程对象的标识符，客户端通过提供远程对象的标识符访问注册表，来得到远程对象的引用。这个标识符是类似`URL`地址格式的，它要满足的规范如下：

- 该名称是`URL`形式的，类似于`http`的`URL`，schema是rmi；
- 格式类似于`rmi://host:port/name`，`host`指明注册表运行的注解，`port`表明接收调用的端口，`name`是一个标识该对象的简单名称。
- 主机和端口都是可选的，如果省略主机，则默认运行在本地；如果端口也省略，则默认端口是**1099**；



### RMI服务端与客户端代码

实现RMI所需要的API

- java.rmi：提供客户端需要的类、接口和异常；
- java.rmi.server：提供服务端需要的类、接口和异常；
- java.rmi.registry：提供注册表的创建以及查找和命名远程对象的类、接口和异常；



#### 远程类定义

什么对象可以被客户端进行远程调用？这个问题从编程的角度来看，**实现了`java.rmi.Remote`接口的类或者继承了`java.rmi.Remote`接口的所有接口都是远程对象**。这些继承或者实现了该接口的类或者接口中定义了客户端可以访问的方法。这个远程对象中可能有很多个方法，但是**只有在远程接口中声明的方法才能从远程调用**

实现过程中的注意事项：

- 子接口的每个方法都**必须**声明抛出`java.rmi.RemoteException`异常，该异常是使用`RMI`时可能抛出的大多数异常的父类。
- 子接口的实现类应该直接或者间接继承`java.rmi.server.UnicastRemoteObject`类，该类提供了很多支持`RMI`的方法，具体来说，这些方法可以通过`JRMP`协议导出一个远程对象的引用，并通过动态代理构建一个可以和远程对象交互的`Stub`对象。具体的实现看如下的例子。



```java
import java.rmi.Remote;
import java.rmi.RemoteException;

public interface RMIInterface extends Remote {
    String hello() throws RemoteException;
}
```



```java
import java.io.IOException;
import java.rmi.RemoteException;
import java.rmi.server.UnicastRemoteObject;

public class RMIImpl extends UnicastRemoteObject implements RMIInterface {
    protected RMIImpl() throws RemoteException {
        super();
    }

    @Override
    public String hello() throws RemoteException {
        System.out.println("call hello().");
        return "this is hello().";
    }

}
```





#### 服务端

```java
import java.rmi.Naming;
import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;
public class RMIServer {
    public static String HOST = "100.100.1.5";
    public static int PORT = 1099;
    public static String RMI_PATH = "/hello";
    public static final String RMI_NAME = "rmi://" + HOST + ":" + PORT + RMI_PATH;
    public static void main(String[] args) {
        try {
            // 注册RMI端口
            Registry registry = LocateRegistry.createRegistry(PORT);

            // 创建一个服务
            RMIInterface rmiInterface = new RMIImpl();

            // 服务命名绑定
            Naming.rebind(RMI_NAME, rmiInterface);


            System.out.println("启动RMI服务在" + RMI_NAME);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


}
```



#### 客户端

```java
import java.rmi.Naming;
import java.rmi.registry.LocateRegistry;
import java.rmi.registry.Registry;

public class RMIClient {
    public static void main(String[] args) {
        try {
            // 获取服务注册器
            Registry registry = LocateRegistry.getRegistry("100.100.1.5", 1099);
            // 获取所有注册的服务
            String[] list = registry.list();
            for (String i : list) {
                System.out.println("已经注册的服务：" + i);
            }

            // 寻找RMI_NAME对应的RMI实例
            RMIInterface rt = (RMIInterface) Naming.lookup("rmi://100.100.1.5:1099/hello");

            // 调用Server的hello()方法,并拿到返回值.
            String result = rt.hello();

            System.out.println(result);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
```



#### 对应的流量包

https://github.com/Explorersss/photo/blob/master/rmi.pcapng?raw=true



### 参考

https://blog.csdn.net/lmy86263/article/details/72594760

https://y4er.com/post/java-rmi/



## 工具

 https://github.com/frohoff/ysoserial/ 



## 注意

 `Runtime.getRuntime().exec()`中不能使用管道符等bash需要的方法，工具： http://www.jackson-t.ca/runtime-exec-payloads.html 