<?
require_once '../config.php';
require_once '../servicehelper.php'; //提供 Xml Service 的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();

begin_service_output(); //開始輸出資料。
$c_curr_seme = sprintf ("%03s%s",curr_year(),curr_seme());
$c_class_id="101_2_07_01";

$sql = 
"select a.student_sn,d.class_id,c.seme_num,b.stud_name,
case when a.reward_kind>0 then '獎' else '懲' end Merit,reward_date,reward_reason,d.year,d.semester,
case a.reward_kind when 5 then 1 when -5 then 1 when 6 then 2 when -6 then 2 when 7 then 3 when -7 then 3 else 0 end,
case a.reward_kind when 3 then 1 when -3 then 1 when 4 then 2 when -4 then 2 else 0 end,
case a.reward_kind when 1 then 1 when -1 then 1 when 2 then 2 when -2 then 2 else 0 end from reward a 
inner join stud_base b on a.student_sn=b.student_sn
inner join stud_seme c on b.student_sn=c.student_sn and a.reward_year_seme=case when left(c.seme_year_seme,1)=0 then right(c.seme_year_seme,3) else c.seme_year_seme end
inner join school_class d on concat(d.year,d.semester)=$c_curr_seme and concat(d.c_year,right(d.class_id,2))=c.seme_class 
where d.enable=1 and (b.stud_study_cond=0 or b.stud_study_cond=5) and a.reward_year_seme=$c_curr_seme and d.class_id='$c_class_id'";

//echo $sql."<br/>";

$conn=mysql_connect($mysql_host,$mysql_user,$mysql_pass ) or die("mysql_connect() failed.");
mysql_select_db($mysql_db,$conn) or die("mysql_select_db() failed.");

$result=mysql_query($sql,$conn);

$xml_title="<StudentDisciplineList>";

while($row = mysql_fetch_array ($result)){
$xml.=<<<EOD
	<Student>
		<StudentID>{$row[0]}</StudentID>
		<ClassID>{$row[1]}</ClassID>
		<SeatNumber>{$row[2]}</SeatNumber>
		<Name>{$row[3]}</Name>
		<DisciplineList>
			<Discipline GradeYear="" MeritFlag="{$row[4]}" OccurDate="{$row[5]}" OccurPlace="" Reason="{$row[6]}" SchoolYear="{$row[7]}" Semester="{$row[8]}">
				<Merit A="{$row[9]}" B="{$row[10]}" C="{$row[11]}" />
			</Discipline>
		</DisciplineList>
	</Student>
EOD;
}
$xml_end="</StudentDisciplineList>";

$xml=$xml_title.$xml.$xml_end;

$xml=utf8($xml);
echo $xml;
end_service_output(); //完成輸出。

?>
