<?php
///GET方式
//初始化
$curl=curl_init();
//设置请求的参数
curl_setopt($curl, CURLOPT_URL, "http://www.jb51.net");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HEADER, 1);
//执行
$output = curl_exec($curl);
//释放
curl_close($curl);
//输出
print_r($output);
?>