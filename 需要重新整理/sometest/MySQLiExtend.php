<?php
class MySQLiExtend extends mysqli{
	function getAll(){
		$result=$this->query('select * from custom_field');//执行一条sql语句
		$data=$result->fetch_all(MYSQL_ASSOC);//获取全部数据，MYSQL_ASSOC表示用关联数组的形式
		$result->close();//释放结果对象
		return $data;
	}
}
try{
	$conn=new MySQLiExtend("localhost","root","123456","test");//创建新的数据库连接对象
}
catch(Exception $exec){
	echo $exec;
}

//echo json_encode($conn->getAll());
$conn->close();//释放数据库连接对象
?>