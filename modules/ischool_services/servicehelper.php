<?php
define("ERR_NO_CONNECTION",'001');

//�T�{���檺 Sql Statement ���S�����~�C
function statement_error(){
	global $CONN;

	if($CONN->ErrorNo()){
		throw_error($CONN->ErrorNo(), $CONN->ErrorMsg());
	}
}

//�ˬd�O�_���q�L�{�ҡC
function check_auth(){
	global $CONN;

	if(!$CONN) //�ˬd��Ʈw�s�u�C
		throw_error(ERR_NO_CONNECTION, 'no db connection.');

	//�ˬd AccessToken �O�_�w���ҡC
}

//��X�@�ӿ��~�T���C
function throw_error($code, $msg){
	ob_clean();
	echo "<Error><Code>{$code}</Code><Msg>{$msg}</Msg></Error>";
	end_service_output();
	exit();
}

function begin_service_output(){
	ob_start();
}

function end_service_output(){
	ob_end_flush();
}
?>