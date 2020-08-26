# java反序列化

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





## 工具

 https://github.com/frohoff/ysoserial/ 



## 注意

 `Runtime.getRuntime().exec()`中不能使用管道符等bash需要的方法，工具： http://www.jackson-t.ca/runtime-exec-payloads.html 