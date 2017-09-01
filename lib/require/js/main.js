require.config({
//	baseUrl: "js/lib",
    paths : {
        "jquery" : ["http://libs.baidu.com/jquery/2.0.3/jquery"],
        "abc" : "abc",
        "text":"../lib/text"
    },
//  shim: {
//  	'underscore': {
//  		exports: '_'
//  	},
//  	'backbone': {
//  		deps: ['underscore', 'jquery'],
//  		exports: 'Backbone'
//  	}
//  }
});

if(require.loaded){
	require.loaded();
}
//直到脚本加载和DOM都加载完成才开始
if(require.allReady){
	if(document.readyState === "interactive" || document.readyState === "complete"){
		require.allReady();
	}
	else{
		document.onreadystatechange=function(){
			if(document.readyState === "interactive" || document.readyState === "complete"){
				document.onreadystatechange=null;
				require.allReady();
			}
		}
	}
}
