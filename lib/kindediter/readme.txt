kindedit编辑器
由编辑器本身和各种插件组成，添加插件时需要在K.options中的items中添加对应的项
定义插件KindEditor.plugin('anchor', function(K){})
当工具栏上的按钮被点击时，会触发相应的插件

编辑器自带整理HTML代码的功能，但版本比较老了。会将正常的参数去掉，或者使用一些废弃的参数，比如align
可以在htmlTags参数中设置，css属性前需要加"."
另外可以修改一些字体大小，调色盘之类的参数


可以在外部用类似于插件的方式直接调用编辑器的功能
KindEditor.plugin('anchor', function(K){})，只需要替换一些参数
其中的K=编辑器KindEditor，KindEditor是个全局变量，是KEditor的实例，自动创建的
var self = this应该是指KindEditor.instances中保存的一个实例，需要手动创建，如下，为class为editor的控件创建实例
KindEditor.create('.editor', {
    width: '100%',
    height: '350px',
    resizeType: 1,
    uploadJson: '../../tools/upload_ajax.ashx?action=EditorFile&IsWater=1',
    fileManagerJson: '../../tools/upload_ajax.ashx?action=ManagerFile',
    allowFileManager: true
});
调用控件的安装函数？
KindEditor.plugin("addiframe").call(self,KindEditor)
直接调用控件？打开建好的窗口
self.loadPlugin('filemanager', function() {
	self.plugin.filemanagerDialog({
		viewType : 'VIEW',
		dirName : 'image',
		clickFn : function(url, title) {
			if (self.dialogs.length > 1) {
				K('[name="url"]', div).val(url);
				if (self.afterSelectFile) {
					self.afterSelectFile.call(self, url);
				}
				self.hideDialog();
			}
		}
	});
});