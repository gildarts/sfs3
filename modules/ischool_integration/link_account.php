<?php
require 'config.php';
require 'sfs_login.php';

$role = $_SESSION['ischool_role'];
$userid = $_SESSION['ischool_userid'];
$useruuid = $_SESSION['ischool_useruuid'];

if(isset($_REQUEST['log_id'])){
	$log_id = $_REQUEST['log_id'];
	$log_pass = pass_operate($_REQUEST['log_pass']);

	/* �i��b���s���{�ǡK */

	// �T�w�s�u����
	if (!$CONN) user_error("��Ʈw�s�u���s�b�I���ˬd�����]�w�I",256);

	if ($role == "teacher") {
		$sql = "select teacher_sn from teacher_base where teach_condition = 0 and teach_id=? and login_pass=? and teach_id<>''";

		$records = $CONN -> Execute($sql, array($log_id, $log_pass)) or trigger_error("Sql Error�G{$CONN->ErrorMsg()}", E_USER_ERROR);

		 if(!$CONN -> ErrorNo()) {
		 	list($teacher_sn) = $records -> FetchRow();

		 	if($teacher_sn){
			 	$linksql = 'insert into ischool_account(ref_target_sn, ref_target_role, account, uuid) values(?,?,?,?);';
			 	$CONN -> Execute($linksql, array($teacher_sn, $role, $userid, $useruuid)) or trigger_error("Sql Error�G{$CONN->ErrorMsg()}", E_USER_ERROR);
			 } else {
				$role_str = get_role_string($role);

				head("�b���s������","",1);
				output_login_form($userid, $role_str,'�b���αK�X���~�I');
				foot();
				exit();
			 }
		 }
		 session_destroy();
		 session_start(); 
		 do_login_teacher($teacher_sn);
		 header("location: ../index.php");
	} else {
		trigger_error("���A�w�g�����T�A�Э��s����C",E_USER_ERROR); 
	}
} else {
	$role_str = get_role_string($role);

	head("�b���s������","",1);
	output_login_form($userid, $role_str);
	foot();
}

function get_role_string($role){
	if($role =='teacher')
		return "�Юv";
	else
		trigger_error("���A�w�g�����T�A�Э��s����C",E_USER_ERROR);
}

function output_login_form($userid, $role_str, $msg){
	let_support_bootstrap();

	echo "
<br/>
<span style='width:100%;text-align:center'>
	<h4 class='ui-widget-header' style='padding:15px;color:red;font-size:26px'>
	�п�J�z��Ӧb SFS �ϥΪ��b���K�X�A�o�Ӱʧ@�N�|�� SFS �b���P ischool �b�����ͳs���A���ʧ@�z�u�ݭn���@���C
	</h4>
</span>

	<form action='link_account.php' method='post'  name='checkid'>
		<table style='width:100%;'>
			<tr><td style='text-align:center;padding:15px;'>
			<div  class='ui-widget-header ui-corner-top'  style='width:350px; padding:5px; margin:auto'>
			<span style='text-align:center;'>ischool �b���s��</span>
			</div>
			<div  class='ui-widget-content ui-corner-bottom'  style='width:350px; padding:5px; margin:auto'>
				<table cellspacing='0' cellpadding='3' align='center'>
					<tr class='small'>
					<td nowrap>��J�N��</td><td nowrap>
					<input type='text' name='log_id' size='20' maxlength='15'>
					</td>
					</tr>
					<tr class='small'>
					<td nowrap>��J�K�X</td>
					<td nowrap>
					<input type='password' name='log_pass' size='20' maxlength='15'>
					</td>
					</tr>
					<tr class='small'>
					<td nowrap>�s���b��</td>
					<td>$userid</td>
					</tr>
					<tr class='small'>
					<td nowrap>�s������</td>
					<td>$role_str</td>
					</tr>
					<tr class='small text-danger'>
						<td colspan='2'><h4><em>$msg</em></h4></td>
					</tr>
					<tr>
					<td  colspan='2' style='text-align:center'>
						<input type='submit' value='�T�w' name='B1'>
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