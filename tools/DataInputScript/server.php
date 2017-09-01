<?php
$action = isset($_GET["action"]) ? $_GET["action"] : exit("缺少action参数");

if($action==="link"){
//	$conn=new mysqli($_POST["host"],$_POST["user"],$_POST["password"],$_POST["db"]);
	$conn=new mysqli("localhost","root","","zc");
//	$result=$conn->query('SHOW COLUMNS FROM '.$_POST["table"]);
//	$row=$result->fetch_assoc();
//	$result->close();
	echo json_encode($conn);
	$conn->close();
}
elseif($action==="field"){
	echo json_encode($_POST);
}
elseif($action==="data"){
	echo json_encode($_POST);
}
else{
	echo "指定的action未定义";
	exit;
}

//$conn=new mysqli("localhost","root","123456","test");//创建新的数据库连接对象
////$conn->select_db("test");//指定数据库
//$result=$conn->query('select * from custom_field');//执行一条sql语句
//$row=$result->fetch_row();//获取一行数据
//$row=$result->fetch_assoc();//获取一行数据(关联数组形式)
////print_r($row);
//$data=$result->fetch_all(MYSQL_ASSOC);//获取全部数据，MYSQL_ASSOC表示用关联数组的形式
//$fields=$result->fetch_fields();//获取结果中的字段
//$result->close();//释放结果对象
//$conn->close();//释放数据库连接对象
//echo json_encode($fields);

?>