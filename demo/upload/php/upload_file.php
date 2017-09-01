<?php
//输出文件信息
if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK ){
	echo "错误：" . $_FILES["file"]["error"] . "<br>";
	exit;
}
else{
	echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
	echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
	echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
}

//检查文件的格式和大小
$allowedExts = array("gif", "jpeg", "jpg", "png");//允许上传的图片后缀
$allowedMime = array("image/gif","image/jpeg","image/jpg","image/pjpeg","image/x-png","image/png");

$fileName = explode(".", $_FILES["file"]["name"]);
$extension = end($fileName);// 获取文件后缀名

if (
	in_array($_FILES["file"]["type"], $allowedMime)&&
	in_array($extension, $allowedExts)&&
	$_FILES["file"]["size"] < 204800// 小于 200 kb
){
	echo "类型检查通过" . "<br>";
}
else{
	echo "非法的文件格式" . "<br>";
	exit;
}

//首先，要确定upload文件夹是否存在
//然后，要确定不会覆盖掉原有的文件
//将文件上传到 upload 目录下
move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
echo "文件存储在: " . "upload/" . $_FILES["file"]["name"] . "<br>";
?>