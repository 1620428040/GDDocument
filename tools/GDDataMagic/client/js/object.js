/*创建类需要的函数*/
function createClass(name,params){
//	params.extend;父类
//	params.construct;构造函数
//	params.methods;方法
//	params.statics;静态方法
//	params.property;属性
	var newClass=params.construct;
	newClass.prototype.className=name;//.className可以获取类名
	window[name]=newClass;
	if(params.methods){
		extendClass(newClass.prototype,params.methods);
	}
	if(params.statics){
		extendClass(newClass,params.statics);
	}
	if(params.extend){
		extendClass(newClass,params.extend);
		extendClass(newClass.prototype,params.extend.prototype);
	}
	if(params.property){
		this.property=params.property;
	}
}
//将base中的方法添加到current中，如果已经有了，添加到.base的引用中
//arguments.callee.base.call(this,...)//可以调用被重载的方法
function extendClass(current,base){
	for(var keyName in base){
		if(current[keyName]==null){
			current[keyName]=base[keyName];
		}
		else{
			current[keyName].base=base[keyName];
		}
	}
}
/*创建类需要的函数*/