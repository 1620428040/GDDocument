<?php
//响应头
header("Access-Control-Allow-Origin: *"); //允许的访问源：所有
header("Access-Control-Allow-Headers:X-Requested-With");//允许的访问类型：ajax
//定义常量
define('SQL_HOST', '192.168.1.138');   // 数据库服务器地址
define('SQL_DBNAME', 'bgmobile');    // 数据库名称
define('SQL_USERNAME', 'root');    // 数据库用户账号
define('SQL_PASSWORD', '123');    // 数据库用户密码
define('SQL_CONNECTIONSTRING', 'mysql:dbname='.SQL_DBNAME.';host='.SQL_HOST);    // 数据库链接（依据上面自动生成，无需修改）
//引用文件
require("main.php");
//默认参数
$name=isset($_REQUEST["name"]) ? $_REQUEST["name"] : "news";

$defaultParams=array();
$insertParams=array(
	"action"=>"insert",
	"data"=>array(
		"title"=>"432werer",
		"content"=>"gtrtr",
		"type"=>"1",
		"add_time"=>"1483200000",
		"tag"=>"0",
		"userid"=>"tgrt",
		"username"=>"trgtrgt"
	)
);
$deleteParams=array(
	"action"=>"delete",
	"where"=>"id in (2)"
);

$db=new PDO(SQL_CONNECTIONSTRING, SQL_USERNAME, SQL_PASSWORD);
$magic=DataMagic::createDataMagic($name,$db);
$magic->permission=array("person","oa_news");
$data=$magic->shortcutOperate($defaultParams);
echo json_encode($data);
?>