<?php
//show variables like '%character%';

require_once '../config.php';

$ctx = init_context($_GET['access_token']);

$result = $ctx->Execute("show variables like '%character%';");

echo "<Result>";

while($row = $result->FetchNext()){
	echo '<Record>';
	var_dump($row);
	echo '</Record>';
}

echo "</Result>";
close_context(); //完成輸出。
?>