<?php
require 'config.php';

sfs_check();
?>

<style>
	a:visited{
		color:blue;
	}
	a:hover{
		color:red;
	}
</style>

<?php

echo '<h2>ischool 整合設定畫面!<br/></h2>';

echo "<h2>$callback_url</h2>";

echo '<h3>'.$redirect_url.'</h3>';

echo "<a href='{$redirect_url}'>試試看!</a><br/>";

//trigger_error('hello');
?>
