<?php
//多个正则表达式完成过滤和提取
header("content-type:text/html;charset=utf-8");
require("main.php");

require("../common/php/curl.php");
require("../GDWriteToFile/main.php");

$fileContent=file_get_contents("data/question1");
//writeToFile($fileContent,null,"result/question1");
preg_replace_callback(
"/([0-9]{1,2})、([^（]{0,})（([A-E]{1,5})）([^A]{0,})(A、[^B]{0,}){0,1}(B、[^C]{0,}){0,1}(C、[^D0-9]{0,}){0,1}(D、[^0-9]{0,}){0,1}/", 
function($matches){
	writeToFile($matches);
	$result="{'question': '".$matches[2]."(?)".$matches[4]."','answers': ['实现社会主义', '实现共产主义', '实现按需分配'],'correctAnswer': [2,3]},";
}, $fileContent);
//$pattern1="/([0-9]{1,2})、[^（]{0,}（[A-E]{1,5}）[^A]{0,}([A-E]、[^A-E0-9]{0,}){1,5}/";
//$pattern3="/(\ |\t|\n|\r\n)/";
//
//$GLOBALS['result'] = array();
//preg_replace_callback($pattern1, function($matches1){
//	$index=intval($matches1[1]);
//	$GLOBALS['result'][$index]=array();
//	writeToFile($matches1,"matches1","result/question1");
//	$pattern2="/[0-9]{1,2}、([^（]{0,})（([A-E]{1,5})）([^A]{0,})/";
//	preg_replace_callback($pattern2, function($matches2)use($index){
//		writeToFile($matches2,"matches2","result/question1");
//		$question=preg_replace("/(\ |\t|\n|\r\n)/", "", $matches2[1]."(?)".$matches2[3]);
//		$correctAnswer=preg_replace("/(\ |\t|\n|\r\n)/", "", $matches2[2]);
//		$GLOBALS['result'][$index]["question"]=$question;
//		$GLOBALS['result'][$index]["correctAnswer"]=$correctAnswer;
//		$GLOBALS['result'][$index]["answers"]=array();
//	}, $matches1[0]);
//	$pattern3="/[A-E]、([^A-E0-9]{0,})/";
//	preg_replace_callback($pattern3, function($matches3)use($index){
//		//writeToFile($matches3,"matches3","result/question1");
//		$correctAnswer=preg_replace("/[^^]([A-D])/", ",\\1", $matches2[2]);
//		$GLOBALS['result'][$index]["answers"][]=$matches3[1];
//	}, $matches1[0]);
//	
//}, $fileContent);
//writeToFile($GLOBALS['result']);
//$fileContent=preg_replace("/( |\t|\n)/", "", $fileContent);
//writeToFile($result,"result","result/question1");
//
//$result=preg_match("/[0-9]{1,2}、.{0,}?/", $fileContent);
//writeToFile($result,"result","result/question1");
?>
1、在新的历史条件下，我们党面临着执政、改革开放、（C）、外部环境“四大考验”。
A、商品经济
B、内部环境
C、市场经济
D、执政能力