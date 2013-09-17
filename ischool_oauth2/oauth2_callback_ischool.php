<?php

require_once ("class.OAuthUtil.php");
require_once ("../include/config.php") ; 

//Clear session variables
//先清除必要變數
$session_log_id="";
$session_tea_name="";
$session_tea_sn="";
$session_prob_open="";
$session_who="";
$session_login_chk="";

//啟用SESSION
session_start(); 
session_register("session_log_id"); 
session_register("session_log_pass");
session_register("session_tea_name");
session_register("session_tea_sn");
session_register("session_prob_open");
session_register("session_who");
session_register("session_login_chk");

//配合中心端版本改變,記錄學校ID
$session_prob = get_session_prot();
session_register($session_prob);
//echo "session_prob=$session_prob";
//echo var_dump($_SESSION);
//exit();

if (isset($_GET['code'])) {

	$identity = $_GET['state'];
    
	$oauth_util = new OAuthUtil();
	//======  1. Get Access Token  ===========		
    $code = $_GET['code'];  
	$token = $oauth_util->GetAccessToken($code);
	
	//========  2. Get User Info  ==================
	$user = $oauth_util->GetUserInfo($token["access_token"]);
	$userID = $user["userID"];
	$firstName=iconv("UTF-8","big5",$user["firstName"]);
	//echo $firstName . $userID;	
	
	//========  3. 檢查 table 中是否已有帳號  ===============
	global $CONN,$SFS_PATH_HTML,$session_prob;
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	if ($identity == "ta") {
		$sql_select = " select teacher_sn,name, login_pass from teacher_base where teach_condition = 0 and teach_id='$userID' and teach_id<>''";
		$recordSet = $CONN -> Execute($sql_select) or trigger_error("資料連結錯誤：" . $sql_select, E_USER_ERROR);
			
		while(list($teacher_sn, $name , $login_pass) = $recordSet -> FetchRow()){
			$who = iconv("UTF-8","big5","教師");
			//$who = "教師";
			//echo $who;
			$_SESSION['session_log_id'] = $userID;
			$_SESSION['session_log_pass'] = $login_pass;
			$_SESSION['session_tea_sn'] = $teacher_sn;
			$_SESSION['session_tea_name'] = $name;
			$_SESSION['session_who'] = $who;
			$_SESSION[$session_prob] = get_prob_power($teacher_sn,$who);
			//echo var_dump($_SESSION);
			login_logger($teacher_sn,$who);
			
			// 記錄使用者狀態
			$query = "insert into pro_user_state (teacher_sn,pu_state,pu_time,pu_ip) values($teacher_sn,1,now(),'{$_SERVER['REMOTE_ADDR']}')";
			$CONN -> Execute($query) or user_error("新增失敗！<br>$query",256);
			header("location: ../index.php");
		}
	}
	//bad_login($log_id,2);
	
	//===== 3.1 如果沒有帳號，則新增一個帳號  ===============
	
	
	//===== 4. 寫入登入紀錄 ，並把資訊放到 Session中 ========
	
	
	//===== 5. redirect to index.php  =======================
	
}

else {
	echo "no code";
}

//記錄登入記錄
function login_logger($tea_sn,$who) {
	global $CONN,$REMOTE_ADDR;

	if ($tea_sn!="" && $who!="") {
		$t=date("Y-m-d H:i:s", time());
		$query="select count(teacher_sn) as n from login_log_new where teacher_sn = '$tea_sn' and who = '$who'";
		$res=$CONN->Execute($query);
		$num=$res->fields['n'];
		if ($num>9) {
			$query="delete from login_log_new where teacher_sn = '$tea_sn' and who = '$who' and (no='0' or no>'9')";
			$CONN->Execute($query);
			$query="update login_log_new set no=no-1 where teacher_sn = '$tea_sn' and who = '$who' order by teacher_sn,no";
			$CONN->Execute($query);
			$num=9;
		}
		$CONN->Execute("insert into login_log_new (teacher_sn,who,no,login_time,ip) values ('$tea_sn','$who','$num','$t','$REMOTE_ADDR')");
	}
}


?>