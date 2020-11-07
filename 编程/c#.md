# C#

C# 是类型安全的面向对象的语言

![从 C# 源代码到计算机执行](https://docs.microsoft.com/zh-cn/dotnet/csharp/getting-started/media/introduction-to-the-csharp-language-and-the-net-framework/net-architecture-relationships.png)

## C#的特殊代码

### 字符串



```csharp
string test="test";
Console.WriteLine($"Hello {test}");//Hello test
Console.WriteLine($"Hello {test.Length}");//Hello 4
string.TrimStart();
string.TrimEnd();
string.Trim();
"abc".Replace("abc","abc","");
"abc".ToUpper();
"abc".ToLower();
"abc".Contains("greetings");
"abc".StartsWith("greetings");
"abc".EndsWith("greetings");
```



### 数值运算

 `decimal` 类型的范围较小，但精度高于 `double`。

```c#
double a = 1.0;
double b = 3.0;
Console.WriteLine(a / b);

decimal c = 1.0M;
decimal d = 3.0M;
Console.WriteLine(c / d);
//0.333333333333333
//0.3333333333333333333333333333
```

数字中的 `M` 后缀指明了常数应如何使用 `decimal` 类型。



### foreach 和 列表

```java
var names = new List<string> { "<name>", "Ana", "Felipe" };
foreach (var name in names)
{
  Console.WriteLine($"Hello {name.ToUpper()}!");
}
```

### hello world

```c#
using System;

class Hello
{
    static void Main()
    {
        Console.WriteLine("Hello, World");
    }
}
```



### c#的类型系统

- 值类型
  - 简单类型
    - [有符号整型](https://docs.microsoft.com/zh-cn/dotnet/csharp/language-reference/builtin-types/integral-numeric-types)：`sbyte`、`short`、`int`、`long`
    - [无符号整型](https://docs.microsoft.com/zh-cn/dotnet/csharp/language-reference/builtin-types/integral-numeric-types)：`byte`、`ushort`、`uint`、`ulong`
    - [Unicode 字符](https://docs.microsoft.com/zh-cn/dotnet/standard/base-types/character-encoding-introduction)：`char`，表示 UTF-16 代码单元
    - [IEEE 二进制浮点](https://docs.microsoft.com/zh-cn/dotnet/csharp/language-reference/builtin-types/floating-point-numeric-types)：`float`、`double`
    - [高精度十进制浮点数](https://docs.microsoft.com/zh-cn/dotnet/csharp/language-reference/builtin-types/floating-point-numeric-types)：`decimal`
    - 布尔值：`bool`，表示布尔值（`true` 或 `false`）
  - 枚举类型
    - `enum E {...}` 格式的用户定义类型。 `enum` 类型是一种包含已命名常量的独特类型。 每个 `enum` 类型都有一个基础类型（必须是八种整型类型之一）。 `enum` 类型的值集与基础类型的值集相同。
  - 结构类型
    - 格式为 `struct S {...}` 的用户定义类型
  - 可以为 null 的值类型
    - 值为 `null` 的其他所有值类型的扩展
  - 元组值类型
    - 格式为 `(T1, T2, ...)` 的用户定义类型
- 引用类型
  - 类类型
    - 其他所有类型的最终基类：`object`
    - [Unicode 字符串](https://docs.microsoft.com/zh-cn/dotnet/standard/base-types/character-encoding-introduction)：`string`，表示 UTF-16 代码单元序列
    - 格式为 `class C {...}` 的用户定义类型
  - 接口类型
    - 格式为 `interface I {...}` 的用户定义类型
  - 数组类型
    - 一维、多维和交错。 例如：`int[]`、`int[,]` 和 `int[][]`
  - 委托类型
    - 格式为 `delegate int D(...)` 的用户定义类型



C# 程序使用*类型声明*创建新类型。 类型声明指定新类型的名称和成员。 用户可定义以下六种 C# 类型：类类型、结构类型、接口类型、枚举类型、委托类型和元组值类型。

- `class` 类型定义包含数据成员（字段）和函数成员（方法、属性及其他）的数据结构。 类类型支持单一继承和多形性，即派生类可以扩展和专门针对基类的机制。
- `struct` 类型定义包含数据成员和函数成员的结构，这一点与类类型相似。 不过，与类不同的是，结构是值类型，通常不需要进行堆分配。 结构类型不支持用户指定的继承，并且所有结构类型均隐式继承自类型 `object`。
- `interface` 类型将协定定义为一组已命名的公共成员。 实现 `interface` 的 `class` 或 `struct` 必须提供接口成员的实现代码。 `interface` 可以继承自多个基接口，`class` 和 `struct` 可以实现多个接口。
- `delegate` 类型表示引用包含特定参数列表和返回类型的方法。 通过委托，可以将方法视为可分配给变量并可作为参数传递的实体。 委托类同于函数式语言提供的函数类型。 它们还类似于其他一些语言中存在的“函数指针”概念。 与函数指针不同，委托是面向对象且类型安全的。

`class`、`struct`、`interface` 和 `delegate` 类型全部都支持泛型，因此可以使用其他类型对它们进行参数化。



可以为 null 的类型不需要单独定义。 对于所有不可以为 null 的类型 `T`，都有对应的可以为 null 的类型 `T?`，后者可以包含附加值 `null`。 例如，`int?` 是可保存任何 32 位整数或 `null` 值的类型，`string?` 是可以保存任何 `string` 或 `null` 值的类型。



## 数据库



### sqlite

#### 连接数据库

```c#
con = new SQLiteConnection("Data Source=MyDatabase.sqlite;Version=3;");
con.Open();
```



#### 执行sql语句

```c#
var cmd = new SQLiteCommand(con);
cmd.CommandText = sql;
cmd.ExecuteNonQuery();
```



#### 预处理sql语句

```c#
var cmd = new SQLiteCommand(db.con);
cmd.CommandText = "select role from account where username = @username and password = @password;";

cmd.Parameters.AddWithValue("@username", username);
cmd.Parameters.AddWithValue("@password", password);
cmd.Prepare();
SQLiteDataReader rdr = cmd.ExecuteReader();
while (rdr.Read()) {
    role=rdr.GetInt32(0);
    //要获取第二列就rdr.GetInt32(1);
    return true;
}
```

