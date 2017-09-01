//GET形式发送请求
function ajaxGETRequest(url,params,callback,isAsync,isDebug){
	AJAX(url,"GET",params,callback,isAsync,isDebug);
}
//POST形式发送请求
function ajaxPOSTRequest(url,params,callback,isAsync,isDebug){
	AJAX(url,"POST",params,callback,isAsync,isDebug);
}
//发送AJAX请求的函数，其中isAsync为布尔值，params为字典（关联数组），callback是回调函数
//isDebug==true时，会alert出异常的状态
function AJAX(url,method,params,callback,isAsync,isDebug){
	if(isAsync==null){
		isAsync=true;
	}
	if(isDebug==null){
		isDebug=false;
	}
	
	var xmlhttp;
	if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else { // code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if(isAsync){//异步的方式
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				callback(xmlhttp.responseText);
			}
			else if(isDebug==true&&xmlhttp.status != 200){
				alert("readyState:"+xmlhttp.readyState+"  status:"+xmlhttp.status);
			}
		}
	}
	if(method=="GET"){//GET方式
		if(params==null){
			
		}
		else if(url.indexOf("?")>=0){
			url+="&"+getParamsString(params);
		}
		else{
			url+="?"+getParamsString(params);
		}
		//alert("url:"+url);
		xmlhttp.open(method, url, isAsync);
		xmlhttp.send();
	}
	else if(method=="POST"){//POST方式
		xmlhttp.open(method, url, isAsync);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(getParamsString(params));
	}
	if(!isAsync){//同步的方式
		callback(xmlhttp.responseText);
	}
}
//将数组解析为字符串"&"连接的字符串
function getParamsString(params){
	if(params==null){
		return "";
	}
	var paramString=null;
	for(var key in params){
		var value=params[key];
		if(paramString==null){
			paramString="";
		}
		else{
			paramString+="&";
		}
		if(value==null){
			value="";
		}
		paramString+=key+"="+value;
		//alert("paramString:"+paramString+"  key:"+key+"  value:"+value);
	}
	return paramString;
}
