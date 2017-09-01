if(containerList.length===1){
	$(".mui-content").html(containerList[0]);
}
else{
	var page=parseInt(localStorage.getItem("index-page-"+request.menu)||0);
	var itemsHtml='';
	var indicatorHtml='';
	for(var i=0;i<containerList.length;i++){
		itemsHtml+='<div class="mui-slider-item';
		indicatorHtml+='<div class="mui-indicator';
		if(i===page){
			itemsHtml+=' mui-active';
			indicatorHtml+=' mui-active';
		}
		itemsHtml+='">'+containerList[i]+'</div>';
		indicatorHtml+='"></div>';
	}
	html='<div id="Gallery" class="mui-slider" style="margin-top:15px;"><div class="mui-slider-group">';
	html+=itemsHtml+'</div><div class="mui-slider-indicator">';
	html+=indicatorHtml+'</div></div>';
	$(".mui-content").html(html);
	
	var slider = document.getElementById('Gallery');
	var group = slider.querySelector('.mui-slider-group');
	var items = mui('.mui-slider-item', group);
	//克隆第一个节点
	var first = items[0].cloneNode(true);
	first.classList.add('mui-slider-item-duplicate');
	//克隆最后一个节点
	var last = items[items.length - 1].cloneNode(true);
	last.classList.add('mui-slider-item-duplicate');
	
	group.classList.add('mui-slider-loop');
	group.insertBefore(last, group.firstChild);
	group.appendChild(first);
	slider.addEventListener('slide', function(event) {
		localStorage.setItem("menu-page-"+request.menu,event.detail.slideNumber);
	});
}