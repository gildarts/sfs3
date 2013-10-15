<?php
/*
OAuth �� Callback �����C
*/

require "sfs_login.php";

require_once ("class.OAuthUtil.php");
require_once ("../../include/config.php") ; 

//Clear session variables
//���M�����n�ܼ�
$session_log_id="";
$session_tea_name="";
$session_tea_sn="";
$session_prob_open="";
$session_who="";
$session_login_chk="";

//�ҥ�SESSION
session_destroy(); //���M�����e�� Session�C
session_start(); 
// session_register("session_log_id"); 
// session_register("session_log_pass");
// session_register("session_tea_name");
// session_register("session_tea_sn");
// session_register("session_prob_open");
// session_register("session_who");
// session_register("session_login_chk");

//�t�X���ߺݪ�������,�O���Ǯ�ID
//$session_prob = get_session_prot();
//session_register($session_prob);
//echo "session_prob=$session_prob";
//echo var_dump($_SESSION);
//exit();

if (isset($_GET['code'])) { //oauth code�C

	$role = $_GET['role'];
	
	$oauth_util = new OAuthUtil();
	//======  1. Get Access Token  ===========		
	$code = $_GET['code'];  
	$token = $oauth_util->GetAccessToken($code);

	// var_dump($token);
	// exit();
	
	//========  2. Get User Info  ==================
	$user = $oauth_util->GetUserInfo($token["access_token"]);
	$userID = $user["userID"];
	$userUUID = $user['uuid'];

	$firstName=iconv("UTF-8","big5",$user["firstName"]);

	//========  3. �ˬd table ���O�_�w���b��  ===============
	// �T�w�s�u����
	if (!$CONN) user_error("��Ʈw�s�u���s�b�I���ˬd�����]�w�I",256);

	if ($role == "teacher") {
		$sql = "select ref_target_sn,uuid from ischool_account where account = ? and ref_target_role='teacher';";
		$records = $CONN -> Execute($sql, array($userID)) or trigger_error("Sql Error�G{$CONN->ErrorMsg()}", E_USER_ERROR);

		//�P�_�ӨϥΪ̬O�_�s�b
		if(!$records -> EOF){ //exists
			list($ref_target_sn,$uuid) = $records -> FetchRow();
			do_login_teacher($ref_target_sn); //�i�J SFS ��ӻ{�Ҭy�{�C
			header("location: ../index.php");
		}else{
			$_SESSION['ischool_userid'] = $userID;
			$_SESSION['ischool_role'] = $role;
			$_SESSION['ischool_useruuid'] = $userUUID;
			header("location: link_account.php");
		}
	}else{
		echo "no role!";
	}
} else {
	echo "no code";
}
?>