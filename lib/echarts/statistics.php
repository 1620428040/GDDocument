<?php
//定义常量
define('SQL_HOST', '192.168.1.80');   // 数据库服务器地址
define('SQL_DBNAME', 'sw');    // 数据库名称
define('SQL_USERNAME', 'root');    // 数据库用户账号
define('SQL_PASSWORD', '123456');    // 数据库用户密码
define('SQL_CONNECTIONSTRING', 'mysql:dbname='.SQL_DBNAME.';host='.SQL_HOST);    // 数据库链接（依据上面自动生成，无需修改）
date_default_timezone_set('PRC');

function createEnumForYear($year){
	$yearEnum=array("total"=>0,"details"=>array());
	for($i=1;$i<=12;$i++){
		$yearEnum["details"][$i]=array("total"=>0);
		$yearEnum["details"][$i]["details"]=array_fill(1, cal_days_in_month(CAL_GREGORIAN, $i, $year), 0);
	}
	return $yearEnum;
}
//根据字符串字段中的日期编码
function statisticsByDateCode($dataList,$field){
	global $statistics;
	$statistics=array();
	foreach($dataList as $i => $data){
		preg_replace_callback("/^([0-9]{4})([0-9]{2})([0-9]{2})/", function($matches){
			global $statistics;
			$year=intval($matches[1]);
			$month=intval($matches[2]);
			$day=intval($matches[3]);
			if($year>2100||$year<1900||$month>12||$month<=0||$day<=0||$day>cal_days_in_month(CAL_GREGORIAN, $month, $year)){
				return $matches[0];
			}
			if(!isset($statistics[$year])){
				$statistics[$year]=createEnumForYear($year);
			}
			$statistics[$year]["details"][$month]["details"][$day]++;
			$statistics[$year]["details"][$month]["total"]++;
			$statistics[$year]["total"]++;
			return $matches[0];
		}, $data[$field]);
	}
	return $statistics;
}
function statisticsByDate($dataList,$field){
	$statistics=array();
	foreach($dataList as $i => $data){
		$year=intval(date("Y",$data[$field]));
		$month=intval(date("m",$data[$field]));
		$day=intval(date("d",$data[$field]));
		if(!isset($statistics[$year])){
			$statistics[$year]=createEnumForYear($year);
		}
		$statistics[$year]["details"][$month]["details"][$day]++;
		$statistics[$year]["details"][$month]["total"]++;
		$statistics[$year]["total"]++;
	}
	return $statistics;
}
function statisticsByType($dataList,$field){
	$statistics=array();
	foreach($dataList as $i => $data){
		$type=isset($data[$field])?$data[$field]:"null";
		if(!isset($statistics[$type])){
			$statistics[$type]=0;
		}
		$statistics[$type]++;
	}
	return $statistics;
}

$pdo=new PDO(SQL_CONNECTIONSTRING, SQL_USERNAME, SQL_PASSWORD);
$fileList=$pdo->query("SELECT * from oa_file")->fetchAll(PDO::FETCH_ASSOC);

$dateCode=statisticsByDateCode($fileList, "name");
$createtime=statisticsByDate($fileList, "createtime");
$updatetime=statisticsByDate($fileList, "updatetime");
$hidden=statisticsByType($fileList, "hidden");
$issystem=statisticsByType($fileList, "issystem");
$inheritp=statisticsByType($fileList, "inheritp");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ECharts</title>
    <!-- 引入 echarts.js -->
    <style type="text/css">
    	.echarts-box{
    		float:left;
    		width: 600px;
    		height:400px;
    	}
    	.charts-back {
			cursor: pointer;
			margin: 5px;
			padding: 5px;
			border: solid 1px lightgray;
			border-radius: 5px;
		}
    </style>
    <script src="echarts/echarts.min.js"></script>
    <script src="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
</head>
<body>
    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="timeEcharts" class="echarts-box"></div>
    <div id="typeEcharts" class="echarts-box"></div>
    <div id="dateCode" class="echarts-box"></div>
    <div id="createtime" class="echarts-box"></div>
    <div id="updatetime" class="echarts-box"></div>
    <div id="hidden" class="echarts-box"></div>
    <div id="issystem" class="echarts-box"></div>
    <div id="inheritp" class="echarts-box"></div>
    
    <script type="text/javascript">
    	function TypeStatistics(title,dom,style){
    		this.option=$.extend(true,{},this.defaultOption);
    		this.option.title.text=title;
    		this.dataList=[];
    		this.echarts=echarts.init(dom);
    	}
    	TypeStatistics.prototype.defaultOption={
		    title: {},
		    tooltip : {
		        trigger: 'item',
		        formatter: "{a} <br/>{b} : {c} ({d}%)"
		    },
		    legend: {
                data:[]
           	},
		    series : []
		};
        TypeStatistics.prototype.defaultSeries={
            center: ['50%', '50%'],
            label: {
                normal: {}
            },
            labelLine: {
                normal: {}
            },
            itemStyle: {
                normal: {}
            },
            animationType: 'scale',
            animationEasing: 'elasticOut',
            animationDelay: function (idx) {
                return Math.random() * 200;
            }
       	}
    	TypeStatistics.prototype.addSeries=function(name,type,data,titles){
    		var newData=[];
	    	for(var kn in data){
	    		var title=titles[kn]||kn;
	    		newData.push({name:title,value:data[kn]});
	    	}
    		
    		this.dataList.push(newData);
    		var series=$.extend(true,{},this.defaultSeries);
    		series.name=name;
    		series.type=type;
    		series.data=newData.sort(function (a, b){
    			return a.value - b.value
    		});
    		this.option.legend.data.push(name);
    		this.option.series.push(series);
    	}
    	TypeStatistics.prototype.refresh=function(){
    		var series=this.option.series;
    		if(series.length===1){
    			series[0].radius="50%";
    		}
    		else if(series.length===2){
    			series[0].radius=[0, '30%'];
    			series[0].label.normal.position='inner';
    			
    			series[1].radius=['40%', '60%'];
    		}
    		else if(series.length===3){
    			series[0].radius=[0, '25%'];
    			series[0].label.normal.position='inner';
    			
    			series[1].radius=['30%', '50%'];
    			series[1].label.normal.position='inner';
    			
    			series[2].radius=['55%', '70%'];
    		}
        	this.echarts.setOption(this.option);
    	}
    	
    	function TimeStatistics(title,yName,dom,style){
    		this.option=$.extend(true,{},this.defaultOption);
    		this.option.title.text=title;
    		this.option.yAxis.name=yName;
    		this.dataList=[];
    		this.echarts=echarts.init(dom);
    		this.container=$(dom);
    		this.container.append('<div id="charts-back-box" style="position: absolute;top: 20px;right: 20px;">'+
				'<label class="charts-back" id="total">全部</label>'+
				'<label class="charts-back" id="year" style="display: none;">2016年</label>'+
				'<label class="charts-back" id="month" style="display: none;">4月</label>'+
			'</div>');
    		
    		this.current={year:null,month:null};
    		var self=this;
    		this.echarts.on('click',function(params) {
	        	var num=parseInt(params.name);
	        	if(self.current.year){
	        		if(self.current.month){
	        			
	        		}
	        		else{
	        			self.current.month=num;
	        			self.refresh(self.current.year,self.current.month);
	        			self.container.find("#month").text(self.current.month+"月").show();
	        		}
	        	}
	        	else{
	        		self.current.year=num;
	        		self.refresh(self.current.year);
	        		self.container.find("#year").text(self.current.year+"年").show();
	        	}
	        	return false;
	        });
	        this.container.find("#total").click(function(){
	        	self.back("total");
	        });
	        this.container.find("#year").click(function(){
	        	self.back("year");
	        });
    	}
    	TimeStatistics.prototype.defaultOption={
            title: {},
            tooltip: {},
            legend: {
                data:[]
            },
            xAxis: {
            	name:"x轴",
            	data:null
            },
            yAxis: {
            	name:"y轴"
            },
            series: []
        };
        TimeStatistics.prototype.defaultSeries={
            name: '修改',
            type: 'bar',
            data:null
        }
    	TimeStatistics.prototype.addSeries=function(name,type,data){
    		this.dataList.push(data);
    		var series=$.extend(true,{},this.defaultSeries);
    		series.name=name;
    		series.type=type;
    		this.option.legend.data.push(name);
    		this.option.series.push(series);
    	}
    	TimeStatistics.prototype.refresh=function(year,month){
    		for(var index in this.dataList){
    			var data=this.dataList[index];
    			var newData={"title":[],"value":[]};
	    		if(year){
	    			if(month){
	    				for(var i in data[year].details[month].details) {
		    				newData.title.push(i+"日");
		    				newData.value.push(data[year].details[month].details[i]);
		    			}
		    			data.currentMouthData=newData;
		    			this.option.xAxis.name="日期";
	    			}
	    			else{
	    				for(var i in data[year].details) {
		    				newData.title.push(i+"月");
		    				newData.value.push(data[year].details[i].total);
		    			}
		    			data.currentYearData=newData;
		    			this.option.xAxis.name="月份";
	    			}
	    		}
	    		else{
	    			for(var i in data) {
	    				newData.title.push(i+"年");
	    				newData.value.push(data[i].total);
	    			}
	    			data.currentTotalData=newData;
		    		this.option.xAxis.name="年份";
	    		}
	    		this.option.xAxis.data=newData.title;
	    		this.option.series[index].data=newData.value;
    		}
        	this.echarts.setOption(this.option);
    	}
    	TimeStatistics.prototype.back=function(layer){
    		var layerData="currentYearData";
		    this.option.xAxis.name="月份";
    		if(layer==="total"){
    			this.current.year=null;
    			this.container.find("#year").hide();
    			layerData="currentTotalData";
    			this.option.xAxis.name="年份";
    		}
        	this.current.month=null;
        	this.container.find("#month").hide();
        	
        	for(var index in this.dataList){
				var data=this.dataList[index];
				this.option.xAxis.data=data[layerData].title;
        		this.option.series[index].data=data[layerData].value;
			}
	        this.echarts.setOption(this.option);
        }
        
    	var timeEcharts=new TimeStatistics("文件时间统计","数量",document.getElementById('timeEcharts'));
    	timeEcharts.addSeries('日期编码','bar',<?php echo json_encode($dateCode);?>);
    	timeEcharts.addSeries('上传','bar',<?php echo json_encode($createtime);?>);
    	timeEcharts.addSeries('修改','bar',<?php echo json_encode($updatetime);?>);
    	timeEcharts.refresh();
    	
    	var typeEcharts=new TypeStatistics("文件属性统计",document.getElementById('typeEcharts'));
    	typeEcharts.addSeries('隐藏','pie',<?php echo json_encode($hidden);?>,{0:"不隐藏",1:"对其他人隐藏",2:"预留",3:"完全隐藏"});
    	typeEcharts.addSeries('系统','pie',<?php echo json_encode($issystem);?>,{0:"普通文件",1:"系统文件"});
    	typeEcharts.addSeries('继承','pie',<?php echo json_encode($inheritp);?>,{0:"不继承",1:"继承"});
    	typeEcharts.refresh();
    	
    	var dateCode=new TimeStatistics("文件的日期编码统计","数量",document.getElementById('dateCode'));
    	dateCode.addSeries('日期编码','bar',<?php echo json_encode($dateCode);?>);
    	dateCode.refresh();
    	
    	var createtime=new TimeStatistics("文件创建时间统计","数量",document.getElementById('createtime'));
    	createtime.addSeries('上传','bar',<?php echo json_encode($createtime);?>);
    	createtime.refresh();
    	
    	var updatetime=new TimeStatistics("文件修改时间统计","数量",document.getElementById('updatetime'));
    	updatetime.addSeries('修改','bar',<?php echo json_encode($updatetime);?>);
    	updatetime.refresh();
    	
    	var hidden=new TypeStatistics("文件“隐藏”属性统计",document.getElementById('hidden'));
    	hidden.addSeries('隐藏','pie',<?php echo json_encode($hidden);?>,{0:"不隐藏",1:"对其他人隐藏",2:"预留",3:"完全隐藏"});
    	hidden.refresh();
    	
    	var issystem=new TypeStatistics("文件“系统”属性统计",document.getElementById('issystem'));
    	issystem.addSeries('系统','pie',<?php echo json_encode($issystem);?>,{0:"普通文件",1:"系统文件"});
    	issystem.refresh();
    	
    	var inheritp=new TypeStatistics("文件“继承”属性统计",document.getElementById('inheritp'));
    	inheritp.addSeries('继承','pie',<?php echo json_encode($inheritp);?>,{0:"不继承",1:"继承"});
    	inheritp.refresh();
    </script>
</body>
</html>