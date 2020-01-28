# php实用脚本



## 查看可用函数

```php
<?php 
$arr = get_defined_functions()['internal'];

foreach ($arr as $key => $value) {
    if ( preg_match('/[\x00- 0-9\'"`$&.,|[{_defgops\x7F]+/i', $value) ){
        unset($arr[$key]);
        continue;
    }

    if ( strlen(count_chars(strtolower($value), 0x3)) > 0xd ){
        unset($arr[$key]);
        continue;
    }
}

var_dump($arr);
?>
```



## 查看可用字符

```php

$arr='';
for($i=0;$i<256;$i++)
{
    if(conditioin)
    {
        echo urlencode($arr[$i])."<br />";
    }
}
```



## 查看经过位操作后可获得字符

```php
$a="0123456789abcdefghijklmnopqrstuvwxyz";
$ope="^|&";
for($i=0;$i<strlen($a);$i++)
{
    for($j=0;$j<strlen($a);$j++)
    {   
        for($k=0;$k<strlen($ope);$k++)
        {   
            $tmp="'".$a[$i]."'".$ope[$k]."'".$a[$j]."';";
            echo $tmp."     ";
            eval("echo ".$tmp);
            echo "<br />";
        }
    }
}
for($j=0;$j<strlen($a);$j++)
{   
 
    $tmp="~'".$a[$j]."';";
    echo $tmp."     ";
    eval("echo ".$tmp);
    echo "<br />";
}
```

