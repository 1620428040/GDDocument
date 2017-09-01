///<jscompress sourcefile="main.js" />
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

;
///<jscompress sourcefile="datatype.js" />
//================================================================
//数据类型
//================================================================
/* 基本数据类型。比如文字
 * 
 * 属性
 * field  对应的输入控件实例
 * inputField  对应的输入框的类。定义输入框类之后在这个属性上注册一下，就能在创建输入框时自动调用
 * pattern  该数据类型要求的正则表达式
 */
var DMBaseDataType={
	title:"基础数据类型",
	construct:function(fieldName, fieldMeta) {
		this.fieldName = fieldName;
		this.fieldMeta = fieldMeta;
		var regexp = fieldMeta.regexp || this.defaultRegExp;
		if(regexp) {
			this.regexp = new RegExp(regexp);
		}
	},
	inputField:DataMagic.Field.Abstract, //注册为Base类型数据的输入框
	tranToString: function(data) {
		return(data == null || data === "") ? "" : data.toString();
	},
	tranToData: function(str) {
		return str;
	},
	//验证数据是否符合该类型的要求，比如说Number类型的数据必须全部由数字组成
	typeValidation: function(value) {
		return this.pattern ? this.pattern.test(value) : true;
	},
	createInputField: function(data) {
		if(this.field == null) {
			this.field = new this.inputField(this);
		}
		this.field.createField(this.fieldName, this.fieldMeta, data);
		return this.field;
	},
	validationValue: function(value, input) {
		var validated = true;
		//检查是否为空
		if(validated && value === null) {
			validated = this.fieldMeta.notNull ? false : true;
		}
		//检查是否符合正则表达式
		if(validated && this.regexp) {
			validated = this.regexp.test(value);
		}
		//检查是否符合数据类型的要求
		validated = validated && this.typeValidation(value);
		//错误提示
		if(validated) {
			this.field.hideMistake(input);
		} else {
			this.field.showMistake(input);
		}
		DataMagic.debug("类型检查  value:"+value+"  validated:"+validated);
		return validated;
	},
	getValue: function() {
		var value = this.field.getValue(this.field.input);
		this.validated = this.validationValue(value, this.field.input);
		return this.tranToData(value);
	},
	createSearchField: function() {
		if(this.field == null) {
			this.field = new this.inputField(this);
		}
		this.field.createSearchField(this.fieldName, this.fieldMeta, null);
		return this.field;
	},
	validationSearchParams: function(value,input) {
		var validated = (value === null) ? true : this.typeValidation(value);
		if(validated) {
			this.field.hideMistake(input);
		} else {
			this.field.showMistake(input);
		}
		return validated;
	},
	getSearchParams: function() {
		var value = this.field.getValue(this.field.input);
		this.validated = this.validationSearchParams(value, this.field.input);
		return this.tranToData(value);
	}
}
DataMagic.DataType.Base = Class.extend(DMBaseDataType);

/*长文本类型*/
DataMagic.DataType.LongText = DataMagic.DataType.Base.inherit("长文本数据类型");

/*数字类型*/
var DMNumberDataType={
	title:"数字数据类型",
	pattern: /^[0-9]{0,}$/,
	tranToData: function(str) {
		return str == null ? null : parseInt(str);
	},
	getSearchParams: function() {
		var values = {};

		var min = this.field.getValue(this.field.minInput);
		var validated1 = this.validationSearchParams(min, this.field.minInput);
		values.start = this.tranToData(min);

		var max = this.field.getValue(this.field.maxInput);
		var validated2 = this.validationSearchParams(max, this.field.maxInput);
		values.end = this.tranToData(max);

		this.validated = validated1 && validated2;
		if(values.start === null && values.end === null) {
			return null;
		}
		return values;
	}
}
DataMagic.DataType.Number = DataMagic.DataType.Base.extend(DMNumberDataType);

/*日期类型*/
var DMDateDataType={
	title:"日期数据类型",
	format: "yyyy年M月d日",
	pattern: /^[0-9]{4}年[0-9]{1,2}月[0-9]{1,2}日$/,
	tranToString: function(s) {
		if(s == null || s === "") {
			return "";
		}
		if(typeof s === "string") {
			s = parseInt(s);
		}
		var dat = new Date(s * 1000);
		return dat.format(this.format);
	},
	tranToData: function(str) {
		if(str === null) {
			return null;
		}
		var date = Date.createFormFormatString(this.format, str);
		return date.getTime() / 1000;
	}
}
DataMagic.DataType.Date = DataMagic.DataType.Number.extend(DMDateDataType);

/*日期时间类型*/
var DMDateTimeDataType={
	title:"日期加时间数据类型",
	format: "yyyy年M月d日  H时m分",
	pattern: /^[0-9]{4}年[0-9]{1,2}月[0-9]{1,2}日  [0-9]{1,2}时[0-9]{1,2}分$/
}
DataMagic.DataType.DateTime = DataMagic.DataType.Date.extend(DMDateTimeDataType);

/* 单选类型
 * 属性
 * options  选项
 * any 当进行搜索时，表示任意选项的字符串
 */
var DMSelectDataType={
	title:"单选数据类型",
	construct:function(fieldName, fieldMeta) {
		DataMagic.DataType.Base.apply(this,arguments);
		this.options = {};
		this.any = "任意";
		var titles = [];
		var splited = fieldMeta.valueRange.split(";");
		for(var i in splited) {
			var kvp = splited[i].split(",");
			this.options[kvp[0]] = kvp[1];
			titles.push(kvp[1]);
		}
		this.pattern = new RegExp("^(?:" + titles.join("|") + "|" + this.any + ")$");
	},
	typeValidation: function(value) {
		return this.pattern.test(value);
	},
	tranToString: function(value) {
		return this.options[value] || "";
	},
	tranToData: function(str) {
		for(var kn in this.options) {
			var kv = this.options[kn];
			if(kv === str) {
				return kn;
			}
		}
	}
}
DataMagic.DataType.Select = DataMagic.DataType.Base.extend(DMSelectDataType);

/*多选类型*/
var DMMultipleDataType={
	title:"多选数据类型",
	typeValidation: function(value) {
		var values = value.split(",");
		for(var i in values) {
			if(!this.pattern.test(values[i])) {
				return false;
			}
		}
		return true;
	},
	tranToString: function(value) {
		var str = [];
		var values = value.split(",");
		for(var i in values) {
			str.push(this.options[value]);
		}
		return str.length > 0 ? str.join(",") : null;
	},
	tranToData: function(str) {
		var str = str.split(",");
		var values = [];
		for(var i in str) {
			for(var kn in this.options) {
				var kv = this.options[kn];
				if(kv = str[i]) {
					values.push(kn);
				}
			}
		}
		return values.length > 0 ? values.join(",") : null;
	}
}
DataMagic.DataType.Multiple = DataMagic.DataType.Select.extend(DMMultipleDataType);

/*布尔类型*/
var DMBoolDataType={
	title:"布尔数据类型",
	construct:function(fieldName, fieldMeta) {
		DataMagic.DataType.Base.call(this, fieldName, fieldMeta);
		this.options = {"1": "是","0": "否"};
		this.any = "任意";
	},
	typeValidation: function(value) {
		return value === this.any ? true : /^[是否]$/.test(value);
	},
	tranToString: function(bool) {
		return bool == null ? null : (bool === "1" ? "是" : "否");
	},
	tranToData: function(str) {
		var value = null;
		if(str === "是") {
			value = "1";
		} else if(str === "否") {
			value = "0";
		}
		return value;
	}
}
DataMagic.DataType.Bool = DataMagic.DataType.Select.extend(DMBoolDataType);
;
///<jscompress sourcefile="model.js" />
//================================================================
//数据模型
//================================================================
/* 数据模型类，负责联系后台进行增删改查等操作，本地存储数据等
 * 
 * 属性
 * name给Model命名
 * meta是指元数据
 * data是指数据
 * storage是sessionStorage或者localStorage
 * host服务器地址
 * server进行不同的操作时，具体的URL链接，如果没有定义，则通过host生成。
 * filter指定固定的过滤条件，当前model只能搜索filter限定的范围
 * lastFilter上一次的搜索条件，用来在搜索之后，实现统计等功能
 * pagesize分页，每页多少条
 * page当前页数
 */
var DMListModel={
	title:"基础model",
	construct:function(name, storage, host, controller, params ) {
		this.name = name;
		this.storage = storage;
		this.server = {
	//		getMeta:null,
	//		insert:null,
	//		delete:null,
	//		update:null,
	//		search:null
		};
		if(host) {
			this.host = host;
		}
		this.controller = controller;
		if(params){
			this.pagesize=params.pagesize||20;
			this.page=params.page||0;
			this.filter=params.filter||null;
			this.lastFilter=this.filter;
			//兼容的写法
			if(!(params.pagesize||params.page||params.filter)){
				this.filter=params;
			}
		}
		else{
			this.pagesize=20;
			this.page=0;
		}
		if(host || this.server.getMeta) {
			this.downloadMeta();
		}
	},
	//设置服务器为标准的接口
	getServerUrl: function(action) {
		if(this.server[action]) {
			return this.server[action];
		}
		if(this.host) {
			var url = this.host;
			var sign = url.indexOf("?") < 0 ? "?" : "&";
			return url + sign + "name=" + this.name + "&action=" + action;
		} else {
			DataMagic.debug("需要指定服务器地址或者提供meta")
		}
	},
	//从指定的地址下载元数据
	downloadMeta: function(callback) {
		var self = this;
		DataMagic.Model.request(this.getServerUrl("getMeta"), {
			where: this.filter,
			pagesize:this.pagesize,
			page:this.page
		}, function(data) {
			self.initWithMeta(data);
			if(callback) {
				callback.call(this);
			}
		});
	},
	//使用指定的元数据初始化
	initWithMeta: function(meta) {
		this.meta = meta;
		//		meta.showIndex=(meta.showIndex==="1"||meta.showIndex===1);
		//		meta.allowMove=(meta.allowMove==="1"||meta.allowMove===1);
		for(var key in this.meta.fieldList) {
			var value = this.meta.fieldList[key];
			var dataType = DataMagic.DataType[value.type || "Base"] || DataMagic.DataType.Base;
			value.dataType = new dataType(key, value);
			//			value.notNull=(value.notNull==="1"||value.notNull===1);
		}
		this.controller.onMetaLoad();

		if(meta.data) {
			this.data = this.changeDataIndex(meta.data);
			this.controller.onDataLoad();
		} else if(this.host || this.server.search) {
			var self = this;
			this.search(function() {
				self.controller.onDataLoad();
			});
		}
	},
	//将data数组的索引改成使用id索引
	changeDataIndex: function(data) {
		var newData = [];
		for(var i in data) {
			newData[data[i].id] = data[i];
		}
		return newData;
	},
	//从服务器获取
	request: function(url, params, callback) {
		var self = this;
		DataMagic.Model.request(url, params, function(data) {
			callback(data);
		});
	},
	//保存到本地存储
	save: function() {
		if(this.storage) {
			this.storage.setItem(this.name + "_meta", JSON.stringify(this.meta));
			this.storage.setItem(this.name, JSON.stringify(this.data));
		}
	},
	//从本地存储读取
	read: function() {
		if(this.storage) {
			this.meta = JSON.parse(this.storage.getItem(this.name + "_meta"));
			this.data = JSON.parse(this.storage.getItem(this.name));
			return this.data;
		}
	},
	//清空
	clear: function() {
		if(this.storage) {
			this.storage.removeItem(this.name + "_meta");
			this.storage.removeItem(this.name);
		}
		this.data = null;
	},
	//获取其中的一条
	detail: function(id) {
		return this.data[id];
	},
	//TODO:等写完增删改查，需要再改一下。读取数据，如果没有就下载
	readOrLoad: function(url, params, callback) {
		var data = this.read();
		if(data == null) {
			data = this.request(url, params, callback);
		} else {
			callback(data);
		}
	},
	//插入一条数据，data指要插入的数据，callback是回调函数，参数是包含新的ID的数据
	insert: function(data, callback) {
		var self = this;
		var newData = $.extend({}, data);
		if(newData.id) {
			delete newData.id;
		}
		this.request(this.getServerUrl("insert"), { data: newData }, function(result) {
			newData.id = result;
			self.data[newData.id] = newData;
			if(callback) {
				callback(newData);
			}
		});
	},
	//删除若干条数据，data指要删除的数据的索引组成的数组;callback是回调函数，参数是被删除的ID
	deleteItems: function(ids, callback) {
		var self = this;
		this.request(this.getServerUrl("delete"), { where: { id: ids } }, function(result) {
			for(var i in ids) {
				delete self.data[ids[i]];
			}
			if(callback) {
				callback(ids);
			}
		});
	},
	//修改一条数据，data指要修改后的数据(包含在this.data的)
	update: function(id, data, callback) {
		var self = this;
		var newData = $.extend({}, data);
		if(newData.id) {
			delete newData.id;
		}
		this.request(this.getServerUrl("update"), { where: { id: id }, data: newData }, function(result) {
			data.id = id;
			self.data[id] = data;
			if(callback) {
				callback(data);
			}
		});
	},
	//搜索，where指搜索条件，callback指回调函数，参数为搜索到的数据数组
	search: function(where, callback) {
		where = where || {};
		if(this.filter) {
			$.extend(where, this.filter);
		}
		this.lastFilter=where;
		var self = this;
		this.request(this.getServerUrl("search"), {
			where: where,
			pagesize:this.pagesize,
			page:this.page
		}, function(result) {
			if(callback) {
				self.data = self.changeDataIndex(result);
				callback(result);
			}
		});
	},
	pageTurn:function(num,success,failed){
		var gotoPage=this.page+num;
		if(gotoPage<0){
			failed("已经是第一页了");
			return;
		}
		var self = this;
		this.request(this.getServerUrl("search"), {
			where: this.lastFilter,
			pagesize:this.pagesize,
			page:gotoPage
		}, function(result) {
			if(result.length){
				self.data = self.changeDataIndex(result);
				self.page=gotoPage;
				success(result);
			}
			else{
				failed("已经是最后一页了")
			}
		});
	},
	pageTurnTo:function(num,callback){
		var self = this;
		this.request(this.getServerUrl("search"), {
			where: this.lastFilter,
			pagesize:this.pagesize,
			page:this.page
		}, function(result) {
			if(callback) {
				self.data = self.changeDataIndex(result);
				callback(result);
			}
		});
	},
	statistics:function(statistics,callback) {
		var self = this;
		this.request(this.getServerUrl("statistics"), {where:this.lastFilter,statistics:statistics}, function(result) {
			if(callback) {
				callback(result);
			}
		});
	}
}
DataMagic.Model = Class.extend(DMListModel,{
	request: function(url, params, callback) {
		if(url == null) {
			return;
		}
		DataMagic.ajaxSend(params);
		
//		$.ajax({
//			type: "post",
//			url: url,
//			async: true,
//			cache:false,
//			data: params,
//			success: function(data,status,xhr) {
//				if(data == null || data === "") {
//					DataMagic.debug("服务器返回的内容为空");
//					return;
//				}
//				try {
//					if(typeof data==="string"){
//						data = JSON.parse(data);
//					}
//					if(data.status === "error") {
//						DataMagic.debug(data.reason);
//						return;
//					}
//				} catch(e) {
//					DataMagic.debug("解析数据失败:" + e);
//				}
//				callback(data);
//			},
//			crossDomain: true,
//			complete:function(xhr,status){
//				DataMagic.ajaxStop();
//			}
//		});


		$.ajax({
			type: "get",
			url: url,
			async: true,
			cache:false,
			data: params,
			dataType:'jsonp',
			success: function(data,status,xhr) {
				if(data == null || data === "") {
					DataMagic.debug("服务器返回的内容为空");
					return;
				}
				try {
					if(data.status === "error") {
						DataMagic.debug(data.reason);
						return;
					}
				} catch(e) {
					DataMagic.debug("解析数据失败:" + e);
				}
				callback(data);
			},
			complete:function(xhr,status){
				DataMagic.ajaxStop();
			}
		});
	}
});;
///<jscompress sourcefile="controller.js" />
//================================================================
//控制器
//================================================================
/* 控制器类，负责接收用户的操作请求，调用model，然后更新view
 * 
 * 属性
 * model指当前控制器对应的model
 * list指当前控制器对应的列表(或者表格)对象
 * toolbar工具栏
 * form表单对象
 * ready布尔值，是否准备完成，可以加载数据了
 */
var DMListController={
	title:"基础控制器",
	construct:function(name, storage, host, params) {
		DataMagic.Controller.instanceList.push(this);
		this.model = new DataMagic.Model(name, storage, host, this, params);
		this.list = new DataMagic.View.List(this);
		this.toolbar = new DataMagic.View.Toolbar(this);
		this.form = new DataMagic.View.Form(this);
	}, 
	onDOMLoad: function() {
		DataMagic.debug("DOM加载完成");
		this.list.initView($(".DMList"));
		this.toolbar.initView($(".DMToolbar"));
		this.form.initView($(".DMForm"));
		if(this.model.meta) {
			this.initViewWithMeta();
		}
	},
	onMetaLoad: function() {
		DataMagic.debug("meta加载完成");
		if(document.readyState === "interactive" || document.readyState === "complete") {
			this.initViewWithMeta();
		}
	},
	onDataLoad: function() {
		DataMagic.debug("data加载完成");
		if(this.ready) {
			this.list.insert(this.model.data);
		}
	},
	onReady: null,
	initViewWithMeta: function() {
		DataMagic.debug("开始初始化视图");
		this.ready = true;
		DataMagic.initViewWithMeta(this.model.meta);
		if(this.list) {
			this.list.clearAll();
			if(this.model.data) {
				this.list.insert(this.model.data);
			}
		}
		this.toolbar.refresh(this.model.meta.tools);
		if(this.onReady) {
			this.onReady();
		}
	},
	/* 执行指定的函数
	 * action要执行的函数
	 * coerce强制执行而不管工具栏中有没有这个按钮
	 */
	execute: function(action,coerce) {
		if(!coerce&&this.toolbar.buttons.indexOf(action)===-1){
			return false;
		}
		if(this[action]) {
			this[action]();
		} else {
			DataMagic.debug(this);
			DataMagic.debug("调用的方法不存在:" + action);
		}
	},
	/*控制表单*/
	showForm: function(mode, data) {
		DataMagic.debug("显示表单");
		DataMagic.debug(data);
		this.form.mode = mode;
		this.form.clearAll();
		if(mode === "insert") {
			for(var key in this.model.meta.fieldList) {
				var value = this.model.meta.fieldList[key];
				var field = value.dataType.createInputField(value.defaultValue);
				this.form.append(field);
			}
		} else if(mode === "search") {
			for(var key in this.model.meta.fieldList) {
				var value = this.model.meta.fieldList[key];
				var field = value.dataType.createSearchField();
				this.form.append(field);
			}
		} else {
//			console.log(data);
			for(var key in this.model.meta.fieldList) {
				var value = this.model.meta.fieldList[key];
//				console.log(key,value,data[key]);
				var field = value.dataType.createInputField(data[key]);
				this.form.append(field);
			}
		}
		this.form.show();
	},
	showFormForDetails: function(mode) { //在表单中显示一条数据的详情，进行查看或修改
		var cells = this.list.getSelectedItems(true);
		if(cells) {
			var id = this.list.getIDWithItems(cells)[0];
			var data = this.model.data[id];
			this.list.hide();
			if(mode === "update") {
				this.toolbar.refresh(["save", "cancel"]);
			} else {
				this.toolbar.refresh(["cancel"]);
			}
			this.showForm(mode, data);
			this.currentID = id;
			this.currentItem = cells;
		}
	},
	getFormData: function() {
		var getter = this.form.mode === "search" ? "getSearchParams" : "getValue";
		var result = {};
		var validated = true; //数据是否通过了验证
		for(var kn in this.model.meta.fieldList) {
			var kv = this.model.meta.fieldList[kn];
			var value = kv.dataType[getter]();
			if(value) {
				result[kn] = value;
			}
			validated = kv.dataType.validated && validated;
			if(!validated) {
//				DataMagic.dialog(kn);
			}
		}
		if(!validated) {
			DataMagic.dialog("您输入的数据格式不正确！");
			return;
		}
		return result;
	},
	save: function() {
		var result = this.getFormData();
		if(result == null) {
			return;
		}
		DataMagic.debug("获取表单的值");
		DataMagic.debug(result);
		
		delete result.id;

		var self = this;
		if(this.form.mode === "insert") {
			this.model.insert(result, function(data) {
				self.list.insert(data);
			});
		} else if(this.form.mode === "update") {
			var self = this;
			this.model.update(this.currentID, result, function(data) {
				self.list.update(self.currentItem, data, self.model.meta.fieldList);
			});
		} else if(this.form.mode === "search") {
			this.model.search(result, function(data) {
				self.list.clearAll();
				self.list.insert(data);
			});
		}
		this.cancel();
	},
	cancel: function() {
		this.toolbar.refresh(this.model.meta.tools);
		this.list.show();
		this.form.hide();
	},
	/*控制列表*/
	insert: function() {
		this.list.hide();
		this.showForm("insert");
		this.toolbar.refresh(["save", "cancel"]);
	},
	deleteItems: function() {
		var cells = this.list.getSelectedItems(true,true);
		if(cells){
			var ids = this.list.getIDWithItems(cells);
			var self = this;
			this.model.deleteItems(ids, function() {
				self.list.remove(cells);
			});
		}
	},
	update: function() {
		this.showFormForDetails("update");
	},
	browse: function() {
		this.showFormForDetails("browse");
	},
	search: function() {
		this.list.hide();
		this.showForm("search");
		this.toolbar.refresh(["save", "cancel"]);
	},
	refresh: function() {
		var self = this;
		this.model.search(null, function(data) {
			self.list.clearAll();
			self.list.insert(data);
		});
	},
	pageTurn:function(num){
		var self = this;
		this.model.pageTurn(num,function(data){
			self.list.clearAll();
			self.list.insert(data);
		},function(message){
			DataMagic.alert(message);
		});
	}
}
DataMagic.Controller = Class.extend(DMListController,{
	instanceList: [],
	onDOMLoad:function() {
		//因为是放在$()中的，所以此处的DataMagic.Controller不能改成this
		for(var i in DataMagic.Controller.instanceList) {
			DataMagic.Controller.instanceList[i].onDOMLoad();
		}
	}
});
//当DOM加载完成时，通知控制器，初始化视图对象
$(DataMagic.Controller.onDOMLoad);;
///<jscompress sourcefile="view.js" />
//================================================================
//视图部分
//只使用jQuery的情况下，实现对页面元素的操作逻辑，只有基本的样式
//================================================================

/*view类的基类*/
var DMBaseView={
	title:"视图类的基类",
	clearAll: function() {
		this.container.empty();
	},
	append: function(view) {
		if(!view.container) {
			DataMagic.debug(view);
		}
		this.container.append(view.container);
	},
	show: function() {
		this.container.show();
	},
	hide: function() {
		this.container.hide();
	}
}
DataMagic.View.Base = DataMagic.View.Abstract.extend(DMBaseView);

/* 工具栏类
 * buttons 按钮组
 * */
var DMToolbarView={
	title:"工具栏类",
	executeEvent:"click",//当发生什么事件时，触发操作，在手机版上是"tap"，在电脑版上是"click"
	buttonPool: {},
	initView:function(container){
		this.container = container;
		var self=this;
		this.container.find(".DMButton").each(function(index,element){
			var item=$(element);
			var command=item.data("command");
			self.buttonPool[command]=item;
		}).remove();
	},
	refresh: function(buttons) {
		this.buttons=buttons;
		this.container.find(".DMButton").remove();
		for(var i in buttons) {
			var self = this;
			var command = buttons[i];
			if(command==="delete"){
				command="deleteItems";
			}
			var button=this.buttonPool[command]?$(this.buttonPool[command]):this.buildButton(command);
			button.appendTo(this.container);
			button.on(this.executeEvent,function() {
				self.controller.execute($(this).data("command"));
			});
		}
	},
	buildButton: function(command) {
		return $('<a class="DMButton" data-command="' + command + '"><span>' + command + '</span></a>');
	}
}
DataMagic.View.Toolbar = DataMagic.View.Base.extend(DMToolbarView);

/* 列表类
 */
var DMListView={
	title:"列表类",
	initView:function(container){
		this.container = container;
		this.item = this.container.find(".DMItem").detach();
		if(this.item.length === 0) {
			this.item = this.buildItem();
		}
		var self = this;
		this.item.click(function(ev) {
			self.onItemClick($(this));
		});
		this.item.dblclick(function(ev) {
			self.onItemDblclick($(this));
		});
	},
	onItemClick:function(item){
		item.toggleClass("selected");
	},
	//打开选中的一项的详情页
	onItemDblclick:function(item){
		this.container.find(".selected").removeClass("selected");
		item.addClass("selected");
		this.controller.execute("browse");
	},
	buildItem:function(){
		return $('<li class="DMItem">'
			+'<div><span data-field="title"></span></div>'
			+'<div style="font-size: 14px;color: gray;font-family: kaiti;">'
			+'<span data-field="people"></span>'
			+'<span data-field="date"></span></div></li>');
	},
	clearAll: function() {
		this.container.find(".DMItem").remove();
	},
	//notNull,是否允许为空;multiple,是否允许多选;mess,如果发生错误,要显示的提示
	getSelectedItems: function(notNull,multiple,mess) {
		var items = this.container.find(".DMItem.selected");
		if(notNull&&items.length===0){
			DataMagic.dialog(mess||"至少选择一条数据");
			return false;
		}
		if(!multiple&&items.length>1){
			items.removeClass("selected");
			DataMagic.dialog(mess||"只能选择一条数据");
			return false;
		}
		return items;
	},
	getIDWithItems: function(items) {
		var ids = [];
		for(var i = 0; i < items.length; i++) {
			var id = items.eq(i).data("id");
			ids.push(id);
		}
		return ids;
	},
	insert: function(data) {
		if(!(data instanceof Array)) {
			data = [data];
		}
		for(var i in data) {
			var dataItem = data[i];
			var cell = this.item.clone(true);
			this.update(cell, dataItem, this.controller.model.meta.fieldList);
			cell.data("id", dataItem.id);
			this.container.append(cell);
		}
	},
	update: function(cell, data, fieldMeta) {
		var self = this;
		cell.find("[data-field]").each(function() {
			var current = $(this);
			var field = current.data("field");
			if(data[field]) {
				if(fieldMeta[field]) {
					current.html(fieldMeta[field].dataType.tranToString(data[field]));
				} else {
					current.html(data[field]);
				}
			}
		});
	},
	remove: function(cells) {
		cells.remove();
	}
}
DataMagic.View.List = DataMagic.View.Base.extend(DMListView);

/*表格类*/
DataMagic.View.Table = DataMagic.View.List.inherit("表格类");

/*表单类*/
var DMFormView={
	title:"表单类",
	initView:function(container){
		this.container = container;
		this.container.hide();
	}
}
DataMagic.View.Form = DataMagic.View.Base.extend(DMFormView);

/* 输入框的基类
 * 
 * 属性
 * input对应输入框
 */
var DMBaseField={
	title:"输入框的基类",
	buildField: function(name, meta, data) {
		return $('<div>'+this.buildInput(name, meta, data)+'</div>');
	},
	buildInput:function(name, meta, data,type){
		return '<label for="' + name + '">' + meta.title + '</label>'+
				'<input class="DMInput" type="text" id="' + name + '"/>';
	},
	createField: function(name, meta, data) {
		this.container = this.buildField(name,meta,data);
		this.input=this.container.find(".DMInput");
		this.listenInputChange(this.input);
		this.setValue(this.input, data);
	},
	listenInputChange:function(input){
		var self = this;
		input.change(function(){
			self.onInputChange($(this));
		});
		input.click(function(){
			self.onInputClick($(this));
		});
	},
	onInputChange:function(input){
		this.dataType.validationValue(this.getValue(input),input);
	},
	setValue: function(input, value) {
		if(value != null) {
			var str = this.dataType.tranToString(value);
			input.val(str);
		}
	},
	getValue: function(input) {
		var value = input.val();
		return value === "" || value === null ? null : value;
	},
	showMistake: function(input) {
		input.removeClass("input-success");
		input.addClass("input-error");
	},
	hideMistake: function(input) {
		input.removeClass("input-error");
		input.addClass("input-success");
	}
}
DataMagic.Field.Base = DataMagic.Field.Abstract.extend(DMBaseField);
DataMagic.DataType.Base.prototype.inputField = DataMagic.Field.Base;;
///<jscompress sourcefile="test.js" />
//================================================================
//以下部分是方便测试用的函数
//================================================================
//用给定的数据填充表单
function fill() {
	var data = {
		"id": 1232,
		"title": "测试-值班",
		"duty_content": "ergre",
		"duty_userid": 442124,
		"people": "ergre",
		"inputuserid": 4241,
		"inputusername": "ergre",
		"date": "2017年4月5日  8时28分",
		"begin_time": "2017年10月10日",
		"end_time": "2017年10月10日",
//		"visitor": 1,
		"visitor_name": "ergre",
//		"file": 1,
		"file_name": "ergre"
	}
	for(var kn in data) {
		var node = $('[id=' + kn + ']');
		if(node.is("input")||node.is("textarea")){
			node.val(data[kn]);
		}
		else{
			node.text(data[kn]);
		}
	}
};
///<jscompress sourcefile="DataMagic.mui.js" />
//================================================================
//以下适配MUI框架的内容
//================================================================
DataMagic.initViewWithMeta = function(meta) {
	$(".mui-title").html(meta.describe);
}
DataMagic.dialog = function(mess) {
	mui.toast(mess);
}
DataMagic.alert = function(mess) {
	mui.alert(mess);
}
//当发送ajax请求时，显示一个进度条
DataMagic.ajaxSend=function(){
	var progressbar = mui('body').progressbar();
	if(progressbar.show) {
		progressbar.show();
	} else {
		$(function() {
			mui('body').progressbar().show();
		});
	}
}
DataMagic.ajaxStop=function(){
	mui('body').progressbar().hide();
}
DataMagic.View.Toolbar.prototype.executeEvent="tap";
DataMagic.View.Toolbar.prototype.buttonPool={
	"browse":'<a class="mui-tab-item DMButton" data-command="browse"><span class="mui-icon mui-icon-more-filled"></span><span class="mui-tab-label">浏览</span></a>',
	"insert":'<a class="mui-tab-item DMButton" data-command="insert"><span class="mui-icon mui-icon-plus"></span><span class="mui-tab-label">新建</span></a>',
	"deleteItems":'<a class="mui-tab-item DMButton" data-command="deleteItems"><span class="mui-icon mui-icon-trash"></span><span class="mui-tab-label">删除</span></a>',
	"update":'<a class="mui-tab-item DMButton" data-command="update"><span class="mui-icon mui-icon-compose"></span><span class="mui-tab-label">修改</span></a>',
	"search":'<a class="mui-tab-item DMButton" data-command="search"><span class="mui-icon mui-icon-search"></span><span class="mui-tab-label">查找</span></a>',
	"save":'<a class="mui-tab-item DMButton" data-command="save"><span class="mui-icon mui-icon-search"></span><span class="mui-tab-label">确认</span></a>',
	"cancel":'<a class="mui-tab-item DMButton" data-command="cancel"><span class="mui-icon mui-icon-search"></span><span class="mui-tab-label">取消</span></a>',
	"refresh":'<a class="mui-tab-item DMButton" data-command="refresh"><span class="mui-icon mui-icon-refresh"></span><span class="mui-tab-label">刷新</span></a>'
}
DataMagic.View.Toolbar.prototype.buildButton=function(command) {
	return $('<a class="mui-tab-item DMButton" data-command="' + command + '"><span class="mui-icon mui-icon-plus"></span><span class="mui-tab-label">' + DataMagic.dictionary[command] + '</span></a>');
}
DataMagic.View.List.prototype.buildItem=function(){
	return $('<li class="mui-table-view-cell mui-media DMItem">'+
			'<div><span data-field="title"></span></div>'+
			'<div style="font-size: 14px;color: gray;font-family: kaiti;">'+
				'<span data-field="people" class="mui-pull-left"></span>'+
				'<span data-field="date" class="mui-pull-right"></span>'+
			'</div></li>');
}
DataMagic.Field.Base.prototype.buildField=function(name, meta, data) {
	return $('<div class="mui-input-row">'+
				'<label for="' + name + '">' + meta.title + '</label>'+
				'<input class="mui-input-clear DMInput" type="text" id="' + name + '" placeholder="' + (meta.describe||"") + '"/>'+
			'</div>');
}

var DMLongTextField={
	title:"长文本输入框",
	buildField:function(name, meta, data) {
		return $('<div class="mui-input-row" style="height: 120px;">'+
					'<label for="' + name + '">' + meta.title + '</label>'+
					'<textarea id="' + name + '" style="height: 100%;" class="DMInput" rows="5" placeholder="' + (meta.describe||"") + '"></textarea>'+
				'</div>');
	}
}
DataMagic.Field.LongText = DataMagic.Field.Base.extend(DMLongTextField);
DataMagic.DataType.LongText.prototype.inputField = DataMagic.Field.LongText;

/* 数字类型的输入框
 * 注意
 * 如果将input控件改成number类型的，而且用户输入了字符，就无法触发change事件，所以是个坑
 * 
 * 属性
 * input对应正常情况下的输入框
 * minInput/maxInput在搜索状态下，输入最小值和最大值的输入框（数字类型及其子类）
 */
var DMNumberField={
	title:"数字类型的输入框",
	addon:{"min":"最小值","max":"最大值"},
	buildSearchField:function(name, meta, data){
		return $(this.buildInput(name, meta, data,"min")+
				this.buildInput(name, meta, data,"max"));
	},
	buildInput:function(name, meta, data, type){
		if(type){
			var inputID = name + "-" + type ;
			var html='<div class="mui-input-row">'+
						'<label for="' + inputID + '">' + (type === "min" ? meta.title : "") +'</label>'+
						'<input class="mui-input-clear DMInput" type="text" id="' +inputID + '" placeholder="输入要查找的 '+meta.title+'的范围的'+this.addon[type]+'"/>'+
					'</div>';
			return html;
		}
	},
	createSearchField: function(name, meta, data) {
		this.container = this.buildSearchField(name, meta, data);
		this.inputs = this.container.find(".DMInput");
		this.minInput = this.inputs.eq(0);
		this.maxInput = this.inputs.eq(1);
		this.listenInputChange(this.inputs);
	}
}
DataMagic.Field.Number = DataMagic.Field.Base.extend(DMNumberField);
DataMagic.DataType.Number.prototype.inputField = DataMagic.Field.Number;


/* 日期类型输入框
   * 属性
   * format  用在picker中的时间格式，跟默认的格式不一样
   * option  调用picker时的设置
   * */
DataMagic.Field.Date = DataMagic.Field.Number.inherit("日期类型输入框", null, null, {
	format:"yyyy-MM-dd",
	option:{"type": "date"},
	buildField: function(name, meta, data) {//disalbed
		return $('<div class="mui-input-row">'+
					'<label for="' + name + '">' + meta.title + '</label>'+
					'<label id="' + name + '" class="DMInput picker-input"></label>'+
				'</div>');
	},
	buildSearchField:function(name, meta, data){
		return $(this.buildInput(name, meta, data,"min")+
				this.buildInput(name, meta, data,"max"));
	},
	buildInput:function(name, meta, data, type){
		if(type){
			var inputID = name + "-" + type ;
			var html='<div class="mui-input-row">'+
						'<label for="' + inputID + '">' + (type === "min" ? meta.title : "") +'</label>'+
						'<label id="' +inputID + '" class="picker-input DMInput"></label>'+
					'</div>';
			return html;
		}
	},
	//输入框被点击，弹出picker
	onInputClick: function(input) {
		var option = this.option;
		var value = input.text();
		if(value) {
			option["value"]=Date.changeFormat(this.dataType.format,this.format,value);
		}
		var picker = new mui.DtPicker(option);
		var self=this;
		picker.show(function(rs) {
			input.text(Date.changeFormat(self.format,self.dataType.format,rs.text));
			picker.dispose();
		});
	},
	setValue: function(input, value) {
		if(value != null) {
			var str = this.dataType.tranToString(value);
			input.text(str);
		}
	},
	getValue: function(input) {
		var value = input.text();
		return value === "" || value === null ? null : value;
	}
});
DataMagic.DataType.Date.prototype.inputField = DataMagic.Field.Date;

/*时间日期类型输入框*/
DataMagic.Field.DateTime = DataMagic.Field.Date.inherit("时间日期类型输入框", null, null, {
	format:"yyyy-MM-dd HH:mm",
	option:{}
});
DataMagic.DataType.DateTime.prototype.inputField = DataMagic.Field.DateTime;


/* 单选类型的输入框
 * 
 * 属性
 * picker 从底部弹出的选择器
 * options选项
 */
DataMagic.Field.Select = DataMagic.Field.Date.inherit("单选类型的输入框", function(dataType) {
	this.dataType = dataType;
	this.picker = this.createPicker();
	this.options = this.dataType.options;
}, null, {
	createPicker: function() {
		var picker = $("#popover-select");
		if(picker.length === 0) {
			picker = $('<div id="popover-select" class="mui-popover mui-popover-action mui-popover-bottom">' +
				'<ul class="mui-table-view" id="select-options"></ul>' +
				'<ul class="mui-table-view">' +
					'<li class="mui-table-view-cell"><a id="confirm"><b>确认</b></a></li>' +
					'<li class="mui-table-view-cell"><a id="cancel"><b>取消</b></a></li>' +
				'</ul></div>');
			$("body").append(picker);
		}
		return picker;
	},
	onInputClick: function() {
		var html = '';
		for(var kn in this.options) {
			var kv = this.options[kn];
			html += '<li class="mui-table-view-cell" data-value="' + kn + '">' + kv + '</li>';
		}
		var self = this;
		this.items = this.picker.find("#select-options").html(html).children("li");
		this.items.click(function() {
			self.onselect($(this));
		});
		this.picker.find("#confirm").click(function() {
			self.onconfirm();
		});
		this.picker.find("#cancel").click(function() {
			self.oncancel();
		});
		mui("#popover-select").popover("show");
	},
	//当picker中的选项被点击时
	onselect: function(item) {
		this.input.text(item.text());
		mui("#popover-select").popover("hide");
	},
	onconfirm: function() {
		mui("#popover-select").popover("hide");
	},
	oncancel: function() {
		mui("#popover-select").popover("hide");
	},
	createSearchField: function(name, meta, data) {
		this.options[-1] = this.dataType.any;
		this.createField(name, meta, data);
		delete this.options[-1];
	}
});
DataMagic.DataType.Select.prototype.inputField = DataMagic.Field.Select;

/*多选类型的输入框*/
DataMagic.Field.Multiple = DataMagic.Field.Select.inherit("多选类型的输入框", null, null, {
	//多选跟单选的区别是，选中后不会立即关闭picker，直到用户点击确认
	onselect: function(item) {
		item.toggleClass("selected");
	},
	onconfirm: function() {
		var items = this.picker.find(".selected");
		var values = [];
		for(var i = 0; i < items.length; i++) {
			values.push(items.eq(i).text());
		}
		this.input.text(values.join(","));
		mui("#popover-select").popover("hide");
	}
});
DataMagic.DataType.Multiple.prototype.inputField = DataMagic.Field.Multiple;;
