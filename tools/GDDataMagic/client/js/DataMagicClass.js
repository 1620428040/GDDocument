//需要导入jQuery框架
//需要导入对应的css样式表

//创建类
//已经获取到数据，则从meta,dataURL传入meta和数据
//需要获取，则meta传入名称，dataURL传入链接
function DataMagicClass(selector,meta,dataURL,callback){
	if(typeof meta==="string"){
		this.dataURL=dataURL;
		//请求数据
		this.send({
			"action":"getMeta",
			"name":"person"
		},function(meta){
			dealData.call(this,meta);
		});
	}
	else{
		dealData.call(this,meta,dataURL);
	}
	function dealData(meta,data){
		if(data==null){
			data=meta.data;
		}
		this.container=$(selector);
		this.meta=this.metaTrans(meta);
		this.data=data;
		callback.call(this);
	}
}
//处理元数据
DataMagicClass.prototype.metaTrans=function(meta){
	meta.showIndex=(meta.showIndex==="1"||meta.showIndex===1);
	meta.allowMove=(meta.allowMove==="1"||meta.allowMove===1);
	Object.enumerate(meta.fieldList,function(key,value){
		if(value.type==="date"){
			value.input=new DMDateInput(key,value);
		}
		else if(value.type==="number"){
			value.input=new DMNumberInput(key,value);
		}
//		else if(value.type==="singleSelector"||value.type==="multiSelector"){
//			value.input=new DMSelector(key,value);
//		}
		else{
			value.input=new DMBaseInput(key,value);
		}
		value.notNull=(value.notNull==="1"||value.notNull===1);
	});
	return meta;
}
DataMagicClass.prototype.createTable=function(){
	this.list=new DMTable(this);
	this.toolbar=new DMToolbar(this,this.list,this.meta.feature);
}
DataMagicClass.prototype.createList=function(){
	this.list=new DMList(this);
}
DataMagicClass.prototype.createForm=function(data,mode){
	//TODO:根据元数据直接创建表单，用于参数设置等场景
	return new DMForm(this);
}
DataMagicClass.prototype.dialog=function(mess){
	//TODO:弹出的提示消息
	alert(mess);
}
DataMagicClass.prototype.send=function(data,success){
	var self=this;
	$.ajax({
		type:"post",
		url:self.dataURL,
		data:data,
		success:function(meta){
			meta=JSON.parse(meta);
			success.call(self,meta);
		},
		async:true
	});
}

/*控件类的基类*/
function DMBaseControl(){
	
}
DMBaseControl.prototype.execute=function(action){
	if(this[action]){
		this[action]();
	}
	else{
		console.error("调用的方法不存在:"+action);
	}
}
DMBaseControl.prototype.appendTo=function(selector){
	$(selector).append(this.container);
}

/*列表类，计划作为表格类的基类*/
function DMList(dm){
	this.toolbar=new DMToolbar(dm,this,dm.meta.feature);
	this.toolbar.show();
	this.form=new DMForm(dm);
	this.form.container.hide();
	this.container=dm.container.find(".DMList");
	this.dm=dm;
	this.item=this.container.find(".DMItem").detach();
	if(this.item.length===0){
		this.item=this.getDefalutItem();
	}
	this.item.click(this.onCellSelect);
	this.rebindData(dm.data);
}
DMList.prototype.showForm=function(){
	this.container.hide();
	this.form.toolbar.show();
	this.form.container.show();
}
DMList.prototype.hideForm=function(){
	this.container.show();
	this.toolbar.show();
	this.form.container.hide();
}
DMList.prototype.getDefalutItem=function(){
	var html='<div class="DMItem">';
	Object.enumerate(this.dm.meta.fieldList,function(keyName,fieldMeta){
		html+='<div data-field="'+keyName+'"></div>';
	});
	html+='</div>';
	return $(html);
}
DMList.prototype.rebindData=function(data){
	this.dm.data=[];
	this.container.find(".DMItem").remove();
	for (var i=0;i<data.length;i++) {
		this.insertCell(data[i]);
	}
}
DMList.prototype.getSelectedCell=function(){
	return this.container.find(".DMItem.selected");
}
DMList.prototype.getIndexWithCell=function(cell){
	var id=cell.data("id");
	for (var i=0;i<this.dm.data.length;i++) {
		if(this.dm.data[i].id===id){
			return i;
		}
	}
	return -1;
}
DMList.prototype.onCellSelect=function(ev){
	$(this).toggleClass("selected");
}
DMList.prototype.insertCell=function(cellData){
	var cell=this.item.clone(true);
	this.bindCellData(cell,cellData,this.dm.meta.fieldList);
	cell.data("id",cellData.id);
	cellData.cell=cell;
	this.dm.data.push(cellData);
	this.container.append(cell);
}
DMList.prototype.bindCellData=function(cell,cellData,fieldMeta){
	cell.find("[data-field]").each(function(){
		var current=$(this);
		var field=current.data("field");
		current.html(fieldMeta[field].input.tranToString(cellData[field]));
	});
}
DMList.prototype.deleteCells=function(cells,indexs){
	for (var i=0;i<indexs.length;i++) {
		delete this.dm.data[indexs[i]];
	}
	var newList=[];
	for (var key in this.dm.data) {
		newList.push(this.dm.data[key]);
	}
	this.dm.data=newList;
	cells.remove();
}
DMList.prototype.updateCell=function(cellData,cell,index){
	this.dm.data[index]=cellData;
	this.bindCellData(cell,cellData,this.dm.meta.fieldList);
}
DMList.prototype.insert=function(){
	var self=this;
	this.form.bind("insert",null,function(result,data){
		data.id=parseInt(result);
		self.insertCell(data,true);
		self.hideForm();
	});
	this.showForm();
}
DMList.prototype.delete=function(){
	var cells=this.getSelectedCell();
	if(cells.length==0){
		this.dm.dialog("请至少选择一行");
	}
	else{
		var ids=[];
		var indexs=[];
		var self=this;
		cells.each(function(index,elem){
			var index=self.getIndexWithCell($(elem));
			indexs.push(index);
			ids.push(self.dm.data[index].id);
		});
		var params={};
		params.action="delete";
		params.table=this.dm.meta.bindTable;
		params.where="id in ("+ids.join(",")+")";
		var self=this;
		this.dm.send(params,function(data){
			self.deleteCells(cells,indexs);
		});
	}
}
DMList.prototype.update=function(){
	var cells=this.getSelectedCell();
	if(cells.length==0){
		this.dm.dialog("请至少选择一行");
	}
	else{
		var cell=cells.eq(0);
		var index=this.getIndexWithCell(cell);
		var self=this;
		this.form.bind("update",this.dm.data[index],function(result,data){
			self.updateCell(data,cell,index);
			self.hideForm();
		});
		this.showForm();
	}
}
DMList.prototype.search=function(){
	var self=this;
	this.form.bind("search",null,function(data){
		self.rebindData(data);
		self.hideForm();
	});
	this.showForm();
}
DMList.extend(DMBaseControl);

/*表格类*/
function DMTable(dm){
	this.form=new DMForm(dm);
	this.form.container.hide();
	var html='<tr class="DMHead">';
	Object.enumerate(dm.meta.fieldList,function(key,value){
		html+='<th>'+value.title+'</th>';
	});
	html+='</tr>';
	this.container=dm.container.find(".DMTable").html(html);
	this.dm=dm;
	this.item=this.container.find(".DMItem").detach();
	if(this.item.length===0){
		this.item=this.getDefalutItem();
	}
	this.item.click(this.onCellSelect);
	this.rebindData(dm.data);
}
DMTable.prototype.getDefalutItem=function(){
	var html='<tr class="DMItem">';
	Object.enumerate(this.dm.meta.fieldList,function(keyName,fieldMeta){
		html+='<td data-field="'+keyName+'"></td>';
	});
	html+='</tr>';
	return $(html);
}
DMTable.extend(DMList);
//function DMTable(dm){
//	var html='<table><tr>';
//	Object.enumerate(dm.meta.fieldList,function(key,value){
//		html+='<th>'+value.title+'</th>';
//	});
//	html+='</tr></table>';
//	this.form=new DMForm(dm);
//	this.container=$(html);
//	this.dm=dm;
//	this.rebindData(dm.data);
//}
//DMTable.prototype.rebindData=function(data){
//	this.dm.data=[];
//	this.container.find("tr>td").parent().remove();
//	for (var i=0;i<data.length;i++) {
//		this.insertCell(data[i]);
//	}
//}
//DMTable.prototype.getSelectedCell=function(){
//	return this.container.find("tr.selected");
//}
//DMTable.prototype.getIndexWithCell=function(cell){
//	var id=cell.data("id");
//	for (var i=0;i<this.dm.data.length;i++) {
//		if(this.dm.data[i].id===id){
//			return i;
//		}
//	}
//	return -1;
//}
//DMTable.prototype.onCellSelect=function(ev){
//	$(this).toggleClass("selected");
//}
//DMTable.prototype.insertCell=function(cellData){
//	var html='<tr>';
//	Object.enumerate(this.dm.meta.fieldList,function(keyName,fieldMeta){
//		html+='<td>'+fieldMeta.input.tranToString(cellData[keyName])+'</td>';
//	});
//	html+='</tr>';
//	var cell=$(html);
//	cell.data("id",cellData.id);
//	cell.click(this.onCellSelect);
//	cellData.cell=cell;
//	this.dm.data.push(cellData);
//	this.container.append(cell);
//}
//DMTable.prototype.deleteCells=function(cells,indexs){
//	for (var i=0;i<indexs.length;i++) {
//		delete this.dm.data[indexs[i]];
//	}
//	var newList=[];
//	for (var key in this.dm.data) {
//		newList.push(this.dm.data[key]);
//	}
//	this.dm.data=newList;
//	cells.remove();
//}
//DMTable.prototype.updateCell=function(cellData,cell,index){
//	this.dm.data[index]=cellData;
//	var tds=cell.find("td");
//	Object.enumerate(this.dm.meta.fieldList,function(keyName,fieldMeta,index){
//		if(keyName==="id"){
//			return;
//		}
//		tds.eq(index).html(fieldMeta.input.tranToString(cellData[keyName]));
//	});
//}
//DMTable.prototype.insert=function(){
//	var self=this;
//	this.form.bind("insert",null,function(result,data){
//		data.id=parseInt(result);
//		self.insertCell(data,true);
//	});
//}
//DMTable.prototype.delete=function(){
//	var cells=this.getSelectedCell();
//	if(cells.length==0){
//		this.dm.dialog("请至少选择一行");
//	}
//	else{
//		var ids=[];
//		var indexs=[];
//		var self=this;
//		cells.each(function(index,elem){
//			var index=self.getIndexWithCell($(elem));
//			indexs.push(index);
//			ids.push(self.dm.data[index].id);
//		});
//		var params={};
//		params.action="delete";
//		params.table=this.dm.meta.bindTable;
//		params.where="id in ("+ids.join(",")+")";
//		var self=this;
//		this.dm.send(params,function(data){
//			self.deleteCells(cells,indexs);
//		});
//	}
//}
//DMTable.prototype.update=function(){
//	var cells=this.getSelectedCell();
//	if(cells.length==0){
//		this.dm.dialog("请至少选择一行");
//	}
//	else{
//		var cell=cells.eq(0);
//		var index=this.getIndexWithCell(cell);
//		var self=this;
//		this.form.bind("update",this.dm.data[index],function(result,data){
//			self.updateCell(data,cell,index);
//		});
//	}
//}
//DMTable.prototype.search=function(){
//	var self=this;
//	this.form.bind("search",null,function(data){
//		self.rebindData(data);
//	});
//}
//DMTable.extend(DMBaseControl);

/*表单类*/
function DMForm(dm){
	var container=dm.container.find(".DMForm");
	Object.enumerate(dm.meta.fieldList,function(keyName,fieldMeta){
		if(keyName==="id"){
			return;
		}
		container.append(fieldMeta.input.container);
	});
	this.toolbar=new DMToolbar(dm,this,["save","cancel"]);
	this.container=container;
	this.dm=dm;
}
DMForm.prototype.bind=function(mode,data,doSave){
	this.container.find(".illegal").removeClass("illegal");
	if(mode==="insert"){
		Object.enumerate(this.dm.meta.fieldList,function(key,value){
			value.input.bindData(mode,value.defaultValue);
		});
	}
	else if(mode==="search"){
		Object.enumerate(this.dm.meta.fieldList,function(key,value){
			value.input.bindData(mode);
		});
	}
	else{
		Object.enumerate(this.dm.meta.fieldList,function(key,value){
			value.input.bindData(mode,data[key]);
		});
		this.dataID=data.id;
	}
	this.doSave=doSave;
	this.mode=mode;
}
DMForm.prototype.save=function(){
	this.container.find(".needValidation:visible").each(function(){
		$(this).change();
	});
	if(this.container.find(".illegal").length>0){
		this.dm.dialog("您输入的数据格式不正确！");
		return;
	}
	var params={};
	params.action=this.mode;
	params.table=this.dm.meta.bindTable;
	if(this.mode==="search"){
		var result=[];
		Object.enumerate(this.dm.meta.fieldList,function(key,value){
			var res=value.input.getSearchStr();
			if(res){
				result=result.concat(res);
			}
		});
		params.where=result.join(" and ");
	}
	else{
		var result={};
		Object.enumerate(this.dm.meta.fieldList,function(key,value){
			result[key]=value.input.getValue();
		});
		if(this.mode==="update"){
			params.where="id="+this.dataID;
		}
		delete result.id;
		params.data=result;
	}
	var self=this;
	this.dm.send(params,function(data){
		if(self.doSave){
			result.id=self.dataID;
			self.doSave(data,result);
		}
	});
}
DMForm.prototype.cancel=function(){
	this.dm.list.hideForm();
}
DMForm.extend(DMBaseControl);

/*工具栏类*/
function DMToolbar(dm,target,buttons){
	var container=dm.container.find(".DMToolbar");
	var buttonPoor=DMToolbar.buttonPoor;
	if(buttonPoor==null){
		buttonPoor={};
		dm.container.find(".DMButton").detach().each(function(){
			var button=$(this);
			buttonPoor[button.data("command")]=button;
		});
		DMToolbar.buttonPoor=buttonPoor;
	}
	for (var i in buttons) {
		var command=buttons[i];
		if(buttonPoor[command]==null){
			buttonPoor[command]=$('<button type="button" class="DMButton" data-command="'+command+'">'+command.translate()+'</button>');
		}
	}
	this.target=target;
	this.buttons=buttons;
	this.container=container;
}
DMToolbar.prototype.show=function(){
	this.container.find(".DMButton").remove();
	for (var i in this.buttons) {
		var self=this;
		this.container.append(DMToolbar.buttonPoor[this.buttons[i]].clone().click(function(){
			self.target.execute($(this).data("command"));
		}));
	}
}
DMToolbar.buttonPoor=null;
DMToolbar.extend(DMBaseControl);

/*输入控件类的基类*/
function DMBaseInput(fieldName,fieldMeta){
	this.fieldName=fieldName;
	this.fieldMeta=fieldMeta;
	this.container=$('<div><label for="'+fieldName+'">'+fieldMeta.title+'</label></div>');
	this.input=$('<input type="text" id="'+fieldName+'"/>').appendTo(this.container);
	this.needValidation(this.input);
	if(fieldMeta.describe){
		this.input.attr("title",fieldMeta.describe);
	}
	var regexp=fieldMeta.regexp||this.defaultRegExp;
	if(regexp){
		this.regexp=new RegExp(regexp);
	}
}
DMBaseInput.prototype.tranToString=function(data){
	return data.toString();
}
DMBaseInput.prototype.getValue=function(){
	return this.input.val();
}
DMBaseInput.prototype.getSearchStr=function(){
	var value=this.input.val();
	if(value!==null&&value!==''){
		return [this.fieldName+" like '%"+this.input.val()+"%'"];
	}
}
DMBaseInput.prototype.bindData=function(mode,data){
	this.mode=mode;
	this.input.val(data);
}
DMBaseInput.prototype.needValidation=function(jq){
	jq.addClass("needValidation");
	var self=this;
	jq.change(function(){
		var input=$(this);
		var value=input.val();
		var isNull=(value==null||value==='');
		var notNull=self.fieldMeta.notNull&&self.mode!=="search";//不允许为空
		var result=!(isNull&&notNull);
		if(result&&self.regexp&&!isNull){
			result=self.regexp.test(value);
		}
		if(result){
			input.removeClass("illegal");
		}
		else{
			input.addClass("illegal");
		}
	});
}

function DMNumberInput(fieldName,fieldMeta){
	DMBaseInput.call(this,fieldName,fieldMeta);
	this.span=$('<span>至</span>').appendTo(this.container);
	this.endInput=$('<input type="text"/>').appendTo(this.container);
	this.needValidation(this.endInput);
}
DMNumberInput.prototype.defaultRegExp="^[0-9]{0,}$";
DMNumberInput.prototype.getValue=function(input){
	input=input||this.input;
	return parseInt(input.val());
}
DMNumberInput.prototype.getSearchStr=function(){
	var start=this.getValue(this.input);
	var end=this.getValue(this.endInput);
	var result=[];
	if(!isNaN(start)&&start!==0){
		result.push(this.fieldName+">="+start);
	}
	if(!isNaN(end)&&end!==0){
		result.push(this.fieldName+"<="+end);
	}
	return result;
}
DMNumberInput.prototype.bindData=function(mode,data){
	this.mode=mode;
	if(mode==="search"){
		this.span.show();
		this.endInput.show();
	}
	else{
		this.span.hide();
		this.endInput.hide();
		this.input.val(data);
	}
}
DMNumberInput.extend(DMBaseInput);

/*日期控件*/
function DMDateInput(fieldName,fieldMeta){
	DMNumberInput.call(this,fieldName,fieldMeta);
	this.datepicker(this.input);
	this.datepicker(this.endInput);
}
DMDateInput.prototype.defaultRegExp="^[0-9]{4}年[0-9]{1,2}月[0-9]{1,2}日$";
DMDateInput.prototype.tranToString=function(s){
	if(typeof s ==="string"){
		s=parseInt(s);
	}
	if(s>0){
		var dat=new Date(s*1000);
		return dat.getFullYear()+"年"+dat.getMonth()+"月"+dat.getDate()+"日";
	}
	return "";
}
DMDateInput.prototype.getValue=function(input){
	input=input||this.input;
	var nums=input.val().split(/[年,月]/);
	var date=new Date(parseInt(nums[0]),parseInt(nums[1]),parseInt(nums[2]));
	return date.getTime()/1000;
}
DMDateInput.prototype.bindData=function(mode,data){
	this.mode=mode;
	if(mode==="search"){
		this.span.show();
		this.endInput.show();
	}
	else{
		this.span.hide();
		this.endInput.hide();
		this.input.val(this.tranToString(data));
	}
}
//给日期输入框添加日期选择器（中文）
DMDateInput.prototype.datepicker=function(jq){
	jq.datepicker({
        /* 区域化周名为中文 */
        dayNamesMin : ["日", "一", "二", "三", "四", "五", "六"],
        /* 每周从周一开始 */
        firstDay : 1,
        /* 区域化月名为中文习惯 */
        monthNames : ["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],
        /* 月份显示在年后面 */
        showMonthAfterYear : true,
        /* 年份后缀字符 */ 
        yearSuffix : "年",
        /* 格式化中文日期  因为月份中已经包含“月”字，所以这里省略） */ 
        dateFormat : "yy年MMdd日"
    });
}
DMDateInput.extend(DMNumberInput);

//选择框的基类，继承于DMBaseInput
//function DMSelector(fieldName,fieldMeta){
//	this.fieldName=fieldName;
//	this.fieldMeta=fieldMeta;
//	this.container=$('<div><label for="'+fieldName+'">'+fieldMeta.title+'</label></div>');
//	this.options=$('<div type="text" id="'+fieldName+'"/></div>').appendTo(this.container);
//	if(fieldMeta.describe){
//		this.options.attr("title",fieldMeta.describe);
//	}
//}
//DMSelector.prototype.toString=function(data){
////	if(typeof s ==="string"){
////		s=parseInt(s);
////	}
////	if(s>0){
////		var dat=new Date(s*1000);
////		return dat.getFullYear()+"年"+dat.getMonth()+"月"+dat.getDate()+"日";
////	}
//	return "";
//}
//DMSelector.prototype.getValue=function(input){
////	input=input||this.input;
////	var nums=input.val().split(/[年,月]/);
////	var date=new Date(parseInt(nums[0]),parseInt(nums[1]),parseInt(nums[2]));
////	return date.getTime()/1000;
//}
//DMSelector.prototype.bindData=function(mode,data){
////	this.mode=mode;
////	if(mode==="search"){
////		this.span.show();
////		this.endInput.show();
////	}
////	else{
////		this.span.hide();
////		this.endInput.hide();
////		this.input.val(DMDateInput.toString(data));
////	}
//}
//DMSelector.extend(DMBaseInput);