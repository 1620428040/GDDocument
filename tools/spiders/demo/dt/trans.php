<?php
//将save.json中的文件修改并上传到数据库中
header("content-type:text/html;charset=utf-8");

require("../../../GDWriteToFile/main.php");
require("../../../MySQLClass/main.php");

//$db=new MySQLClass("60.208.94.132","root","fcl2016","party");
$db=new MySQLClass("192.168.1.138","root","123","bgmobile");
//$db=new MySQLClass("127.0.0.1","root","123456","gdsm");

$db->connect();

//uploadQuestion($db, "两学一做", "data/judge.json");
//uploadQuestion($db, "两学一做", "data/save.json");
//uploadQuestion($db, "国企党建", "data/guoqidangjian.json");
//uploadQuestion($db, "时事政治", "data/shishizhengzhi1.json");
//uploadQuestion($db, "时事政治", "data/shishizhengzhi2.json");
//uploadQuestion($db, "时事政治", "data/shishizhengzhi3.json");
//uploadQuestion($db, "时事政治", "data/shishizhengzhi4.json");
uploadQuestion($db, "安全生产", "data/anquanshengchan.json");

$db->close();

function uploadQuestion($db,$testName,$filePath){
	echo "testName".$testName."<br/>";
	echo "filePath".$filePath."<br/>";
	$json=file_get_contents($filePath);
	print_r($json);
	$data=json_decode($json,TRUE);
	print_r($data);
	//writeToFile($data,"save2.json");
	$newData=array();
	foreach($data as $ques){
		$newQues=array();
		$newQues["question"]=$ques["question"];
		$newQues["answers"]=array();
		$newQues["correctAnswer"]=array();
		for($i=0;$i<count($ques["answers"]);$i++){
			if($ques["answers"][$i]!==""){
				$newQues["answers"][$i]=$ques["answers"][$i];//preg_replace("/^[A-D,/、]{0,1}/", "", $ques["answers"][$i]);
			}
		}
		if(strstr($ques["correctAnswer"], "A")||strstr($ques["correctAnswer"], "对")){
			$newQues["correctAnswer"][]=1;
		}
		if(strstr($ques["correctAnswer"], "B")||strstr($ques["correctAnswer"], "错")){
			$newQues["correctAnswer"][]=2;
		}
		if(strstr($ques["correctAnswer"], "C")){
			$newQues["correctAnswer"][]=3;
		}
		if(strstr($ques["correctAnswer"], "D")){
			$newQues["correctAnswer"][]=4;
		}
		if(strstr($ques["correctAnswer"], "E")){
			$newQues["correctAnswer"][]=5;
		}
		$newData[]=$newQues;
		
		$question=parserData($newQues["question"],"","",null);
		$answers=parserData($newQues["answers"],"","",null);
		$correctAnswer=parserData($newQues["correctAnswer"],"","",null);
		
		$sql="INSERT INTO `questions` (`id`, `testname`, `question`, `answers`, `correctAnswer`) VALUES (NULL, '".$testName."', '".$question."', '".$answers."', '".$correctAnswer."')";
		writeToFile($sql,"sql");
		$db->query($sql);
	}
}
?>