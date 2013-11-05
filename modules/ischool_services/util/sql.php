<?php

/**********************************
  MYSQL 連接設定
***********************************/
// mysql 主機
$mysql_host ="localhost";

// mysql 使用者
$mysql_user ="root";

// mysql 密碼
$mysql_pass ="12345";

// 資料庫名稱
$mysql_db   ="sfs3";
  $conn=mysql_connect($mysql_host,$mysql_user,$mysql_pass ) or die("mysql_connect() failed.");
  mysql_select_db($mysql_db,$conn) or die("mysql_select_db() failed.");
mysql_query("SET NAMES 'latin1'");
?>
<html>

<head>

<meta charset="big5" />


<meta name="viewport" content="width=device-width" />
<form method="post">
<textarea name=sql rows=20 cols=80><?=$sql?></textarea>
<input type=submit name=inq value="查詢">
</form>
<?

$sql=$_POST[sql];
if($sql<>"")
{	
	$datas=split("\r\n",$sql);
	for($i=0;$i<count($datas);$i++)
	{
		$strSQL=$datas[$i];
		//echo $strSQL."<br/>";
		if($strSQL<>"")
		{
			$result=mysql_query($strSQL,$conn);
			var_dump($result);
		    if(!$result==true)
		            echo  $strSQL."<br/>";   
		    else
		    	 echo "已匯入第".($i+1)."筆<br/>";
		}

	}
}	

?>

</body>

</html>