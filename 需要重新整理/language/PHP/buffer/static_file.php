<?php
//第一次访问时，将输出结果从缓存中取出，生成一个静态文件，以后直接打开静态文件
$filepath='static_file.html';
if(file_exists($filepath)){
	readfile($filepath);
}
else{
	ob_start();
	echo 'static file test.<br/>输出静态文件作为缓存';
	$string = ob_get_contents();
	file_put_contents($filepath, $string);
	ob_flush();
	flush();
}
?>