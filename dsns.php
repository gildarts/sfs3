<?php

function GetPDO(){
	if(!$CONFIG['pdo']){
		$dbh=new PDO('pgsql:host=10.1.1.190;port=5432;dbname=dsns;user=postgres;password=2a56789);');
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$CONFIG['pdo']=$dbh;
	}
	return $CONFIG['pdo'];
}

$CONN = GetPDO();

$sql = "select * from name_map where name = 'test.kh.edu.tw'";

$cmd = $CONN->prepare($sql);
$cmd->execute();

while ($row = $cmd->fetch(PDO::FETCH_ASSOC)) {
	$redirect = $row['physical_url'];
	echo $redirect;
	//header("Location:$redirect");
}

echo '<br/>';

echo $_GET['n'];

//header("Location:http://www.google.com.tw" ) ; 
?>

