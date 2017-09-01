<?
session_start();
if(!isset($_SESSION["user"])||count($_SESSION["user"])>5){
	$_SESSION["user"]=array(
		array(
			"firstname"=>"名",
			"lastname"=>"姓",
			"phone"=>"手机号",
			"email"=>"电子邮箱"
		),
		array(
			"firstname"=>"名2",
			"lastname"=>"姓2",
			"phone"=>"手机号2",
			"email"=>"电子邮箱2"
		)
	);
}

$table= isset($_REQUEST["table"])? $_REQUEST["table"]:"user";
$action=isset($_REQUEST["action"])? $_REQUEST["action"]: "search";
$index=isset($_REQUEST["phone"])?search("phone", $_REQUEST["phone"]):FALSE;
$dataLog=array();
$dataLog["session"]=$_SESSION;
$dataLog["request"]=$_REQUEST;

$result=null;
try{
	switch($action){
		case "create":
			$_SESSION["user"][]=$_REQUEST;
			break;
		case "delete":
			if($index) {unset($_SESSION["user"][$index]);}
			break;
		case "updete":
			if($index) {$_SESSION["user"][$index]=$_REQUEST;}
			break;
		case "search":
			$result=$_SESSION["user"];
			break;
	}
	if($result==null){
		$result=array("success"=>"ok");
	}
	$dataLog["session2"]=$_SESSION;
	$dataLog["result"]=$result;
}
catch(Exception $exce){
	$result=array("errorMsg"=>"fail");
	$dataLog["exception"]=$exce;
}

file_put_contents("data.json", json_encode($dataLog));
echo json_encode($result);



function search($key,$value){
	for($i=0;$i<count($_SESSION["user"]);$i++){
		if($_SESSION["user"][$i][$key]==$value){
			return $i;
		}
	}
	return FALSE;
}
?>