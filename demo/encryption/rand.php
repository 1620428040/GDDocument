<?php
define("lb", "<br/>");


$size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC);
echo $size.lb;

$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
$size = mcrypt_enc_get_iv_size($td);
echo $size.lb;

$iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
echo $iv.lb;

echo strToNumber($iv, "-", 16);

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
?>