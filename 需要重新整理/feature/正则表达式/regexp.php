<?php
//在PHP中有两套正则表达式函数库。一套是由PCRE（Perl Compatible Regular Expression）库提供的。
//PCRE库使用和Perl相同的语法规则实现了正则表达式的模式匹配，其使用以“preg_”为前缀命名的函数。
//另一套是由POSIX（Portable Operation System interface）扩展库提供的。
//POSIX扩展的正则表达式由POSIX 1003.2定义，一般使用以“ereg_”为前缀命名的函数。 
//两套函数库的功能相似，执行效率稍有不同。一般而言，实现相同的功能，使用PCRE库的效率略占优势。

//查找
//int preg_match (string $pattern, string $content [, array $matches]) 

//替换
//string ereg_replace (string $pattern, string $replacement, string $string) 
//ereg_replace()在$string中搜索模式字符串$pattern，并将所匹配结果替换 为$replacement。
//当$pattern中包含模式单元（或子模式）时，$replacement中形如“\1”或“$1”的位置将依次被这些子 模式所匹配的内容替换。
//而“\0”或“$0”是指整个的匹配字符串的内容。
//需要注意的是，在双引号中反斜线作为转义符使用，所以必须使用“\\0”，“ \\1”的形式。 
//"/"是限定符，表示正则表达式的边界，斜杠后可添加搜索模式之类的参数
ereg_replace("/(hello)/", "\\1,\\1", "hello world");
preg_match($pattern, $subject)

//调用回调函数的替换函数
//mixed preg_replace_callback ( mixed $pattern , callable $callback , mixed $subject [, int $limit = -1 [, int &$count ]] )
preg_replace_callback($pattern, $callback, $subject);
//回调函数的签名
//string handler ( array $matches )
$string  =  'hello world' ;
$pattern  =  '/(he)([l]{2})o/' ;
echo preg_replace_callback($pattern,  function($matches){
	print_r($matches);
    return  $matches[1].','.$matches[2];
} , $string);
//$matches是个数组，其中$matches[0]是整个匹配的字符串，其他的是其中的分组
?>