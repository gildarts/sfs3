<!doctype html>
<html>
	<head>
		<meta charset="big5"/>
		<style>
			a {
				text-decoration: none;
				color: blue;
			}
			
			a:hover {
				text-decoration: underline;
				color: red ;
			}
		</style>
	</head>
	<body>

<?php
require_once (__DIR__ . "/../include/config.php") ;

global $CONN,$SFS_PATH_HTML,$session_prob;
// 確定連線成立
if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

//Show tables
echo "<a name='top'>Tables : </a><br/>"	;
echo "<table border='1' cellspacing='0' cellpadding='5'><tr style='background-color:pink;'><td>d_table_name</td><td>d_table_cname</td><td>d_table_group</td></tr>";
$sql_select = " select d_table_name, d_table_cname, d_table_group from sys_data_table order by d_table_name";
$recordSet = $CONN -> Execute($sql_select) or trigger_error("資料連結錯誤：" . $sql_select, E_USER_ERROR);
	
$table_index =1;
$dicTables = [];

while(list($d_table_name, $d_table_cname , $d_table_group) = $recordSet -> FetchRow()){
	echo "<tr>";
	echo "<td style='padding:4px 8px;'><a href='#".$d_table_name. "'>". $table_index . " . " .$d_table_name . "</a></td>";
	echo "<td>$d_table_cname</td>";
	echo "<td>$d_table_group</td>";
	echo "</tr>";
	$table_index++;
	$dicTables[$d_table_name] = $d_table_cname;
}
echo "</table>"; 


//Show tables
//echo "Fields : <br/>"	;
echo "<table border='1' cellspacing='0' cellpadding='5'>";
//echo "<td>d_table_name</td>";
/*
echo "<td>d_field_name</td>";
echo "<td>d_field_cname</td>";
echo "<td>d_field_type</td>";
echo "<td>d_field_order</td>";
echo "<td>d_is_display</td>";
echo "<td>d_field_xml</td>";
*/

echo "</tr>";
$sql_select = " select d_table_name,d_field_name,d_field_cname,d_field_type,d_field_order,d_is_display,d_field_xml from sys_data_field order by d_table_name,d_field_name";
$recordSet = $CONN -> Execute($sql_select) or trigger_error("資料連結錯誤：" . $sql_select, E_USER_ERROR);

$table_name = "";
$table_index =1;

while(list($d_table_name, $d_field_name , $d_field_cname, $d_field_type, $d_field_order , $d_is_display, $d_field_xml) = $recordSet -> FetchRow()){
	if ($table_name != $d_table_name) {	//new table
		echo "<tr><td colspan='5' style='background-color:lightyellow;'>";
		echo "<a name='" . $d_table_name . "'>";
		echo "$table_index . " . $d_table_name;
		echo "</a>";
		echo " <span style='margin-left:20px;'>" . $dicTables[$d_table_name] . "</span>" ;
		echo "</td>";
		echo "<td>";
		echo "<a href='#top'>back to top</a>";
		echo "</td>";
		echo "</tr>";
		
		//echo "<td>d_table_name</td>";
		echo "<tr style='background-color:pink;'>";
		echo "<td>d_field_name</td>";
		echo "<td>d_field_cname</td>";
		echo "<td>d_field_type</td>";
		echo "<td>d_field_order</td>";
		echo "<td>d_is_display</td>";
		echo "<td>d_field_xml</td>";
		echo "</tr>";
		
		$table_index++;
		$table_name = $d_table_name;
	}

	echo "<tr>";
	//echo "<td>$d_table_name</td>";
	echo "<td>$d_field_name</td>";
	echo "<td>$d_field_cname</td>";
	echo "<td>$d_field_type</td>";
	echo "<td>$d_field_order</td>";
	echo "<td>$d_is_display</td>";
	echo "<td>$d_field_xml</td>";
	echo "</tr>";
	
}
echo "</table>"; 

?>

	</body>
</html>