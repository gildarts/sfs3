<?php

session_start();

$_SESSION['session'] = 'abcdef';

echo $_SESSION['session'];

?>