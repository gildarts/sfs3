<?php
require 'config.php';

sfs_check();

head("帳號連結管理","",1);

echo "
<script src='js/bootstrap.js'></script>
<link rel='stylesheet' href='css/bootstrap.css'>
<h1 style='color:blue'>ischool 帳號連結管理</h1><br/>";

$sql = "select name,teach_id,account,ischool_account.uid
	from teacher_base left join ischool_account on teacher_base.teacher_sn = ischool_account.ref_target_sn
	where true or ischool_account.ref_target_role = 'teacher'
	order by ischool_account.account desc,teacher_base.teacher_sn;";

$records = $CONN -> Execute($sql) or trigger_error($CONN->ErrorMsg, E_USER_ERROR);

echo "<div class='row'><div class='col-md-6'>
<table class='table table-bordered table-hover'>
	<thead>
		<tr><th>姓名</th><th>SFS 帳號</th><th>ischool 帳號</th><th>解除連結</th></tr>
	</thead>
	<tbody>";

while(list($name, $teach_id, $account,$uid) = $records->FetchRow()){
	$success = (!$account)?'':"class='success'";
	$unlink = (!$account)?'':"<a class='text-center' href='unlink_account.php?uid=$uid&redirect=setup.php'>解除</a>";

	echo "<tr $success>
		<td>$name</td><td>$teach_id</td><td>$account</td>
		<td>$unlink</td>
	</tr>";
}
echo "</tbody></table></div></div>";

echo "<h2></span><a class='btn btn-link' href='{$redirect_url}'>You can try it.(Teacher)</a></h2><br/>";

foot();
?>
