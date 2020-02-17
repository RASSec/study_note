# php序列化的一些特性









```php
<?php
class test{
    public $my_static = 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
    public function __destruct()
    {
        echo $this->my_static;
    }

}
class tesa{

}
$a=str_replace("tesa","test",serialize(new tesa()));
unserialize($a);


结果:aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
```

