<?php
define("lb", "<br/>");
//$base是指数字的进制
function strToNumber($str,$glue,$base){
	$length = strlen($str);
	$result = array();
	for($i=0;$i<$length;$i++){
		$result[]=base_convert(ord($str[$i]), 10, $base) ;
	}
	return join($glue, $result);
}
function numberToStr($dec,$glue,$base){
	$codes=explode($glue, $dec);
	$result = "";
	for($i=0;$i<count($codes);$i++){
		$result.=chr(base_convert($codes[$i], $base, 10));
	}
	return $result;
}

//AES 算法和 CBC 模式  加密解密 文件
$path="F:/encrypt/123.docx";
$content=file_get_contents($path);
$extension=pathinfo($path,PATHINFO_EXTENSION);
$parentPath=pathinfo($path,PATHINFO_DIRNAME);

/* 打开加密算法和模式 */
$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');

/* 创建初始向量，并且检测密钥长度。 
 * Windows 平台请使用 MCRYPT_RAND。 */
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
$ks = mcrypt_enc_get_key_size($td);

/* 创建密钥 */
$key = substr(md5('very secret key'), 0, $ks);

/* 初始化加密 */
mcrypt_generic_init($td, $key, $iv);

/* 加密数据 */
$encrypted = mcrypt_generic($td, $content."{{encrypted_file_end}}");

/* 结束加密，执行清理工作 */
mcrypt_generic_deinit($td);

/* 初始化解密模块 */
mcrypt_generic_init($td, $key, $iv);

/* 解密数据 */
$decrypted = mdecrypt_generic($td, $encrypted);
$exploded=explode("{{encrypted_file_end}}", $decrypted);
$decrypted=$exploded[0];

/* 结束解密，执行清理工作，并且关闭模块 */
mcrypt_generic_deinit($td);
mcrypt_module_close($td);

file_put_contents($parentPath."/iv.txt", strToNumber($iv, "-", 16));
file_put_contents($parentPath."/encrypted.enc", $encrypted);
file_put_contents($parentPath."/decrypted1.".$extension, $decrypted);

echo md5_file($path).lb;
echo md5_file($parentPath."/decrypted1.".$extension).lb;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		
	</body>
</html>