<?php

//Sql �d�߫�^�Ǫ��ʸ����O�C

class QueryResultSet{
	private $result;

	public function __construct($result){
		$this->result = $result;
	}

	public function FetchNext(){
		return mysql_fetch_array ($this->result);
	}
}

?>