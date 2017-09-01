//字符串对象中的方法
var str="hello world";
str.search();//检索与正则表达式相匹配的值。

str.match();//找到一个或多个正则表达式的匹配。
//返回的是数组，str.match()[0]是匹配到的字符串，str.match()[1......]子字符串

str.replace();//替换与正则表达式匹配的子串。
//regexp　必需。规定了要替换的模式的 RegExp 对象。请注意，如果该值是一个字符串，则将它作为要检索的直接量文本模式，而不是首先被转换为 RegExp 对象。 
//replacement　必需。一个字符串值。规定了替换文本或生成替换文本的函数。

//如果replacement是字符串，那么每个匹配都将由字符串替换。但是 replacement 中的 $ 字符具有特定的含义。如下表所示，它说明从模式匹配得到的字符串将用于替换。 
//字符　　				替换文本 
//$1、$2、...、$99　　	与 regexp 中的第 1 到第 99 个子表达式相匹配的文本。 
//$&　					与 regexp 相匹配的子串。 
//$`　					位于匹配子串左侧的文本。 
//$'　					位于匹配子串右侧的文本。 
//%　					直接量符号。 

//如果replacement 可以是函数而不是字符串。
//在这种情况下，每个匹配都调用该函数，它返回的字符串将作为替换文本使用。
//该函数的第一个参数是匹配模式的字符串。接下来的参数是与模式中的子表达式匹配的字符串，可以有 0 个或多个这样的参数。
//接下来的参数是一个整数，声明了匹配在 stringObject 中出现的位置。
//最后一个参数是 stringObject 本身。
var str="hello world";
var reg=/(he)([l]{2})o/;
str.replace(reg,function(regStr,subStr1,subStr2,num,strObj){
	alert(regStr);
	alert(subStr1);
	alert(subStr2);
	alert(num);
	alert(strObj);
});


str.split();//把字符串分割为字符串数组。


//RegExp 对象

var reg1=new RegExp();
//参数 pattern 是一个字符串，指定了正则表达式的模式或其他正则表达式。
//参数 attributes 是一个可选的字符串，包含属性 "g"、"i" 和 "m"，分别用于指定全局匹配、区分大小写的匹配和多行匹配。
//ECMAScript 标准化之前，不支持 m 属性。如果 pattern 是正则表达式，而不是字符串，则必须省略该参数。
//返回值是数组

var reg2=/he[l]{2}o/;
reg1.compile();//编译正则表达式。
reg1.exec();//检索字符串中指定的值。返回找到的值，并确定其位置。
reg2.test();//检索字符串中指定的值。返回 true 或 false。
