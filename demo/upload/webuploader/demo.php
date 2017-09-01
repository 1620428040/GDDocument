<?php
$result=array();
$result["request"]=$_REQUEST;
$result["file"]=$_FILES;
$result["date"]=date("Y-m-d H:i:s");
$result_file="result.json";

//$resultList=file_exists($result_file) ? json_decode(file_get_contents($result_file)) : array();
//$resultList[]=$result;
//file_put_contents($result_file,json_encode($resultList)."\n",FILE_APPEND);
file_put_contents($result_file,json_encode($result).",\n",FILE_APPEND);


//$file = $_FILES['mof'];
//$type = trim(strrchr($_POST['test'], '.'), '.');
//
//if ($file['error'] == 0) {
//	if (!file_exists('./upload/upload.' . $type)) {
//		if (!move_uploaded_file($file['tmp_name'], '/Users/guodong/Desktop/upload.' . $type)) {
//			echo 'failed';
//		}
//	} 
//	else {
//		$content = file_get_contents($file['tmp_name']);
//		if (!file_put_contents('./upload/upload.' . $type, $content, FILE_APPEND)) {
//			echo 'failed';
//		}
//	}
//} 
//else {
//	echo 'failed';
//}
?>