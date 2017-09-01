<?php
//php中的回调函数，如果使用了外部的变量，应该用use关键字声明，但是这样函数中的操作不会改变外部变量原来的值
//解决方式是使用超全局变量$GLOBALS['']
preg_replace_callback($filter,  function($matches)use($filter,$linkReg,$pathReg){
	$link=preg_replace($filter, $linkReg, $matches[0]);
	$path=preg_replace($filter, $pathReg, $matches[0]);
	print_r("link:$link  path:$path");
	//writeFile($savename, "\"".$matches[0]."\"\n", "a+");
    return  $matches[0];
} , $fileContent);
?>