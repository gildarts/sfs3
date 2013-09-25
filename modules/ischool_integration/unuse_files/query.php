<?php
require_once (__DIR__ . "/../include/config.php") ;

$tablename = $_POST["sql"];
?>
<!doctype html>
<html>
<head>
	<script>
		var assignTable = function(src) {
			//alert(src.value);
			document.getElementById('sql').value=src.value;
		}
	</script>
</head>
<body>


<?php 
$con = mysql_connect("$mysql_host","$mysql_user","$mysql_pass");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db($mysql_db, $con);
	
$sql_select = " select d_table_name, d_table_cname from sys_data_table order by d_table_name";
$recordSet = mysql_query($sql_select) or trigger_error("資料連結錯誤：" . $sql_select, E_USER_ERROR);
	

?>

<form action="query.php" method="post">
<textarea name="sql" id="sql" style="width:600px;height:80px;"><?php echo $tablename; ?></textarea>
<br/>
<select id="sel" onchange="assignTable(this);">
<?php
	while($row = mysql_fetch_assoc($recordSet)){	
		$tname = $row["d_table_name"];
		$cname = $row["d_table_cname"];
		echo "<option value='$tname'" ; 
		if ($tablename == $tname)
			echo " selected ";
		echo ">". $tname."(" . $cname . ")</option>";
	}
?>
</select>

<input type="submit" value="Query"/> 
</form>


<?
if (isset($_POST["sql"])) {	
	
	$fields = array();
	

	echo "<table border='1' cellspacing='0'><tr>";
	$result = mysql_query("SHOW COLUMNS FROM $tablename");
	if (!$result) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			echo "<td>".$row["Field"] . "</td>";	
			array_push($fields, $row["Field"]);			
		}
	}
	echo "</tr>";

	$i = 0;
	if ( strtoupper(substr($tablename, 0, 7)) == "SELECT ") {
		$sql = str_replace("\\\'", "'", $tablename);		
	}
	else
		$sql = "SELECT * FROM sfs3.$tablename";
		
	$res = mysql_query($sql);
	$length = mysql_field_len($res, 0);
	//echo $length;
	//echo var_dump($fields);

	while($row = mysql_fetch_array($res))
	{
	  echo "<tr>";	  
	  foreach ($fields as &$value) {
		echo "<td>" . $row[$value]. "</td>";
	  }
	  echo "</tr>"; 	  
	}
	echo "</table>";
}
else {
	echo iconv("UTF-8","big5","未收到提交資料");
}
?>

</body>
</html>




