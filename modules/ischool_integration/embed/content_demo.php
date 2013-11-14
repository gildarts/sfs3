<?php

// $Id: stat.php 5904 2010-03-11 01:54:49Z brucelyc $

/* 取得設定檔 */
include_once "bridge.php";

sfs_check(); //SFS 的檢查是否有登入。

$r = rand(1,7);
$l = rand(2,5);
$result = $CONN->Execute("select stud_name,stud_person_id from stud_base limit {$l} offset {$r}");

while(!$result->EOF){
	echo "
		<h3>{$result->fields['stud_name']} => {$result->fields['stud_person_id']}</h3>
	";

	$result->FetchRow();
}

?>
