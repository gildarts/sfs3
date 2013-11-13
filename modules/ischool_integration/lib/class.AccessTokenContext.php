<?php
//負責 Service 執行過程中所需要的相關資訊與功能。
require_once ('consts.php');
require_once ('class.QueryResultSet.php');

class AccessTokenContext{

	public $Connection = null;

	public $UserName = null;

	public $FirstName = null;

	public $LastName = null;

	public $UUID = null;

	public function Execute($sql){
		$result = mysql_query($sql, $this->Connection);

		if(mysql_errno())
			throw_error(ERR_EXECUTE_QUERY_ERROR, mysql_error());

		return new QueryResultSet($result);
	}

	public function GetUserInfo($role){
		if($role=='teacher')
			return $this->GetTeacherLocalUserInfo();
		else
			return null;
	}

	//user_sn, name, email
	private function GetTeacherLocalUserInfo(){
		$account = $this->UserName;
		$sql = "
		select ref_target_sn,ref_target_role,name,email,sex 
		from ischool_account 
			join teacher_base on teacher_base.teacher_sn = ischool_account.ref_target_sn
			left join teacher_connect on teacher_base.teacher_sn=teacher_connect.teacher_sn
		where account='$account' and ref_target_role='teacher'";

		$result = $this->Execute($sql);
		while($row = $result->FetchNext()){
			$personal = array(
				'user_sn'=>$row['ref_target_sn'],
				'name'=>utf8($row['name']),
				'email'=>$row['email'],
				'gender'=>$row['sex']);	

			if($personal['gender']=='1')
				$personal['gender'] = utf8('男');
			else if ($personal['gender']=='2'){
				$personal['gender'] = utf8('女');
			}

			return $personal;
		}

		return null;
	}
}
?>