<?
require_once '../config_old.php';
require_once '../servicehelper_old.php'; //���� Xml Service ��������ơC

header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/xml; charset=utf-8');

check_auth();

begin_service_output(); //�}�l��X��ơC

$xml=<<<EOD
<Body>
   <Response>
      <Exam>
         <Id>1</Id>
         <ExamName>��1���w�����q</ExamName>
         <Description />
         <DisplayOrder>1</DisplayOrder>
      </Exam>
      <Exam>
         <Id>2</Id>
         <ExamName>��2���w�����q</ExamName>
         <Description />
         <DisplayOrder>2</DisplayOrder>
      </Exam>
      <Exam>
         <Id>3</Id>
         <ExamName>��3���w�����q</ExamName>
         <Description />
         <DisplayOrder>3</DisplayOrder>
      </Exam>
   </Response>
</Body>
EOD;

$xml=iconv("big5","UTF-8",$xml);
echo $xml;
end_service_output(); //������X�C

?>
