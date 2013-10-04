<?php
define('AUTH_USERINFO','https://auth.ischool.com.tw/services/me2.php');

require_once ('config.php');
require_once ('servicehelper.php');
require_once ("../ischool_integration/class.HTTP.php");

$access_token = $_GET['access_token'];

$userinfo = GetUserInfo($access_token);

var_dump($userinfo);

function GetUserInfo($token) {
	$url = AUTH_USERINFO ."?access_token=$token&token_type=bearer";	    

	$res = HTTP::Get($url);  
	$user = json_decode($res, true);
	return $user;
}
?>