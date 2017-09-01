//需要导入jQuery框架
//需要导入对应的css样式表

//创建类
//已经获取到数据，则从meta,dataURL传入meta和数据
//需要获取，则meta传入名称，dataURL传入链接
createClass("DataMagicClass",{
	construct:function(selector,meta,dataURL,callback){
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
			this.selector=selector;
			this.meta=this.metaTrans(meta);
			this.data=data;
			callback.call(this);
		}
	},
	methods:{//处理元数据
		metaTrans:function(meta){
			Object.enumerate(meta.fieldList,function(key,value){
				value.inputClass=DMBaseInput.getSubClass(value.type);
			});
			return meta;
		},
		table:function(){
			this.table=new DMTable(this);
			this.toolbar=new DMToolbar(this.table,this.meta.feature);
			var container=$(this.selector);
			container.find(".DataMagic-table").append(this.table.container);
			container.find(".DataMagic-form").append(this.table.form.container);
			container.find(".DataMagic-toolbar").append(this.toolbar.container);
		},
		form:function(data,mode){
			//TODO:根据元数据直接创建表单，用于参数设置等场景
			return new DMForm(this);
		},
		toggle:function(){
			this.table.container.toggle();
			this.toolbar.container.toggle();
			this.table.form.container.toggle();
		},
		dialog:function(mess){
			//TODO:弹出的提示消息
			alert(mess);
		},
		send:function(data,success){
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
	}
});
/*控件类的基类*/
createClass("DMBaseControl",{
	construct:function(){},
	methods:{
		execute:function(action){
			if(this[action]){
				this[action]();
			}
			else{
				console.error("调用的方法不存在:"+action);
			}
		},
		appendTo:function(selector){
			$(selector).append(this.container);
		}
	}
});

/*表格类*/
createClass("DMTable",{
	construct:function(dm){
		var html='<table><tr>';
		Object.enumerate(dm.meta.fieldList,function(key,value){
			html+='<th>'+value.title+'</th>';
		});
		html+='</tr></table>';
		this.form=new DMForm(dm);
		this.container=$(html);
		this.dm=dm;
		this.rebindData(dm.data);
	},
	extend:DMBaseControl,
	methods:{
		rebindData:function(data){
			this.dm.data=[];
			this.container.find("tr>td").parent().remove();
			for (var i=0;i<data.length;i++) {
				this.insertCell(data[i]);
			}
		},
		getSelectedCell:function(){
			return this.container.find("tr.selected");
		},
		getIndexWithCell:function(cell){
			var id=cell.data("id");
			for (var i=0;i<this.dm.data.length;i++) {
				if(this.dm.data[i].id===id){
					return i;
				}
			}
			return -1;
		},
		onCellSelect:function(ev){
			$(this).toggleClass("selected");
		},
		insertCell:function(cellData){
			var html='<tr>';
			Object.enumerate(this.dm.meta.fieldList,function(keyName,fieldMeta){
				console.dir(window[fieldMeta.inputClass]);
				html+='<td>'+window[fieldMeta.inputClass].toString(cellData[keyName])+'</td>';
			});
			html+='</tr>';
			var cell=$(html);
			cell.data("id",cellData.id);
			cell.click(this.onCellSelect);
			cellData.cell=cell;
			this.dm.data.push(cellData);
			this.container.append(cell);
		},
		deleteCells:function(cells,indexs){
			for (var i=0;i<indexs.length;i++) {
				delete this.dm.data[indexs[i]];
			}
			var newList=[];
			for (var key in this.dm.data) {
				newList.push(this.dm.data[key]);
			}
			this.dm.data=newList;
			cells.remove();
		},
		updateCell:function(cellData,cell,index){
			this.dm.data[index]=cellData;
			var tds=cell.find("td");
			Object.enumerate(this.dm.meta.fieldList,function(keyName,fieldMeta,index){
				if(keyName==="id"){
					return;
				}
				tds.eq(index).html(window[fieldMeta.inputClass].toString(cellData[keyName]));
			});
		},
		insert:function(){
			var self=this;
			this.form.bind("insert",null,function(result,data){
				data.id=parseInt(result);
				self.insertCell(data,true);
			});
		},
		delete:function(){
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
		},
		update:function(){
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
				});
			}
		},
		search:function(){
			var self=this;
			this.form.bind("search",null,function(data){
				self.rebindData(data);
			});
		}
	}
});

/*表单类*/
createClass("DMForm",{
	extend:DMBaseControl,
	construct:function(dm){
		var inputs={};
		var container=$('<div style="display:none"></div>');
		Object.enumerate(dm.meta.fieldList,function(keyName,fieldMeta){
			if(keyName==="id"){
				return;
			}
			var currentInput=new window[fieldMeta.inputClass](keyName,fieldMeta);
			container.append(currentInput.container);
			inputs[keyName]=currentInput;
		});
		container.append(new DMToolbar(this,["save","cancel"]).container);
		this.container=container;
		this.inputs=inputs;
		this.dm=dm;
	},
	methods:{
		bind:function(mode,data,doSave){
			if(mode==="insert"){
				var fieldList=this.dm.meta.fieldList;
				Object.enumerate(this.inputs,function(keyName,control){
					control.bindData(mode,fieldList[keyName].defaultValue);
				});
			}
			else if(mode==="search"){
				var fieldList=this.dm.meta.fieldList;
				Object.enumerate(this.inputs,function(keyName,control){
					control.bindData(mode);
				});
			}
			else{
				Object.enumerate(this.inputs,function(keyName,control){
					control.bindData(mode,data[keyName]);
				});
				this.dataID=data.id;
			}
			this.dm.toggle();
			this.doSave=doSave;
			this.mode=mode;
		},
		save:function(){
			if(this.container.find(".illegal").length>0){
				this.dm.dialog("您输入的数据格式不正确！");
				return;
			}
			var params={};
			params.action=this.mode;
			params.table=this.dm.meta.bindTable;
			if(this.mode==="search"){
				var result=[];
				Object.enumerate(this.inputs,function(keyName,control){
					var res=control.getSearchStr();
					if(res){
						result=result.concat(res);
					}
				});
				params.where=result.join(" and ");
			}
			else{
				var result={};
				Object.enumerate(this.inputs,function(keyName,control){
					result[keyName]=control.getValue();
				});
				if(this.mode==="update"){
					params.where="id="+this.dataID;
				}
				result.id=undefined;
				params.data=result;
			}
			var self=this;
			this.dm.send(params,function(data){
				if(self.doSave){
					self.doSave(data,result);
				}
			});
			this.dm.toggle();
		},
		cancel:function(){
			this.dm.toggle();
		}
	}
});

/*工具栏类*/
createClass("DMToolbar",{
	extend:DMBaseControl,
	construct:function(target,buttons){
		var container=$("<div class='toolbar'></div>");
		for(var i=0;i<buttons.length;i++){
			var command=buttons[i];
			var currentButton=$('<button type="button" data-command="'+command+'"></button>');
			if(command==="insert"){
				currentButton.text("新建");
			}
			else if(command==="delete"){
				currentButton.text("删除");
			}
			else if(command==="update"){
				currentButton.text("修改");
			}
			else if(command==="search"){
				currentButton.text("查找");
			}
			else if(command==="save"){
				currentButton.text("保存");
			}
			else if(command==="cancel"){
				currentButton.text("取消");
			}
			currentButton.click(function(){
				target.execute($(this).data("command"));
			});
			container.append(currentButton);
		}
		this.container=container;
	}
});

/*输入控件类的基类*/
createClass("DMBaseInput",{
	construct:function(fieldName,fieldMeta){
		this.fieldName=fieldName;
		this.fieldMeta=fieldMeta;
		this.container=$('<div><label for="'+fieldName+'">'+fieldMeta.title+'</label></div>');
		this.input=$('<input type="text" id="'+fieldName+'"/>').appendTo(this.container);
		var regexp=fieldMeta.regexp||this.defaultRegExp;
		if(regexp){
			this.regexp=new RegExp(regexp);
			var self=this;
			this.input.change(function(){
				self.match($(this));
			});
		}
	},
	statics:{
		//获取字段的类型对应的input类
		getSubClass:function(type){
			if(type==="date"){
				return "DMDateInput";
			}
			else if(type==="number"){
				return "DMNumberInput";
			}
			else{
				return "DMBaseInput";
			}
		},
		toString:function(data){
			return data.toString();
		}
	},
	methods:{
		getValue:function(){
			return this.input.val();
		},
		getSearchStr:function(){
			var value=this.input.val();
			if(value!==null&&value!==''){
				return [this.fieldName+" like '%"+this.input.val()+"%'"];
			}
		},
		bindData:function(mode,data){
			this.input.val(data);
		},
		match:function(input){
			var value=input.val();
			if(this.regexp.test(value)){
				input.removeClass("illegal");
			}
			else{
				input.addClass("illegal");
			}
		}
	}
});

createClass("DMNumberInput",{
	extend:DMBaseInput,
	construct:function(fieldName,fieldMeta){
		DMBaseInput.call(this,fieldName,fieldMeta);
		this.span=$('<span>至</span>').appendTo(this.container);
		this.endInput=$('<input type="text"/>').appendTo(this.container);
		if(this.regexp){
			var self=this;
			this.endInput.change(function(){
				self.match($(this));
			});
		}
	},
	methods:{
		defaultRegExp:"^[0-9]{0,}$",
		getValue:function(input){
			input=input||this.input;
			return parseInt(input.val());
		},
		getSearchStr:function(){
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
		},
		bindData:function(mode,data){
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
	}
});

/*日期控件*/
createClass("DMDateInput",{
	extend:DMNumberInput,
	construct:function(fieldName,fieldMeta){
		DMNumberInput.call(this,fieldName,fieldMeta);
		this.datepicker(this.input);
		this.datepicker(this.endInput);
	},
	statics:{
		toString:function(s){
			if(typeof s ==="string"){
				s=parseInt(s);
			}
			if(s>0){
				var dat=new Date(s*1000);
				return dat.getFullYear()+"年"+dat.getMonth()+"月"+dat.getDate()+"日";
			}
			return "";
		}
	},
	methods:{
		defaultRegExp:"^[0-9]{4}年[0-9]{2}月[0-9]{2}日$",
		getValue:function(input){
			input=input||this.input;
			var nums=input.val().split(/[年,月]/);
			var date=new Date(parseInt(nums[0]),parseInt(nums[1]),parseInt(nums[2]));
			return date.getTime()/1000;
		},
		bindData:function(mode,data){
			if(mode==="search"){
				this.span.show();
				this.endInput.show();
			}
			else{
				this.span.hide();
				this.endInput.hide();
				this.input.val(DMDateInput.toString(data));
			}
		},
		//给日期输入框添加日期选择器（中文）,
		datepicker:function(jq){
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
	}
});