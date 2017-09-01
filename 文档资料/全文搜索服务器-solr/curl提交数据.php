<?php
/////GET方式请求
//function curlGETRequest($url){
//	//初始化
//	$curl=curl_init();
//	//设置请求的参数
//	curl_setopt($curl, CURLOPT_URL, $url);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//	curl_setopt($curl, CURLOPT_HEADER, 0);
//	//执行
//	$output = curl_exec($curl);
//	//释放
//	curl_close($curl);
//	return $output;
//}
/////POST方式请求,$params要求是数组
//function curlPOSTRequest($url,$params){
//	$curl = curl_init();
//	curl_setopt($curl, CURLOPT_URL, $url);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//	// post数据
//	curl_setopt($curl, CURLOPT_POST, 1);
//	// post的变量
//	curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
//	$output = curl_exec($curl);
//	curl_close($curl);
//	return $output;
//}
/////POST方式发送json数据
//function curlPOSTJson($url,$data){
//	$curl = curl_init($url);
//	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//	curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
//	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//	    'Content-Type: application/json'
//	));
//	$output = curl_exec($curl);
//	curl_close($curl);
//	return $output;
//}
/////POST方式发送xml数据
//function curlPOSTXml($url,$data){
//	$curl = curl_init($url);
//	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//	curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
//	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//	    'Content-Type: text/xml'
//	));
//	$output = curl_exec($curl);
//	curl_close($curl);
//	return $output;
//}
//
//function curlPOSTJSONTestData(){
//	$url="http://localhost:8983/solr/collection2/update?commit=true";
//	echo $url."\n";
//	$data='{"add": {"doc": {"id": "15264","content": "json数据传送测试2"}}}';
//	echo $data."\n";
//	$curl = curl_init($url);
//	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//	curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
//	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//	    "Content-Type: application/json"
//	));
//	echo $curl."\n";
//	$output = curl_exec($curl);
//	curl_close($curl);
//	echo $output;
//}
//function curlPOSTXMLTestData(){
//	$url="http://localhost:8983/solr/collection2/update?commit=true";
//	echo $url."\n";
//	$data="<add><doc><field name='id'>testdoc2213</field><field name='content'>测试数据23231</field></doc></add>";
//	echo $data."\n";
//	$curl = curl_init($url);
//	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
//	curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
//	curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
//	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//	    "Content-Type: text/xml"
//	));
//	echo $curl."\n";
//	$output = curl_exec($curl);
//	curl_close($curl);
//	echo $output;
//}
?>