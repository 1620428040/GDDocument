<?php
//检查路径所在目录是否存在，如果不存在则创建（即使需要创建多层文件夹）
function makesureDirExists($path){
	if(file_exists($path)){
		return;
	}
	$superDir=getSuperDir($path);
	if($superDir==null){
		return;
	}
	makeDir($superDir);
}
//创建目录（多层）
function makeDir($dir){
	if(file_exists($dir)){
		return;
	}
	$superDir=getSuperDir($dir);
	if($superDir!=null){
		makeDir($superDir);
	}
	mkdir($dir);
}
//获取上级目录
function getSuperDir($path){
	$dirs=explode("/", $path);
	if(count($dirs)==1){
		return null;
	}
	$newPath=$dirs[0];
	for($i=1;$i<count($dirs)-1;$i++){
		$newPath.="/".$dirs[$i];
	}
	return $newPath;
}
?>