//================================================================
//以下部分是方便测试用的函数
//================================================================
//用给定的数据填充表单
function fill() {
	var data = {
		"id": 1232,
		"title": "测试-值班",
		"duty_content": "ergre",
		"duty_userid": 442124,
		"people": "ergre",
		"inputuserid": 4241,
		"inputusername": "ergre",
		"date": "2017年4月5日  8时28分",
		"begin_time": "2017年10月10日",
		"end_time": "2017年10月10日",
//		"visitor": 1,
		"visitor_name": "ergre",
//		"file": 1,
		"file_name": "ergre"
	}
	for(var kn in data) {
		var node = $('[id=' + kn + ']');
		if(node.is("input")||node.is("textarea")){
			node.val(data[kn]);
		}
		else{
			node.text(data[kn]);
		}
	}
}