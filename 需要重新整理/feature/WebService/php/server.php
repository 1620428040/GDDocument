<?php
//php版的web service 服务器
//在服务器端SoapServer的构造函数可以省略服务器端地址，但是需要定义uri
function get(){
	return 'php webservice test string';
}
function say($name){
	return $name . ",hai!";
}
function add($num1,$num2){
	return $num1+$num2;
}
$server=new SoapServer(null,array('uri'=>'http://soap/'));
$server->addFunction('get');
$server->addFunction('say');
$server->addFunction('add');
$server->handle();
?>