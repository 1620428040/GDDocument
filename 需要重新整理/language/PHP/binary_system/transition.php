<?php
define("lb", "<br/>");
//主要记录不同进制之间的转换方法和相应的函数。其实只要记得base_convert函数就可以了
//这些函数的参数可以是字符串，也可以是数字。返回值是字符串
//如果是参数是数字的话，会先被转换为字符串，如果某一位上格式不对，这一位以及之后的部分会被丢弃，只转换这一位之前的部分
//注意：bin2hex不是这一类的函数。而是将ASCII字符串转换成十六进制表示的形式。详情见ascii.php

//十进制转换为二进制
//string decbin ( int $number )
echo decbin(10).lb;//1010
echo decbin("10").lb;//1010

//十进制转换为八进制
//string decoct ( int number ) 

//十进制转换为十六进制
//string dechex ( int number ) 

//二进制转换为十六进制
//string bin2hex ( string str )
echo dechex(bindec("1010")).lb;//a
//bin2hex()不是二进制转换为十进制的函数，而是将ASCII字符串转换成十六进制表示的形式
echo bin2hex("1010").lb;//31303130
echo bin2hex("a").lb;//61

//二进制转换为十进制
//number bindec ( string binary_string ) 
echo bindec("1010").lb;//10
echo bindec(1010).lb;//10
echo bindec(1013).lb;//5//最后一位格式不对，所以只转换前几位

//在任意进制之间转换数字
//string base_convert ( string number, int frombase, int tobase ) 
echo base_convert("1010", 2, 10);//10
?>