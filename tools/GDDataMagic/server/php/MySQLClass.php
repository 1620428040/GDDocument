<?php
//需要加载的文件
//require("../GDWriteToFile/main.php");

//操作实例
//require("MySQL.class.php");
//$db=new MySQL("127.0.0.1","root","123456","user");
//$db->header();
//$db->connect();
//$result=$db->insert("person", array("name"=>"张三","age"=>23));\
//$result=$db->delete("person", "name='王五'");
//$result=$db->update("person", "name='李四'", array("name"=>"李德生","age"=>24));
//$result=$db->search("person", "name='张三'",array("name","age"),array("name"=>null,"age"=>null));
//$result=$db->query("delete from person");
//print_r($result);
//$db->close();
class MySQLClass{
	var $server;//数据库所在的服务器
	var $username;//用户名
	var $password;//密码
	var $database;//数据库名
	
	var $connect;//与数据库的连接

	//构造函数
	function __construct($server,$username=null,$password=null,$database=null){
		if(is_array($server)){
			$this->server=$server["server"];
			$this->username=$server["username"];
			$this->password=$server["password"];
			$this->database=$server["database"];
		}
		else{
			$this->server=$server;
			$this->username=$username;
			$this->password=$password;
			$this->database=$database;
		}
	}
	//连接
	function connect(){
		if($this->connect){
			$this->close();
		}
		$this->connect=mysql_connect($this->server,$this->username,$this->password);
		$this->setDatabase($this->database);
		if(!$this->connect){
			die("Error:".mysql_error());
		}
	}
	//关闭连接
	function close(){
		if($this->connect){
			mysql_close($this->connect);
		}
	}
	//设置活动的数据库
	function setDatabase($databaseName){
		mysql_select_db($databaseName,$this->connect);
	}
	//添加header
	function header(){
		header("Content-type: text/html; charset=utf-8");
	}
	//执行任意的sql语句
	function query($query){
		writeToFile('query', $query);
		$result=mysql_query($query,$this->connect);
		if(!$result){
			die("Error:".mysql_error());
		}
		else{
			return $result;
		}
	}
	//解析返回的结果为二维数组
	function fetch($result){
		try{
			mysql_data_seek($result, 0);
			$table=array();
			while($row=mysql_fetch_array($result,MYSQL_ASSOC)){
				$table[]=$row;
			}
			return $table;
		}
		catch(Exception $exce){
			echo 'Message: ' .$exce->getMessage();
		}
	}
	//将返回的数组中的
	//插入新的数据，$array为传递参数用的关联数组
	//$database->insert("tablename", array("name"=>"张三","age"=>23));
	function insert($table,$array){
		$sqlString="insert into $table(";
		$str="value(";
		$i=0;
		foreach($array as $key=>$value){
			$sqlString.=$key;
			if(is_string($value)){
				$str.="'".$value."'";
			}
			else{
				$str.=$value;
			}
			if($i!=count($array)-1){
				$sqlString.=",";
				$str.=",";
			}
			else{
				$sqlString.=") ";
				$str.=") ";
			}
			$i++;
		}
		$sqlString.=$str;
		$this->query($sqlString);
		return mysql_insert_id($this->connect);
	}
	//删除记录
	//$database->delete("tablename", "name='张三' or age=231");
	function delete($table,$where){
		$sqlString="delete from $table";
		
		//添加where条件
		$sqlString.=$this->parserWhere($where);

		return $result=$this->query($sqlString);
	}
	//更新记录
	function update($table,$where,$array){
		$sqlString="update $table set ";
		$i=0;
		foreach($array as $key=>$value){
			$sqlString.="$key=";
			if(is_string($value)){
				$sqlString.="'".$value."'";
			}
			else{
				$sqlString.=$value;
			}
			if($i!=count($array)-1){
				$sqlString.=",";
			}
			else{
				$sqlString.=" ";
			}
			$i++;
		}
		
		//添加where条件
		$sqlString.=$this->parserWhere($where);
		
		return $this->query($sqlString);
	}
	//按条件搜索，返回值是一个二维数组
	//$result=$db->search("person",array("or"=>array("name="=>"张三","age="=>23),"name="=>"张三","age="=>21),array("name","age"),array("name"=>null,"age"=>null));
	//参数是表名，条件，需要的字段名，排序
	function search($table,$where,$which,$order){
		$filed="";
		if($which==null||$which=="*"){
			$filed="*";
		}
		elseif(is_string($which)){
			$filed=$which;
		}
		elseif(is_array($which)){
			for($i=0;$i<count($which);$i++){
				$filed.=$which[$i];
				if($i!=count($which)-1){
					$filed.=",";
				}
			}
		}
		$sqlString="select $filed from $table";
		
		//添加where条件
		$sqlString.=$this->parserWhere($where);
		
		if($order!=null){
			if(is_string($order)){
				$sqlString.=" order by $order";
			}
			elseif(is_array($order)){
				$sqlString.=" order by ";
				$i=0;
				foreach($order as $key=>$value){
					if($value==null||$value==0||$value=="ASC"||$value=="asc"){
						$sqlString.="$key asc";
					}
					else{
						$sqlString.="$key desc";
					}
					if($i!=count($order)-1){
						$sqlString.=",";
					}
					$i++;
				}
			}
		}
		$result=$this->query($sqlString);
		return $this->fetch($result);
	}
	//解析where条件,允许为空，允许为数组，允许为字符串，默认为and 类型的连接
	function parserWhere($where){
		if($where==null){
			return "";
		}
		elseif(is_string($where)){
			return " where ".$where;
		}
		elseif(is_array($where)){
			return " where ".$this->parserWhereArray($where);
		}
		else{
			return "";
		}
	}
	function parserWhereArray($array,$type="and"){
		$count=count($array);
		$i=0;
		$string="";
		foreach($array as $key=>$value){
			if($key=="and"||$key=="AND"){
				$string .=$this->parserWhereArray($value,"and");
			}
			elseif($key=="or"||$key=="OR"){
				$string .=$this->parserWhereArray($value,"or");
			}
			else{
				if(is_string($value)){
					$string.= "$key'".$value."'";
				}
				else{
					$string.= "$key$value";
				}
			}
			$i++;
			if($i<$count){
				$string .=" $type ";
			}
		}
		if($count>1){
			$string="( $string )";
		}
		return $string;
	}
}
?>