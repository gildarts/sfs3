<?php
require_once '../config.php';

//授權使用者，取得使用者資訊。
$ctx = init_context($_GET['access_token']);

$request = simplexml_load_string($HTTP_RAW_POST_DATA);

if(!$request)
	throw_error(ERR_REQUEST_INVALID,'Request format invalid.<![CDATA['.$HTTP_RAW_POST_DATA.']]>');

$curr_semester = sprintf ("%03s%s",curr_year(),curr_seme()); //101,2 -> 1012
$curr_year = curr_year();
$curr_seme = curr_seme();

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

echo '<StudentAttendances>';
$student_sn = '';
$previous = '';

while($row = $result->FetchNext()){ //Loop Student
	echo "<Student>";	
	
	echo "<StudentID>{$row['student_sn']}</StudentID>";	 //student_sn 就是 ID 的意思，是流水號。
	echo utf8("<StudentName>{$row['stud_name']}</StudentName>");
	echo "<StudentNumber>{$row['stud_id']}</StudentNumber>"; //這個是學號。
	echo "<SeatNumber>{$row['seme_num']}</SeatNumber>";
	echo utf8("<ClassName>{$row['class_name']}</ClassName>");
	echo "<ClassID>{$row['class_id']}</ClassID>";

	echo '<DetailsList>';
	$occur_date_previous = '';
	while($row = $result->FetchNext()){ //Loop Occur Date
		$student_sn = $row['student_sn'];
		$occur_date = $row['date'];

		if($previous == '')
			$previous = $student_sn;

		echo "<Detail OccurDate='{$occur_date}' SchoolYear='{$curr_year}' Semester='{$curr_seme}'>";
		while($row = $result->FetchNext()){ //Loop Detail (Period)
			$occur_date = $row['date'];
			$absence_type = utf8($row['absent_kind']);
			$period = utf8($row['section']);

			if($occur_date_previous =='')
				$occur_date_previous = $occur_date;

			if($period == 'uf' || $period == 'df')
				$AttendanceType = utf8('集會');
			else
				$AttendanceType = utf8('一般');

			echo "<Period AbsenceType='{$absence_type}' AttendanceType='{$AttendanceType}'>{$period}</Period>";	

			if($occur_date_previous != $occur_date){
				$occur_date_previous = $occur_date;
				break;
			}
		}
		echo "</Detail>";

		if($student_sn != $previous){
			$previous = $student_sn;
			break;
		}
	}
	echo '</DetailsList>';
	echo "</Student>";
}
echo '</StudentAttendances>';

close_context();
?>
