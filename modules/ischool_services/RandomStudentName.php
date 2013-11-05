<?php
require_once 'config.php';

$ctx = init_context($_GET['access_token']);

$field_name = 'stud_name';
$field_gender = 'stud_sex';
$field_key = 'student_sn';
$table = 'stud_base';

$sql = "select $field_name, $field_gender, $field_key from $table";

$result = $ctx->Execute($sql);

$rows = array();
while($row = $result->FetchNext()){
	array_push($rows, $row);
}

$male = array();
$female = array();

for($i=0; $i<count($rows); $i++){
	$row = $rows[$i];

	if($row[$field_gender] == 1){
		array_push($male, $row[$field_name]);
	}else{
		array_push($female, $row[$field_name]);
	}
}

echo '<Commands>';
//print_comand($rows,'Origin');
for($i=0; $i<count($rows);$i++){
	$rnd = rand($i,count($rows)-1);
	$row = $rows[$i];
	$row_exchange = $rows[$rnd];

	//ец┤л Row
	$rows[$i] = $row_exchange;
	$rows[$rnd] = $row;
}

for($i=0; $i<count($rows);$i++){
	$rows[$i][$field_name] = random_string($rows[$i][$field_name], $rows[$i][$field_gender]);
}

print_comand($rows,'Random');
echo '</Commands>';

close_context();

//=========================================================================

function print_comand($rows, $tag_name){
	global $field_name, $field_gender, $field_key, $table, $ctx;

	// for($i=0; $i<count($rows);$i++){
	// 	$row = $rows[$i];

	// 	$name = utf8($row[$field_name]);
	// 	$gender = $row[$field_gender];
	// 	$key = $row[$field_key];

	// 	$cmd = "update $table set $field_name='{$name}',$field_gender='{$gender}' where $field_key='{$key}';";
	// 	echo "<$tag_name gender='{$gender}' key='{$key}'>".$cmd."</$tag_name>";

	// 	//$ctx->Execute($cmd);
	// }

	for($i=0; $i<count($rows);$i++){
		$row = $rows[$i];

		$name = utf8($row[$field_name]);
		$gender = $row[$field_gender];
		$key = $row[$field_key];

		echo "update $table set $field_name='{$name}',$field_gender='{$gender}' where $field_key='{$key}';\n";
	}
}

function random_string($str, $gender){
	global $male, $female;

	$newname = '';
	if($gender == 1)
		$newname = $male[rand(0,count($male)-1)];
	else
		$newname = $female[rand(0,count($female)-1)];

	$result = mb_substr($str,0,1).mb_substr($newname,1,strlen($newname)-1);

	//echo utf8($result);
	//exit();

	return ($result);
}
?>
