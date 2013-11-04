<?
require_once '../config_old.php';
require_once '../servicehelper_old.php'; //提供 Xml Service 的相關函數。

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();

begin_service_output(); //開始輸出資料。

$xml=<<<EOD
<Body>
   <Response>
      <Exam>
         <Id>1</Id>
         <ExamName>第1次定期評量</ExamName>
         <Description />
         <DisplayOrder>1</DisplayOrder>
      </Exam>
      <Exam>
         <Id>2</Id>
         <ExamName>第2次定期評量</ExamName>
         <Description />
         <DisplayOrder>2</DisplayOrder>
      </Exam>
      <Exam>
         <Id>3</Id>
         <ExamName>第3次定期評量</ExamName>
         <Description />
         <DisplayOrder>3</DisplayOrder>
      </Exam>
   </Response>
</Body>
EOD;

$xml=iconv("big5","UTF-8",$xml);
echo $xml;
end_service_output(); //完成輸出。

?>
