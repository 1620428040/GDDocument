//比较两个对象是否相同，返回值以及含义
//遇到存在死循环的对象会出错!使用compareObjectSimple函数
//"null"			至少有一个对象为空
//"differentType"	类型不同	
//"differentKey"	两个对象有不同的键
//"differentValue"	两个对象有不同的值
//true				两个对象相同
function compareObject(obj1,obj2){
	var isSameKeys=compareObjectKey(obj1,obj2)
	if(isSameKeys!=true){
		return isSameKeys;
	}
	var type=getObjectType(obj1);
	if(isPrimitiveType(type)){
		return true;
	}
	for(var keyName in obj1){
		if(compareObject(obj1[keyName],obj2[keyName])!=true){
			return "differentValue";
		}
	}
	return true;
}
//简单的比较对象，只比较子元素的键和其中原始类型的数据，不通过递归比较所有子元素
//如果对象中存在死循环的话，只能用这个方法比较键是否都相同
//返回值同compareObject
function compareObjectSimple(obj1,obj2){
	var isSameKeys=compareObjectKey(obj1,obj2)
	if(isSameKeys!=true){
		return isSameKeys;
	}
	var type=getObjectType(obj1);
	if(isPrimitiveType(type)){
		return true;
	}
	for(var keyName in obj1){
		if(isPrimitiveType(type)&&obj1!=obj2){
			return "differentValue";
		}
	}
	return true;
}
//确定是否是相同的类（所有键都相同）
//返回值同compareObject
function compareObjectKey(obj1,obj2){
	if(obj1==null||obj2==null){
		return "null";
	}
	var type=getObjectType(obj1);
	//alert(typeof)
	if(type!=getObjectType(obj2)){
		return "differentType";
	}
	if(isPrimitiveType(type)){
		return obj1===obj2;
	}
	var keys1=getAllKey(obj1);
	var keys2=getAllKey(obj2);
	if(keys1.length!=keys2.length){
		return "differentKey";
	}
	for (var i=0;i<keys1.length;i++) {
		if(keys1[i]!=keys2[i]){
			return "differentKey";
		}
	}
	return true;
}
//获取对象的类名（只返回第二节）
function getObjectType(obj){
	var type=Object.prototype.toString.call(obj).match(/\[([^\ ]{1,}) ([^\]]{1,})]/);
	return type[2];
}
//以字符串的形式返回所有的键名
function getAllKey(obj){
	var keys=[];
	for (var keyName in obj) {
		keys.push(keyName);
	}
	return keys;
}
function isPrimitiveType(type){
	if(type=="Undefined"||type=="Null"||type=="Boolean"||type=="Number"||type=="String"){
		return true;
	}
	else{
		return false;
	}
}
