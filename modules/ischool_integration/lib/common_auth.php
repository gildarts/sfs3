<?php
require_once 'class.AccessTokenContext.php'; 
require_once 'class.ConnectionInfo.php';
require_once 'class.QueryResultSet.php';

//啟動一個 Context。
//Context 代表了目前使用者的狀態，包含認證授權結果、資料庫連線等。
function init_context($access_token){
	ob_start();
	output_headers();

	$userinfo = getUserInfo($access_token);

	if($userinfo['error'] != ''){
		throw_error(ERR_ACCESS_TOKEN_EXPIRED,$userinfo['error_description']);
	}

	$ctx = new AccessTokenContext();
	$ctx->Connection = get_sfs_connection_info()->NewConnection();
	$ctx->UserName = $userinfo['userID'];
	$ctx->FirstName = $userinfo['firstName'];
	$ctx->LastName = $userinfo['lastName'];
	$ctx->UUID = $userinfo['uuid'];

	return $ctx;
}

//關閉一個 Context。
function close_context(){
	ob_end_flush();
}

function output_headers(){
	header('Access-Control-Allow-Methods: POST, GET');
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: text/xml; charset=utf-8');
	header("Expires: 0");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
}

// Get UserInfo by access token.
// array(5) {
//   ["sysID"]=>string(3) "333"
//   ["userID"]=>string(28) "guest@ischool.com.tw"
//   ["firstName"]=>string(9) "Guest"
//   ["lastName"]=>string(0) ""
//   ["uuid"]=>string(36) "dkedi382-638b-3dses-b873-184d48ed69fa"
// }
function getUserInfo($token) {
	$url = AUTH_USERINFO ."?access_token=$token&token_type=bearer";

	$res = http_get($url);
	$user = json_decode($res, true);
	return $user;
}
?>