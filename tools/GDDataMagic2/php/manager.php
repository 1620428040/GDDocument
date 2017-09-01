<?php
//直接读取相应的数据表的结构信息，生成默认的meta文件
//$name="";
//$tableName="foo";

//定义常量
define('SQL_HOST', '192.168.1.80');   // 数据库服务器地址
define('SQL_DBNAME', 'sw');    // 数据库名称
define('SQL_USERNAME', 'root');    // 数据库用户账号
define('SQL_PASSWORD', '123456');    // 数据库用户密码
//define('SQL_HOST', '192.168.1.138');   // 数据库服务器地址
//define('SQL_DBNAME', 'bgmobile');    // 数据库名称
//define('SQL_USERNAME', 'root');    // 数据库用户账号
//define('SQL_PASSWORD', '123');    // 数据库用户密码
define('SQL_CONNECTIONSTRING', 'mysql:dbname='.SQL_DBNAME.';host='.SQL_HOST);    // 数据库链接（依据上面自动生成，无需修改）

//create("duty", "oa_duty");
//create("stamp", "oa_stamp_log");
//create("leave", "oa_noteforleave");
//create("travel", "oa_travel");
//create("workLog", "oa_work_log");
//create("recruit", "oa_hr_recruit");
//create("candidate", "oa_hr_candidate");
//create("train", "oa_hr_train");
//create("questionscore", "questionscore");
//create("party_info", "oa_hr_partyinfo");
//create("party_dues", "partyfee");
//create("party_join", "joinparty");
//create("bpm", "bpm_task");
//create("notice", "oa_sys_notice");
//create("message", "oa_msg");
create("uploadfile", "oa_uploadfile");


function create($name,$tableName){
	$path="../meta/".$name.".json";

	$pdo=new PDO(SQL_CONNECTIONSTRING, SQL_USERNAME, SQL_PASSWORD);
	$tableInfo=$pdo->query("SHOW TABLES LIKE '".$tableName."'")->fetchAll(PDO::FETCH_ASSOC);
	if($tableInfo==null){//如果表不存在，则报错
		throw new Exception("要修改的表不存在！");
	}
	else{
		//表信息
		$meta=file_exists($path)?json_decode(file_get_contents($path),TRUE):array();//如果文件已经存在，则根据已有文件进行修改
		isset($meta["describe"])||$meta["describe"]=$tableInfo[0]["Comment"];
		$meta["bindTable"]=$tableName;
		$meta["primaryFields"]=null;
		isset($meta["tools"])||$meta["tools"]=array("browse","delete","search","refresh");
		isset($meta["alias"])||$meta["alias"]=array("people"=>"","title"=>"","date"=>"");
		isset($meta["fieldList"])||$meta["fieldList"]=array();
		
		//字段信息
		$result=$pdo->query("SHOW FULL COLUMNS FROM `".$tableName."`")->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row){
			$field=isset($meta["fieldList"][$row["Field"]])?$meta["fieldList"][$row["Field"]]:array();
			if($row["Key"]==="PRI"){
				$meta["primaryFields"]=$row["Field"];
			}
			$type=explode("(",$row["Type"]);
			isset($field["title"])||$field["title"]=urlencode($row["Comment"]);
			$field["notNull"]=$row["Null"]!=="NO";
			$field["dataType"]=$type[0];
			$field["maxlength"]=intval(str_replace(")", "", $type[1]));
			if(!isset($field["type"])){
				if($type[0]==="int"){
					if($field["maxlength"]==11&&stristr($row["Field"], "time")){
						$field["type"]="Date";
					}
					else{
						$field["type"]="Number";
					}
				}
				elseif($type[0]==="varchar"){
					if($field["maxlength"]>50){
						$field["type"]="LongText";
					}
					else{
						$field["type"]="Base";
					}
				}
			}
			$meta["fieldList"][$row["Field"]]=$field;
		}
	}
	$meta=urldecode(json_encode($meta));
	echo "<pre>".json_encode($meta)."</pre>";
	file_put_contents($path, decodeUnicode($meta));
}
function decodeUnicode($str){
    return preg_replace_callback(
    	'/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str
	);
}
?>