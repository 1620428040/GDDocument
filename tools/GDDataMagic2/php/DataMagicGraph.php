<?php
class DataMagicGraph extends DataMagicList{
	function __construct($name="",$meta=null,$db){
		parent::__construct($name,$meta,$db);
		$this->node=array();
		foreach($this->meta["node"] as $name=>$info){
			$this->node[$name]=$this->createDataMagicList($name, $db);
		}
//		print_r($this);
//		exit;
	}
	//快捷操作，直接通过一组参数直接执行相应的操作
	function shortcutOperate($params){
		$result=null;
		$action=isset($params["action"])?$params["action"]:null;
		
		if($action==="getMeta"||$action==null){//默认是返回元数据
			$result=$this->exportMeta();
			$result["data"]=$this->findChildrenNode();
		}
		elseif(method_exists($this, $action)){
			$result=$this->$action($params);
		}
		else{
			$this->errorMess=array("status"=>"error","reason"=>"指定的action未定义");
			return $this->errorMess;
		}
		return $result;
	}
	//为每种节点生成一个DataMagicList对象
	function createSubList($nodeName){
		$name=$this->meta[$nodeName]["metaName"];
		$extend=$this->meta[$nodeName]["extend"];
		$meta=$this->getMeta($name);
		$meta=array_merge($meta,$extend);
		if(isset($meta["handler"])&&class_exists($meta["handler"])){
			$node=new $meta["handler"]($name,$meta,$this->db);
		}
		else{
			$node=new DataMagicList($name,$meta,$this->db);
		}
		return $node;
	}
	function mergeListMeta($node){
		if(isset($this->meta[$node])){
			$meta=$this->getMeta($this->meta[$node]["meta"]);
			$this->meta[$node]=array_merge($meta,$this->meta[$node]["extend"]);
		}
	}
	function exportMeta(){
		$meta=$this->meta;
		foreach($this->node as $name=>$node){
			$meta["node"][$name]=$node->exportMeta();
		}
		unset($meta["handler"]);
		unset($meta["root"]);
		return $meta;
	}
	//查找输入的节点的子节点
	function findChildrenNode($id=null){
		$params=array("where"=>array("parent"=>0));
		$data=array();
		foreach($this->node as $name=>$node){
			$data[$name]=$node->search($params);
		}
		return $data;
	}
}
?>