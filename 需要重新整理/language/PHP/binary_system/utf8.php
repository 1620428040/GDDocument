<?php
/* 字符集和字符编码的问题
 * 字符集，例如Unicode，规定了每个字符对应的“码值”
 * 比如“我们”，转换成Unicode码是“\u6211\u4eec”
 * 字符编码，例如utf-8，规定了码值如何表示为二进制数据，
 * 比如“我们”，转换成utf-8格式，是“11100110 10001000 10010001 11100100 10111011 10101100”
 * UCS编码实际上就是Unicode字符集对应的编码方式（Unicode的学名是"Universal Multiple-Octet Coded Character Set"，简称为UCS）
 * "UCS-2BE"。UCS又分为UCS-2,UCS-4。BE、LE是指字节序，大头在前还是小头在前
 * ASCII码既是字符集又是字符编码
 */
/* 参考http://www.cnblogs.com/xinruzhishui/p/5763894.html
 * utf-8是一种可变字节的编码方式，最多可以使用6个字节表示一个字符
 * 1字节 0xxxxxxx 
 * 2字节 110xxxxx 10xxxxxx 
 * 3字节 1110xxxx 10xxxxxx 10xxxxxx 
 * 4字节 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx 
 * 5字节 111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 
 * 6字节 1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 
 * 将高位上的10去掉，只留下x表示的位置上的数字，然后转换为十六进制，就是码值
 * 例如，“我”存储为“11100110 10001000 10010001”
 * 去掉高位是“0110 001000 010001”
 * 转换为十六进制是“6211”，用Unicode码表示就是“\u6211”
 */
 
//将Unicode码解码
//在低于PHP5.4版本的PHP中，使用json_encode函数，会将输入的字符串转码成Unicode码
//在PHP5.4, 这个问题终于得以解决, Json新增了一个选项: JSON_UNESCAPED_UNICODE, 故名思议, 就是说, Json不要编码Unicode.
//在低版本中，可以通过以下方式将Unicode编码恢复
define("lb", "<br/>");
$data=array("a"=>"你好","b"=>"早上好");
$json=json_encode($data);
echo $json.lb;
echo decodeUnicode($json).lb;
echo decodeUnicode2($json).lb;

function decodeUnicode($str){
    return preg_replace_callback(
    	'/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str
	);
}
function decodeUnicode2($str){
    return preg_replace_callback(
    	'/\\\\u([0-9a-f]{4})/i',//获取Unicode编码的编码部分
    	function($matches){
    		$packed=pack("H*", $matches[1]);//将Unicode编码的编码部分按照十六进制的格式转化成二进制数据（字符串）
    		return mb_convert_encoding($packed, "UTF-8", "UCS-2BE");//转换编码格式，将UCS-2BE转换成"UTF-8"格式
    	},
    	$str
	);
}
?>