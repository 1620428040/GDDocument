//在IE浏览器中，每一种mime类型对应的能打开此类型的浏览器插件
var needIEPlugin={
	"application/pdf":[
		"FoxitReader.FoxitReaderCtl",
		"PDF.PdfCtrl",
		"AcroPDF.PDF"
	]
}
//检测浏览器能否在线打开给定的MIME类型，返回值是当前浏览器中能打开这种类型的插件的名称
function didSupportMimeType(needMime){
	var supportPluginNames=[];
	//检测非IE浏览器有没有相应的插件
	for(var pluginIndex=0;pluginIndex<navigator.plugins.length;pluginIndex++){
		var plugin=navigator.plugins[pluginIndex];
		for (var mimeIndex=0;mimeIndex<plugin.length;mimeIndex++) {
			var mime=plugin[mimeIndex];
			if(mime.type===needMime){
				supportPluginNames.push(plugin.name);
			}
		}
	}
	if(supportPluginNames.length===0){
		//检测IE游览器有没有相应的插件
		for (var IEPluginIndex=0;IEPluginIndex<needIEPlugin[needMime].length;IEPluginIndex++) {
			try {
				var IEPluginName=needIEPlugin[needMime][IEPluginIndex];
	          	var hasIEPlugin = new ActiveXObject(IEPluginName);
	          	if (hasIEPlugin) {
	          		supportPluginNames.push(IEPluginName);
	          	}
	        } 
	        catch (e) {
	        }
		}
	}
	return supportPluginNames;
}
//insertPDFViewer("../pdf/123.pdf",800,600)
function insertPDFViewer(path,width,height){
	var htmlStr="";
	var pluginNames=didSupportMimeType("application/pdf");
	if(pluginNames.length>0){
		htmlStr='<embed width="800" height="600" src="'+path+'"></embed>';
	}
	else{
		htmlStr="没有找到能打开PDF文件的插件";
	}
	//Adobe reader 有时候会出错，所以需要用object控件嵌套一下
	htmlStr='<object classid="clsid:CA8A9780-280D-11CF-A24D-444553540000" width="'+width+'" height="'+height+'" border="0">'+
		    '<param name="_Version" value="65539">'+
		    '<param name="_ExtentX" value="20108">'+
		    '<param name="_ExtentY" value="10866">'+
		    '<param name="_StockProps" value="0">'+
		    '<param name="SRC" value="'+path+'">'+
		    htmlStr+'</object>';
	return htmlStr;
}
//允许使用PDF.js解析PDF的情况
//transform=0,不转换;1,如果其他方式失败则转换;2,默认转换;4,默认转换且禁止打印和下载
//注意，如果需要转换的话，需要提供PDF文件相对于网站根目录的位置
function insertPDFViewer2(path,width,height,transform){
	var PDFjsPath="/GDTestCode/tools/plugin/pdf/";
	var htmlStr="";
	if(transform==null){
		transform=0;
	}
	else if(transform===2){
		htmlStr='<iframe src="'+PDFjsPath+'viewer.html?file='+path+'" width="800" height="600"></iframe>';
		return htmlStr;
	}
	else if(transform===3){
		htmlStr='<iframe src="'+PDFjsPath+'viewer2.html?file='+path+'" width="800" height="600"></iframe>';
		return htmlStr;
	}
	
	var pluginNames=didSupportMimeType("application/pdf");
	if(pluginNames.length>0){
		htmlStr='<embed width="800" height="600" src="'+path+'"></embed>';
	}
	else if(transform===1){
		htmlStr='<iframe src="'+PDFjsPath+'viewer.html?file='+path+'" width="800" height="600"></iframe>';
	}
	else{
		htmlStr="没有找到能打开PDF文件的插件";
	}
	//Adobe reader 有时候会出错，所以需要用object控件嵌套一下
	htmlStr='<object classid="clsid:CA8A9780-280D-11CF-A24D-444553540000" width="'+width+'" height="'+height+'" border="0">'+
		    '<param name="_Version" value="65539">'+
		    '<param name="_ExtentX" value="20108">'+
		    '<param name="_ExtentY" value="10866">'+
		    '<param name="_StockProps" value="0">'+
		    '<param name="SRC" value="'+path+'">'+
		    htmlStr+'</object>';
	return htmlStr;
}