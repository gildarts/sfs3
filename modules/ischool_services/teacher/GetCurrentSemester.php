<?php
require_once '../config.php';
require_once '../servicehelper.php'; //���� Xml Service ��������ơC

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();


begin_service_output(); //�}�l��X��ơC

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
end_service_output(); //������X�C
?>