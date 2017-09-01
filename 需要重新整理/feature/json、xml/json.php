<?php
//php中没有字典对象，关联数组就相当于字典对象
//php中，json的编码解码依靠json_encode()和json_decode()函数（php5.2+）

//将json数据解析为php中的对象或数组
json_decode($json,true);//关联数组
json_decode($json);//对象
//注意：第二个参数是是否将json对象转化为关联数组，一定要注意

//关联数组 转化为 json数据
$arr = array ('a'=>1,'b'=>2,'c'=>3,'d'=>4,'e'=>5);
echo json_encode($arr);
//输出为{"a":1,"b":2,"c":3,"d":4,"e":5}
json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//JSON_UNESCAPED_UNICODE,以unicode编码输出汉字
//JSON_PRETTY_PRINT,输出的字符串会被用空格缩进

//对象 转化为 json数据
$obj->body           = 'another post';
$obj->id             = 21;
$obj->approved       = true;
$obj->favorite_count = 1;
$obj->status         = NULL;
echo json_encode($obj);
//输出为
//{
//　　"body":"another post",
//　　"id":21,
//　　"approved":true,
//　　"favorite_count":1,
//　　"status":null
//}

//索引数组 转化为 json数据
$arr = Array('one', 'two', 'three');
echo json_encode($arr);
//输出为["one","two","three"]

//类 转化为 json数据
class Foo {
	const     ERROR_CODE = '404';
	public    $public_ex = 'this is public';
	private   $private_ex = 'this is private!';
	protected $protected_ex = 'this should be protected'; 
	public function getErrorCode() {
		return self::ERROR_CODE;
	}
}
$foo = new Foo;
$foo_json = json_encode($foo);
echo $foo_json;
//输出结果是{"public_ex":"this is public"} 
//注意：自定义的类，只有公有的变量会被转化到json中
?>