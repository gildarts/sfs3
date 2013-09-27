<?php
require 'config.php';

sfs_check();

//unlink code
$uid = $_REQUEST['uid'];

$sql = "delete from ischool_account where uid=?";
$CONN -> Execute($sql,array($uid)) or trigger_error($CONN->ErrorMsg, E_USER_ERROR);

header("location: ".$_REQUEST['redirect']);
?>