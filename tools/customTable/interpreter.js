/*基于jQuery框架的解释器*/
//callback(index)返回显示cell的元素的选择器
function createStructure(box,name,type,style,data,callback){
	var meta=null;
	if(typeof name==="string"){
		//通过ajax请求获取结构的meta
	}
	else if(typeof meta=="object"){
		meta=name;
	}
	else{
		return;
	}
//	var fieldTemplate=$(box+' [data-each="field"]');
//	for (var fieldName in meta.fieldList) {
//		var fieldMeta=meta.fieldList[fieldName];
//		var fieldDom=fieldTemplate.clone();
//		fieldDom.html(fieldMeta.title);
//	}
//	for (var dataIndex=0;dataIndex<data.length;dataIndex++) {
//		$('[data-each="field"]').html(fieldMeta.title);
//		var dataRow=data[dataIndex];
//		html+='<tr>';
//		for (var fieldName in meta.fieldList) {
//			var fieldData=dataRow[fieldName];
//			html+='<td>'+fieldData+'</td>'
//		}
//		html+='</tr>';
//	}
//	alert($('[data-each="field"]').length);
	
	var html="";
	if(type==="table"){
		html+='<table';
		if(style!=null){
			html+=' class="'+style+'"';
		}
		html+='>';
		html+='<tr>';
		for (var fieldName in meta.fieldList) {
			var fieldMeta=meta.fieldList[fieldName];
			html+='<th>'+fieldMeta.title+'</th>'
		}
		html+='</tr>';
		for (var dataIndex=0;dataIndex<data.length;dataIndex++) {
			var dataRow=data[dataIndex];
			html+='<tr>';
			for (var fieldName in meta.fieldList) {
				var fieldData=dataRow[fieldName];
				html+='<td>'+fieldData+'</td>'
			}
			html+='</tr>';
		}
		html+='</table>';
	}
	$(box).html(html);
}
