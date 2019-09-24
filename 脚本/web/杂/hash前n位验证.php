<?php
$hashfuc='md5';
while(1){
    $arg = trim(fgets(STDIN));
    $i = 0;
    while(++$i){
        if(substr($hashfuc($i), 0, strlen($arg)) === $arg){
            echo($i."\n". $hashfuc($i)."\n");
            break;
        }
    }
}
?>