<?php
$path = __DIR__."/../app/_cache/application.log";
if(is_file($path) && is_readable($path)){
    print(file_get_contents($path));
}
?>