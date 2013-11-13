<?php
/*
<select name="reward_kind" size="1" style="background-color:#FFFFFF;font-size:13px">
<option value="">-- 選擇獎懲 --
</option><option value="1">嘉獎一次</option>
<option value="2">嘉獎二次</option>
<option value="3">小功一次</option>
<option value="4">小功二次</option>
<option value="5">大功一次</option>
<option value="6">大功二次</option>
<option value="7">大功三次</option>
<option value="-1">警告一次</option>
<option value="-2">警告二次</option>
<option value="-3">小過一次</option>
<option value="-4">小過二次</option>
<option value="-5">大過一次</option>
<option value="-6">大過二次</option>
<option value="-7">大過三次</option>
</select>
*/

require_once '../config.php';

//授權使用者，取得使用者資訊。
$ctx = init_context($_GET['access_token']);

$request = simplexml_load_string($HTTP_RAW_POST_DATA);

if(!$request)
	throw_error(ERR_REQUEST_INVALID,'Request format invalid.<![CDATA['.$HTTP_RAW_POST_DATA.']]>');

$userinfo = $ctx->GetUserInfo('teacher');

if(!$userinfo) { //檢查如果使用者不存在。
	throw_error(ERR_USER_DONOT_EXISTS, '使用者不存在，可能未進行帳號連結。');
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
	case when a.reward_kind>0 then '獎' else '懲' end Merit,reward_date,reward_reason,d.year,d.semester,
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
	echo "<StudentID>{$student_sn}</StudentID>";	 //student_sn 就是 ID 的意思，是流水號。
	echo "<StudentName>";
	echo utf8($row['stud_name']);
	echo "</StudentName>";
	echo "<StudentNumber>{$row['stud_id']}</StudentNumber>"; //這個是學號。
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
			$i++; //關鍵加一格。
		}

		echo "</Detail>";
	}

	echo '</DetailsList>'; //DetailList End
	echo "</Student>";
}
echo '</StudentAttendances>';

close_context();
?>
