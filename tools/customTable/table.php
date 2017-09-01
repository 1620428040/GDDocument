<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
		<script src="../../framework/jQuery/jquery/jquery-3.0.0.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="interpreter.js" type="text/javascript" charset="utf-8"></script>
		<link rel="stylesheet" type="text/css" href="style.css"/>
	</head>
	<body>
		<div id="table-box">
		</div>
		<script type="text/javascript">
			var base=<?php echo file_get_contents("base.json");?>;
			createStructure("#table-box",base.meta.person,"table","default",base.data.person);
		</script>
	</body>
</html>