###cURL 是一种利用URL语法规定来传输文件和数据的工具.
- 支持很多协议，如HTTP、FTP、TELNET等。
- 可以在命令行中模拟浏览器访问网页
- PHP也支持 cURL 库。

###相关链接：
[本文将介绍 cURL 的一些高级特性，以及在PHP中如何运用它](http://www.jb51.net/article/34745.htm)
[php中curl的详细解说](http://blog.csdn.net/yanhui_wei/article/details/21530811)


###php中的例子
- GET方式
```php
$curl=curl_init();//初始化
//设置请求的参数
curl_setopt($curl, CURLOPT_URL, "http://www.jb51.net");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HEADER, 0);
$output = curl_exec($curl);//执行
curl_close($curl);//释放
print_r($output);//输出
```

- POST方式
```php
$server="http://localhost/web_services.php";
$post_data = array ("username" => "bob","key" => "12345");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $server);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);// POST方式发送
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的数据
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
$output = curl_exec($ch);
curl_close($ch);
print_r($output);
```

- 常用参数含义：
```php
CURLOPT_URL//设定URL
CURLOPT_RETURNTRANSFER//如果等于1，则数据会作为返回值；等于0，数据会直接发送给浏览器
CURLOPT_HEADER//启用时会将头文件的信息作为数据流输出

//使用POST方式发送请求
CURLOPT_POST//等于1，则使用POST方式
CURLOPT_POSTFIELDS//POST形式发送的字段，数组格式

//是否使用SSL验证
CURLOPT_SSL_VERIFYPEER
CURLOPT_SSL_VERIFYHOST
```