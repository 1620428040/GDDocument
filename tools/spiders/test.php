<?php
require("../GDWriteToFile/main.php");
require("../common/php/curl.php");
require("../common/php/path.php");
require("main.php");

echo "test start <br/>";

//从文件中找出相关的链接
filter("index.html", "save.html","/http:\/\/[^\ \"\'\)]{0,}/");

//编写下载的规则
$rules=array(
	//http://1.ss.faisys.com/js/comm/jquery/jquery-mousewheel.min.js?v=201408111734
	//http://1.ss.faisys.com/js/comm/fai.min.js?v=201606131822
	array(
		"searchReg"=>"/http:\/\/1.ss.faisys.com\/([^\?]{0,})\?v=[0-9]{0,}/",
		"replaceReg"=>"\\1",
		"isRelative"=>FALSE,
		"type"=>"js"
	),
	//http://2.ss.faisys.com/css/base.min.css?v=201606141918
	array(
		"searchReg"=>"/http:\/\/2.ss.faisys.com\/([^\?]{0,})\?v=[0-9]{0,}/",
		"replaceReg"=>"\\1",
		"isRelative"=>FALSE,
		"type"=>"css"
	),
	//http://9816927.s21i-9.faiusr.com/2/ABUIABACGAAg1fb_ugUoit2NCjBVOEY.jpg
	//http://9816927.s21i-9.faiusr.com/2/ABUIABACGAAg5f3_ugUo1ImR-gYwVThG.jpg
	array(
		"searchReg"=>"/http:\/\/9816927.s21i-9.faiusr.com\/([^\)\"\'\ \n\t]{0,})/",
		"replaceReg"=>"image\/\\1",
		"isRelative"=>FALSE,
		"type"=>"source"
	),
	//http://8286748.s21i-8.faiusr.com/4/ABUIABAEGAAg8uehtQUo5uSH4AUwATgj.png
    //http://8286748.s21i-8.faiusr.com/4/ABUIABAEGAAgj_yltQUo5KaUoAMw6Ac4OA.png
    array(
		"searchReg"=>"/http:\/\/8286748.s21i-8.faiusr.com\/([^\)\"\'\ \n\t]{0,})/",
		"replaceReg"=>"image\/\\1",
		"isRelative"=>FALSE,
		"type"=>"source"
	),
	//http://jz.faisys.com/image/pro/20160101/domainSearchImg.png
	//http://jz.faisys.com/image/pro/20160101/close.png?v=201601181937
	array(
		"searchReg"=>"/http:\/\/jz.faisys.com\/([^\)\"\'\ \n\t\?]{0,})(\?v=){0,1}[0-9]{0,}/",
		"replaceReg"=>"image/\\1",
		"isRelative"=>FALSE,
		"type"=>"source"
	),
	array(
		"searchReg"=>"/http:\/\/0.ss.faisys.com\/image\/loading\/dot.gif/",
		"replaceReg"=>"image/loading\/dot.gif",
		"isRelative"=>FALSE,
		"type"=>"source"
	),
	//	/col.jsp?id=111
	array(
		"searchReg"=>"/\/col.jsp\?id=([0-9]{3})/",
		"replaceReg"=>"\\1.html",
		"isRelative"=>TRUE,
		"type"=>"page"
	)
);


//下载一个页面，函数会将页面中出现的相关的链接返回，可以继续下载其他页面
//$result=spiders("http://www.jndsw.icoc.in/", "jndsw/","index.html",$rules,TRUE);
//writeToFile($result["pages"]);

//下载一组页面
$jsonPages='{
    "http://www.jndsw.icoc.in//col.jsp?id=101":{
        "saveDir":"jndsw/",
        "pageName":"101.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=102":{
        "saveDir":"jndsw/",
        "pageName":"102.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=108":{
        "saveDir":"jndsw/",
        "pageName":"108.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=103":{
        "saveDir":"jndsw/",
        "pageName":"103.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=109":{
        "saveDir":"jndsw/",
        "pageName":"109.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=104":{
        "saveDir":"jndsw/",
        "pageName":"104.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=107":{
        "saveDir":"jndsw/",
        "pageName":"107.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=113":{
        "saveDir":"jndsw/",
        "pageName":"113.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=111":{
        "saveDir":"jndsw/",
        "pageName":"111.html"
    },
    "http://www.jndsw.icoc.in//col.jsp?id=110":{
        "saveDir":"jndsw/",
        "pageName":"110.html"
    }
}';

$pages=json_decode($jsonPages,TRUE);
foreach($pages as $url=>$pathInfo){
	$result=spiders($url, $pathInfo["saveDir"],$pathInfo["pageName"],$rules,TRUE);
	$pages+=$result["pages"];
}
writeToFile($pages);

?>