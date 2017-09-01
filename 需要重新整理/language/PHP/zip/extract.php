<?php
$zip = new ZipArchive;//新建一个ZipArchive的对象
if ($zip -> open('test.zip') === TRUE) {
	$zip -> extractTo('images');//假设解压缩到在当前路径下images文件夹内
	$zip -> close();//关闭处理的zip文件
}
?>