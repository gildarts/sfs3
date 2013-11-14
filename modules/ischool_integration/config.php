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

$base_url =  "http" . ($_SERVER['HTTPS'] ? 's' : '') . "://{$_SERVER['HTTP_HOST']}/";
$callback_path = dirname($_SERVER['SCRIPT_NAME']).'/auth_callback.php';
$callback_url =  "http" . ($_SERVER['HTTPS'] ? 's' : '') . "://{$_SERVER['HTTP_HOST']}{$callback_path}";
$redirect_url = "https://auth.ischool.com.tw/oauth/authorize.php?client_id=$client_id&response_type=code&redirect_uri=".urlencode($callback_url);

// echo $callback_path.'<br/>';
// echo $callback_url.'<br/>';
// echo $redirect_url.'<br/>';
//exit();

function let_support_bootstrap(){
	echo "
	<script src='js/bootstrap.js'></script>
	<link rel='stylesheet' href='css/bootstrap.css'>";
}

?>
