# C#

C# 是类型安全的面向对象的语言

![从 C# 源代码到计算机执行](https://docs.microsoft.com/zh-cn/dotnet/csharp/getting-started/media/introduction-to-the-csharp-language-and-the-net-framework/net-architecture-relationships.png)

## 字符串

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

