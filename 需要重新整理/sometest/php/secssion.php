<?php
session_start();//必须在任何输出之前启动session
if($_SESSION["editonline"]==null){
	echo "unset<br/>";
	$_SESSION["editonline"]="pageoffice";
}
else{
	echo "isset<br/>";
	print_r($_SESSION);
}
?>