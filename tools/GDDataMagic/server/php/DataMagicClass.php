<?php
//需要MySQLClass
class DataMagicClass{
	var $db;
	function __construct($db){
		$this->db=$db;
	}
	public function shortcutOperate($permission,$params){
		//检测是否是允许访问的数据表
		if(isset($params["table"])){
			$permit=FALSE;
			for($i=0;$i<count($permission);$i++){
				if($params["table"]===$permission[$i]){
					$permit=TRUE;
					break;
				}
			}
			if(!$permit) {
				writeToFile(array("permission"=>$permission,"params"=>$params),"请求的操作没有许可");
				return array("请求的操作没有许可");
			}
		}
		
		//检测参数中有没有敏感字段，比如select where insert等
		foreach($params as $key=>$value){
			if(preg_match("/(select|where|insert|delete|update)/", json_encode($value),$match)&&$key!="action"){
				writeToFile(array("permission"=>$permission,"params"=>$params,"match"=>$match),"疑似SQL注入");
				if(count($match)>20){//count($match)表示敏感字段的数量，看情况调整
					return null;
				}
			}
		}
		
		//数据库操作
		$result=null;
		if($params["action"]==="insert"){
			$result=$this->db->insert($params["table"], $params["data"]);
		}
		elseif($params["action"]==="delete"){
			$result=$this->db->delete($params["table"], $params["where"]);
			//$result=$db->delete("person", "name='王五'");
		}
		elseif($params["action"]==="update"){
			$result=$this->db->update($params["table"], $params["where"], $params["data"]);
			//$result=$db->update("person", "name='李四'", array("name"=>"李德生","age"=>24));
		}
		elseif($params["action"]==="search"){
			//此处$params["data"]指要输出的字段
			$result=$this->db->search($params["table"], $params["where"],$params["data"],$params["order"]);
			//$result=$db->search("person", "name='张三'",array("name","age"),array("name"=>null,"age"=>null));
		}
		elseif($params["action"]==="getMeta"){
			$result=$this->getMeta($params["name"]);
		}
//		//如果用户对"custom_table","custom_field"表进行修改操作，则同步到bindTable绑定的表中
//		if($params["table"]=="custom_table"){
//			setCustomTable($db,$params);
//		}
		return $result;
	}
	function getMeta($name){
		$result=$this->db->search("tables", "name='$name' or id='$name'","*",null);
		$table=$result[0];
		$columns=$this->db->search("fields", "id in (".$table["fieldList"].")","*",null);
		$meta=array();
		$fieldList=array();
		
		//处理表格相关的meta
		foreach($table as $key=>$value){
			if($value!=null){
				$meta[$key]=$table[$key];
			}
		}
		
		//排序
		$sortIndex=explode(",",$table["fieldList"]);
		$newColumns=array();
		for($i=0;$i<count($columns);$i++){
			if($columns[$i]["id"]==$table["primaryField"]){
				$meta["primaryField"]=$columns[$i]["field"];
			}
			for($j=0;$j<count($sortIndex);$j++){
				if($columns[$i]["id"]==$sortIndex[$j]){
					$newColumns[$j]=$columns[$i];
					break;
				}
			}
		}
		$columns=$newColumns;
		
		//处理字段相关的meta
		for($i=0;$i<count($columns);$i++){
			$fieldList[$columns[$i]["field"]]=array();
			foreach($columns[$i] as $key=>$value){
				if($value!=null&&$key!="field"){
					$fieldList[$columns[$i]["field"]][$key]=$value;
				}
			}
		}
		$meta["fieldList"]=$fieldList;
		$meta["feature"]=explode(",",$table["feature"]);
		$meta["data"]=$this->db->search($table["bindTable"],null,null,null);
		return $meta;
		
//		//去掉field字段中"."后面的内容
////		$table["field"]=preg_replace("/\..{0,}?(,|$)/", "$1", $table["field"]);
//		
//		
//		for($i=0;$i<count($columns);$i++){
//			$fieldList[$columns[$i]["field"]]=array();
//			foreach($columns[$i] as $key=>$value){
//				if($value!=null&&$key!="field"){
//					$fieldList[$columns[$i]["field"]][$key]=$value;
//				}
////				if($key=="type"&&$value=="extern"){
////					$prarms=explode(":", $columns[$i]["valueRange"]);
////					$bindTable=$this->db->search("custom_table", "name='".$prarms[0]."'","bindTable");
////					$data=$this->db->search($bindTable[0]["bindTable"], "",$prarms[1].",".$prarms[2]);
////					foreach($data as $record){
////						$extern[$columns[$i]["field"]][$record[$prarms[1]]]=$record[$prarms[2]];
////					}
////				}
//			}
//		}
//		$table["fieldList"]=$fieldList;
//		//按照表格的field字段排序
//		$sortIndex=explode(",",$table["fieldList"]);
//		$newColumns=array();
//		for($i=0;$i<count($sortIndex);$i++){
//			for($j=0;$j<count($columns);$j++){
//				if($columns[$j]["id"]==$sortIndex[$i]){
//					$newColumns[$i]=$columns[$j];
//					break;
//				}
//			}
//		}
////		$table["extern"]=$extern;
//		$table["columns"]=$newColumns;
////		
		
	}
}
?>