<?php

require_once ('class.HTTP.php');

class OAuthUtil {

	private $client_id = "3c89ddf7d3689e205d9f50b7e0093c55";
	private $client_secret="d390700570bc0912efecd62ddeb5248c8099cd7b2c6cdd20a291aa8452e48db1";
	private $redirect_url = "http://sfs3.ischool.com.tw/ischool_oauth2/oauth2_callback_ischool.php";
	private $token_endpoint ="https://auth.ischool.com.tw/oauth/token.php" ;
	private $userInfo_url = "https://auth.ischool.com.tw/services/me2.php";	    

	public function GetAccessToken($code) {
		$fields = array(
                'grant_type'=>'authorization_code',
                'code'=>$code,
                'client_id'=>$this->client_id,
                'client_secret'=>$this->client_secret,
                'redirect_uri'=> $this->redirect_url
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
