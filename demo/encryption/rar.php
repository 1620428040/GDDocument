<?php
header("Content-type: text/html; charset=utf-8");

//文件压缩加密功能，将系统中保存的文件全部压缩并且加密
define('RAR_PACK_ENABLED', TRUE);//是否使用压缩加密功能
define('RAR_PACK_PASSWORD', "123456");
define('RAR_PACK_EXEPATH', "D:\\Program Files\\WinRAR\\Rar.exe");

class RAR{
	static $state_code=array(
		0=>"操作成功",
		1=>"没有发生致命错误",
		2=>"发生一个致命错误",
		3=>"无效校验和。数据损坏",
		4=>"试图修改使用 'k' 命令锁定的压缩文件",
		5=>"写入磁盘错误",
		6=>"文件打开错误",
		7=>"错误的命令行选项",
		8=>"没有足够的内存进行操作",
		9=>"文件创建错误",
		10=>"没有找到与指定的掩码和选项匹配的文件",
		11=>"密码错误",
		255=>"用户中断操作"
	);
	//执行拼接好的命令
	static function exec($command){
		if(!(RAR_PACK_ENABLED&&file_exists(RAR_PACK_EXEPATH))){
			return FALSE;
		}
		exec($command,$result,$state);
		
//		echo "命令行:".$command."\n";
//		echo "状态:".self::$state_code[$state]."\n";
//		foreach($result as $index=>$line){
//			echo "\t".mb_convert_encoding($line, "utf-8", "gbk")."\n";
//		}
		
		if($state===0){return TRUE;}
		throw new Exception(self::$state_code[$state]);
		return FALSE;
	}
	//将文件压缩加密，保存在相同路径下
	static function packInSitu($path,$password=null,$available=null){
		if(!file_exists($path)||is_dir($path)){
			return FALSE;
		}
		if($password==null) {
			$password=RAR_PACK_PASSWORD;
		}
		$savePath=$path.".zip";
		$command="\"".RAR_PACK_EXEPATH."\" a -t -df -hp\"$password\" -ep1 \"$savePath\" \"$path\"";
		return self::exec($command);
	}
	//将加密压缩包解压到相同路径下
	static function unpackInSitu($path,$password=null){
		$rarPath=$path.".zip";
		if(!file_exists($rarPath)){
			return FALSE;
		}
		if($password==null) {
			$password=RAR_PACK_PASSWORD;
		}
		$pathinfo=pathinfo($path);
		$filePath=$pathinfo["dirname"];
		$command="\"".RAR_PACK_EXEPATH."\" e -or -hp\"$password\" \"$rarPath\" * \"$filePath\"";
		return self::exec($command);
	}
	//读取压缩包中的文件内容并将文件重新压缩加密  RAR::getContents代替 ile_get_contents
	static function getContents($path,$password=null){
		if(file_exists($path)||self::unpackInSitu($path)){
			$content=file_get_contents($path);
			self::packInSitu($path);
			return $content;
		}
		return FALSE;
	}
	//读取压缩包中的文件内容并将文件重新压缩加密  RAR::read代替readfile
	static function read($path,$password=null){
		if(file_exists($path)||self::unpackInSitu($path)){
			readfile($path);
			self::packInSitu($path);
			return TRUE;
		}
		return FALSE;
	}
}
//RAR::packInSitu("G:\\123.txt");
//RAR::unpackInSitu("G:\\123.txt");
//RAR::read("G:\\123.txt");
//echo RAR::getContents("G:\\123.txt");
?>