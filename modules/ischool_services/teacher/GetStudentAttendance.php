<?php
require_once '../config.php';

//���v�ϥΪ̡A���o�ϥΪ̸�T�C
$ctx = init_context($_GET['access_token']);

$request = simplexml_load_string($HTTP_RAW_POST_DATA);

if(!$request)
	throw_error(ERR_REQUEST_INVALID,'Request format invalid.<![CDATA['.$HTTP_RAW_POST_DATA.']]>');

$userinfo = $ctx->GetUserInfo('teacher');

if(!$userinfo) { //�ˬd�p�G�ϥΪ̤��s�b�C
	throw_error(ERR_USER_DONOT_EXISTS, '�ϥΪ̤��s�b�A�i�ॼ�i��b���s���C');
}

$curr_year = curr_year();
$curr_seme = curr_seme();

if($request->Condition->SchoolYear)
	$curr_year = $request->Condition->SchoolYear;

if($request->Condition->SchoolYear)
	$curr_seme = $request->Condition->Semester;

$curr_semester = sprintf ("%03s%s",$curr_year,$curr_seme); //101,2 -> 1012

$class_id=$request->Condition->ClassID;//101_2_07_01

$sql = "
select school_class.class_id,stud_base.student_sn, stud_base.stud_name,stud_base.stud_id,stud_seme.seme_num,
	stud_absent.year,stud_absent.semester,stud_absent.date,stud_absent.absent_kind,stud_absent.section,
	CONCAT(school_class.c_year,school_class.c_name) class_name
from stud_base join stud_seme on stud_base.student_sn = stud_seme.student_sn
	join school_class on stud_seme.seme_year_seme = CONCAT(school_class.year,school_class.semester)
		and stud_seme.seme_class = CONCAT(school_class.c_year,school_class.c_name)
	join stud_absent on stud_absent.class_id = school_class.class_id 
		and stud_absent.stud_id = stud_base.stud_id
where stud_seme.seme_year_seme in ('$curr_semester') and school_class.class_id='$class_id' and school_class.enable=1
order by school_class.class_sn,stud_seme.seme_num,stud_absent.date,stud_absent.section";

$result = $ctx->Execute($sql);

$rows = array();

while($row = $result->FetchNext()){
	array_push($rows, $row);
}

echo '<StudentAttendances>';
$student_sn = '';
$student_current = '';
$occur_date_current = '';

for($i = 0; $i<count($rows);){ //Loop Student
	$row = $rows[$i];

	$student_sn = $row['student_sn'];
	$occur_date = $row['date'];

	echo "<Student>";	
	echo "<StudentID>{$student_sn}</StudentID>";	 //student_sn �N�O ID ���N��A�O�y�����C
	echo "<StudentName>";
	echo utf8($row['stud_name']);
	echo "</StudentName>";
	echo "<StudentNumber>{$row['stud_id']}</StudentNumber>"; //�o�ӬO�Ǹ��C
	echo "<SeatNumber>{$row['seme_num']}</SeatNumber>";
	echo utf8("<ClassName>{$row['class_name']}</ClassName>");
	echo "<ClassID>{$row['class_id']}</ClassID>";

	echo '<DetailsList>'; //DetailList Begin

	$student_current = $student_sn;
	for(; $i<=count($rows);){
		$row = $rows[$i];

		$student_sn = $row['student_sn'];
		$occur_date = $row['date'];

		if($student_current != $student_sn){
			break;
		}

		echo "<Detail OccurDate='{$occur_date}' SchoolYear='{$curr_year}' Semester='{$curr_seme}' i='$i' t='$t'>";

		$occur_date_current = $occur_date;
		for(; $i<=count($rows);){
			$row = $rows[$i];

			$student_sn = $row['student_sn'];
			$occur_date = $row['date'];			
			$absence_type = utf8($row['absent_kind']);
			$period = utf8($row['section']);

			if($student_current != $student_sn){
				break;
			}

			if($occur_date_current != $occur_date){
				break;
			}

			echo "<Period AbsenceType='$absence_type' AttendanceType='' i='$i' t='$t' x='$x'>{$period}</Period>";
			$i++; //����[�@��C
		}

		echo "</Detail>";
	}

	echo '</DetailsList>'; //DetailList End
	echo "</Student>";
}
echo '</StudentAttendances>';

close_context();
?>
