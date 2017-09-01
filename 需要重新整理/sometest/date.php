<?php
date_default_timezone_set('PRC');

$year=intval(date("Y",time()));
$month=intval(date("m",time()))+1;
$day=intval(date("d",time()));

echo $year."  ".$month."  ".$day."  ".date("h:i:s",time())."\n";
echo time();
?>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<script type="text/javascript">
			var date=new Date();
			console.log(date.getTime());
		</script>
	</body>
</html>