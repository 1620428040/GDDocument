<?php
//$bin=("a", "hello world");
$data=array(
	"grger"=>"freger",
	"feref"=>array(
		"gferg",
		"gfregter",
		"trr"=>4234
	)
);
$data={};
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		<pre><?php echo $data;?>
		</pre>
		<pre><?php print $data;?>
		</pre>
		<pre><?php printf("=>%s<=","rfefer");?>
		</pre>
		<pre><?php print_r($data);?>
		</pre>
		<pre><?php var_dump($data);?>
		</pre>
		<pre><?php var_export($data);?>
		</pre>
	</body>
</html>