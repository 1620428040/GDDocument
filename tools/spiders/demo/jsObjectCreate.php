<?php
////THREE.Bone.prototype = Object.create( THREE.Object3D.prototype );
////用来从three.js的源码中获取它的类继承结构的脚本
//$path="D:\\zend\\Apache2\\htdocs\\GDDocument\\framework\\three\\test\\three.js";
//if(file_exists($path)){
//	$context=file_get_contents($path);
//	$GLOBALS["classList"]=array();
//	$result=preg_replace_callback("/THREE\.([0-9a-zA-Z]{1,})\s{0,}=\s{0,}function/", function ($matches){
//			$GLOBALS["classList"][$matches[1]]=array();
//          return $matches[0];
//  	}, $context);
//	$result=preg_replace_callback("/THREE\.([0-9a-zA-Z]{1,})\.prototype = Object\.create\(\s{0,}THREE\.([0-9a-zA-Z]{1,})\.prototype\s{0,}\)/", function ($matches) {
//			$GLOBALS["classList"][$matches[2]][]=$matches[1];
////			echo $matches[1]."  ".$matches[2]."\n";
//          return $matches[0];
//  	}, $context);
//	$list=moveClassArray($GLOBALS["classList"]);
//	showList($list);
//}
//else{
//	echo "file not exists";
//}
//function moveClassArray($list){
//	$nameList=array_keys($list);
//	for($i=count($nameList)-1;$i>=0;$i--){
//		$key=$nameList[$i];
//		$value=$list[$key];
//		foreach($value as $index=>$className){
//			if(isset($list[$className])){
//				$list[$key][$className]=$list[$className];
//				unset($list[$className]);
//				unset($list[$key][$index]);
//			}
//		}
//	}
//	return $list;
//}
//function showList($list,$layer=""){
//	if(count($list)>0){
//		foreach($list as $key=>$value){
//			echo $layer.$key."\n";
//			showList($value,$layer."    ");
//		}
//	}
//}
?>