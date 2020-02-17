<?php
$hashfuc='md5';
$arg = trim(fgets(STDIN));
$len=strlen($arg);
while(1){
    
    $i = 0;
    while(++$i){
        if(substr($hashfuc($i), 0,$len ) === $arg){
            echo($i."\n". $hashfuc($i)."\n");
            exit();
        }
    }
}
?>