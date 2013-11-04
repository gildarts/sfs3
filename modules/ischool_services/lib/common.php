<?php
function throw_error($code, $msg){
	ob_clean();
	echo "<Error><Code>{$code}</Code><Msg>".utf8($msg)."</Msg></Error>";
	end_service_output();
	exit();
}

function http_get($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_URL, $url );
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //skip ssl verify

	$result = curl_exec($ch);
	curl_close ($ch);

	return $result;
}

//big5 to utf8.
function utf8($val){
	return iconv('big5', 'utf-8',$val);
}

// function create_guid($namespace = '') {  
//     static $guid = '';
//     $uid = uniqid("", true);
//     $data = $namespace;
//     $data .= $_SERVER['REQUEST_TIME'];
//     $data .= $_SERVER['HTTP_USER_AGENT'];
//     $data .= $_SERVER['LOCAL_ADDR'];
//     $data .= $_SERVER['LOCAL_PORT'];
//     $data .= $_SERVER['REMOTE_ADDR'];
//     $data .= $_SERVER['REMOTE_PORT'];
//     $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));

//     return $hash;
// }
?>