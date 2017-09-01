/*常用的工具函数*/
//将首字母转换为大写字母
String.toFirstCaseUpper=function(str){
	return str[0].toUpperCase()+str.substr(1,str.length)
}
//当前类继承另一个类
//调用类的构造方法obj.__proto__.constructor
//父类的构造方法obj.__proto__.__proto__.constructor
//显式的调用父类的方法obj.__proto__.__proto__.method
Function.prototype.extend=function(base){
//	这样写虽然简便，但不能被IE浏览器支持（除了edge）
//	if(this.__proto__){
//		this.prototype.__proto__=base.prototype;
//		this.__proto__=base;
//	}
	overriding(this.prototype,base.prototype);
	overriding(this,base);
	this.prototype.constructor.base=base.prototype.constructor;
}
function overriding(current,base){
	for(var keyName in base){
		if(current[keyName]==null){
			current[keyName]=base[keyName];
		}
		else{
			current[keyName].base=base[keyName];
		}
	}
}
Function.prototype.define=function(){
	window["self"]=this;
	window["method"]=this.prototype;
}
Function.prototype.defineEnd=function(){
	window["self"]=null;
	window["method"]=null;
}
//枚举一个对象的属性，之所以没放在prototype中，是因为那样会将enum函数也枚举出来
Object.enumerate=function(obj,callback){
	var index=0;
	for (var keyName in obj) {
		callback(keyName,obj[keyName],index);
		index++;
	}
}
//启用tooltip
$(function(){
	$(document).tooltip();
});

//将常用的英语单词翻译成汉语
var ECDictionary={
	"insert":"新建",
	"delete":"删除",
	"update":"修改",
	"search":"查找",
	"save":"保存",
	"cancel":"取消"
}
String.prototype.translate=function(){
	if(ECDictionary[this]){
		return ECDictionary[this];
	}
	else{
		return str;
	}
}
