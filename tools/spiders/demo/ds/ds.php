<?php
//党史办的网站转化成本地项目用的脚本
require("../../../GDWriteToFile/main.php");
require("../../../common/php/curl.php");
require("../../../common/php/path.php");
require("../../main.php");

//要下载的主页地址
//$indexUrl="http://www.jndsw.icoc.in/";

//项目保存地址相对于本脚本的路径
$relativePath="jndsw/";

//主页文件名
//$pageName="index.html";

//处理规则
$rules=array(
	//http://1.ss.faisys.com/js/comm/jquery/jquery-mousewheel.min.js?v=201408111734
	//http://1.ss.faisys.com/js/comm/fai.min.js?v=201606131822
	array(
		"searchReg"=>"/http:\/\/1.ss.faisys.com\/([^\?]{0,})\?v=[0-9]{0,}/",
		"replaceReg"=>"\\1",
		"type"=>"js"
	),
	//http://2.ss.faisys.com/css/base.min.css?v=201606141918
	array(
		"searchReg"=>"/http:\/\/2.ss.faisys.com\/([^\?]{0,})\?v=[0-9]{0,}/",
		"replaceReg"=>"\\1",
		"type"=>"css"
	),
	//http://9816927.s21i-9.faiusr.com/2/ABUIABACGAAg1fb_ugUoit2NCjBVOEY.jpg
	//http://9816927.s21i-9.faiusr.com/2/ABUIABACGAAg5f3_ugUo1ImR-gYwVThG.jpg
	array(
		"searchReg"=>"/http:\/\/9816927.s21i-9.faiusr.com\/([^\)\"\'\ \n\t]{0,})/",
		"replaceReg"=>"image\/\\1",
		"type"=>"source"
	),
	//http://jz.faisys.com/image/pro/20160101/domainSearchImg.png
	//http://jz.faisys.com/image/pro/20160101/close.png?v=201601181937
	array(
		"searchReg"=>"/http:\/\/jz.faisys.com\/([^\)\"\'\ \n\t\?]{0,})(\?v=){0,1}[0-9]{0,}/",
		"replaceReg"=>"image\/\\1",
		"type"=>"source"
	),
	array(
		"searchReg"=>"/http:\/\/0.ss.faisys.com\/image\/loading\/dot.gif/",
		"replaceReg"=>"image\/loading\/dot.gif",
		"type"=>"source"
	)
);

//日志文件保存路径
$logFilePath="downloadLog";

echo "download start<br/>";


//下载一组页面
$urls=array();
$urls["index.html"]="http://www.jndsw.icoc.in/";
$urls["zhonggongdangshi.html"]="http://www.jndsw.icoc.in/col.jsp?id=101";
$urls["jinandangshi.html"]="http://www.jndsw.icoc.in/col.jsp?id=102";
$urls["quanchengjiaozi.html"]="http://www.jndsw.icoc.in/col.jsp?id=111";

foreach($urls as $filename=>$url){
	//下载页面的HTML文件
	downloadWebPages($url, $relativePath, $filename, $rules,$logFilePath);
	//根据给定的规则，下载页面所需要的资源文件，并且替换相应的链接
	downloadSource($relativePath, $filename, $rules,$logFilePath);
}

////下载HTML文件
////downloadWebPages($indexUrl, $relativePath, $pageName, $rules,$logFilePath);
//function downloadWebPages($url,$relativePath,$pageName,$rules,$logFilePath="downloadLog"){
//	//获取目标页面html文件
//	$content=curlGETRequestImitateBrowser($url);
//	print_r($content);
//	
//	$filePath=$relativePath.$pageName;
//	makesureDirExists($filePath);
//	file_put_contents($filePath, $content);
//}
//
////下载网页所引用的资源
////$relativePath表示主页相对于当前页面的位置
////$rules,数组类型，指对特定字符串和url的处理规则,例如
////array(
////	array("searchReg"=>"","replaceReg"=>"","type"=>"","source"=>)
////);
////type分为"page"需要下载的页面/"source"需要下载的资源文件/"string"需要替换的字符串/"other"其它
////$logFilePath,日志文件中列出数据
////例子downloadSource("../test/", "index.html", $rules);
//function downloadSource($relativePath,$pageName,$rules,$logFilePath="source"){
//	$filePath=$relativePath.$pageName;
//	$pageContent=file_get_contents($filePath);
//	
//	$GLOBALS['results']=array();
//	
//	for($i=0;$i<count($rules);$i++){
//		$rule=$rules[$i];
//		$pageContent=preg_replace_callback($rule['searchReg'],  function($matches)use($rule,$relativePath){
//			if(!isset($rule['source'])){
//				$rule['source']="";
//			}
//			$sourceUrl=$rule['source'].$matches[0];
//			//writeToFile($sourceUrl);
//			$link=preg_replace($rule['searchReg'], $rule['replaceReg'],$sourceUrl);
//			//writeToFile($link);
//			$sourceSavePath=$relativePath.$link;
//			
//			if(!file_exists($sourceSavePath)){
//				//writeToFile($sourceSavePath);
//				$sourceContent=curlGETRequest($sourceUrl);
//				makesureDirExists($sourceSavePath);
//				file_put_contents($sourceSavePath, $sourceContent);
//				
//				$result=$rule;
//				$result["sourceUrl"]=$sourceUrl;
//				$result["link"]=$link;
//				$GLOBALS['results'][]=$result;
//			}
//			//writeToFile($rule);
//		    return $link;
//		} , $pageContent);
//	}
//	file_put_contents($filePath, $pageContent);
//	return $GLOBALS['results'];
//	//writeToFile($rules,"资源链接替换",$logFilePath);
//}
?>