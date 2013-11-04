<?php
require_once '../config.php';

$ctx = init_context($_GET['access_token']);

$userinfo = $ctx->GetUserInfo('teacher');

if(!$userinfo) { //檢查如果使用者不存在。
	throw_error(ERR_USER_DONOT_EXISTS, '使用者不存在，可能未進行帳號連結。');
}

$teacher_sn=$userinfo['user_sn'];
$c_curr_seme = sprintf ("%03s%s", curr_year(), curr_seme());

$class_name = teacher_sn_to_class_name($teacher_sn);
$class_id = $class_name[2];

$sql =
"select c.class_id, c.c_year, c.c_name ,count(*) studentcount from stud_base a
inner join stud_seme b on a.student_sn=b.student_sn
inner join school_class c on b.seme_year_seme=CONCAT(c.year,c.semester) and b.seme_class=CONCAT(c.c_year,c_name)
inner join teacher_base d on c.teacher_1=d.name
where c.enable=1 and (a.stud_study_cond=0 or a.stud_study_cond=5) and c.class_id='$class_id'
group by class_id, c_year, c_name";

$result = $ctx->Execute($sql);
while($row = $result->FetchNext()){

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