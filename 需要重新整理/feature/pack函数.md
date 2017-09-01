###根据设定的端序，字符集的不同，字符串编码成的二进制数据也不同
PHP 中
pack函数用于将其它进制的数字压缩到位字符串之中。也就是把其它进制数字转化为ASCII码字符串(对应的二进制形式)。
unpack是用来解包经过pack打包的数据包，如果成功，则返回数组。其中格式化字符和执行pack时一一对应，但是需要额外的指定一个key，用作返回数组的key。多个字段用/分隔
这些函数一般用在网络通信中

###相关链接
[PHP: 深入pack/unpack](https://my.oschina.net/goal/blog/195749)

###例子
```php
<?php
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
```

