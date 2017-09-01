<?php
require("../../../GDWriteToFile/main.php");
require("../../../common/php/curl.php");
require("../../../common/php/path.php");
require("../../main.php");

echo "test start <br/>";

//编写下载的规则
$rules=array(
	array(
		"searchReg"=>"/(?<=\"|\')http:\/\/[^\"\']{0,}?\/([^\/\"\']{0,}\.(js|css))(?=\"|\')/",
		"splitReg"=>"/http:\/\/[^\"\']{0,}?\/([^\/\"\']{0,}\.(js|css))/",
		"replaceReg"=>"\\2/\\1",
		"isRelative"=>FALSE,
		"type"=>"js"
	),
	array(
		"searchReg"=>"/(?<=\"|\')http:\/\/[^\"\']{0,}?\/([^\/\"\']{0,}\.(gif|png|jpg))(?=\"|\')/",
		"splitReg"=>"/http:\/\/[^\"\']{0,}?\/([^\/\"\']{0,}\.(gif|png|jpg))/",
		"replaceReg"=>"img/\\1",
		"isRelative"=>FALSE,
		"type"=>"js"
	),
	array(
		"searchReg"=>"/(?<=\"|\')[^\"\']{0,}?\/([^\/\"\']{0,}\.(js|css))(?=\"|\')/",
		"splitReg"=>"/[^\"\']{0,}?\/([^\/\"\']{0,}\.(js|css))/",
		"replaceReg"=>"\\2/\\1",
		"isRelative"=>TRUE,
		"type"=>"js"
	),
	array(
		"searchReg"=>"/(?<=\"|\')[^\"\']{0,}?\/([^\/\"\']{0,}\.(gif|png|jpg))(?=\"|\')/",
		"splitReg"=>"/[^\"\']{0,}?\/([^\/\"\']{0,}\.(gif|png|jpg))/",
		"replaceReg"=>"img/\\1",
		"isRelative"=>TRUE,
		"type"=>"js"
	)
);


//下载一个页面，函数会将页面中出现的相关的链接返回，可以继续下载其他页面
$result=spiders("a.html", "zgdsw/","index.html",$rules,false);
writeToFile($result);

//下载一组页面
//$jsonPages='{
//  "http://www.jndsw.icoc.in//col.jsp?id=101":{
//      "saveDir":"jndsw/",
//      "pageName":"101.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=102":{
//      "saveDir":"jndsw/",
//      "pageName":"102.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=108":{
//      "saveDir":"jndsw/",
//      "pageName":"108.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=103":{
//      "saveDir":"jndsw/",
//      "pageName":"103.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=109":{
//      "saveDir":"jndsw/",
//      "pageName":"109.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=104":{
//      "saveDir":"jndsw/",
//      "pageName":"104.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=107":{
//      "saveDir":"jndsw/",
//      "pageName":"107.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=113":{
//      "saveDir":"jndsw/",
//      "pageName":"113.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=111":{
//      "saveDir":"jndsw/",
//      "pageName":"111.html"
//  },
//  "http://www.jndsw.icoc.in//col.jsp?id=110":{
//      "saveDir":"jndsw/",
//      "pageName":"110.html"
//  }
//}';
//
//$pages=json_decode($jsonPages,TRUE);
//foreach($pages as $url=>$pathInfo){
//	$result=spiders($url, $pathInfo["saveDir"],$pathInfo["pageName"],$rules,TRUE);
//	$pages+=$result["pages"];
//}
//writeToFile($pages);

?>