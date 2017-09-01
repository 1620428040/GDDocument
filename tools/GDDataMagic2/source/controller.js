//================================================================
//控制器
//================================================================
/* 控制器类，负责接收用户的操作请求，调用model，然后更新view
 * 
 * 属性
 * model指当前控制器对应的model
 * list指当前控制器对应的列表(或者表格)对象
 * toolbar工具栏
 * form表单对象
 * ready布尔值，是否准备完成，可以加载数据了
 */
var DMListController={
	title:"基础控制器",
	construct:function(name, storage, host, params) {
		DataMagic.Controller.instanceList.push(this);
		this.model = new DataMagic.Model(name, storage, host, this, params);
		this.list = new DataMagic.View.List(this);
		this.toolbar = new DataMagic.View.Toolbar(this);
		this.form = new DataMagic.View.Form(this);
	}, 
	onDOMLoad: function() {
		DataMagic.debug("DOM加载完成");
		this.list.initView($(".DMList"));
		this.toolbar.initView($(".DMToolbar"));
		this.form.initView($(".DMForm"));
		if(this.model.meta) {
			this.initViewWithMeta();
		}
	},
	onMetaLoad: function() {
		DataMagic.debug("meta加载完成");
		if(document.readyState === "interactive" || document.readyState === "complete") {
			this.initViewWithMeta();
		}
	},
	onDataLoad: function() {
		DataMagic.debug("data加载完成");
		if(this.ready) {
			this.list.insert(this.model.data);
		}
	},
	onReady: null,
	initViewWithMeta: function() {
		DataMagic.debug("开始初始化视图");
		this.ready = true;
		DataMagic.initViewWithMeta(this.model.meta);
		if(this.list) {
			this.list.clearAll();
			if(this.model.data) {
				this.list.insert(this.model.data);
			}
		}
		this.toolbar.refresh(this.model.meta.tools);
		if(this.onReady) {
			this.onReady();
		}
	},
	/* 执行指定的函数
	 * action要执行的函数
	 * coerce强制执行而不管工具栏中有没有这个按钮
	 */
	execute: function(action,coerce) {
		if(!coerce&&this.toolbar.buttons.indexOf(action)===-1){
			return false;
		}
		if(this[action]) {
			this[action]();
		} else {
			DataMagic.debug(this);
			DataMagic.debug("调用的方法不存在:" + action);
		}
	},
	/*控制表单*/
	showForm: function(mode, data) {
		DataMagic.debug("显示表单");
		DataMagic.debug(data);
		this.form.mode = mode;
		this.form.clearAll();
		if(mode === "insert") {
			for(var key in this.model.meta.fieldList) {
				var value = this.model.meta.fieldList[key];
				var field = value.dataType.createInputField(value.defaultValue);
				this.form.append(field);
			}
		} else if(mode === "search") {
			for(var key in this.model.meta.fieldList) {
				var value = this.model.meta.fieldList[key];
				var field = value.dataType.createSearchField();
				this.form.append(field);
			}
		} else {
//			console.log(data);
			for(var key in this.model.meta.fieldList) {
				var value = this.model.meta.fieldList[key];
//				console.log(key,value,data[key]);
				var field = value.dataType.createInputField(data[key]);
				this.form.append(field);
			}
		}
		this.form.show();
	},
	showFormForDetails: function(mode) { //在表单中显示一条数据的详情，进行查看或修改
		var cells = this.list.getSelectedItems(true);
		if(cells) {
			var id = this.list.getIDWithItems(cells)[0];
			var data = this.model.data[id];
			this.list.hide();
			if(mode === "update") {
				this.toolbar.refresh(["save", "cancel"]);
			} else {
				this.toolbar.refresh(["cancel"]);
			}
			this.showForm(mode, data);
			this.currentID = id;
			this.currentItem = cells;
		}
	},
	getFormData: function() {
		var getter = this.form.mode === "search" ? "getSearchParams" : "getValue";
		var result = {};
		var validated = true; //数据是否通过了验证
		for(var kn in this.model.meta.fieldList) {
			var kv = this.model.meta.fieldList[kn];
			var value = kv.dataType[getter]();
			if(value) {
				result[kn] = value;
			}
			validated = kv.dataType.validated && validated;
			if(!validated) {
//				DataMagic.dialog(kn);
			}
		}
		if(!validated) {
			DataMagic.dialog("您输入的数据格式不正确！");
			return;
		}
		return result;
	},
	save: function() {
		var result = this.getFormData();
		if(result == null) {
			return;
		}
		DataMagic.debug("获取表单的值");
		DataMagic.debug(result);
		
		delete result.id;

		var self = this;
		if(this.form.mode === "insert") {
			this.model.insert(result, function(data) {
				self.list.insert(data);
			});
		} else if(this.form.mode === "update") {
			var self = this;
			this.model.update(this.currentID, result, function(data) {
				self.list.update(self.currentItem, data, self.model.meta.fieldList);
			});
		} else if(this.form.mode === "search") {
			this.model.search(result, function(data) {
				self.list.clearAll();
				self.list.insert(data);
			});
		}
		this.cancel();
	},
	cancel: function() {
		this.toolbar.refresh(this.model.meta.tools);
		this.list.show();
		this.form.hide();
	},
	/*控制列表*/
	insert: function() {
		this.list.hide();
		this.showForm("insert");
		this.toolbar.refresh(["save", "cancel"]);
	},
	deleteItems: function() {
		var cells = this.list.getSelectedItems(true,true);
		if(cells){
			var ids = this.list.getIDWithItems(cells);
			var self = this;
			this.model.deleteItems(ids, function() {
				self.list.remove(cells);
			});
		}
	},
	update: function() {
		this.showFormForDetails("update");
	},
	browse: function() {
		this.showFormForDetails("browse");
	},
	search: function() {
		this.list.hide();
		this.showForm("search");
		this.toolbar.refresh(["save", "cancel"]);
	},
	refresh: function() {
		var self = this;
		this.model.search(null, function(data) {
			self.list.clearAll();
			self.list.insert(data);
		});
	},
	pageTurn:function(num){
		var self = this;
		this.model.pageTurn(num,function(data){
			self.list.clearAll();
			self.list.insert(data);
		},function(message){
			DataMagic.alert(message);
		});
	}
}
DataMagic.Controller = Class.extend(DMListController,{
	instanceList: [],
	onDOMLoad:function() {
		//因为是放在$()中的，所以此处的DataMagic.Controller不能改成this
		for(var i in DataMagic.Controller.instanceList) {
			DataMagic.Controller.instanceList[i].onDOMLoad();
		}
	}
});
//当DOM加载完成时，通知控制器，初始化视图对象
$(DataMagic.Controller.onDOMLoad);