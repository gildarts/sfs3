<?php
/*
OAuth 的 Callback 頁面。
*/

require "sfs_login.php";

require_once ("class.OAuthUtil.php");
require_once ("../../include/config.php") ; 

//Clear session variables
//先清除必要變數
$session_log_id="";
$session_tea_name="";
$session_tea_sn="";
$session_prob_open="";
$session_who="";
$session_login_chk="";

//啟用SESSION
session_destroy(); //先清掉先前的 Session。
session_start(); 
// session_register("session_log_id"); 
// session_register("session_log_pass");
// session_register("session_tea_name");
// session_register("session_tea_sn");
// session_register("session_prob_open");
// session_register("session_who");
// session_register("session_login_chk");

//配合中心端版本改變,記錄學校ID
//$session_prob = get_session_prot();
//session_register($session_prob);
//echo "session_prob=$session_prob";
//echo var_dump($_SESSION);
//exit();

if (isset($_GET['code'])) { //oauth code。

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

	//========  3. 檢查 table 中是否已有帳號  ===============
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	if ($role == "teacher") {
		$sql = "select ref_target_sn,uuid from ischool_account where account = ? and ref_target_role='teacher';";
		$records = $CONN -> Execute($sql, array($userID)) or trigger_error("Sql Error：{$CONN->ErrorMsg()}", E_USER_ERROR);

		//判斷該使用者是否存在
		if(!$records -> EOF){ //exists
			list($ref_target_sn,$uuid) = $records -> FetchRow();
			do_login_teacher($ref_target_sn); //進入 SFS 原來認證流程。
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