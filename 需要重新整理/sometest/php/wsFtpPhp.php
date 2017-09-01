<?php 
//http://localhost:81/wsFtpPhp.php
header("content-type:text/html;charset=utf-8");

$client=new SoapClient('http://localhost:2015/WebService/GDFtpWebService.asmx?wsdl');
$result=$client->uploadFilesWithRecord(array(
"ftpIP"=>"ftp://127.0.0.1:592/",
//"ftpPath"=>"",
"ftpUserName"=>"ftp",
"ftpPassWord"=>"123456",
"sqlUserID"=>"sa",
"sqlPassword"=>"123456",
"sqlServer"=>"SONG",
"sqlDatabase"=>"MyDB",
"path"=>'C:\\Users\\admin\\Desktop\\',
"fileNameList"=>array("工作报告.docx","asp.net笔记.txt","web service.txt"),
"newFileName"=>"new",
));
print_r($result->uploadFilesWithRecordResult);

?>