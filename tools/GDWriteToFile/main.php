<?php
//一组将内容写入文件的函数
//国栋
//2016年4月27日
//2016年6月6日	修改了对是否是索引数组的判断（但如果是空数组的话，是没法区分的）

//将数据写入文件的函数
//$data:任意类型的数据
//$title:标题
//$filepath:写入文件的路径，默认是"log.txt"
//$cover:是否覆盖之前的内容
//$tag:标记，可以随便插入一段字符串用来说明
function writeToFile($data,$title=null,$filepath="log.txt",$cover=FALSE,$tag=null){
	if($filepath==null){
		$filepath="log.txt";
	}
	if($cover==null){
		$cover=FALSE;
	}
	
	$output="\n";
	if($title!=null&&is_string($title)&&$title!=""){
		$output.="名称:".$title."\n";
	}
	if($tag!=null&&is_string($tag)&&$tag!=""){
		$output.=$tag."\n";
	}
	if(is_resource($data)){
		$output.="类型：外部资源\n";
		$output.="资源类型:".get_resource_type($data)."\n";
	}
	elseif(is_bool($data)){
		$output.="类型：布尔值\n";
		$output.=$data?"true":"false";
	}
	else{
		if(is_string($data)){
			$output.="类型：字符串\n";
		}
		elseif(is_numeric($data)){
			$output.="类型：数值\n";
		}
		elseif(is_array($data)){
			$output.="类型：数组\n";
		}
		elseif(is_object($data)){
			$output.="类型：对象\n";
			$output.="类名：".get_class($data)."\n";
		}
		else{
			$output.="其他类型";
		}
		
		if(checkVersion('5.4.0')){
			$output.=json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		}
		else{
			$array=json_decode(json_encode($data),TRUE);
			//$output.=json_encode($data);
			$output.=parserData($array);
		}
	}
	$output.="\n";
	
	$writeType=$cover?"w+":"a+";
	
	$fp=fopen($filepath,$writeType);//"w+"覆盖写，“a+”添加
	if(!$fp){
		die("打开文件失败，请检查是否有读写权限:$filepath");
	}
	$len=strlen($output);
	fwrite($fp,$output,$len);
	fclose($fp);
	
	return $output;
}
//将函数堆栈信息写入文件的函数
//$limit记录的个数，0为不限制
function writeBacktrace($limit=0,$title="堆栈调试",$filepath="log.txt",$cover=FALSE){
	$backtrace=debug_backtrace($limit);
	if(!$backtrace){
		$backtrace="获取不到堆栈信息";
	}
	writeToFile($backtrace,$title,$filepath,$cover);
}
//比较php的版本是否大于等于某个版本号
function checkVersion($version){
    $php_version = explode('-', phpversion());
    $php_version = $php_version[0];
    $pass = strnatcasecmp($php_version, $version) >= 0 ? true : false; //=0表示版本为5.2.9  ＝1表示大于5.2.9 =-1表示小于5.2.9
    return $pass;
}
//如果php版本太低，则用这个函数解析数据，取代json_encode()函数
//$indent==null则不换行
//但是不会将特殊字符替换成转义字符
function parserData($data,$name="",$beginOfLine="",$indent="    ",$end=""){
	$newLine=$indent==null?"":"\n";
	if($name!=""){
		$name='"'.$name.'"'.":";
	}
	$string="";
	if(is_string($data)){
		$string.=$beginOfLine.$name.'"'.$data.'"'.$end.$newLine;
	}
	elseif(is_array($data)){
		if(is_list($data)){
			$string.=$beginOfLine.$name."[".$newLine;
			$count=count($data);
			$i=0;
			foreach($data as $value){
				$i++;
				$comma="";
				if($i<$count){
					$comma=",";
				}
				$string.=parserData($value,"",$beginOfLine.$indent,$indent,$comma);
			}
			$string.=$beginOfLine."]$end".$newLine;
		}
		else{
			$string.=$beginOfLine.$name."{".$newLine;
			$count=count($data);
			$i=0;
			foreach($data as $key=>$value){
				$i++;
				$comma="";
				if($i<$count){
					$comma=",";
				}
				$keyname=strval($key);
				$string.=parserData($value,$keyname,$beginOfLine.$indent,$indent,$comma);
			}
			$string.=$beginOfLine."}$end".$newLine;
		}
	}
	else{
		$string.=$beginOfLine.$name.$data."$end".$newLine;
	}
	return $string;
}
//判断是否是索引数组
function is_list($arr){
	$keys = array_keys($arr);
	$i=0;
	foreach($keys as $key){
		if($i!==$key){
			return FALSE;
		}
		$i++;
	}
	return TRUE;
}
?>
