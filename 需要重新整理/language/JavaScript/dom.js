//这里记下的都是关于浏览器对象和DOM对象的知识
//DOM是指文档对象模型

//在 HTML DOM （文档对象模型）中，每个部分都是节点：
//文档本身是文档节点
Document
document.all
//所有 HTML 元素是元素节点
Element
document.all[0].toString();//一般可以获取到类名
alert(elementList[ele].innerHTML);//获取元素内部的HTML代码
alert(elementList[ele].innerText);//不常用，只能被IE支持，不能显示html格式的内容
elementList[ele].outerHTML;//包括节点本身在内的HTML字符串
jQuery中的html(),兼容性更好

//所有 HTML 属性是属性节点
Attribute
//HTML 元素内的文本是文本节点

//注释是注释节点








//Browser对象，通常没有统一的标准，但大多数浏览器都支持
//Window 对象表示浏览器中打开的窗口。
window
//如果文档包含框架（frame 或 iframe 标签），浏览器会为 HTML 文档创建一个 window 对象，并为每个框架创建一个额外的 window 对象。
//注释：没有应用于 window 对象的公开标准，不过所有浏览器都支持该对象。

//所有的全局对象和属性都是通过window对象被引用的,例如
//window.document 等同与 document
//window.alert()  等同于 alert()

//window对象可以通过通过window或 self显式的引用自身
//可以通过parent top frames引用与当前window有关系的window对象
//
//window对象有一些可以控制当前窗口的属性和方法


//浏览器Navigator对象,表示当前的浏览器
navigator
navigator.plugins//当前浏览器已经安装的插件和支持的MIME类型
navigator.onLine//当前是否处于脱机状态
//还有其他相关的信息，UA标识之类的

//Screen对象,提供一些屏幕相关的参数
screen

//History对象可以访问浏览器的浏览历史,不过无法获得具体的URL
history
history.back();
history.forward();
history.go();//数字或URL
