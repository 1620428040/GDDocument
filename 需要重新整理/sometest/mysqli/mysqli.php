<?php
class GDMySQLi extends mysqli{
	public static $instance;
	public function select($table,$field,$where){
		$mysqli=new mysqli();
		$mysqli->connect();
		$mysqli->query();
		$this->query();
	}
	public function delete(){
		
	}
}

$db=new mysqli();
$db->query();
?>