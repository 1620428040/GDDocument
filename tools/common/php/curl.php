<?php
//模仿360浏览器发送请求，这样请求百度的页面反而不可以
function curlGETRequestImitateBrowser($url){
	$curl=curl_init();
	//设置请求的参数
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	
	$header=array();
	$header[]="User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36";
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	//执行
	$output = curl_exec($curl);
	//释放
	curl_close($curl);
	return $output;
}
///GET方式请求
function curlGETRequest($url){
	//初始化
	$curl=curl_init();
	//设置请求的参数
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	//执行
	$output = curl_exec($curl);
	//释放
	curl_close($curl);
	return $output;
}
///POST方式请求,$params要求是数组
function curlPOSTRequest($url,$params){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	// post数据
	curl_setopt($curl, CURLOPT_POST, 1);
	// post的变量
	curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}
///POST方式发送json数据
function curlPOSTJson($url,$data){
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json'
	));
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}
?>