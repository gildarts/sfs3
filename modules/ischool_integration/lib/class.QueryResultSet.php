<?php

//Sql 查詢後回傳的封裝類別。

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