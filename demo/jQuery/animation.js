var colorPoint=$("<div class='colorPoint'></div>")
background.append(colorPoint);
colorPoint.animate({width:2000},10000);

//jQuery 自定义动画
//jQuery 函数创建自定义动画的语法：
$(selector).animate({params},[duration],[easing],[callback]);
//关键的参数是 params。它定义了产生动画的属性。可以同时设置多个此类属性：
animate({width:"70%",opacity:0.4,marginLeft:"0.6in",fontSize:"3em"});
//第二个参数是 duration。它定义用来应用于动画的时间。它设置的值是："slow", "fast", "normal" 或 毫秒。


//注意:DOM对象大多数是不能移动位置的,除非设置了position: absolute/relative;