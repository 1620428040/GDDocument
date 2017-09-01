"use strict";
/* DataMagic  一个实现自定义列表、表单、表格等功能的框架
 * 
 * 修改说明2017-1-22
 * 这次修改，希望能使用MVC设计模式，将原来的代码解构，并且实现只替换View部分就能适应不同的UI框架
 * 
 * 依赖关系
 * 基础功能只需要jQuery
 * 样式需要导入对应的css样式表
 * 
 */

//================================================================
//polyfills补充一些JavaScript引擎不提供的功能
//================================================================
//抽象函数，用在抽象类中，防止调用到null导致的报错
Function.abstract = function() {
	DataMagic.debug("abstract function");
}
//空函数
Function.empty = function() {}
/*Object.create作用与高版本的JavaScript中的Object.create相同*/

if(!Object.create) {
	Object.create = function(parentPrototype) {
		Function.empty.prototype = parentPrototype;
		return new Function.empty();
	}
}

/* Class  用来定义类的一个基类
 * 
 * Class.create  生成一个新类
 * var Animal = Class.create();//生成一个Animal类
 * 
 * Class.inherit  生成一个继承当前类的类
 * var Cat = Animal.inherit();//生成一个继承父类Animal类的新类 Cat类
 * 
 * this.getClass().base.call(this,...)  如果需要调用父类的构造函数
 * 
 * 参数
 * title类名，可以是任意字符串，在调试时很有用。可省略
 * parentClass父类，如果省略该参数，则继承Class类
 * constructor构造函数，如果省略该参数，则构造函数是一个自动生成的、调用父类的构造函数的函数
 * classMethods类方法
 * methods方法
 */
var Class = function() {};
Class.inherit = function(title, constructor, classMethods, methods) {
	var parentClass=this;
	//生成默认的构造函数
	var newClass = typeof constructor === "function" ? constructor : function() {
		parentClass.apply(this, arguments);
	}
	//继承原型
	newClass.prototype = Object.create(parentClass.prototype);
	//复制父类的类方法
	for(var kn in parentClass) {
		newClass[kn] = parentClass[kn];
	}
	//添加新的类方法
	if(classMethods) {
		for(var kn in classMethods) {
			newClass[kn] = classMethods[kn];
		}
	}
	//添加新的方法
	if(methods) {
		for(var kn in methods) {
			newClass.prototype[kn] = methods[kn];
		}
	}
	//设置各种参数
	newClass.prototype.constructor = newClass;
	newClass.classTitle = title;
	newClass.base = parentClass;
	return newClass;
}
Class.extend=function(prototype,classMethods){
	return this.inherit(prototype.title,prototype.construct,classMethods,prototype);
}
//当调用obj.toString时，显示当前类或者对象的介绍
Class.classTitle = "基础类";
Class.toString = function() {
	if(this.base && this.classTitle == null) {
		return this.base.toString() + " 的子类";
	}
	return this.classTitle;
}
Class.prototype.toString = function() {
	return this.getClass().toString() + " 的实例";
}
//获取当前实例所在的类;不能兼容ie8
Class.prototype.getClass = function() {
	if(Object.getPrototypeOf){
		return Object.getPrototypeOf(this).constructor;
	}
	else{
		return "当前浏览器不支持获取原型";
	}
}

/* Date.prototype.format	将 Date 转化为指定格式的String
 * 
 * 参数：
 * fmt  字符串    可以使用的符号：年(y)、月(M)、日(d)、12小时(h)、24小时(H)、分(m)、秒(s)、毫秒(S)、周(E)、季度(q)
 * yyyy表示四位数的年份;yy表示两位数的年份
 * MM表示有前导零;M表示没有前导零的月份
 * 
 * eg:
 * (new Date()).format("yyyy-MM-dd hh:mm:ss.S")==> 2006-07-02 08:09:04.423
 * 
 * TODO:有个bug，如果使用12小时制，没办法确认是上午还是下午
 */
Date.prototype.format = function(fmt) {
	var o = {
		"M+": this.getMonth() + 1, //月份
		"d+": this.getDate(), //日
		"h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12, //小时
		"H+": this.getHours(), //小时
		"m+": this.getMinutes(), //分
		"s+": this.getSeconds(), //秒
		"q+": Math.floor((this.getMonth() + 3) / 3), //季度
		"S": this.getMilliseconds() //毫秒
	};
	var week = {
		"0": "/u65e5",
		"1": "/u4e00",
		"2": "/u4e8c",
		"3": "/u4e09",
		"4": "/u56db",
		"5": "/u4e94",
		"6": "/u516d"
	};
	if(/(y+)/.test(fmt)) {
		fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	}
	if(/(E+)/.test(fmt)) {
		fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "/u661f/u671f" : "/u5468") : "") + week[this.getDay() + ""]);
	}
	for(var k in o) {
		if(new RegExp("(" + k + ")").test(fmt)) {
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		}
	}
	return fmt;
}
Date.createFormFormatString = function(fmt, str) {
	var formatSign = ["y", "M", "d", "H", "h", "m", "s", "S"];
	var list = [];
	var time = new Date(0);
	for(var i in formatSign) {
		var sign = formatSign[i];
		if(new RegExp("(" + sign + "+)").test(fmt)) {
			list.push({
				sign: sign,
				length: RegExp.$1.length,
				index: fmt.indexOf(RegExp.$1)
			});
		}
	}
	list.sort(function(a, b) {
		return a.index - b.index;
	});
	var parts = str.match(new RegExp("([0-9]{1,4})", "g"));
	for(var i in list) {
		var kn = list[i];
		var kv = parts[i];
		if(kn.sign === "y") {
			time.setYear(parseInt("2000".substr(0, 4 - kn.length) + kv));
		} else if(kn.sign === "M") {
			time.setMonth(parseInt(kv) - 1);
		} else if(kn.sign === "d") {
			time.setDate(parseInt(kv));
		} else if(kn.sign === "H") {
			time.setHours(parseInt(kv));
		} else if(kn.sign === "h") {
			time.setHours(parseInt(kv));
		} else if(kn.sign === "m") {
			time.setMinutes(parseInt(kv));
		} else if(kn.sign === "s") {
			time.setSeconds(parseInt(kv));
		} else if(kn.sign === "S") {
			time.setMilliseconds(parseInt(kv));
		}
	}
	return time;
}
Date.changeFormat=function(from,to,value){
	return Date.createFormFormatString(from,value).format(to);
}

//================================================================
//相关的功能都定义在DataMagic类中
//================================================================
var DataMagic = {
	Model: null, //模型
	Controller: null, //控制器
	View: {}, //View类组
	DataType: {}, //数据类型类组
	Field: {}, //输入框类组
	//当DOM和meta都加载完后，初始化视图
	initViewWithMeta: function(meta) {
	},
	//console有时候会被禁用，所以改成用这个方法输出日志
	debug:function(mess){
//		console.log(mess);
	},
	//弹出提示框
	dialog: function(mess) {
		alert(mess);
	},
	//弹出警告框
	alert: function(mess) {
		alert(mess);
	},
	//数据发送之前，进行的操作
	ajaxSend:function(data) {
	},
	//数据接收结束，进行的操作
	ajaxStop:function() {
	}
};


//================================================================
//抽象层，定义了MVC各层的抽象接口
//为了避免出现调用到null的情况，所以未定义的函数的值设置为Function.abstract
//尽量避免在非view部分调用跟UI框架相关的代码。比如直接调用jQuery对象的show、hide、append等
//================================================================

/* view类的抽象类
 * 
 * 属性
 * container UI对象对应的jQuery对象
 */
var DMAbstractView={
	title:"抽象视图",
	construct:function(controller) {
		this.controller = controller;
	},
	initView: Function.abstract, //当页面的OM加载完成后进行的操作
	clearAll: Function.abstract, //清除所有内容
	append: Function.abstract, //将控件添加到当前控件上
	show: Function.abstract, //显示
	hide: Function.abstract //隐藏
}
DataMagic.View.Abstract = Class.extend(DMAbstractView);

/* 输入框的抽象类
 * 
 * 属性
 * input对应正常情况下的input元素
 * minInput/maxInput对应搜索模式下，数字类型及其子类用的，用来输入最小值和最大值的输入框
 */
var DMAbstractField={
	title:"输入框的抽象类",
	construct:function(dataType) {
		this.dataType = dataType;
	},
	setValue: Function.abstract, //设置值
	getValue: Function.abstract, //获取值
	createField: function(name, meta, data) {}, //创建输入框
	//创建搜索框
	createSearchField: function(name, meta, data) {
		this.createField(name, meta, data);
	},
	showMistake: Function.abstract, //显示错误标记
	hideMistake: Function.abstract, //隐藏错误标记
	onInputClick:function(input){}//输入框被点击
}
DataMagic.Field.Abstract = DataMagic.View.Abstract.extend(DMAbstractField);

