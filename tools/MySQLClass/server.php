<?php
//MySQL+PHP 的服务器，用来在外部调用

//导入MySQL类
require("MySQL.class.php");
//默认参数
$server="127.0.0.1";//数据库所在的服务器
$username="root";//用户名
$password="123456";//密码
$database="test";//数据库名

//如果用户输入参数，则覆盖掉默认参数
if($_GET['server']!=null){
	$server=$_GET['server'];
}
if($_GET['username']!=null){
	$username=$_GET['username'];
}
if($_GET['password']!=null){
	$password=$_GET['password'];
}
if($_GET['database']!=null){
	$database=$_GET['database'];
}

//连接数据库
$db=new MySQL($server,$username,$password,$database);
$db->connect();


//执行查询操作
if($_GET['action']=="query"){
	
}

//关闭数据库
$db->close();
?>