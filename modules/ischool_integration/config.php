<?php
// $dirPath = dirname(__FILE__);
// require_once $dirPath."/module-cfg.php";

// include_once  realpath("$dirPath/../../include/config.php");
// include_once realpath("$dirPath/../../include/sfs_case_dataarray.php");
// include_once realpath("$dirPath/../../include/sfs_case_PLlib.php");
// include_once realpath("$dirPath/../../open_flash_chart/open_flash_chart_object.php");

$dirPath = dirname(__FILE__);
require_once $dirPath."/module-cfg.php";

include_once  realpath("$dirPath/../../include/config.php");

$ret_array =&get_module_setup("ischool_integration");
list($client_id,$client_secret) = array($ret_array['client_id'], $ret_array['client_secret']);

$callback_path = dirname($_SERVER['SCRIPT_NAME']).'/auth_callback.php?role=teacher';
$callback_url =  "http" . ($_SERVER['HTTPS'] ? 's' : '') . "://{$_SERVER['HTTP_HOST']}{$callback_path}";
$redirect_url = "https://auth.ischool.com.tw/oauth/authorize.php?client_id=$client_id&response_type=code&redirect_uri=".urlencode($callback_url);

?>
