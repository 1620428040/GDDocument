<?php
$result=shell_exec("D:\\xpdf\\pdftotext -cfg \"D:\\xpdf\\xpdfrc\" \"C:\\Users\\admin\\Desktop\\q1.pdf\" -");
file_put_contents("result", $result);
?>