<?php
define("lb", "<br/>");
//将二进制的字符串转化成十进制（或者十六进制）的数字的方法

//返回字符的 ASCII 码值
//注意：实际上是返回字符串第一个字节的十进制形式，所以不一定在ASCII编码表中，有可能是其他字符编码的第一个字节
//int ord ( string $string )
echo ord("h").lb;//104
echo ord("我").lb;//230,只返回第一个字节

//返回相对应于 ascii 所指定的单个字符。
//string chr ( int $ascii )
echo chr("104").lb;//h
echo chr("230").lb;//�

//把二进制的参数 str 转换为的十六进制的字符串。转换使用字节方式，高四位字节优先。
//string bin2hex ( string $str )
//注意：这个函数不是转换数字的进制，而是返回字符编码的十六进制形式
echo bin2hex("h").lb;//68

//转换十六进制字符串为二进制字符串
//string hex2bin ( string $data )(PHP >= 5.4.0)
//echo hex2bin("68").lb;//h

//字符串长度的计算方法
//count() - 计算数组中的单元数目，或对象中的属性个数
echo count("你好，世界！hello,word!").lb;//1
//mb_strlen() - 获取字符串的长度(根据字符串编码)
echo mb_strlen("你好，世界！hello,word!","utf-8").lb;//17
//strlen() - 获取字符串的长度(字符串实际占用的空间)
echo strlen("你好，世界！hello,word!").lb;//29

//输出一个字符串的十进制形式
function strToDec($str,$glue){
	$length = strlen($str);
	$result = array();
	for($i=0;$i<$length;$i++){
		$result[]=ord($str[$i]);
	}
	return join($glue, $result);
}
function decToStr($dec,$glue){
	$codes=explode($glue, $dec);
	$result = "";
	for($i=0;$i<count($codes);$i++){
		$result.=chr($codes[$i]);
	}
	return $result;
}
//输出十六进制
//$base是指数字的进制
function strToNumber($str,$glue,$base){
	$length = strlen($str);
	$result = array();
	for($i=0;$i<$length;$i++){
		$result[]=base_convert(ord($str[$i]), 10, $base) ;
	}
	return join($glue, $result);
}
function numberToStr($dec,$glue,$base){
	$codes=explode($glue, $dec);
	$result = "";
	for($i=0;$i<count($codes);$i++){
		$result.=chr(base_convert($codes[$i], $base, 10));
	}
	return $result;
}
$string = "你好，世界！hello,word!";
var_dump($string);//原始中文
$dec=strToDec($string, " ");
var_dump($dec);
$str=decToStr($dec, " ");
var_dump($str);
//十六进制
$dec=strToNumber($string, " ",16);
var_dump($dec);
$str=numberToStr($dec, " ",16);
var_dump($str);
//二进制
$dec=strToNumber($string, " ",2);
var_dump($dec);
$str=numberToStr($dec, " ",2);
var_dump($str);
?>

<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		
	</body>
</html>