<?php
require_once ('config.php');

$ctx = init_context($_GET['access_token']);

$personal = ($ctx->GetUserInfo('teacher'));

if($personal)
	echo "<h6>{$personal['user_sn']}</h6>";
else
	echo "You aren't a valid user.";

// $result = $ctx->Execute('select name from teacher_base limit 10');

// echo '<root>';
// while($row = $result->FetchNext()){
//     echo "<h6>".utf8($row[0])."</h6>";
// }

// echo '</root>';

close_context();
?>