//JQuery对象就是使用“$()”，将DOM对象包装起来。
//JQuery对象不能调用DOM对象的属性和方法，同样DOM对象也不能调用JQuery对象的属性和方法。 
$()=jQuery();//两种写法等价
this;//js中值当前对象，触发事件的对象
$(this);

//JQuery对象都是数组，数组中对应的是DOM对象，例如
this=$(this)[0]

$(document).ready();//dom加载完成
$(this).html();//获取、设置DOM对象的内容

$(this).attr();//添加，修改对象的属性
$(this).removeAttr();//移除对象的属性

$(this).addClass();//如，“$("p").addClass("selected");”，向所有P元素中追加“selected”样式。 
$(this).removeClass();//--- 从匹配的元素中删除全部或指定的class。如，“$("p").removeClass("selected");”，删除所有P元素中的“selected”。 
$(this).toggleClass();//--- 控制样式上的重复切换。如果类名存在则删除它，如果类名不存在则添加它。如，“$("p").toggleClass("selected");”，所有的P元素中，如果存在“selected”样式就删除“selected”样式，否则就添加“selected”样式。 
$(this).hasClass();//判断有没有某个css类

$(this).click();//触发点击事件,或者设置点击事件
$(this).unbind();//移除绑定的事件