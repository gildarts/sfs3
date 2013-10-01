<?php

echo $_SERVER['REQUEST_METHOD'].'<br/>';

file_put_contents('callback.txt',$_SERVER['REQUEST_METHOD']."\n".print_r($_REQUEST,TRUE));

var_dump($_REQUEST);
?>