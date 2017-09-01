<?php
//大文件下载，不需要显式的调用缓存区，只要将文件内容分片的放到缓存中，当超过缓存区的大小时，会自动将缓存的内容输出

//文件在服务器上的路径,在php中"/"和"\\"作用相同
//$path = "G:/软件安装包/Adobe_Photoshop_CS6_gr.zip";
//用收到的path参数作为文件地址
$path=isset($_REQUEST["path"])?$_REQUEST["path"]:FALSE;
if(!$path){
	echo "需要设置文件地址path";
	return;
}
$name=pathinfo($path,PATHINFO_BASENAME);//文件保存到用户本地时的文件名，这里是截取的服务器上的文件名

//文件路径转码，因为windows当前的字符集是gb2312，这样做可以解决无法正确读取中文路径的问题
$name = iconv("utf-8", "gb2312", $name);
$path = iconv("utf-8", "gb2312", $path);

//首先要判断给定的文件存在与否
if (!file_exists($path)) {
	echo "没有该文件文件:".$path;
	return;
}
//检查用户是否有下载权限
//if(!strpos($path, OA_FILE_ROOT."/zip/")){
//	echo "没有下载权限";
//	return;
//}

$fp = fopen($path, "r");//文件指针
$size = filesize($path);

//下载文件需要用到的响应头
Header("Content-type: application/octet-stream");//以二进制的格式下载
Header("Accept-Ranges: bytes");//允许断点续传
Header("Accept-Length:" . $size);
Header("Content-Disposition: attachment; filename=" . $name);//以附件的形式下载，文件不会在用户页面中打开

$buffer = 1024;//每次读取的大小
$file_count = 0;//已经传输的内容的长度
//向浏览器返回数据
while (!feof($fp) && $file_count < $size) {
	$file_con = fread($fp, $buffer);
	$file_count += $buffer;
	echo $file_con;
}
fclose($fp);
//unlink($path);//一次性的下载，下载结束后删除文件
?>
