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
DataMagic.DataType.Base.prototype.inputField = DataMagic.Field.Base;