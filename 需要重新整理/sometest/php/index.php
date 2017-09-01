<?php
if($_GET["action"]!=null){
	writeArrayToFile("request", $_REQUEST);
	writeArrayToFile("data", json_decode($_POST["data"],TRUE));
}
else{
	postRequest("http://localhost:81/gdcode/php/index.php?action=1", array("a"=>"fefe"));
}
echo "ok";


function do_post_request($url, $data, $optional_headers = null) {
	$params = array('http' => array('method' => 'POST', 'content' => $data));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	//writeStringToFile("ctx", $ctx);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}
function postRequest($server, $data){
	$post_string=json_encode($data);
    $context = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded;charset=utf-8' .
                        '\r\n'.'User-Agent : Jimmy\'s POST Example beta' .
                        '\r\n'.'Content-length:' . strlen($post_string) + 5,
            'content' => 'data=' . $post_string)
        );
    $stream_context = stream_context_create($context);
    $data = file_get_contents($server, false, $stream_context);
    return $data;
}

?>