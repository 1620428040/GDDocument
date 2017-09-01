<?php
	//$allData=file_get_contents("person.json");//从json文件中读取
	
	//从后台读取
//	require("../server/php/writeToFile.php");
//	require("../server/php/MySQLClass.php");
//	require("../server/php/DataMagicClass.php");
//	$config=json_decode(file_get_contents("../server/config.json"),TRUE);
//	$db=new MySQLClass($config["database"]);
//	$db->connect();
//	$magic=new DataMagicClass($db);
//	$allData=$magic->getMeta("person");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
		<script src="../client/framework/jquery-3.0.0.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../client/framework/jQueryUI/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="../client/framework/jQueryUI/jquery-ui.min.css"/>
		<script src="../client/js/function.js" type="text/javascript" charset="utf-8"></script>
		<script src="../client/js/object.js" type="text/javascript" charset="utf-8"></script>
		<script src="../client/js/DataMagicClass.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="../client/css/style.css"/>
	</head>
	<body>
		<div class="DataMagic default">
			<div class="DMToolbar">
				<button type="button" class="DMButton" data-command="insert">rgregr</button>
			</div>
			<div class="DMList">
				<div class="DMItem">
					<div data-field="name"></div>
					<div data-field="birthDay"></div>
				</div>
			</div>
			<div class="DMForm">
				
			</div>
		</div>
		<script>
//			var allData=<?php //echo json_encode($allData);?>;
//			var meta=allData;
//			var data=meta.data;console.log(data);
			$(function(){
				var meta="person";
				var dataURL="../server/php/main.php";
				new DataMagicClass(".DataMagic",meta,dataURL,function(){
//					var table=this.table();
					var list=this.list();
				});
			});
		</script>
		<!--<pre><?php //print_r($allData);?></pre>-->
	</body>
</html>
