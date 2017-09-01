<?php
//设置参数
$filePath="data/aq.txt";
$signReplace=array("．"=>".","　"=>" ","（"=>"(","）"=>")","Ａ"=>"A","Ｂ"=>"B","Ｃ"=>"C","Ｄ"=>"D","Ｅ"=>"E");
$numberReplace=array("1"=>"A","2"=>"B","3"=>"C","4"=>"D",""=>"E");

//读取文件
$content=file_get_contents($filePath);
//将全角符号替换成半角符号
$content=str_replace(array_keys($signReplace), array_values($signReplace), $content);
//将文档切割成单个问题
$content=str_replace("\r\n\r\n", "\r\n", $content);
$content.="\r\n\r\n";
$content=preg_replace("/([0-9]+、)/", "\r\n$1", $content);
preg_match_all("/[0-9]+、([^\r\n]+\r\n)+/", $content,$out);
$items=$out[0];
echo "共处理".count($items)."条题目\n";

//逐条处理
$data=array();
foreach($items as $item){
	
	preg_match("/[0-9]+、 *(.*?)(?=\r\n *[A-E]\.)/s", $item,$out2);
	$question=$out2[1];
	
	preg_match_all("/[A-E]\. *([^ \t\r\nA-E]+)/", $item,$out3);
	$option=$out3[1];
	
	preg_match("/(?:\( *|_+)([A-E]+)(?: *\)|_+)/", $question,$out4);//(  A  )//__A__
	if(count($out4)===0){//处理正确答案用数字表示这种情况
		preg_match("/(?:\( *|_+)([1-4]+)(?: *\)|_+)/", $question,$out4);
		if(count($out4)!==0){
			$out4[1]=$numberReplace[$out4[1]];
		}
	}
	if(count($out4)===0){//处理答案写在题干最后的情况
		preg_match("/([A-E]+) *$/", $question,$out4);
	}
	$question=str_replace($out4[0], "____", $question);
	$answer=$out4[1];
	
	$quest=array("question"=>$question,"answers"=>$option,"correctAnswer"=>$answer);
	if(count($option)<2||!$answer){
		echo "\n出现异常：\n";
		echo $item."\n";
		print_r($quest);
	}
	else{
		$data[]=$quest;
	}
}

echo "\n处理结果：\n";
echo json_encode($data);
?>