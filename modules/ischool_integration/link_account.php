<?php
require 'config.php';
require 'sfs_login.php';

$role = $_SESSION['ischool_role'];
$userid = $_SESSION['ischool_userid'];
$useruuid = $_SESSION['ischool_useruuid'];

if(isset($_REQUEST['log_id'])){
	$log_id = $_REQUEST['log_id'];
	$log_pass = pass_operate($_REQUEST['log_pass']);

	/* 進行帳號連結程序… */

	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	if ($role == "teacher") {
		$sql = "select teacher_sn from teacher_base where teach_condition = 0 and teach_id=? and login_pass=? and teach_id<>''";

		$records = $CONN -> Execute($sql, array($log_id, $log_pass)) or trigger_error("Sql Error：{$CONN->ErrorMsg()}", E_USER_ERROR);

		 if(!$CONN -> ErrorNo()) {
		 	list($teacher_sn) = $records -> FetchRow();

		 	if($teacher_sn){
			 	$linksql = 'insert into ischool_account(ref_target_sn, ref_target_role, account, uuid) values(?,?,?,?);';
			 	$CONN -> Execute($linksql, array($teacher_sn, $role, $userid, $useruuid)) or trigger_error("Sql Error：{$CONN->ErrorMsg()}", E_USER_ERROR);
			 } else {
				$role_str = get_role_string($role);

				head("帳號連結頁面","",1);
				output_login_form($userid, $role_str,'帳號或密碼錯誤！');
				foot();
				exit();
			 }
		 }
		 session_destroy();
		 session_start(); 
		 do_login_teacher($teacher_sn);
		 header("location: ../index.php");
	} else {
		trigger_error("狀態已經不正確，請重新執行。",E_USER_ERROR); 
	}
} else {
	$role_str = get_role_string($role);

	head("帳號連結頁面","",1);
	output_login_form($userid, $role_str);
	foot();
}

function get_role_string($role){
	if($role =='teacher')
		return "教師";
	else
		trigger_error("狀態已經不正確，請重新執行。",E_USER_ERROR);
}

function output_login_form($userid, $role_str, $msg){
	let_support_bootstrap();

	echo "
<br/>
<span style='width:100%;text-align:center'>
	<h4 class='ui-widget-header' style='padding:15px;color:red;font-size:26px'>
	請輸入您原來在 SFS 使用的帳號密碼，這個動作將會使 SFS 帳號與 ischool 帳號產生連結，此動作您只需要做一次。
	</h4>
</span>

	<form action='link_account.php' method='post'  name='checkid'>
		<table style='width:100%;'>
			<tr><td style='text-align:center;padding:15px;'>
			<div  class='ui-widget-header ui-corner-top'  style='width:350px; padding:5px; margin:auto'>
			<span style='text-align:center;'>ischool 帳號連結</span>
			</div>
			<div  class='ui-widget-content ui-corner-bottom'  style='width:350px; padding:5px; margin:auto'>
				<table cellspacing='0' cellpadding='3' align='center'>
					<tr class='small'>
					<td nowrap>輸入代號</td><td nowrap>
					<input type='text' name='log_id' size='20' maxlength='15'>
					</td>
					</tr>
					<tr class='small'>
					<td nowrap>輸入密碼</td>
					<td nowrap>
					<input type='password' name='log_pass' size='20' maxlength='15'>
					</td>
					</tr>
					<tr class='small'>
					<td nowrap>連結帳號</td>
					<td>$userid</td>
					</tr>
					<tr class='small'>
					<td nowrap>連結身份</td>
					<td>$role_str</td>
					</tr>
					<tr class='small text-danger'>
						<td colspan='2'><h4><em>$msg</em></h4></td>
					</tr>
					<tr>
					<td  colspan='2' style='text-align:center'>
						<input type='submit' value='確定' name='B1'>
					</td>
					</tr>
				</table>
			</div>
			</td>
			</tr>
		</table>
	</form>";
}
?>