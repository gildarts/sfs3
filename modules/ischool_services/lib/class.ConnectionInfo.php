<?php
//$mysql_host, $mysql_user, $mysql_pass, $mysql_db
//包含資料庫連線的相關資訊。
require_once ('consts.php');

class ConnectionInfo{
	private $host, $user, $pass, $dbname;

	public function __construct($host, $dbname, $user, $pass){
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->dbname = $dbname;
	}

	//Get the database connection instance.
	public function NewConnection(){
		try{
			$conn = mysql_connect($this->host, $this->user, $this->pass );

			if(!$conn)
				throw_error(ERR_OPEN_CONNECTION,'Open database error.');

			mysql_select_db($this->dbname, $conn);

			if(mysql_errno())
				throw_error(ERR_OPEN_CONNECTION,mysql_error());

		}catch(Exception $e){
			throw_error(ERR_OPEN_CONNECTION,$e->getMessage());
		}

		return $conn;
	}
}

/*
// mysqli
$mysqli = new mysqli("example.com", "user", "password", "database");
$result = $mysqli->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = $result->fetch_assoc();
echo htmlentities($row['_message']);

// PDO
$pdo = new PDO('mysql:host=example.com;dbname=database', 'user', 'password');
$statement = $pdo->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = $statement->fetch(PDO::FETCH_ASSOC);
echo htmlentities($row['_message']);

// mysql
$c = mysql_connect("example.com", "user", "password");
mysql_select_db("database");
$result = mysql_query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = mysql_fetch_assoc($result);
echo htmlentities($row['_message']);
*/

?>