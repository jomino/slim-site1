<?php
$path = __DIR__."/../app/_cache/application.log";
if(is_file($path) && is_readable($path)){
    print("<pre>");
    print(str_replace('\n',PHP_EOL,file_get_contents($path)));
    print("</pre>");
}else{
    print("<h3>no log !!</h3>");
}
?>