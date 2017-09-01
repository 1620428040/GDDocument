//================================================================
//数据类型
//================================================================
/* 基本数据类型。比如文字
 * 
 * 属性
 * field  对应的输入控件实例
 * inputField  对应的输入框的类。定义输入框类之后在这个属性上注册一下，就能在创建输入框时自动调用
 * pattern  该数据类型要求的正则表达式
 */
var DMBaseDataType={
	title:"基础数据类型",
	construct:function(fieldName, fieldMeta) {
		this.fieldName = fieldName;
		this.fieldMeta = fieldMeta;
		var regexp = fieldMeta.regexp || this.defaultRegExp;
		if(regexp) {
			this.regexp = new RegExp(regexp);
		}
	},
	inputField:DataMagic.Field.Abstract, //注册为Base类型数据的输入框
	tranToString: function(data) {
		return(data == null || data === "") ? "" : data.toString();
	},
	tranToData: function(str) {
		return str;
	},
	//验证数据是否符合该类型的要求，比如说Number类型的数据必须全部由数字组成
	typeValidation: function(value) {
		return this.pattern ? this.pattern.test(value) : true;
	},
	createInputField: function(data) {
		if(this.field == null) {
			this.field = new this.inputField(this);
		}
		this.field.createField(this.fieldName, this.fieldMeta, data);
		return this.field;
	},
	validationValue: function(value, input) {
		var validated = true;
		//检查是否为空
		if(validated && value === null) {
			validated = this.fieldMeta.notNull ? false : true;
		}
		//检查是否符合正则表达式
		if(validated && this.regexp) {
			validated = this.regexp.test(value);
		}
		//检查是否符合数据类型的要求
		validated = validated && this.typeValidation(value);
		//错误提示
		if(validated) {
			this.field.hideMistake(input);
		} else {
			this.field.showMistake(input);
		}
		DataMagic.debug("类型检查  value:"+value+"  validated:"+validated);
		return validated;
	},
	getValue: function() {
		var value = this.field.getValue(this.field.input);
		this.validated = this.validationValue(value, this.field.input);
		return this.tranToData(value);
	},
	createSearchField: function() {
		if(this.field == null) {
			this.field = new this.inputField(this);
		}
		this.field.createSearchField(this.fieldName, this.fieldMeta, null);
		return this.field;
	},
	validationSearchParams: function(value,input) {
		var validated = (value === null) ? true : this.typeValidation(value);
		if(validated) {
			this.field.hideMistake(input);
		} else {
			this.field.showMistake(input);
		}
		return validated;
	},
	getSearchParams: function() {
		var value = this.field.getValue(this.field.input);
		this.validated = this.validationSearchParams(value, this.field.input);
		return this.tranToData(value);
	}
}
DataMagic.DataType.Base = Class.extend(DMBaseDataType);

/*长文本类型*/
DataMagic.DataType.LongText = DataMagic.DataType.Base.inherit("长文本数据类型");

/*数字类型*/
var DMNumberDataType={
	title:"数字数据类型",
	pattern: /^[0-9]{0,}$/,
	tranToData: function(str) {
		return str == null ? null : parseInt(str);
	},
	getSearchParams: function() {
		var values = {};

		var min = this.field.getValue(this.field.minInput);
		var validated1 = this.validationSearchParams(min, this.field.minInput);
		values.start = this.tranToData(min);

		var max = this.field.getValue(this.field.maxInput);
		var validated2 = this.validationSearchParams(max, this.field.maxInput);
		values.end = this.tranToData(max);

		this.validated = validated1 && validated2;
		if(values.start === null && values.end === null) {
			return null;
		}
		return values;
	}
}
DataMagic.DataType.Number = DataMagic.DataType.Base.extend(DMNumberDataType);

/*日期类型*/
var DMDateDataType={
	title:"日期数据类型",
	format: "yyyy年M月d日",
	pattern: /^[0-9]{4}年[0-9]{1,2}月[0-9]{1,2}日$/,
	tranToString: function(s) {
		if(s == null || s === "") {
			return "";
		}
		if(typeof s === "string") {
			s = parseInt(s);
		}
		var dat = new Date(s * 1000);
		return dat.format(this.format);
	},
	tranToData: function(str) {
		if(str === null) {
			return null;
		}
		var date = Date.createFormFormatString(this.format, str);
		return date.getTime() / 1000;
	}
}
DataMagic.DataType.Date = DataMagic.DataType.Number.extend(DMDateDataType);

/*日期时间类型*/
var DMDateTimeDataType={
	title:"日期加时间数据类型",
	format: "yyyy年M月d日  H时m分",
	pattern: /^[0-9]{4}年[0-9]{1,2}月[0-9]{1,2}日  [0-9]{1,2}时[0-9]{1,2}分$/
}
DataMagic.DataType.DateTime = DataMagic.DataType.Date.extend(DMDateTimeDataType);

/* 单选类型
 * 属性
 * options  选项
 * any 当进行搜索时，表示任意选项的字符串
 */
var DMSelectDataType={
	title:"单选数据类型",
	construct:function(fieldName, fieldMeta) {
		DataMagic.DataType.Base.apply(this,arguments);
		this.options = {};
		this.any = "任意";
		var titles = [];
		var splited = fieldMeta.valueRange.split(";");
		for(var i in splited) {
			var kvp = splited[i].split(",");
			this.options[kvp[0]] = kvp[1];
			titles.push(kvp[1]);
		}
		this.pattern = new RegExp("^(?:" + titles.join("|") + "|" + this.any + ")$");
	},
	typeValidation: function(value) {
		return this.pattern.test(value);
	},
	tranToString: function(value) {
		return this.options[value] || "";
	},
	tranToData: function(str) {
		for(var kn in this.options) {
			var kv = this.options[kn];
			if(kv === str) {
				return kn;
			}
		}
	}
}
DataMagic.DataType.Select = DataMagic.DataType.Base.extend(DMSelectDataType);

/*多选类型*/
var DMMultipleDataType={
	title:"多选数据类型",
	typeValidation: function(value) {
		var values = value.split(",");
		for(var i in values) {
			if(!this.pattern.test(values[i])) {
				return false;
			}
		}
		return true;
	},
	tranToString: function(value) {
		var str = [];
		var values = value.split(",");
		for(var i in values) {
			str.push(this.options[value]);
		}
		return str.length > 0 ? str.join(",") : null;
	},
	tranToData: function(str) {
		var str = str.split(",");
		var values = [];
		for(var i in str) {
			for(var kn in this.options) {
				var kv = this.options[kn];
				if(kv = str[i]) {
					values.push(kn);
				}
			}
		}
		return values.length > 0 ? values.join(",") : null;
	}
}
DataMagic.DataType.Multiple = DataMagic.DataType.Select.extend(DMMultipleDataType);

/*布尔类型*/
var DMBoolDataType={
	title:"布尔数据类型",
	construct:function(fieldName, fieldMeta) {
		DataMagic.DataType.Base.call(this, fieldName, fieldMeta);
		this.options = {"1": "是","0": "否"};
		this.any = "任意";
	},
	typeValidation: function(value) {
		return value === this.any ? true : /^[是否]$/.test(value);
	},
	tranToString: function(bool) {
		return bool == null ? null : (bool === "1" ? "是" : "否");
	},
	tranToData: function(str) {
		var value = null;
		if(str === "是") {
			value = "1";
		} else if(str === "否") {
			value = "0";
		}
		return value;
	}
}
DataMagic.DataType.Bool = DataMagic.DataType.Select.extend(DMBoolDataType);
