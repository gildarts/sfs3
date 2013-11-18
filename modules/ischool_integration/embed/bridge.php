<?php
/*
OAuth �� Callback �����C
�ѼƤ��|�a�� code�C
*/
require_once "../sfs_login.php";
require_once ("../lib/class.OAuthUtil.php");

if(!is_module_installed())
	user_error(('���w�� ischool_integration �ҲաC'),256);

session_start(); 

$access_token = $_GET['token'];
$role = $_GET['role'];

if (isset($access_token)) {

	$oauth_util = new OAuthUtil();

	//========  2. Get User Info  ==================
	//$access_token += $access_token.'hello';

	$user = $oauth_util->GetUserInfo($access_token);

	if(isset($user->error)){
		user_error($user->error_description, 256);
	}

	$userID = $user->userID;
	$userUUID = $user->uuid;

	$firstName=iconv("UTF-8","big5",$user->firstName);

	//========  3. �ˬd table ���O�_�w���b��  ===============
	// �T�w�s�u����
	if (!$CONN) user_error("��Ʈw�s�u���s�b�I���ˬd�����]�w�I",256);

	if (!isset($role)) {
		user_error(iconv("UTF-8","big5",'�Ы��w���� teacher�Bstudent...'),256);
	}

	$sql = "select ref_target_sn,uuid,uid from ischool_account where account = ? and ref_target_role='{$role}';";
	$records = $CONN -> Execute($sql, array($userID)) or trigger_error("Sql Error�G{$CONN->ErrorMsg()}", E_USER_ERROR);

	$_SESSION['ischool_userid'] = $userID;
	$_SESSION['ischool_role'] = $role;
	$_SESSION['ischool_useruuid'] = $userUUID;

	//�P�_�ӨϥΪ̬O�_�s�b
	if(!$records -> EOF){ //exists
		list($ref_target_sn, $uuid, $uid) = $records -> FetchRow();

		$_SESSION['ischool_uid'] = $uid;

		if($role == 'teacher'){
			do_login_teacher($ref_target_sn); //�i�J SFS ��ӻ{�Ҭy�{�C
		}
		else if($role == 'student'){
			do_login_student($ref_target_sn);
		}else{
			user_error(iconv("UTF-8","big5",'���䴩������G{$role}'),256);
		}

		//�����n�J�I
	}else{
		user_error(iconv("UTF-8","big5","�ϥΪ� �u{$userID}�v�b����u{$role}�v���䤣��I"),256);	
	}
} else {
	user_error(iconv("UTF-8","big5",'�����w AccessToken�A�L�k�{�ҡC'),256);
}
?>