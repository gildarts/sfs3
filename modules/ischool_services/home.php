<?php
echo "資料服務模組。<br/><br/>";

$services = array('Info.GetMyInfo.php');

foreach($services as $service){
	echo "<a href='$service'>$service</a>";
}

?>