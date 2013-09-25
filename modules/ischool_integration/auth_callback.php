<?php

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
session_start(); 
session_register("session_log_id"); 
session_register("session_log_pass");
session_register("session_tea_name");
session_register("session_tea_sn");
session_register("session_prob_open");
session_register("session_who");
session_register("session_login_chk");

//�t�X���ߺݪ�������,�O���Ǯ�ID
$session_prob = get_session_prot();
session_register($session_prob);
//echo "session_prob=$session_prob";
//echo var_dump($_SESSION);
//exit();

if (isset($_GET['code'])) {

	$role = $_GET['role'];
    
	$oauth_util = new OAuthUtil();
	//======  1. Get Access Token  ===========		
    	$code = $_GET['code'];  
	$token = $oauth_util->GetAccessToken($code);
	
	//========  2. Get User Info  ==================
	$user = $oauth_util->GetUserInfo($token["access_token"]);
	$userID = $user["userID"];
	$firstName=iconv("UTF-8","big5",$user["firstName"]);
	//echo $firstName . $userID;	
	
	//============== �L�X�Ӭݬ� ==================
	// echo var_dump($user);//'User:'.$user["userID"];
	// exit();

	//========  3. �ˬd table ���O�_�w���b��  ===============
	global $CONN,$SFS_PATH_HTML,$session_prob;
	// �T�w�s�u����
	if (!$CONN) user_error("��Ʈw�s�u���s�b�I���ˬd�����]�w�I",256);

	echo $role."<br/>";
	if ($role == "teacher") {
		$sql = "select uid,ref_target_sn,ref_target_role,account,uuid from ischool_account where account = '$userID';";
		$records = $CONN -> Execute($sql) or trigger_error("��Ƴs�����~�G" . $sql, E_USER_ERROR);

		echo "begin<br/>";

		echo $sql.'<br/>';
		if($CONN -> connect_errno){
			echo "connect_errno<br/>";
			while(list(,$ref_target_sn,,,) = $records -> FetchRow()){
				echo $ref_target_sn."<br/>";
			}
		}
		echo "end<br/>";

		exit();		
		$sql = "select teach_id,name,login_pass from teacher_base;";
		$records = $CONN -> Execute($sql) or trigger_error("��Ƴs�����~�G" . $sql, E_USER_ERROR);

		echo "begin<br/>";
		while(list($teach_id,$name,$login_pass) = $records -> FetchRow()){
			echo $teach_id."<br/>";
			echo $name."<br/>";
			echo $login_pass."<br/>";
		}
		echo "end<br/>";

		exit();
		$sql_select = " select teacher_sn,name, login_pass from teacher_base where teach_condition = 0 and teach_id='$userID' and teach_id<>''";
		$recordSet = $CONN -> Execute($sql_select) or trigger_error("��Ƴs�����~�G" . $sql_select, E_USER_ERROR);

		while(list($teacher_sn, $name , $login_pass) = $recordSet -> FetchRow()){
			$who = iconv("UTF-8","big5","�Юv");
			//$who = "�Юv";
			//echo $who;
			$_SESSION['session_log_id'] = $userID;
			$_SESSION['session_log_pass'] = $login_pass;
			$_SESSION['session_tea_sn'] = $teacher_sn;
			$_SESSION['session_tea_name'] = $name;
			$_SESSION['session_who'] = $who;
			$_SESSION[$session_prob] = get_prob_power($teacher_sn,$who);
			//echo var_dump($_SESSION);
			login_logger($teacher_sn,$who);
			
			// �O���ϥΪ̪��A
			$query = "insert into pro_user_state (teacher_sn,pu_state,pu_time,pu_ip) values($teacher_sn,1,now(),'{$_SERVER['REMOTE_ADDR']}')";
			$CONN -> Execute($query) or user_error("�s�W���ѡI<br>$query",256);
			header("location: ../index.php");
		}
		echo "no way!";
	}else{
		echo "no role!";
	}
	//bad_login($log_id,2);
	
	//===== 3.1 �p�G�S���b���A�h�s�W�@�ӱb��  ===============
	
	
	//===== 4. �g�J�n�J���� �A�ç��T��� Session�� ========
	
	
	//===== 5. redirect to index.php  =======================
	
}

else {
	echo "no code";
}

//�O���n�J�O��
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