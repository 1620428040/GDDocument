<?php
$tableName="foo";
$meta=array(
	"bindTable"=>$tableName,
	"feature"=>array("browse","insert","delete","update","search","refresh"),
	"fieldList"=>array()
);
$pdo=new PDO('mysql:host=localhost;dbname=test', "root", "123456");
$isExists=$pdo->query("SHOW TABLES LIKE '".$tableName."'")->fetchAll(PDO::FETCH_ASSOC);
if($isExists==null){//如果表不存在，则报错
	$result=array("要修改的表不存在！");
}
else{
	$result=$pdo->query("SHOW COLUMNS FROM `".$tableName."`")->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row){
		$field=array();
		$field["notNull"]=$row["Null"]!=="NO";
		if($row["Key"]==="PRI"){
			$meta["primaryFields"]=$row["Field"];
		}
		$type=explode("(",$row["Type"]);
		$field["dataType"]=$type[0];
		$field["maxlength"]=intval(str_replace(")", "", $type[1]));
		$meta["fieldList"][$row["Field"]]=$field;
	}
}
$pdo=null;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		<pre><?php print_r($isExists);?></pre>
		<pre><?php print_r($result);?></pre>
		<pre><?php echo json_encode($meta);?></pre>
	</body>
</html>