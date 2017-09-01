<?php
//pack是将不同格式的数据打包到二进制字符串中的函数
//string pack ( string $format [, mixed $args [, mixed $... ]] )
//$args是要打包的数据，后面允许有任意个要打包的数据作为参数
//$format是$args打包之前的格式

//用来打包本来就是字符串的数据，其实没有任何变化（除了空白部分可能会被替换）。因为本来就是二进制字符串了，想要将二进制数据输出，需要用ord函数
$word="你好，世界！           hello,word!";
var_dump($word);
$packed=pack("A*",$word);
var_dump($packed);

//A将字符串空白以 SPACE 字符 (空格) 填满
$str=(pack("A*", "中国"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);

//H十六进位字符串，高位在前
$str=(pack("H*", "fffe"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);

//C无号字符
$str=(pack("C*", "55","56","57"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);
   
//i字符 短整形  32位 4个字节 64位8个字节
$str=(pack("i", "100"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);
   
//s字符 短整形 2个字节
$str=(pack("s", "100"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);
   
//l字符 长整形 4个字节
$str=(pack("l", "100"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);

//f字符 单精度浮点 4个字节
$str=(pack("f", "100"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);

//d字符 双精度浮点 8个字节
$str=(pack("d", "100"));
echo  $str,"=",strlen($str),"字节\n";
getAscill($str);
   
function getAscill($str){
	$arr=str_split($str);
	foreach ($arr as $v){
		echo $v,"=",ord($v),"\n";
	}
	echo "=============\r\n\r\n";
}
?>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		
	</body>
</html>