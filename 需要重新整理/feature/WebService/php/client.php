<?php
//php版的web service 客户端
header("content-type:text/html;charset=utf-8");
//SoapClient对象的构造函数，第一个参数是wsdl文件的链接，一般用于调用.net服务器上的web service。如果没有wsdl文件，则需要在第二个参数上定义web service服务器地址和uri

//不使用wsdl文件的时候
$client=new SoapClient(null,array('uri'=>'http://soap/','location'=>'http://localhost:81/phpwebservice/server.php'));
$result=$client->get();
echo $result."<br/>";
$result=$client->say('lee');
echo $result."<br/>";
$result=$client->add(3,9);
echo $result."<br/>";


//使用wsdl文件的时候
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