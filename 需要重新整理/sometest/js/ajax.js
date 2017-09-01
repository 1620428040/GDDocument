var xmlhttp;
if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp = new XMLHttpRequest();
} 
else { // code for IE6, IE5
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange = function() {
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
	}
}
//GET方式
xmlhttp.open("GET", "test1.txt", true);
xmlhttp.send();
//POST方式
xmlhttp.open("POST", "server.php?action=post", true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("fname=Bill&lname=Gates");

//发送AJAX请求的函数，其中isAsync,isPost为布尔值，params为字典（hash数组），callback是回调函数
function AJAX(url,isAsync,isPost,params,callback){
	var paramString=null;
	for(var key in params){
		var value=params[key];
		if(paramString==null){
			paramString="";
		}
		else{
			paramString+="&";
		}
		paramString+=key+"="+value;
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
		}
	}
	if(!isPost){//GET方式
		xmlhttp.open("GET", url+"?"+paramString, isAsync);
		xmlhttp.send();
	}
	else{//POST方式
		xmlhttp.open("POST", url, isAsync);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(params);
	}
	if(!isAsync){//同步的方式
		callback(xmlhttp.responseText);
	}
}
