<?php
require_once '../config.php';
require_once '../servicehelper.php'; //提供 Xml Service 的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();

$sql = 
'select teacher_base.teacher_sn,name,sex,email,cell_phone 
from teacher_base left join teacher_connect on teacher_base.teacher_sn=teacher_connect.teacher_sn
where teacher_base.teacher_sn=?';

$result = $CONN-> execute($sql,array($_SESSION['session_tea_sn'])) or statement_error();

begin_service_output(); //開始輸出資料。

echo '<Body><Result><Info>';

while(list($teacher_sn,$name,$sex,$email,$cell_phone) = $result -> FetchRow()){
	echo "<ID>" . utf8($teacher_sn) . "</ID>";
	echo "<Name>" . utf8($name) . "</Name>";
	echo "<Gender>" . utf8($sex) . "</Gender>";
	echo "<Nickname/>";
	echo "<Email>" . utf8($email) . "</Email>";
	echo "<ContractPhone>" . utf8($cell_phone) . "</ContractPhone>";	
}

echo '</Info></Result></Body>';

end_service_output(); //完成輸出。
?>