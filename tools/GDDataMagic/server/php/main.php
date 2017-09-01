<?php
	require("writeToFile.php");
	require("MySQLClass.php");
	require("DataMagicClass.php");
	$config=json_decode(file_get_contents("../config.json"),TRUE);
	$db=new MySQLClass($config["database"]);
	$db->connect();
	$magic=new DataMagicClass($db);
	$data=$magic->shortcutOperate(array("person"), $_REQUEST);
	//$data=$magic->getMeta("person");
	echo json_encode($data);
	$db->close();
?>