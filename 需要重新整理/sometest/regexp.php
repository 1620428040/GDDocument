<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
<?php 
$string  =  'April 15, 2003' ;
$pattern  =  '/(\w+) (\d+), (\d+)/i' ;
$replacement  =  '${1}1,$3' ;
echo  preg_replace ( $pattern ,  $replacement ,  $string );

echo "<br/>";

$string  =  'April 15, 2003' ;
$pattern  =  '/(\w+) (\d+), (\d+)/i' ;
echo preg_replace_callback($pattern,  function($matches){
    return  $matches[1].','.$matches[3];
} , $string);

echo "<br/>";

$string  =  'hello world' ;
$pattern  =  '/(he)([l]{2})o/' ;
echo preg_replace_callback($pattern,  function($matches){
	print_r($matches);
    return  $matches[1].','.$matches[2];
} , $string);

?>
		<script type="text/javascript">
			var str="hello world";
			var reg=/(he)([l]{2})o/;
			str.replace(reg,function(regStr,subStr1,subStr2,num,strObj){
//				alert(regStr);
//				alert(subStr1);
//				alert(subStr2);
//				alert(num);
//				alert(strObj);
			});
		</script>
	</body>
</html>
