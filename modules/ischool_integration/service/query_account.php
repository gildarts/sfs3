<?php
require_once ("../config.php");
require_once ("../lib/class.OAuthUtil.php");

if(!is_module_installed())
	throw_error(ERR_MODULE_NOT_INSTALLED, 'The module "ischool_integration" didn\'t install.');

init_context();

$access_token = $_GET['token'];

if(!isset($access_token))
	throw_error(ERR_GET_PARAMETER_LOST, ('The argument "token" was lost.'));

if(!class_exists('OAuthUtil'))
	throw_error(ERR_CLASS_NOT_FOUND, 'The class OAuthUtil was not found.');

$oauth_util = new OAuthUtil();
$user = $oauth_util->GetUserInfo($access_token);

if(isset($user->error)){
	throw_error(ERR_ACCESS_TOKEN_ERROR, $user->error_description);
}

$userID = $user->userID;
$userUUID = $user->uuid;

$sql = "select uid, ref_target_role, uuid from ischool_account where account = ?;";
$records = $CONN -> Execute($sql, array($userID)) or throw_error(ERR_SQL_ERROR, $CONN->ErrorMsg());

//判斷該使用者是否存在
if(list($uid, $target_role, $uuid) = $records->FetchRow()){ //not exists
	echo "<Result><UID>$uid</UID><Role>$target_role</Role><UUID>$uuid</UUID></Result>";
} else {
	throw_error(ERR_ACCOUNT_NOT_LINKED, 'The account "'.$userID.'" does not exist.');
}

end_context();
?>