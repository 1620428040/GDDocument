<?php
//需要引用的文件
//require("../common/php/curl.php");

//"searchReg","replaceReg"都是正则表达式
//"isRelative"使用来网页中搜索到描述URL的路径是相对路径还是绝对路径
//"type"表示类型，如果是"page"/"source"或其他
//$rules=array(
//	array("searchReg"=>"","replaceReg"=>"","isRelative"=>"","type"=>"")
//);


//根据给定的正则表达式搜索相符的内容
//编写rules之前，可以用这个函数过滤出要修改的内容
//$savePath保存修改后的结果的文件路径
//匹配的信息会保存在log.txt文件中
function filter($filePath,$savePath,$regexp){
	$GLOBALS['result'] = array();
	$fileContent=file_get_contents($filePath);
	//print_r($fileContent);
	$newContent=preg_replace_callback($regexp,  function($matches){
		//print_r($matches[0]."<br/>");
		$GLOBALS['result'][]=$matches;
	    return  "[replace]";
	} , $fileContent);
	writeToFile($GLOBALS['result'],"filter");
	file_put_contents($savePath, $newContent);
}


//抓取网页链接的简单爬虫
//$saveDir必须是以“/”结尾的文件夹
function spiders($url,$saveDir,$pageName,$rules,$isDownload){
	$GLOBALS['results']=array();
	$GLOBALS['pages']=array();
	
	//$content=file_get_contents($url);
	$content=curlGETRequestImitateBrowser($url);
	foreach($rules as $rule){
		$content=preg_replace_callback($rule['searchReg'],  function($matches)use($url,$saveDir,$rule,$isDownload){
			$sourceUrl=$rule['isRelative']?$url:"";
			$sourceUrl.=$matches[0];
			
			//$link是当前处理的页面到资源文件的链接
			$splitReg=isset($rule['splitReg']) ? $rule['splitReg'] : $rule['searchReg'];
			$link=preg_replace($splitReg, $rule['replaceReg'],$matches[0]);
			//writeToFile($link);
			$sourceSavePath=$saveDir.$link;
			
			if($isDownload){
				if($rule["type"]==="page"){
					$pathInfo=pathinfo($sourceSavePath);
					$page=array();
					$page["saveDir"]=$pathInfo['dirname']."/";
					$page["pageName"]=$pathInfo['basename'];
					$GLOBALS['pages'][$sourceUrl]=$page;
					//spiders($sourceUrl, $pathInfo['dirname']."/",$pathInfo['basename'], $rules,TRUE);
				}
				elseif($rule["type"]==="string"){
					//如果仅用来替换字符串，这里什么都不用做
				}
				else{
					if(!file_exists($sourceSavePath)){
						//writeToFile($sourceSavePath);
						$sourceContent=curlGETRequest($sourceUrl);
						makesureDirExists($sourceSavePath);
						file_put_contents($sourceSavePath, $sourceContent);
					}
				}
			}
			
			$result=$rule;
			$result["fined"]=$matches[0];
			$result["link"]=$link;
			$result["sourceSavePath"]=$sourceSavePath;
			$GLOBALS['results'][$sourceUrl]=$result;

		    return $link;
		} , $content);
	}
	$fileSavePath=$saveDir.$pageName;
	makesureDirExists($fileSavePath);
	file_put_contents($fileSavePath, $content);
	
	$results['results']=$GLOBALS['results'];
	$results['pages']=$GLOBALS['pages'];
	return $results;
}


//下载HTML文件
//downloadWebPages($indexUrl, $relativePath, $pageName, $rules,$logFilePath);
function downloadWebPages($url,$relativePath,$pageName,$rules,$logFilePath="downloadLog"){
	//获取目标页面html文件
	$content=curlGETRequestImitateBrowser($url);
	print_r($content);
	
	$filePath=$relativePath.$pageName;
	makesureDirExists($filePath);
	file_put_contents($filePath, $content);
}

//下载网页所引用的资源
//$relativePath表示主页相对于当前页面的位置
//$rules,数组类型，指对特定字符串和url的处理规则,例如
//array(
//	array("searchReg"=>"","replaceReg"=>"","type"=>"","source"=>)
//);
//type分为"page"需要下载的页面/"source"需要下载的资源文件/"string"需要替换的字符串/"other"其它
//$logFilePath,日志文件中列出数据
//例子downloadSource("../test/", "index.html", $rules);
function downloadSource($relativePath,$pageName,$rules,$logFilePath="source"){
	$filePath=$relativePath.$pageName;
	$pageContent=file_get_contents($filePath);
	
	$GLOBALS['results']=array();
	
	for($i=0;$i<count($rules);$i++){
		$rule=$rules[$i];
		$pageContent=preg_replace_callback($rule['searchReg'],  function($matches)use($rule,$relativePath){
			if(!isset($rule['source'])){
				$rule['source']="";
			}
			$sourceUrl=$rule['source'].$matches[0];
			//writeToFile($sourceUrl);
			$link=preg_replace($rule['searchReg'], $rule['replaceReg'],$sourceUrl);
			//writeToFile($link);
			$sourceSavePath=$relativePath.$link;
			
			if(!file_exists($sourceSavePath)){
				//writeToFile($sourceSavePath);
				$sourceContent=curlGETRequest($sourceUrl);
				makesureDirExists($sourceSavePath);
				file_put_contents($sourceSavePath, $sourceContent);
				
				$result=$rule;
				$result["sourceUrl"]=$sourceUrl;
				$result["link"]=$link;
				$GLOBALS['results'][]=$result;
			}
			//writeToFile($rule);
		    return $link;
		} , $pageContent);
	}
	file_put_contents($filePath, $pageContent);
	return $GLOBALS['results'];
	//writeToFile($rules,"资源链接替换",$logFilePath);
}

function downloadMassFiles($params){
	foreach($params as $url=>$record){
		$fileContent=curlGETRequestImitateBrowser($url);
		makesureDirExists($record["sourceSavePath"]);
		file_put_contents($record["sourceSavePath"], $fileContent);
	}
	
}
?>