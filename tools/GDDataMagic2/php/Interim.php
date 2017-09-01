<?php
require_once("Datamagic.php");

//针对已经有的旧的数据表的适配版本
class Interim extends DataMagic{
//	function search($params){
//		$params=$params||array();
//		if($_REQUEST["name"]==="bpm"){
//			if(isset($_REQUEST["creatorname"])){
//				$params["where"]["creatorname"]=$_REQUEST["creatorname"];
//			}
//			if(isset($_REQUEST["type"])&&$_REQUEST["type"]==="done"){
//				$params["where"]["result"]="y";
//			}
//		}
////		print_r($params);
//		return parent::search($params);
//	}
//	function outputDataDispose($data){
//		return $data;
//	}
}
?>