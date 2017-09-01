<?php
header("Content-type: text/html; charset=utf-8"); 
$stateCode=array(
	0=>"操作成功",
	1=>"没有发生致命错误",
	2=>"发生一个致命错误",
	3=>"无效校验和。数据损坏",
	4=>"试图修改使用 'k' 命令锁定的压缩文件",
	5=>"写入磁盘错误",
	6=>"文件打开错误",
	7=>"错误的命令行选项",
	8=>"没有足够的内存进行操作",
	9=>"文件创建错误",
	10=>"没有找到与指定的掩码和选项匹配的文件",
	11=>"密码错误",
	255=>"用户中断操作"
);

$password="123456";
$savePath="D:\\system\\Desktop\\test.zip";
$filePath="D:\\system\\Desktop\\123.docx";
$exePath="D:\\Program Files\\WinRAR\\Rar.exe";
//$workPath="D:\\oafiles\\";
$workPath="D:\\system\\Desktop\\";

//测试程序是否存在
//$back=exec($exePath,$result,$state);

//压缩一个文件
$command="\"$exePath\" a -hp\"$password\" -w\"$workPath\" -scu \"$savePath\" \"$filePath\"";
$back=exec($command,$result,$state);
echo "<pre>";
echo "命令:".$command."\n";
echo "处理结果:\n";
print_r($result);
echo "状态码:".$stateCode[$state]."\n";
echo "返回值:\n";
print_r(mb_convert_encoding($back, "UTF-8", "GBK"));
echo "</pre>";
?>