/*在原生的JavaScript中*/
//监听文档加载状态的改变
document.onreadystatechange=function(event){
	console.log(event);
	console.log(document.readyState);
	console.log(document.readyState==="complete");
}
//监听文档加载完成
document.onload=function(event){
	console.log(event);
}
//查看当前的文档加载状态
document.readyState

/*在jQuery中，两种方法等效*/
$(document).ready(function(){
	
});
$(function(){
	
});