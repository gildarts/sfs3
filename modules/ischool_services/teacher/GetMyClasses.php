<?php
require_once '../config.php';
require_once '../data_library.php'; //提供資料處理的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf8');

check_auth();

begin_service_output(); //開始輸出資料。
$teacher_sn=$_SESSION['session_tea_sn'];
$c_curr_seme = sprintf ("%03s%s", curr_year(), curr_seme());

$class_name = teacher_sn_to_class_name($teacher_sn);
$class_id = $class_name[2];

$sql =
"select c.class_id, c.c_year, c.c_name ,count(*) studentcount,d.teacher_sn from stud_base a
inner join stud_seme b on a.student_sn=b.student_sn
inner join school_class c on b.seme_year_seme=CONCAT(c.year,c.semester) and b.seme_class=CONCAT(c.c_year,c_name)
inner join teacher_base d on c.teacher_1=d.name
where c.enable=1 and (a.stud_study_cond=0 or a.stud_study_cond=5) and c.class_id='$class_id'
group by class_id, c_year, c_name";



$conn=mysql_connect($mysql_host,$mysql_user,$mysql_pass ) or die("mysql_connect() failed.");
mysql_select_db($mysql_db,$conn) or die("mysql_select_db() failed.");

$result=mysql_query($sql,$conn);

while($row = mysql_fetch_array ($result)){

    $class_full_name = class_id_to_full_class_name($row[0]);

$xml=<<<EOD
<ClassList>
   <Class ClassID="{$row[0]}">
    	<GradeYear>{$row[1]}</GradeYear>
    	<ClassName>{$class_full_name}</ClassName>
  	<StudentCount>{$row[3]}</StudentCount>
  </Class>
</ClassList>
EOD;
}

$xml=utf8($xml);
echo $xml;
end_service_output(); //完成輸出。

?>