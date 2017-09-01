<?php
class DataMagic{
	var $db;//数据库链接
	var $meta;//数据对象的元数据
	var $permission;//允许访问的数据表
	var $sensitive;//禁止访问的敏感数据表，只有$permission为空时有效
	var $errorMess;//返回的错误提示
	const defaultDataSource="file";//"file",文件;"db",文件;
	
	//工厂方法，要求$meta["handler"]中的类是DataMagic的子类
	static function createDataMagic($name,$db){
		$meta=self::getMeta($name);
		$inst=null;
		if(isset($meta["handler"])&&class_exists($meta["handler"])){
			$inst=new $meta["handler"]();
		}
		else{
			$inst=new DataMagic();
		}
		$inst->db=$db;
		$inst->meta=$meta;
		return $inst;
	}
	static function getMeta($name){
		//如果相应的meta文件存在，则直接读取文件
		$path="../meta/".$name.".json";
		$meta=null;
		if(self::defaultDataSource==="file"&&file_exists($path)){
			$meta=json_decode(file_get_contents($path),TRUE);
		}
		else{
			$meta=$this->getMetaFromDatabase($name)||$this->getMetaFromDefault($name);
			file_put_contents($path, json_encode($meta));
		}
		return $meta;
	}
	//从数据库的tables表中读取元数据
	static function getMetaFromDatabase($name){
		$result=$this->db->query("SELECT * from tables where name='$name' or id='$name'")->fetchAll(PDO::FETCH_ASSOC);
		if($result){
			$table=$result[0];
			if($table["bindTable"]&&$this->authorityExamine($table["bindTable"])===FALSE){
				echo json_encode($this->errorMess);
				exit;
			}
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
			return $meta;
		}
	}
	//直接获取相应的数据表，生成默认的元数据
	static function getMetaFromDefault($name){
		return $result=$this->db->query("SELECT * from tables where name='$name' or id='$name'")->fetchAll(PDO::FETCH_ASSOC);
	}
	//快捷操作，直接通过一组参数执行相应的操作
	function shortcutOperate($params){
		$result=null;
		$action=isset($params["action"])?$params["action"]:null;
		$where=isset($params["where"])?$params["where"]:null;
		$data=isset($params["data"])?$params["data"]:null;
		$fields=isset($params["fields"])?$params["fields"]:null;
		$order=isset($params["order"])?$params["order"]:null;
		
		if($where&&is_array($where)){
			$where=$this->parserWhere($this->meta["fieldList"], $where);
		}
		if($data&&is_array($data)){
			$data=$this->dataExamine($this->meta["fieldList"], $data);
			if($data===FALSE){
				return $this->errorMess;
			}
		}
		if(method_exists($this, $action)){
			$result=$this->$action(array("where"=>$where,"data"=>$data,"action"=>$action,"fields"=>$fields,"order"=>$order));
		}
		else{//默认是返回元数据，所以不用写getMeta的对象方法
			$result=$this->meta;
		}
		return $result;
	}
	function insert($params){
		if(!isset($this->insertStmt)){
			$fieldNames=array();
			foreach($this->meta["fieldList"] as $key=>$value){
				if($key==="id"){
					continue;
				}
				$fieldNames[]=$key;
			}
			$this->insertStmt=$this->db->prepare("INSERT INTO `".$this->meta["bindTable"]."` (`".join("`, `", $fieldNames)."`) VALUES (:".join(", :", $fieldNames).")");
		}
		return $this->insertStmt->execute($params["data"]);
	}
	function delete($params){
		if(!isset($this->deleteStmt)){
			
		}
		return $this->db->exec("DELETE FROM `".$this->meta["bindTable"]."` WHERE ".$params["where"]);
	}
	function update($params){
		
	}
	function search($params){
		
	}
	//创建查询条件
	function parserWhere($fieldList,$where){
		$whereStr=array();
		foreach($fieldList as $key=>$value){
			$whereItem=$where[$key];
			if($whereItem){
				if($key==="id"){
					$whereStr[]=$key."=".$whereItem;
				}
				elseif($value["type"]==="number"||$value["type"]==="date"){
					if($whereItem["start"]){
						$whereStr[]=$key.">".$whereItem["start"];
					}
					if($whereItem["end"]){
						$whereStr[]=$key."<".$whereItem["end"];
					}
				}
				else{
					$whereStr[]=$key." like '%".$whereItem."%'";
				}
			}
		}
		return join(" and ", $whereStr);
	}
	//检查数据格式是否符合要求
	function dataExamine($fieldList,$data){
		foreach($fieldList as $key=>$value){
			if($key==="id"){
				continue;
			}
			$dataItem=$data[$key];
			if(isset($value["notNull"])&&$value["notNull"]==="1"&&($dataItem==null||$dataItem==="")){
				$this->errorMess=array("status"=>"error","reason"=>"字段：".$key." 的值不能为空");
				return FALSE;
			}
			if($dataItem&&isset($value["regexp"])&&!preg_match($value["regexp"], $dataItem)){
				$this->errorMess=array("status"=>"error","reason"=>"字段：".$key." 的值格式错误");
				return FALSE;
			}
		}
		return $data;
	}
	//检查是否有权限访问数据
	function authorityExamine($tableName){
		$permit=TRUE;
		if($this->permission){
			foreach($this->permission as $permission){
				if($tableName===$permission){
					$permit=TRUE;
					break;
				}
			}
			$this->errorMess=array("status"=>"error","reason"=>"数据表:".$tableName." 不在允许访问的范围内");
			$permit=FALSE;
		}
		elseif($this->sensitive){
			foreach($this->sensitive as $permission){
				if($tableName===$permission){
					$this->errorMess=array("status"=>"error","reason"=>"数据表:".$tableName." 属于敏感数据，无权访问");
					$permit=FALSE;
					break;
				}
			}
			$permit=TRUE;
		}
		if($permit===FALSE) {
			writeToFile($this->errorMess);
		}
		return $permit;
	}
}
?>