DataMagic.initViewWithMeta=function(meta){
	$(".DMTitle").html(meta.describe);
}

DataMagic.View.Toolbar.prototype.buildButton=function(command) {
	return $('<a class="btn btn-default DMButton" data-command="'+command+'"><span>'+command+'</span></a>');
}

DataMagic.View.List.prototype.buildItem=function(){
	return $('<li class="list-group-item DMItem">'
				+'<!--span class="badge">新</span>-->'
				+'<div>'
					+'<span data-field="title"></span>'
				+'</div>'
				+'<div style="font-size: 14px;color: gray;font-family: kaiti;">'
					+'<span data-field="people"></span>'
					+'<span data-field="date" class="pull-right"></span>'
				+'</div>'
			+'</li>');
}

DataMagic.Field.Base.prototype.buildField=function(name, meta, data) {
	return $('<div class="form-group">'+
				this.buildInput(name, meta, data)+
				'<p class="help-block">' + (meta.describe||"") + '</p>'+
			'</div>');
}
DataMagic.Field.Base.prototype.buildInput=function(name, meta, data){
	return '<label for="' + name + '">' + meta.title + '</label>'+
			'<input class="form-control DMInput" type="text" id="' + name + '"/>';
}
DataMagic.Field.Base.prototype.showMistake=function(input) {
	var container=input.parent();
	container.removeClass("has-success");
	container.addClass("has-error");
}
DataMagic.Field.Base.prototype.hideMistake=function(input) {
	var container=input.parent();
	container.removeClass("has-error");
	container.addClass("has-success");
}

/*长文本输入框*/
var DMLongTextField={
	title:"长文本输入框",
	buildInput:function(name, meta, data){
		return '<label for="' + name + '">' + meta.title + '</label>'+
				'<textarea id="' + name + '" class="form-control DMInput" rows="5"></textarea>';
	}
}
DataMagic.Field.LongText = DataMagic.Field.Base.extend(DMLongTextField);
DataMagic.DataType.LongText.prototype.inputField = DataMagic.Field.LongText;


/* 数字类型的输入框
 * 注意
 * 如果将input控件改成number类型的，而且用户输入了字符，就无法触发change事件，所以是个坑
 * 
 * 属性
 * input对应正常情况下的输入框
 * minInput/maxInput在搜索状态下，输入最小值和最大值的输入框（数字类型及其子类）
 */
var DMNumberField={
	title:"数字类型的输入框",
	addon:{"min":"最小","max":"最大"},
	buildSearchField:function(name, meta, data){
		return $('<div class="form-group">'+
					'<div>'+this.buildInput(name, meta, data,"min")+'</div>'+
					'<div>'+this.buildInput(name, meta, data,"max")+'</div>'+
					'<p class="help-block">' + (meta.describe||"") + '</p>'+
				'</div>');
	},
	buildInput:function(name, meta, data, type){
		if(type){
			var inputID = name + "-" + type ;
			var html='<label for="' + inputID + '">' + (type === "min" ? meta.title : "") +'</label>';
			html+='<span class="input-group-addon">'+this.addon[type]+'</span>';
			html+='<input class="form-control DMInput" type="text" id="' + inputID + '"/>';
			return html;
		}
		else{
			return DataMagic.Field.Base.prototype.buildInput(name, meta, data);
		}
	},
	createSearchField: function(name, meta, data) {
		this.container = this.buildSearchField(name, meta, data);
		this.inputs = this.container.find(".DMInput");
		this.minInput = this.inputs.eq(0);
		this.maxInput = this.inputs.eq(1);
		this.listenInputChange(this.inputs);
	}
}
DataMagic.Field.Number = DataMagic.Field.Base.extend(DMNumberField);
DataMagic.DataType.Number.prototype.inputField = DataMagic.Field.Number;


$.fn.datetimepicker.dates['zh-CN'] = {
	days: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六", "星期日"],
	daysShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六", "周日"],
	daysMin:  ["日", "一", "二", "三", "四", "五", "六", "日"],
	months: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
	monthsShort: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
	today: "今天",
	suffix: [],
	meridiem: ["上午", "下午"]
};
/* 日期类型输入框
 * 属性
 * format  用在picker中的时间格式，跟默认的格式不一样
 * option  调用picker时的设置
 * */
var DMDateField={
	title:"日期类型输入框",
	addon:{"min":"最早","max":"最晚"},
	option:{
		format: 'yyyy年M月d日',
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 4,
		minView: 2,
		forceParse: 0
    },
	listenInputChange:function(input){
		var self = this;
		input.change(function(){
			self.onInputChange($(this));
		});
		input.datetimepicker(this.option);
	}
}
DataMagic.Field.Date = DataMagic.Field.Number.extend(DMDateField);
DataMagic.DataType.Date.prototype.inputField = DataMagic.Field.Date;

/*时间日期类型输入框*/
var DMDateTimeField={
	title:"时间日期类型输入框",
	option:{
		format: 'yyyy年M月d日  H时i分',
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 4,
		minView: 0,
		forceParse: 0,
		minuteStep:1
    }
}
DataMagic.Field.DateTime = DataMagic.Field.Date.extend(DMDateTimeField);
DataMagic.DataType.DateTime.prototype.inputField = DataMagic.Field.DateTime;

/* 单选类型的输入框
 * 
 * 属性
 * options选项
 */
var DMSelectField={
	title:"单选类型的输入框",
	construct:function(dataType) {
		this.dataType = dataType;
		this.options = this.dataType.options;
	},
	buildInput:function(name, meta, data){
		var html='<label for="' + name + '">' + meta.title + '</label>'+
				'<div class="btn-group DMInput" id="' + name + '">';
		for(var kn in this.options) {
			html += '<a class="btn btn-default" data-value="' + kn + '"><span>' + this.options[kn] + '</span></a>';
		}
		html+='</div>';
		return html;
	},
	listenInputChange:function(input){
		var self = this;
		this.items = this.container.find(".btn").click(function() {
			self.onselect($(this));
		});
		input.change(function(){
			self.onInputChange($(this));
		});
		input.click(function(){
			self.onInputClick($(this));
		});
	},
	createSearchField: function(name, meta, data) {
		this.options[-1] = this.dataType.any;
		this.createField(name, meta, data);
		delete this.options[-1];
	},
	//当picker中的选项被点击时
	onselect: function(item) {
		this.items.removeClass("selected");
		item.addClass("selected");
	},
	setValue: function(input, value) {
		this.items.removeClass("selected");
		if(value != null) {
			this.items.filter("[data-value=" + value + "]").addClass("selected");
		}
	},
	getValue: function(input) {
		var items = input.find(".selected");
		return items.length ? items.eq(0).text() : null;
	}
}
DataMagic.Field.Select = DataMagic.Field.Base.extend(DMSelectField);
DataMagic.DataType.Select.prototype.inputField = DataMagic.Field.Select;

/*多选类型的输入框*/
var DMMultipleField={
	title:"多选类型的输入框",
	//多选跟单选的区别是，选中后不会立即关闭picker，直到用户点击确认
	onselect: function(item) {
		item.toggleClass("selected");
	},
	setValue: function(input, value) {
		this.items.removeClass("selected");
		if(value != null) {
			var valueList=value.split(",");
			for(var i in valueList){
				this.items.filter("[data-value=" + value[i] + "]").addClass("selected");
			}
		}
	},
	getValue: function(input) {
		var items = input.find(".selected");
		var values = [];
		for(var i = 0; i < items.length; i++) {
			values.push(items.eq(i).text());
		}
		return items.length ? values.join(",") : null;
	}
}
DataMagic.Field.Multiple = DataMagic.Field.Select.extend(DMMultipleField);
DataMagic.DataType.Multiple.prototype.inputField = DataMagic.Field.Multiple;