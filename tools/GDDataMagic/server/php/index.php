<?php
require("writeToFile.php");
require("MySQLClass.php");
require("DataMagicClass.php");


try{
	$config=json_decode(file_get_contents("../config.json"),TRUE);
	$db=new MySQLClass($config["database"]);
	$db->connect();
	$magic=new DataMagicClass($db);
	$personMeta=$magic->getMeta("person");
	writeToFile($personMeta,"","../../log.log");
}
catch(Exception $e){
	writeToFile($e,"","../../error.log");
}
?>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<script type="text/javascript">
			var json=<?php echo json_encode($personMeta);?>;
			console.log(json);
		</script>
	</body>
</html>