<?php
//可以通过shell执行服务器上安装的程序，程序的返回值以字符串的形式输出
$text = shell_exec("D:\\xpdf\\pdftotext -cfg \"D:\\xpdf\\xpdfrc\" \"D:/oafiles/realfile/2016/06/23/156864\" -");
?>