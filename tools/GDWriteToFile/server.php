<?php
require("main.php");
//echo "==>".$_POST['data']."<==";
$data=json_decode($_POST['data'],TRUE);
writeToFile($data,$_POST['title'],$_POST['filepath'],$_POST['cover'],$_POST['tag']);
//echo "ok";
?>