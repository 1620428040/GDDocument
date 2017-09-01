<?php
//旧的入口页面，新的入口页面是index.php

//响应头
header("Access-Control-Allow-Origin: *"); //允许的访问源：所有
header("Access-Control-Allow-Headers:X-Requested-With");//允许的访问类型：ajax

//导入设置信息
//require_once("../../../settings-local.php");
//或者定义常量
define('SQL_HOST', '192.168.1.80');   // 数据库服务器地址
define('SQL_DBNAME', 'sw');    // 数据库名称
define('SQL_USERNAME', 'root');    // 数据库用户账号
define('SQL_PASSWORD', '123456');    // 数据库用户密码
define('SQL_CONNECTIONSTRING', 'mysql:dbname='.SQL_DBNAME.';host='.SQL_HOST);    // 数据库链接（依据上面自动生成，无需修改）

//导入类库文件
require("DataMagicList.php");

//默认参数
$name=isset($_REQUEST["name"]) ? $_REQUEST["name"] : "news";

//拿来测试的参数
$demo=array(
	"default"=>array(),
	"insert"=>array(
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
	),
	"delete"=>array(
		"action"=>"delete",
		"where"=>"id in (2)"
	),
	"search"=>array(
		"action"=>"search",
		"where"=>array(
			"title"=>"trg"
		)
	)
);

$db=new PDO(SQL_CONNECTIONSTRING, SQL_USERNAME, SQL_PASSWORD);
$magic=DataMagicList::createDataMagicList($name,$db);
$magic->permission=array("person","oa_news");
$data=$magic->shortcutOperate($_REQUEST);

//兼容jsonp协议
if (isset($_REQUEST['callback'])) {
	$callback=$_REQUEST['callback'];
    echo $callback . '(' . json_encode($data) . ');';
} else {
    echo json_encode($data);
}
?>