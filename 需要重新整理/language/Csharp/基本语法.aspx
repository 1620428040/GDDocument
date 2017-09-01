<%@ Page Language="C#" Trace="true" %>
<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<head>
		<title>ASPX Page</title>
	</head>
	<body>
	 	
	</body>
</html>

<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Main.aspx.cs" Inherits="Main" %>
在每个aspx文件的开头，都要调用这个语句，代表提前运行C#脚本

C#脚本中
一堆引用命名空间的语句作为开头
然后public partial class Main : System.Web.UI.Page创建一个web页面（窗口）继承于System.Web.UI.Page类

页面加载后，会调用protected void Page_Load(object sender, EventArgs e)方法

在控制台输出信息
Console.Write;C#中的输出方法
System.Diagnostics.Debug.Write("hello world!");asp.net中可以在控制台的输出窗口看到

读取设置文件中的某个字符串作为标题
<title><%=System.Configuration.ConfigurationManager.AppSettings["SYSTitle"]%></title>

asp.net的界面可以直接设计，点击事件在设计界面点击，会自动生成并配置，直接写代码也可以（但要注明runat="server"）
如果在OnClick="ImageButton1_Click"中提前设置了函数名，则会生成相应的函数，如果没有，会自动生成一个名称


在C#中，文件系统的管理
Path指包含文件目录和文件名在内的路径

文件系统对象，对这些对象的操作
Directory指文件夹
File指单个文件

对象信息
FileSystemInfo是文件系统信息对象的基类，包含创建日期之类的信息，有两个子类
DirectoryInfo
FileInfo

using(){}的小括号中定义的对象，在这个函数块结束的时候，会自动释放


在asp.net中控制网页的DOM
有runat="server"标记，并且设置有ID的控件，可以直接在代码中引用的
按钮之类的控件要放在有runat="server"属性的form（表单，asp.net中称为窗体）中，因为他们实际都是button标签
要在页面上直接添加，用this.controls，不过一般用不着
获取父控件Parent，获取子控件的数组Controls
Table控件，用Rows获取行，再用Cells获取单元格
带event标记的委托，似乎都需要用+=  -=来增加，删除


有些对象可以使用索引的形式（在类的定义中有说明）
this[int index]的形式，可以像数组或词典一样使用索引







































