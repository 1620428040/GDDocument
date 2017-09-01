<?php

function get()
{
	return 'nbb';
}
function say($name)
{
	return $name . ",hai!";
}

$server=new SoapServer(null,array('uri'=>'http://soap/','location'=>'http://localhost:81/webservice.php'));
$server->addFunction('get');
$server->addFunction('say');
$server->handle();

?>