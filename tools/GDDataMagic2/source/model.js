//================================================================
//数据模型
//================================================================
/* 数据模型类，负责联系后台进行增删改查等操作，本地存储数据等
 * 
 * 属性
 * name给Model命名
 * meta是指元数据
 * data是指数据
 * storage是sessionStorage或者localStorage
 * host服务器地址
 * server进行不同的操作时，具体的URL链接，如果没有定义，则通过host生成。
 * filter指定固定的过滤条件，当前model只能搜索filter限定的范围
 * lastFilter上一次的搜索条件，用来在搜索之后，实现统计等功能
 * pagesize分页，每页多少条
 * page当前页数
 */
var DMListModel={
	title:"基础model",
	construct:function(name, storage, host, controller, params ) {
		this.name = name;
		this.storage = storage;
		this.server = {
	//		getMeta:null,
	//		insert:null,
	//		delete:null,
	//		update:null,
	//		search:null
		};
		if(host) {
			this.host = host;
		}
		this.controller = controller;
		if(params){
			this.pagesize=params.pagesize||20;
			this.page=params.page||0;
			this.filter=params.filter||null;
			this.lastFilter=this.filter;
			//兼容的写法
			if(!(params.pagesize||params.page||params.filter)){
				this.filter=params;
			}
		}
		else{
			this.pagesize=20;
			this.page=0;
		}
		if(host || this.server.getMeta) {
			this.downloadMeta();
		}
	},
	//设置服务器为标准的接口
	getServerUrl: function(action) {
		if(this.server[action]) {
			return this.server[action];
		}
		if(this.host) {
			var url = this.host;
			var sign = url.indexOf("?") < 0 ? "?" : "&";
			return url + sign + "name=" + this.name + "&action=" + action;
		} else {
			DataMagic.debug("需要指定服务器地址或者提供meta")
		}
	},
	//从指定的地址下载元数据
	downloadMeta: function(callback) {
		var self = this;
		DataMagic.Model.request(this.getServerUrl("getMeta"), {
			where: this.filter,
			pagesize:this.pagesize,
			page:this.page
		}, function(data) {
			self.initWithMeta(data);
			if(callback) {
				callback.call(this);
			}
		});
	},
	//使用指定的元数据初始化
	initWithMeta: function(meta) {
		this.meta = meta;
		//		meta.showIndex=(meta.showIndex==="1"||meta.showIndex===1);
		//		meta.allowMove=(meta.allowMove==="1"||meta.allowMove===1);
		for(var key in this.meta.fieldList) {
			var value = this.meta.fieldList[key];
			var dataType = DataMagic.DataType[value.type || "Base"] || DataMagic.DataType.Base;
			value.dataType = new dataType(key, value);
			//			value.notNull=(value.notNull==="1"||value.notNull===1);
		}
		this.controller.onMetaLoad();

		if(meta.data) {
			this.data = this.changeDataIndex(meta.data);
			this.controller.onDataLoad();
		} else if(this.host || this.server.search) {
			var self = this;
			this.search(function() {
				self.controller.onDataLoad();
			});
		}
	},
	//将data数组的索引改成使用id索引
	changeDataIndex: function(data) {
		var newData = [];
		for(var i in data) {
			newData[data[i].id] = data[i];
		}
		return newData;
	},
	//从服务器获取
	request: function(url, params, callback) {
		var self = this;
		DataMagic.Model.request(url, params, function(data) {
			callback(data);
		});
	},
	//保存到本地存储
	save: function() {
		if(this.storage) {
			this.storage.setItem(this.name + "_meta", JSON.stringify(this.meta));
			this.storage.setItem(this.name, JSON.stringify(this.data));
		}
	},
	//从本地存储读取
	read: function() {
		if(this.storage) {
			this.meta = JSON.parse(this.storage.getItem(this.name + "_meta"));
			this.data = JSON.parse(this.storage.getItem(this.name));
			return this.data;
		}
	},
	//清空
	clear: function() {
		if(this.storage) {
			this.storage.removeItem(this.name + "_meta");
			this.storage.removeItem(this.name);
		}
		this.data = null;
	},
	//获取其中的一条
	detail: function(id) {
		return this.data[id];
	},
	//TODO:等写完增删改查，需要再改一下。读取数据，如果没有就下载
	readOrLoad: function(url, params, callback) {
		var data = this.read();
		if(data == null) {
			data = this.request(url, params, callback);
		} else {
			callback(data);
		}
	},
	//插入一条数据，data指要插入的数据，callback是回调函数，参数是包含新的ID的数据
	insert: function(data, callback) {
		var self = this;
		var newData = $.extend({}, data);
		if(newData.id) {
			delete newData.id;
		}
		this.request(this.getServerUrl("insert"), { data: newData }, function(result) {
			newData.id = result;
			self.data[newData.id] = newData;
			if(callback) {
				callback(newData);
			}
		});
	},
	//删除若干条数据，data指要删除的数据的索引组成的数组;callback是回调函数，参数是被删除的ID
	deleteItems: function(ids, callback) {
		var self = this;
		this.request(this.getServerUrl("delete"), { where: { id: ids } }, function(result) {
			for(var i in ids) {
				delete self.data[ids[i]];
			}
			if(callback) {
				callback(ids);
			}
		});
	},
	//修改一条数据，data指要修改后的数据(包含在this.data的)
	update: function(id, data, callback) {
		var self = this;
		var newData = $.extend({}, data);
		if(newData.id) {
			delete newData.id;
		}
		this.request(this.getServerUrl("update"), { where: { id: id }, data: newData }, function(result) {
			data.id = id;
			self.data[id] = data;
			if(callback) {
				callback(data);
			}
		});
	},
	//搜索，where指搜索条件，callback指回调函数，参数为搜索到的数据数组
	search: function(where, callback) {
		where = where || {};
		if(this.filter) {
			$.extend(where, this.filter);
		}
		this.lastFilter=where;
		var self = this;
		this.request(this.getServerUrl("search"), {
			where: where,
			pagesize:this.pagesize,
			page:this.page
		}, function(result) {
			if(callback) {
				self.data = self.changeDataIndex(result);
				callback(result);
			}
		});
	},
	pageTurn:function(num,success,failed){
		var gotoPage=this.page+num;
		if(gotoPage<0){
			failed("已经是第一页了");
			return;
		}
		var self = this;
		this.request(this.getServerUrl("search"), {
			where: this.lastFilter,
			pagesize:this.pagesize,
			page:gotoPage
		}, function(result) {
			if(result.length){
				self.data = self.changeDataIndex(result);
				self.page=gotoPage;
				success(result);
			}
			else{
				failed("已经是最后一页了")
			}
		});
	},
	pageTurnTo:function(num,callback){
		var self = this;
		this.request(this.getServerUrl("search"), {
			where: this.lastFilter,
			pagesize:this.pagesize,
			page:this.page
		}, function(result) {
			if(callback) {
				self.data = self.changeDataIndex(result);
				callback(result);
			}
		});
	},
	statistics:function(statistics,callback) {
		var self = this;
		this.request(this.getServerUrl("statistics"), {where:this.lastFilter,statistics:statistics}, function(result) {
			if(callback) {
				callback(result);
			}
		});
	}
}
DataMagic.Model = Class.extend(DMListModel,{
	request: function(url, params, callback) {
		if(url == null) {
			return;
		}
		DataMagic.ajaxSend(params);
		
//		$.ajax({
//			type: "post",
//			url: url,
//			async: true,
//			cache:false,
//			data: params,
//			success: function(data,status,xhr) {
//				if(data == null || data === "") {
//					DataMagic.debug("服务器返回的内容为空");
//					return;
//				}
//				try {
//					if(typeof data==="string"){
//						data = JSON.parse(data);
//					}
//					if(data.status === "error") {
//						DataMagic.debug(data.reason);
//						return;
//					}
//				} catch(e) {
//					DataMagic.debug("解析数据失败:" + e);
//				}
//				callback(data);
//			},
//			crossDomain: true,
//			complete:function(xhr,status){
//				DataMagic.ajaxStop();
//			}
//		});


		$.ajax({
			type: "get",
			url: url,
			async: true,
			cache:false,
			data: params,
			dataType:'jsonp',
			success: function(data,status,xhr) {
				if(data == null || data === "") {
					DataMagic.debug("服务器返回的内容为空");
					return;
				}
				try {
					if(data.status === "error") {
						DataMagic.debug(data.reason);
						return;
					}
				} catch(e) {
					DataMagic.debug("解析数据失败:" + e);
				}
				callback(data);
			},
			complete:function(xhr,status){
				DataMagic.ajaxStop();
			}
		});
	}
});