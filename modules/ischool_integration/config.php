<?php

$dirPath = dirname(__FILE__);

//SFS
require_once $dirPath."/module-cfg.php";
include_once  realpath("$dirPath/../../include/config.php");

//通用程式庫。
require_once 'lib/common.php';
require_once 'lib/common_sfs.php'; //SFS 相關的 Library 多是從現有模組 Copy 過來的。
require_once 'lib/common_auth.php'; //處理認證問題

$ret_array =&get_module_setup("ischool_integration");
list($client_id,$client_secret) = array($ret_array['client_id'], $ret_array['client_secret']);

$base_url =  "http" . ($_SERVER['HTTPS'] ? 's' : '') . "://{$_SERVER['HTTP_HOST']}/";
$callback_path = dirname($_SERVER['SCRIPT_NAME']).'/auth_callback.php';
$callback_url =  "http" . ($_SERVER['HTTPS'] ? 's' : '') . "://{$_SERVER['HTTP_HOST']}{$callback_path}";
$redirect_url = "https://auth.ischool.com.tw/oauth/authorize.php?client_id=$client_id&response_type=code&redirect_uri=".urlencode($callback_url);

?>
