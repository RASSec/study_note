# WSL

## 错误修复

### 4294967295

#### 解决方法一

```
参考的对象类型不支持尝试的操作。

[已退出进程，代码为 4294967295]
```

原因：profixier 和 wsl 发生了某种冲突

https://github.com/microsoft/WSL/issues/4177#issuecomment-597736482

>Thanks for the info.
>
>We have reproduced this issue.
>Apparently, wsl.exe displays this error if Winsock LSP DLL gets loaded into its process.
>
>The easiest solution is to use WSCSetApplicationCategory WinAPI call for wsl.exe to prevent this.
>Under the hood the call creates an entry for wsl.exe at HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Services\WinSock2\Parameters\AppId_Catalog
>This tells Windows not to load LSP DLLs into wsl.exe process.
>
>We have a tool that can make this call:
>[www.proxifier.com/tmp/Test20200228/NoLsp.exe](http://www.proxifier.com/tmp/Test20200228/NoLsp.exe)
>
>Please just run as admin with the full path to wsl.exe as the parameter:
>NoLsp.exe c:\windows\system32\wsl.exe
>
>This has fixed the problem in my case.
>
>Please let me know how it works for you.

#### 解决方法2

netsh winsock reset

