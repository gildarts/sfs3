<?php
/*
OAuth 的 Callback 頁面。
參數中會帶有 code。
*/

require_once "../sfs_login.php";
require_once ("../class.OAuthUtil.php");

session_start(); 

$access_token = $_GET['token'];
$role = $_GET['role'];

if ($access_token) {

	$oauth_util = new OAuthUtil();

	//========  2. Get User Info  ==================
	//$access_token += $access_token.'hello';

	$user = $oauth_util->GetUserInfo(&$access_token);

	if(isset($user->error)){
		user_error($user->error_description, 256);
	}

	$userID = $user->userID;
	$userUUID = $user->uuid;

	$firstName=iconv("UTF-8","big5",$user->firstName);

	//========  3. 檢查 table 中是否已有帳號  ===============
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	if ($role == "teacher") {
		$sql = "select ref_target_sn,uuid from ischool_account where account = ? and ref_target_role='teacher';";
		$records = $CONN -> Execute($sql, array($userID)) or trigger_error("Sql Error：{$CONN->ErrorMsg()}", E_USER_ERROR);

		$_SESSION['ischool_userid'] = $userID;
		$_SESSION['ischool_role'] = $role;
		$_SESSION['ischool_useruuid'] = $userUUID;

		//判斷該使用者是否存在
		if(!$records -> EOF){ //exists
			list($ref_target_sn,$uuid) = $records -> FetchRow();

			do_login_teacher($ref_target_sn); //進入 SFS 原來認證流程。

			//完成登入！
		}
	}else{
		user_error(iconv("UTF-8","big5",'請指定角色 teacher、student...'),256);
	}
} else {
	user_error(iconv("UTF-8","big5",'未指定 AccessToken，無法認證。'),256);
}
?>