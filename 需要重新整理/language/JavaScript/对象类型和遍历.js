//获取对象类型
//https://segmentfault.com/q/1010000000669230

//第1种情况是内置对象，
var a = new Date()
var name = Object.prototype.toString.call(a).match(/\[object (.*?)\]/)[1]
//第2种情况，用函数自定义的类
function Foo() {
   var f = new Foo()
}
var name = f.constructor.name
//第 3 种情况，构造是匿名函数
var Foo = function() {}
var f = new Foo()
var name = f.constructor.name    // 得到 ""，这种情况是取不到名字的

//第三种情况，可以自己定义一个类名
var Foo = function() {}
Foo.className = "Foo"
var f = new Foo()
var name = f.constructor.className



//遍历对象，获取对象的属性
function allPrpos(obj) {
	// 用来保存所有的属性名称和值 
	var props = "";
	// 开始遍历 
	for (var p in obj) { // 方法 
		if (typeof(obj[p]) == " function ") {
			obj[p]();
		} else { // p 为属性名称，obj[p]为对应属性的值 
			props += p + " = " + obj[p] + " /n ";
		}
	} // 最后显示所有的属性 
	alert(props);
}
//使用
var div=document.createElement("div");
allPrpos(div);