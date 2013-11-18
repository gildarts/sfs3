<?php
require_once ("../config.php");
require_once ("../lib/class.OAuthUtil.php");

if(!is_module_installed())
	throw_error(ERR_MODULE_NOT_INSTALLED, 'The module "ischool_integration" didn\'t install.');

init_context();

$access_token = $_GET['token'];
$role = $_GET['role'];
$log_id = $_GET['login_id'];
$log_pass = $_GET['login_pass'];

if(!isset($access_token))
	throw_error(ERR_GET_PARAMETER_LOST, ('The argument "token" was lost.'));

if(!isset($role))
	throw_error(ERR_GET_PARAMETER_LOST, ('The argument "role" was lost.'));

if(!class_exists('OAuthUtil'))
	throw_error(ERR_CLASS_NOT_FOUND, 'The class OAuthUtil was not found.');

if(!isset($log_id))
	throw_error(ERR_CREDENTIAL_INVALID, 'The argument "login_id" was lost.');

if(!isset($log_pass))
	throw_error(ERR_CREDENTIAL_INVALID, 'The argument "login_pass" was lost.');

$oauth_util = new OAuthUtil();
$user = $oauth_util->GetUserInfo($access_token);

if(isset($user->error)){
	throw_error(ERR_ACCESS_TOKEN_ERROR, $user->error_description);
}

$userID = $user->userID;
$userUUID = $user->uuid;

$sql = "select ref_target_sn,uuid,uid from ischool_account where account = ? and ref_target_role='{$role}';";
$records = $CONN -> Execute($sql, array($userID)) or throw_error(ERR_SQL_ERROR, $CONN->ErrorMsg());

//判斷該使用者是否存在
if($records -> EOF){ //not exists
	do_account_link($role, $userID, $userUUID, $log_id, $log_pass);
	echo "<Result>Success</Result>";
} else {
	throw_error(ERR_ACCOUNT_LINKED, 'The account "'.$userID.'" was linked.');
}

end_context();
?>