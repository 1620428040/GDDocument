<?php
require("../../../GDWriteToFile/main.php");

$regexp="/(?<=\"|\')[^\"\']{0,}?\/([^\/\"\']{0,}\.(js|css|gif|png|jpg|html|htm))(?=\"|\')/";

$GLOBALS['result'] = array();
$fileContent=file_get_contents("a.html");
//print_r($fileContent);
$newContent=preg_replace_callback($regexp,  function($matches)use($regexp){
	//print_r($matches[0]."<br/>");
	$matches[]=
	//$matches[2]."/".$matches[1];
	preg_replace($regexp, "\\2", $matches[0]);
	$GLOBALS['result'][]=$matches;
    return  "[replace]";
} , $fileContent);
writeToFile($GLOBALS['result'],"filter");

?>