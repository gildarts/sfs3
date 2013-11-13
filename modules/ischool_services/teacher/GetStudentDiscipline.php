<?php
/*
<select name="reward_kind" size="1" style="background-color:#FFFFFF;font-size:13px">
<option value="">-- ��ܼ��g --
</option><option value="1">�ż��@��</option>
<option value="2">�ż��G��</option>
<option value="3">�p�\�@��</option>
<option value="4">�p�\�G��</option>
<option value="5">�j�\�@��</option>
<option value="6">�j�\�G��</option>
<option value="7">�j�\�T��</option>
<option value="-1">ĵ�i�@��</option>
<option value="-2">ĵ�i�G��</option>
<option value="-3">�p�L�@��</option>
<option value="-4">�p�L�G��</option>
<option value="-5">�j�L�@��</option>
<option value="-6">�j�L�G��</option>
<option value="-7">�j�L�T��</option>
</select>
*/

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
select a.student_sn,d.class_id,c.seme_num,b.stud_name,
	case when a.reward_kind>0 then '��' else '�g' end Merit,reward_date,reward_reason,d.year,d.semester,
	case a.reward_kind when 5 then 1 when -5 then 1 when 6 then 2 when -6 then 2 when 7 then 3 when -7 then 3 else 0 end,
	case a.reward_kind when 3 then 1 when -3 then 1 when 4 then 2 when -4 then 2 else 0 end,
	case a.reward_kind when 1 then 1 when -1 then 1 when 2 then 2 when -2 then 2 else 0 end from reward a 
inner join stud_base b on a.student_sn=b.student_sn
inner join stud_seme c on b.student_sn=c.student_sn and a.reward_year_seme=case when left(c.seme_year_seme,1)=0 then right(c.seme_year_seme,3) else c.seme_year_seme end
inner join school_class d on concat(d.year,d.semester)=$curr_semester and concat(d.c_year,right(d.class_id,2))=c.seme_class 
where d.enable=1 and (b.stud_study_cond=0 or b.stud_study_cond=5) and a.reward_year_seme=$curr_semester and d.class_id='$class_id'";

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

		echo "<Detail OccurDate='{$occur_date}' SchoolYear='{$curr_year}' Semester='{$curr_seme}'>";

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

			echo "<Period AbsenceType='$absence_type' AttendanceType=''>{$period}</Period>";
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
