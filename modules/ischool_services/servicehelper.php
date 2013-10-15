<?php
define("ERR_NO_CONNECTION",'001');

//確認執行的 Sql Statement 有沒有錯誤。
function statement_error(){
	global $CONN;

	if($CONN->ErrorNo()){
		throw_error($CONN->ErrorNo(), $CONN->ErrorMsg());
	}
}

//檢查是否有通過認證。
function check_auth(){
	global $CONN;

	if(!$CONN) //檢查資料庫連線。
		throw_error(ERR_NO_CONNECTION, 'no db connection.');

	//檢查 AccessToken 是否已驗證。
}

//丟出一個錯誤訊息。
function throw_error($code, $msg){
	ob_clean();
	echo "<Error><Code>{$code}</Code><Msg>{$msg}</Msg></Error>";
	end_service_output();
	exit();
}

function begin_service_output(){
	ob_start();
}

function end_service_output(){
	ob_end_flush();
}
?>