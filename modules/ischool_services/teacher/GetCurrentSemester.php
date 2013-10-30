<?php
require_once '../config.php';
require_once '../servicehelper.php'; //提供 Xml Service 的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();


begin_service_output(); //開始輸出資料。

$SchoolYear=curr_year();
$Semester=curr_seme();
	
$xml=<<<EOD
<Current>
   <SchoolYear>$SchoolYear</SchoolYear>
   <Semester>$Semester</Semester>
</Current>
EOD;
$xml=utf8($xml);
echo $xml;
end_service_output(); //完成輸出。
?>