<?php
//参考http://php.net/manual/zh/function.readfile.php
$file = 'video/test.mp4';

if (file_exists($file)) {
    //header('Content-Description: File Transfer');//搜不到具体是做什么用的
    //Content-Disposition响应头表示希望浏览器如何处置文件inline/attachment(内嵌/下载)，filename提供一个默认的文件名
    
    //表示希望浏览器下载文件而不是内嵌到页面上
    //header('Content-Type: application/octet-stream');
    //header('Content-Disposition: attachment; filename="'.basename($file).'"');
    
    //对于可以内嵌在网页中的类型，这样写可以使内容内嵌到网页中
    header('Content-Type: video/mp4');//如果是一个不能内嵌的mime类型，仍然会被下载
    header('Content-Disposition: inline; filename="'.basename($file).'"');//inline,内嵌;attachment,附件
    
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);//将文件读取到输出缓存中，相当于echo file_get_contents($file);???
    
    exit;
}
?>