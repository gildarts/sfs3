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

$sql = "delete from ischool_account where uuid=?";
$CONN -> Execute($sql,array($userUUID)) or throw_error(ERR_SQL_ERROR, $CONN->ErrorMsg());
echo "<Result>Success</Result>";

end_context();
?>