<?
require_once '../config_old.php';
require_once '../servicehelper_old.php'; //提供 Xml Service 的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();

begin_service_output(); //開始輸出資料。
$c_curr_seme = sprintf ("%03s%s",curr_year(),curr_seme());
$c_class_id="101_2_07_01";

$sql = 
"select distinct(b.student_sn),b.stud_name,a.stud_id,c.seme_num,d.c_year, right(d.class_id,2) class_num,d.class_id from stud_absent a 
inner join stud_seme c on a.stud_id=c.stud_id and concat(a.year,a.semester)=case when left(c.seme_year_seme,1)=0 then right(c.seme_year_seme,3) else c.seme_year_seme end 
inner join stud_base b on c.student_sn=b.student_sn 
inner join school_class d on a.year=d.year and a.semester=d.semester and c.seme_class=CONCAT(d.c_year,right(d.class_id,2)) 
where d.enable=1 and (b.stud_study_cond=0 or b.stud_study_cond=5) and c.seme_year_seme=$c_curr_seme and d.class_id='$c_class_id' 
order by b.student_sn,b.stud_name,a.stud_id,c.seme_num";

//echo $sql."<br/>";

$conn=mysql_connect($mysql_host,$mysql_user,$mysql_pass ) or die("mysql_connect() failed.");
mysql_select_db($mysql_db,$conn) or die("mysql_select_db() failed.");

$result=mysql_query($sql,$conn);

$xml_title="<StudentAttendances>";

while($row = mysql_fetch_array ($result)){

$xml.=<<<EOD
	<Student>
		<StudentID>{$row[0]}</StudentID>
		<StudentName>{$row[1]}</StudentName>
		<StudentNumber>{$row[2]}</StudentNumber>
		<SeatNumber>{$row[3]}</SeatNumber>
		<ClassName>{$row[4]}年{$row[5]}班</ClassName>
		<ClassID>{$row[6]}</ClassID>
		<DetailList>
EOD;
$sql2 = "select distinct(a.date),a.year,a.semester from stud_absent a 
inner join stud_seme c on a.stud_id=c.stud_id and concat(a.year,a.semester)=case when left(c.seme_year_seme,1)=0 then right(c.seme_year_seme,3) else c.seme_year_seme end 
inner join stud_base b on c.student_sn=b.student_sn 
inner join school_class d on a.year=d.year and a.semester=d.semester and c.seme_class=CONCAT(d.c_year,right(d.class_id,2)) 
where d.enable=1 and (b.stud_study_cond=0 or b.stud_study_cond=5) and c.seme_year_seme=$c_curr_seme and d.class_id='$c_class_id'
and b.student_sn={$row[0]} order by a.date";

$result2=mysql_query($sql2,$conn);

while($row2 = mysql_fetch_array ($result2)){
$xml.=<<<EOD
			<Detail OccurDate="{$row2[0]}" SchoolYear="{$row2[1]}" Semester="{$row2[2]}">
EOD;

		$sql3 = "select a.absent_kind, case when a.section in ('uf','df') then '集會' else '一般' end absent_type, case when a.section='uf' then '升旗' when 'df' then '降旗' else a.section end as section from stud_absent a 
	inner join stud_seme c on a.stud_id=c.stud_id and concat(a.year,a.semester)=case when left(c.seme_year_seme,1)=0 then right(c.seme_year_seme,3) else c.seme_year_seme end 
	inner join stud_base b on c.student_sn=b.student_sn 
	inner join school_class d on a.year=d.year and a.semester=d.semester and c.seme_class=CONCAT(d.c_year,right(d.class_id,2)) 
	where d.enable=1 and (b.stud_study_cond=0 or b.stud_study_cond=5) and c.seme_year_seme=$c_curr_seme and d.class_id='$c_class_id'
	and b.student_sn={$row[0]} and a.date='{$row2[0]}' order by a.section";	

$result3=mysql_query($sql3,$conn);

while($row3 = mysql_fetch_array ($result3)){

$xml.=<<<EOD
		<Period AbsenceType="{$row3[0]}" AttendanceType="{$row3[1]}">{$row3[2]}</Period>
EOD;
}			
$xml.=<<<EOD
	</Detail>
EOD;
}

$xml.=<<<EOD
</DetailList></Student>
EOD;
}
$xml_end="</StudentAttendances>";

$xml=$xml_title.$xml.$xml_end;

$xml=utf8($xml);
echo $xml;
end_service_output(); //完成輸出。

?>
