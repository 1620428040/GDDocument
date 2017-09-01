<?php
/*$this,self,parent的区别*/
//$this是指向当前对象的指针
//self是指向当前类的指针
//parent是指向父类的指针

/*"::","->"的区别*/
//范围解析操作符（也可称作 Paamayim Nekudotayim）或者更简单地说是一对冒号，可以用于访问静态成员，类常量，还可以用于覆盖类中的属性和方法。
//其他情况用"->"

//类的继承，方法的重写例子
class Base{
	function __construct(){
		$this->name="张三";
	}
}
class Class2 extends Base{
	function __construct(){
		parent::__construct();
		echo $this->name;
	}
}
new Class2();
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

<pre>
<?php
//类似于OC中的invoke等方法，php中也有处理调用到不存在的（或没有权限访问）方法的函数，
class Test{
	function __construct(){
		//构造函数
	}
	function __destruct(){
		//析构函数
	}
	function __callStatic($function_name, $args){
	    echo "你所调用的静态方法：$function_name";
	}
	function __call($function_name, $args){
	    echo "你所调用的方法：$function_name(参数：<br />";
	    print_r($args);
	    echo ")不存在！";
	}
}
$test=new Test();
$test->say("haha");
?>
</pre>