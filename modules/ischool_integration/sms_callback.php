<?php

echo $_SERVER['REQUEST_METHOD'].'<br/>';

file_put_contents('callback.txt',$_SERVER['REQUEST_METHOD']."\n".$_SERVER['REQUEST_URI'].'\n'.print_r($_REQUEST,TRUE));

echo print_r($_SERVER);

var_dump($_REQUEST);
?>