<?php
//getallheaders在某些服务器上可以获取请求头，但也可能未定义
//可以自己实现这个函数
print_r(getallheaders());
function getallheaders() { 
	foreach ($_SERVER as $name => $value) { 
		if (substr($name, 0, 5) == 'HTTP_') { 
			$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
		} 
	} 
	return $headers; 
}

//写返回头，应该在任何输出之前
header("Content-type:application/pdf");
?>