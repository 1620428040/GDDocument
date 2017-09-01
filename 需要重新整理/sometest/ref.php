<?php
//测试引用同一个对象的变量，修改其中一个会不会改变另一个
//结论:1.只有当值是对象时，变量保存的是引用;对于其他数据，变量保存的是值
//	  2.在php里，数组和字符串都不是对象;应该避免把数组当成引用
$arr1=array("a","b","c");
$arr2=$arr1;
$arr1[2]="chan";

$item=$arr1[1];
$item="change";

print_r($arr1);
print_r($arr2);
echo $item."\n";

class Test{
	
}
$obj1=new Test();
$obj2=$obj1;
$obj1->value="2333";
print_r($obj2);
$obj2->name="test";
print_r($obj1);


//测试将一个变量传递给一个函数，函数中修改了变量的值，会不会改变原来的变量的值
//结论:1.在函数内部对变量进行的修改，不会影响到函数外面,除非是超全局变量
//	  2.在函数外定义的变量不能在函数内使用（跟其他很多语言不同！），除非是使用function...use...这种语法定义的回调函数
$value=12137;
$GLOBALS["glo"]=32432;
function func($val){
	echo "func.1:".$val."\n";
	$val=4236823;
	$GLOBALS["glo"]=86745;
	echo $value;
	$value=43245;
	echo "func.2:".$val."\n";
}
echo "out.1:".$value."\n";
echo "glo.1:".$GLOBALS["glo"]."\n";
func($value);
echo "out.2:".$value."\n";
echo "glo.2:".$GLOBALS["glo"]."\n";
?>