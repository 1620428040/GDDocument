/*面向切面编程AOP用到的函数
 * context指函数所在对象,funcName要监听的函数，callback插入的函数
 * callback的返回值为空不影响原函数，不为空会影响原函数或返回结果！
 */
//在context[funcName]指定的函数之前插入callback指定的函数
//callback可以改变arguments的值，如果返回为false则不调用原函数
function aopBefore(context,funcName,callback){
	context=context||window;
	var oldFunc=context[funcName];
	var newFunc=function(){
		var newArgu=callback.apply(context,arguments);
		if(oldFunc){
			if(newArgu==null){
				return oldFunc.apply(context,arguments);
			}
			else if(newArgu!=false){
				return oldFunc.apply(context,newArgu);
			}
		}
		else{
			return newArgu;
		}
	}
	context[funcName]=newFunc;
	context[funcName].aopOrigin=oldFunc;
}
//在context[funcName]指定的函数之后插入callback指定的函数
//原函数的返回值作为参数的最后一个传给callback
function aopAfter(context,funcName,callback){
	context=context||window;
	var oldFunc=context[funcName];
	var newFunc=function(){
		var result=null;
		if(oldFunc){
			result=oldFunc.apply(context,arguments);
			arguments[arguments.length]=result;
			arguments.length++;
		}
		return callback.apply(context,arguments)||result;
	}
	context[funcName]=newFunc;
	context[funcName].aopOrigin=oldFunc;
}
//在context[funcName]指定的函数之前插入callback指定的函数
function aopClear(context,funcName){
	context=context||window;
	var oldFunc=context[funcName];
	while(oldFunc.aopOrigin){
		oldFunc=oldFunc.aopOrigin;
	}
	context[funcName]=oldFunc;
}
/*面向切面编程AOP用到的函数*/