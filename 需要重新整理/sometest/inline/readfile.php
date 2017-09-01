<?php
//参考http://php.net/manual/zh/function.readfile.php
$file = '../pdf/123.pdf';

if (file_exists($file)) {
    //header('Content-Description: File Transfer');//搜不到具体是做什么用的
    
//    header('Content-Type: application/octet-stream');
//    header('Content-Disposition: attachment; filename="'.basename($file).'"');//表示希望浏览器如何处置文件，提供一个默认的文件名
    
    //对于可以内嵌在网页中的类型，这样写可以使内容内嵌到网页中
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="'.basename($file).'"');
    
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
else{
	echo "nofile";
}
?>
