<?php
require_once '../config.php';

$ctx = init_context($_GET['access_token']);

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
close_context(); //§¹¦¨¿é¥X¡C
?>