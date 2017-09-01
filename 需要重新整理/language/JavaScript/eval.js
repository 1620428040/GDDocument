//eval()函数能执行传递给它的js代码，并且返回运算结果（如果有的话）
eval("x=10;y=20;document.write(x*y)")
document.write("<br />")

document.write(eval("2+2"))
document.write("<br />")

var x=10
document.write(eval(x+17))
document.write("<br />")

var script="alert('Hello world')";
eval(script);