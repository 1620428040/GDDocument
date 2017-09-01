<?php
try{
	

session_start();

$table= isset($_REQUEST["table"])? $_REQUEST["table"]:null;
$action=isset($_REQUEST["action"])? $_REQUEST["action"]: "";
$result=array();
if($table==null){
	$result["errorMsg"]="table不能为空";
	echo json_encode($result);
	exit;
}

//session
$flag=FALSE;
if($_SESSION["data"]==null||$flag==TRUE){
	$_SESSION["data"]=array();
	$_SESSION["data"][]=array("id"=>"0",
		"type"=>"允许",
		"objectType"=>"个人",
		"objectName"=>"root",
		"permission"=>"完全控制",
		"inheritFrom"=>"不是继承的",
		"inherit"=>"是");
}

switch ($action) {
	case "create":
		$count=count($_SESSION["data"]);
		$_SESSION["data"][$count]=array(
			"id"=>$count,
			"type"=>$_REQUEST["type"],
			"objectType"=>$_REQUEST["objectType"],
			"objectName"=>$_REQUEST["objectName"],
			"permission"=>$_REQUEST["permission"],
			"inherit"=>$_REQUEST["inherit"]);
		break;
		
	case "update":
		$_SESSION["data"][$_REQUEST["id"]]=array(
			"type"=>$_REQUEST["type"],
			"objectType"=>$_REQUEST["objectType"],
			"objectName"=>$_REQUEST["objectName"],
			"permission"=>$_REQUEST["permission"],
			"inherit"=>$_REQUEST["inherit"]);
		break;
	
	case "delete":
		unset($_SESSION["data"][$_REQUEST["id"]]);
		$result["success"]="ok";
		break;
	
	default:
		$result=$_SESSION["data"];
		break;
}
echo json_encode($result);


}
catch(Exception $exce){
	$result["errorMsg"]=$exce;
	echo json_encode($result);
}
?>
