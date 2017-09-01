<?php
require("../server/php/writeToFile.php");
require("../server/php/MySQLClass.php");
require("../server/php/DataMagicClass.php");


try{
	$config=json_decode(file_get_contents("../server/config.json"),TRUE);
	$db=new MySQLClass($config["database"]);
	$db->connect();
	$magic=new DataMagicClass($db);
	$personMeta=$magic->getMeta("person");
	
	writeToFile($personMeta,"","../log.log");
}
catch(Exception $e){
	echo "error";
	writeToFile($e,"","../error.log");
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
		<link rel="stylesheet" type="text/css" href="../client/css/style.css"/>
		<script src="../client/js/jquery-3.0.0.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../client/js/DataMagicClass.js" type="text/javascript" charset="utf-8"></script>
	</head>
	<body>
		<script type="text/javascript">
//			var meta=<?php //echo json_encode($personMeta);?>;
//			aopBefore(null,"DMTable",function(){
//				alert("创建表格");
//			});
//			var person=new DataMagicClass("person","default",<?php //echo json_encode($personMeta);?>);
//			var table=person.table();
//			$("body").append(table.container);
//			$("body").append(person.form("create").container);
//			$("body").append(person.toolbar(table,"read").container);
		</script>
		<pre><?php print_r($personMeta);?></pre>
	</body>
</html>