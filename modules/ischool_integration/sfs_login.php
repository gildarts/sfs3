<?php

//這裡是 SFS 原登入流程的相關程式碼。

function do_login_teacher($target_sn){ //sfs 原來的登入流程。
	global $CONN;

	$session_prob = get_session_prot();

	$sql_select = " select teach_id,teacher_sn,name, login_pass from teacher_base where teach_condition = 0 and teacher_sn = ?";
	$recordSet = $CONN -> Execute($sql_select, array($target_sn)) or trigger_error("資料連結錯誤：" . $sql_select, E_USER_ERROR);

	if(list($teacher_id,$teacher_sn, $name , $login_pass) = $recordSet -> FetchRow()){
		$who = "教師";//iconv("UTF-8","big5","教師");

		// echo $teacher_id.'||'.$login_pass.'||'.$teacher_sn.'||'.$name;
		// exit();

		$_SESSION['session_log_id'] = $teacher_id;
		$_SESSION['session_log_pass'] = $login_pass;
		$_SESSION['session_tea_sn'] = $teacher_sn;
		$_SESSION['session_tea_name'] = $name;
		$_SESSION['session_who'] = $who;
		$_SESSION[$session_prob] = get_prob_power($teacher_sn,$who);

		// echo $teacher_id."|".$login_pass."|".$teacher_sn."|".$name.$_SESSION[$session_prob];
		// exit();
		
		//echo var_dump($_SESSION);
		login_logger($teacher_sn,$who);

		// 記錄使用者狀態
		$query = "insert into pro_user_state (teacher_sn,pu_state,pu_time,pu_ip) values($teacher_sn,1,now(),'{$_SERVER['REMOTE_ADDR']}')";
		$CONN -> Execute($query) or user_error("新增失敗！<br>$query",256);
	} else {
		trigger_error("整合登入發生問題！", E_USER_ERROR);
	}
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