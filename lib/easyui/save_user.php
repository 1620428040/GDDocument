<?php
$json=json_encode($_REQUEST);
file_put_contents("data.json", $json);
$result=array("result"=>"ok");
echo json_encode($result);
?>