(function(params){})(para);//先创建了一个匿名函数对象，然后直接调用
//括号表示执行一行代码，并且返回结果；如果里面的代码超过一行就会报错；括号内部定义的变量不能被外部调用


//禁止选择文字
//给元素添加一个onselectstart="return false;"事件监听

//*.unselectable {
// -moz-user-select: -moz-none;
// -khtml-user-select: none;
// -webkit-user-select: none;
//
// /*
//   Introduced in IE 10.
//   See http://ie.microsoft.com/testdrive/HTML5/msUserSelect/
// */
// -ms-user-select: none;
// user-select: none;
//}