<?
require_once '../config.php';
require_once '../servicehelper.php'; //提供 Xml Service 的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();

begin_service_output(); //開始輸出資料。
$c_curr_seme = sprintf ("%03s%s",curr_year(),curr_seme());
$ClassID="909";

$sql = 
"select stud_seme.stud_id,seme_num,stud_seme.student_sn,stud_name 
from stud_seme
join  stud_base on stud_base.stud_id=stud_seme.stud_id
where stud_seme.seme_class='$ClassID' and seme_year_seme='$c_curr_seme' 
order by seme_num ";

//echo $sql."<br/>";

$conn=mysql_connect($mysql_host,$mysql_user,$mysql_pass ) or die("mysql_connect() failed.");
mysql_select_db($mysql_db,$conn) or die("mysql_select_db() failed.");

$result=mysql_query($sql,$conn);
$fieldnum=mysql_num_fields($result);

$xml_title="<Response>\r";

while($row = mysql_fetch_array ($result)){
$xml.=<<<EOD
   <Student>
      <Id>{$row[2]}</Id>
      <Name>{$row[3]}</Name>
      <StudentNumber>{$row[0]}</StudentNumber>
      <SeatNo>{$row[1]}</SeatNo>
      <LoginName></LoginName>
   </Student>
EOD;

}
$xml_end="</Response>";

$xml=$xml_title.$xml.$xml_end;

$xml=iconv("big5","UTF-8",$xml);
echo $xml;
end_service_output(); //完成輸出。

?>
