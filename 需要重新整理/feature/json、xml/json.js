//由于 JSON 语法是 JavaScript 语法的子集，JavaScript 函数 eval() 可用于将 JSON 文本转换为 JavaScript 对象。
//但是这样将json数据作为脚本执行，存在隐患。
var txt = '{ "employees" : [' +
'{ "firstName":"Bill" , "lastName":"Gates" },' +
'{ "firstName":"George" , "lastName":"Bush" },' +
'{ "firstName":"Thomas" , "lastName":"Carter" } ]}';

//eval() 函数使用的是 JavaScript 编译器，可解析 JSON 文本，然后生成 JavaScript 对象。
//必须把文本包围在括号中，这样才能避免语法错误：
var obj = eval ("(" + txt + ")");

//版本比较高的浏览器，支持json解析器
var obj=JSON.parse(txt);
var txt2=JSON.stringify(obj);

//版本较低，需要引用json.js文件

//stringify的参数
//JSON.stringify(value [, replacer] [, space]) 
//value：是必须要的字段。就是你输入的对象，比如数组啊，类啊等等。 
//replacer：这个是可选的。它又分为2种方式，一种是方法，第二种是数组。 
//space：很好理解，用什么来做分隔符的。 

//当replacer为数组的时候，找出value中，key为replacer数组中的元素的对象
//（value只能是对象，是数组的话replacer数组无效）
//（这个key在replacer数组数组里有相同的元素，就取出，没有就丢弃）
//如果是多级对象，应该把每一级要筛选的对象列出来
var txt = '{ "a":"abc","employees" : [' +
	'{ "firstName":"Bill" , "lastName":"Gates" },' +
	'{ "firstName":"George" , "lastName":"Bush" },' +
	'{ "firstName":"Thomas" , "lastName":"Carter" } ]}';
var obj=JSON.parse(txt);
var txt2=JSON.stringify(obj,["a","employees","firstName"],4);
alert(txt2);

//当replacer为对象的时候，就是说把系列化后的每一个对象（记住 是每一个）传进方法里面进行处理。
var txt = '{ "a":"abc","employees" : [' +
	'{ "firstName":"Bill" , "lastName":"Gates" },' +
	'{ "firstName":"George" , "lastName":"Bush" },' +
	'{ "firstName":"Thomas" , "lastName":"Carter" } ]}';
var obj=JSON.parse(txt);
var txt2=JSON.stringify(obj,function(k,v){
	if(k=="a"){
		return v.toString().toUpperCase();
	}
	return v;
	//alert(data);
},4);
alert(txt2);

//space可以是缩进的位数，也可以是占位符，或者字符串