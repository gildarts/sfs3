<?php

require_once ('class.HTTP.php');
require 'config.php';

class OAuthUtil {
	private $token_endpoint ="https://auth.ischool.com.tw/oauth/token.php" ;
	private $userInfo_url = "https://auth.ischool.com.tw/services/me2.php";	    

	public function GetAccessToken($code) {
		global $callback_url,$client_id,$client_secret; //±q config.php ©wÄ³¡C

		$fields = array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$client_id,
			'client_secret'=>$client_secret,
			'redirect_uri'=> $callback_url
		);

		//url-ify the data for the POST
		$fields_string="" ;
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');
		
		$result = HTTP::Post($this->token_endpoint, $fields_string);
		//echo $result;

		//parse token json string
		$token = json_decode($result, true);

		return $token;
	}
	
	public function GetUserInfo($access_token) {
		$url = $this->userInfo_url ."?access_token=$access_token&token_type=bearer";	    

		$res = HTTP::Get($url);  
		$user = json_decode($res, true);
		return $user;
	}
}


?>
