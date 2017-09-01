<?php
//$uploadPath="C:/Users/admin/Desktop/";//绝对目录
$uploadPath="./upload/";//相对目录
$uploadFileName="upload";//不包含扩展名的文件名

$file = $_FILES['fileData'];//上传的文件
$type = trim(strrchr($_POST['fileName'], '.'), '.');//获取扩展名
$path=$uploadPath.'/'.$uploadFileName.'.' . $type;//文件在服务器上保存的路径

// print_r($_POST['test']);exit;
if ($file['error'] == 0) {
	if (!file_exists($path)) {
		if (!move_uploaded_file($file['tmp_name'],$path)) {
			echo 'failed';
		}
	}
	else {
		$content = file_get_contents($file['tmp_name']);
		if (!file_put_contents($path, $content, FILE_APPEND)) {
			echo 'failed';
		}
	}
} 
else {
	echo 'failed';
}

?>