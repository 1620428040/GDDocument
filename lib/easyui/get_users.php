<?php
$data=$_POST["request"];
$data=json_decode(base64_decode($data));


$result=array(
	array(
		"firstname"=>"名",
		"lastname"=>"姓",
		"phone"=>"手机号",
		"email"=>"电子邮箱"
	),
	array(
		"firstname"=>"名2",
		"lastname"=>"姓2",
		"phone"=>"手机号2",
		"email"=>"电子邮箱2"
	)
);
$json=json_encode($result);
file_put_contents("data.json", $json);
echo $json;
?>