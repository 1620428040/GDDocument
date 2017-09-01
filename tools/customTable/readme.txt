这些组件是实现不同语言和框架之间共用数据结构而实现的
数据结构meta=>基于各种平台的解释器interpreter
除了规定数据的类型、大小之类的数据，meta中也包含了宽度、css等信息

meta使用json描述，一般保存在json文件中，也可以从数据库中取得
数据data的形式分为
关联数组/array/数据表，包含数条记录的形式
对象/object/字典，只有一条记录且是健值对应的形式
外键extern，对应object/array

tip:
解释器获取数据之后应该缓存起来以备后用，然后将数据处理成适合显示的形式
解释器用到的控件应该尽量实现相同接口，否则会显得很复杂
应该尽量一次性的将meta和必要的数据从后台发送给前台
所有的表示路径的字段，都用类似于数组的方式(1,2,3)保存(或者.1.2.3.这种前后都加间隔符的形式)
与默认值相同的值为空，否则meta就过长了
尽量避免将无序数组传给前台

保留字段：
id				每条记录的id
result_index	用来保存排序序号

结构（每种结构包含的字段）structure：#新添加的$改名的
表格，表单，列表：
id
#extend			继承自。。。，路径。如果继承的对象被改变，会随着被继承的对象改变
name
#feature			功能(showIndex,显示序号;allowMove,允许上下移动(需要多加一个字段保存排序序号))
#type			object/array
describe
bindTable
$fieldList		表格中的字段列表
condition
cellDblclick	双击表单中的一行会出现的事件
$primaryFields	作为主键的字段
调整字段,不传给前台：
#hiddenFields	隐藏的字段
#prohibitEdit	禁止编辑的字段
#prohibitChange	当字段的值不为空时，禁止修改
从其他字段派生出来的
columns
data


字段：
id
$extend(class)
title
#result_index	序号
field			
type
$maxlength		数据的最大长度，可以设置输入控件的maxlength属性 
notNull
regexp			检测用的正则表达式
describe
valueRange
defaultValue
$columnWidth
可能在后台根据其他属性调整的：
hidden			隐藏
prohibitEdit	禁止编辑的字段
prohibitChange	当字段的值不为空时，禁止修改
显示相关：
#width
#height

字段类型
bool,布尔;
time,时间;
period,一段时间;
number,数字;
text,文本;
singleSelector,单选;//单选，下拉，或者其他的实现
multipleSelector,多选;//复选框，或者其他的实现
externObject,外键;//直接打开表单
externArray,外键;//打开一个表格或者列表
//AUTO_INCREMENT,自增;
//row_number,行号;
//hidden,隐藏;





核心是GDTable控件，只要定义了要绑定的数据库中的表，和每个字段的属性，就可以自动完成增删改查等功能
	并且可以和其他控件绑定，简单的功能不需要每次都重新写一次
优点是可以被多次复用，而且每次都可以提供比较完善的功能
缺点是纠缠度太高，过于复杂

常见参数的含义
app		控件所在的web应用，方便控件调用后台的方法
model	通常是类似json格式的对象，用来定义控件
target	通常，写this就可以，回调函数中的this等于此处传入的target参数
handler	回调函数，大多数有两个参数，第一个是数据，第二个是类型，视情况而定


可以继续改进的思路
引入表之间的关系，外键等概念
选择，可以增加一个在下级表格中搜索的类型（一个外键对应另一个表中多条记录->另一个表）  类型：extern array  值:1,2,3,4  字段 :tableName:filedName
一个外键对应另一个表中的一条记录，此时另一条记录中的内容作为扩展字段，直接在表中显示，bind_table可以关联多个表，以第一个表为主  类型：extern object  值:4  字段 :tableName:filedName

另外需要完善其他类型的搜索

字段分类，按照继承关系弄成树状图

外键
类型：extern
值:1,2,3,4(表示一个数组)
字段 :filedName(在当前表中的字段名)
可选范围:tableName(被引用的表):filedName(在被引用的表中的字段名):showFiled(要显示的字段，比如值是一组id，要显示的是name字段对应的值)~where(搜索条件)


表格中显示的数据，跟实际保存的数据，有时候格式不一样；需要在表格中添加一个对应关系表
比如extern类型的数据，直接在后台将对应表发到前台，然后在qx.ui.form.extern中写个静态方法转换成要显示的值
时间类型的数据，直接转换为date类型，然后显示


2016年8月30日
目前存在的问题：

表单中的内容，是否可以编辑，还没法控制；
表单中的项目太多时，布局也会有问题
表单中应该加入单选，多选控件，下拉控件（给定选项的）*

extern类型的数据，应该可以控制是否只能单选；
而且目前的对应id和name的对应关系，是直接全部传到前台操作的，数据一多就会有问题

查看、编辑，两种操作应该分开。*
"cellDblclick"事件应该直接指定进行何种操作*
应该加上编辑完表格后，查看预览表格的功能
字段一多就显得很混乱，如何方便地找到需要的字段？


字段类型:TextField,TextField,文本框;SelectBox,SelectBox,下拉框;AUTO_INCREMENT,AUTO_INCREMENT;timeInterval,timeInterval;row_number,row_number;extern,extern;hidden,hidden;number,number;bool,bool;time,time;RadioGroup,RadioGroup;CheckBox,CheckBox






自定义表格、表单控件的架构设想
meta，描述数据的定义和显示时的设置，以json数据的形式存在，通用的
model，保存数据和meta，实现数据的增删改查等操作，包括前台调用后台（基类）
controller，创建model和view，控制model和view的数据交换，有时候需要添加额外的处理数据的逻辑和调整view，可以自定义的部分，甚至能在运行时修改meta，有时候也可以写到model和view的子类中
view,表格的实现部分在前台的各种环境中分别实现，但逻辑差不多（基类）
后台,根据meta处理数据，有存取数据，创建、修改数据表的功能；在各种后台中分别实现，逻辑也相似

meta:
	table:
		id
		name
		bindTable
		multiSelect:yes,no
		describe
		primary:number
		condition:默认搜索条件,以;分隔，允许直接空where条件和特定的语句，比如"duration TimeInterval;"表示当前时间介于TimeInterval表示的时间区间内
		indexColumn:none,hidden,show
		fieldList
		buttonList
		base:继承于某个field，参数为空的时候，使用继承的参数,clone的时候会提供默认值
	field:
		id
		title
		field
		describe
		source:default,onlyShow,SQlAuto
		type:输入控件的类型
		width
		length
		valueRange
		defaultValue
		nullAble
		editAble
		hidden
		visit:public,private;只有visit值为public的field会出现在公共的表中，private只能在所在的table中显示
		base:继承于某个field，参数为空的时候，使用继承的参数,clone的时候会提供默认值
		
其他：
model中需要实现一个关联数组（或对象）fieldAssoc:field.name=>field.id，这样可以避免每次都枚举数组
平时table的indexColumn可以任意设置，排序保存在index字段里，当作为外键时，indexColumn=show，排序保存在外键中(主键的顺序)
