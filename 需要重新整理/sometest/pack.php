<html>
	<head>
		<meta charset="UTF-8"/>
		<title></title>
	</head>
	<body>
		
		
<pre>
<?php
0x23432;
$bin = pack("a", "d");
echo "output: " . $bin . "\n";//即使以二进制的形式输出ASCII码，计算机也会自动转换成字符串
echo "output: 0x" . bin2hex($bin) . "\n";//函数把 ASCII字符的字符串转换为十六进制值，可以用hex2bin转换回来

$bin = pack("a3", "中");
echo "output: 0x" . bin2hex($bin) . "\n";
echo "output: " . chr(0xe4) . chr(0xb8) . chr(0xad) . "\n";//chr()输出以二进制编码的字符
echo "output: " . $bin{0} . $bin{1} . $bin{2} . "\n";
?>
<?php
$bin = @pack("a9SS", "陈一回", 20, 1);
$data = @unpack("a9name/sage/Sgender", $bin);
print_r($data);
?>
</pre>


	</body>
</html>
