<?php
require_once '../config.php';

$ctx = init_context($_GET['access_token']);
$personal = $ctx->GetUserInfo('teacher');

if(!$personal) { //檢查如果使用者不存在。
	throw_error(ERR_USER_DONOT_EXISTS, '使用者不存在，可能未進行帳號連結。');
}

$sql = "
select teacher_base.teacher_sn,name,sex,email,cell_phone 
from teacher_base left join teacher_connect on teacher_base.teacher_sn=teacher_connect.teacher_sn
where teacher_base.teacher_sn='{$personal['user_sn']}'";

$result = $ctx->Execute($sql);

echo '<Body><Result><Info>';

while($row = $result -> FetchNext()){
	echo "<ID>" . utf8($row['teacher_sn']) . "</ID>";
	echo "<Name>" . utf8($row['name']) . "</Name>";
	echo "<Gender>" . $personal['gender'] . "</Gender>";
	echo "<Nickname/>"; //沒有這個欄位。
	echo "<Email>" . utf8($row['email']) . "</Email>";
	echo "<ContractPhone>" . utf8($row['cell_phone']) . "</ContractPhone>";	
}

echo '</Info></Result></Body>';

close_context();
?>