<?php
header("Content-type: text/html; charset=utf-8");
header("Access-Control-Allow-Origin: *"); //允许的访问源：所有
$arr=array("erfer"=>"ergerg","ewf"=>"gerger");
echo json_encode($arr);
?>