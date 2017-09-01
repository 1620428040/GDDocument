//需要导入ajax.js
//../common/js/ajax.js
//需要导入json2.js
//../common/js/json2.js

//需要设置server的位置
var writeToFileServer="server.php";

function writeToFile(data,title,filepath,cover){
	var tag="JavaScript";
	ajaxPOSTRequest(writeToFileServer,{"data":data,"title":title,"filepath":filepath,"cover":cover,"tag":tag},function(result){
		//alert(result);
	});
}

//将对象转化成可以被JSON.stringify()接收的对象
//需要提供会造成死循环的属性
//ie9+
function paramsObject(obj,exclude,thisKey){
	if(thisKey==null){
		thisKey="";
	}
	var data=null;
	var type=getObjectType(obj);
	if(exclude.indexOf(thisKey)>=0){
		data=type[2];
		return;
	}
	else if(type[2]=="String"){
		data=obj;
	}
	else if(type[2]=="Number"){
		data=obj;
	}
	else{
		data={};
		data["ClassName"]=type[2];
		for(var key in obj){
			data[key]=paramsObject(obj[key],exclude,key);
		}
	}
	return data;
}
function getObjectContent(obj,exclude,thisKey,data){

	
	//如果缩进为空，则不换行
	var newLine=indent==null?"":"\n";
	head=head==null?"":head;
	head=indent==null?head:indent+head;
	thisKey=thisKey==null?"":thisKey+":";
	//alert("start");
	//alert("obj="+obj+"  indent="+indent+"  head="+head+"  thisKey="+thisKey);
	//return;
	
	//alert("key:"+thisKey+"  type:"+type);
	//alert(type);

	
	var str=head+"类型:"+type+newLine;
	
	return str;
}
//function getObjectContent(obj,indent,head,thisKey,exclude){
//	if(exclude.indexOf(thisKey)>=0){
//		//alert("跳过:"+thisKey);
//		return;
//	}
//	
//	//如果缩进为空，则不换行
//	var newLine=indent==null?"":"\n";
//	head=head==null?"":head;
//	head=indent==null?head:indent+head;
//	thisKey=thisKey==null?"":thisKey+":";
//	//alert("start");
//	//alert("obj="+obj+"  indent="+indent+"  head="+head+"  thisKey="+thisKey);
//	//return;
//	var type=getObjectType(obj);
//	//alert("key:"+thisKey+"  type:"+type);
//	//alert(type);
//	if(type[2]=="String"){
//		return head+thisKey+obj+newLine;
//	}
//	else if(type[2]=="Number"){
//		return head+thisKey+obj+newLine;
//	}
//	
//	var str=head+"类型:"+type+newLine;
//	for(var key in obj){
//		str+=getObjectContent(obj[key],indent,head,key,exclude);
//		//alert(str);
//	}
//	return str;
//}
//对象的类型，数组形式，例如：
//[object PluginArray],object,PluginArray
//[object String],object,String
//[object Number],object,Number
function getObjectType(obj){
	return Object.prototype.toString.call(obj).match(/\[([^\ ]{1,}) ([^\]]{1,})]/);
}