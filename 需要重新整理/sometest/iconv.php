<?php
echo decodeUnicode("\u7533\u8bf7\u4eba\u59d3\u540d");
function decodeUnicode($str)
{
    return preg_replace_callback(
    	'/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str
	);
}
//ord chr
//hexdec()与dechex()
//echo chr("0x75").chr("0x33");
//$unicode=explode("\\u","\u7533\u8bf7\u4eba\u59d3\u540d");
//$native=$unicode[0];
//for($index=1;$index<count($unicode);$index++){
//	$code=$unicode[$index];
//	$native.=chr("0x".substr($code, 0,2));
//	$native.=chr("0x".substr($code, 2,4));
//	if(strlen($code)>4){
//		$native.=substr($code, 4,strlen($code));
//	}
//}
//echo $native . "<br>";
//
//$str = "Hello world!你好";
//echo bin2hex($str) . "<br>";
////echo hex2bin(bin2hex($str)) . "<br>";
//echo pack("H*",bin2hex($str)) . "<br>";
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		<!--<script>
			var character="\u7533\u8bf7\u4eba\u59d3\u540d".split("\\u");
			var native1=character[0];
			for(var i=1;i<character.length;i++){
				var code=character[i];
				native1+=String.fromCharCode(parseInt("0x"+code.substring(0,4)));
				if(code.length>4){
					native1+=code.substring(4,code.length);
				}
			}
			alert(native1);
		</script>-->
	</body>
</html>