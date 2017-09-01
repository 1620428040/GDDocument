<?php
//定义常量
define('SQL_HOST', '192.168.1.80');   // 数据库服务器地址
define('SQL_DBNAME', 'sw');    // 数据库名称
define('SQL_USERNAME', 'root');    // 数据库用户账号
define('SQL_PASSWORD', '123456');    // 数据库用户密码
define('SQL_CONNECTIONSTRING', 'mysql:dbname='.SQL_DBNAME.';host='.SQL_HOST);    // 数据库链接（依据上面自动生成，无需修改）

$statistics=array();
function statisticsAddYear($statistics,$year){
	$statistics[$year]=array("total"=>0,"details"=>array());
	for($i=1;$i<=12;$i++){
		$statistics[$year]["details"][$i]=array("total"=>0);
		$statistics[$year]["details"][$i]["details"]=array_fill(1, cal_days_in_month(CAL_GREGORIAN, $i, $year), 0);
	}
	return $statistics;
}

$pdo=new PDO(SQL_CONNECTIONSTRING, SQL_USERNAME, SQL_PASSWORD);
$fileList=$pdo->query("SELECT * from oa_file")->fetchAll(PDO::FETCH_ASSOC);

foreach($fileList as $i => $file){
	$year=intval(date("Y",$file["createtime"]));
	$month=intval(date("m",$file["createtime"]))+1;
	$day=intval(date("d",$file["createtime"]));
	if(!isset($statistics[$year])){
		$statistics=statisticsAddYear($statistics,$year);
	}
	$statistics[$year]["details"][$month]["details"][$day]++;
}

foreach($statistics as $year=>$yearData){
	foreach($yearData["details"] as $month=>$monthData){
		foreach($monthData["details"] as $day=>$number){
			$statistics[$year]["details"][$month]["total"]+=$number;
		}
		$statistics[$year]["total"]+=$statistics[$year]["details"][$month]["total"];
	}
}

//$dayList1=array();
//$dayMin=null;
//$dayMax=null;
//foreach($fileList as $i => $file){
//	$day=unixtojd($file["createtime"]);// date("y-m-d",);
//	
//	if($dayMin===null||$day<$dayMin){
//		$dayMin=$day;
//	}//
//	
//	if($dayMax===null||$day>$dayMax){
//		$dayMax=$day;
//	}
//	
//	if(isset($dayList1[$day])){
//		$dayList1[$day]++;
//	}
//	else{
//		$dayList1[$day]=1;
//	}
//}
//$dayList=array();
//for($i=$dayMin;$i<=$dayMax;$i++){
//	if(isset($dayList1[$i])){
//		$dayList[$i]=$dayList1[$i];
//	}
//	else{
//		$dayList[$i]=0;
//	}
//}
//echo $dayMin."--".$dayMax."\n";
//print_r($dayList);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ECharts</title>
    <script src="http://cdn.hcharts.cn/jquery/jquery-1.8.3.min.js"></script>
	<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
	<script src="http://cdn.hcharts.cn/highcharts/modules/drilldown.js"></script>
    <!-- 引入 echarts.js -->
    <script src="../echarts/echarts/echarts.min.js"></script>
</head>
<body>
	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="main1" style="width: 600px;height:400px;"></div>
    <div id="main2" style="width: 600px;height:400px;"></div>
    <div id="main3" style="width: 600px;height:400px;"></div>
    <script type="text/javascript">
    	var data=<?php echo json_encode($statistics);?>;
    	function getDataFor(year,month){
    		var newData={"title":[],"value":[]};
    		if(year){
    			if(month){
    				for(var i in data[year].details[month].details) {
	    				newData.title.push(i+"日");
	    				newData.value.push(data[year].details[month].details[i]);
	    			}
    			}
    			else{
    				for(var i in data[year].details) {
	    				newData.title.push(i+"月");
	    				newData.value.push(data[year].details[i].total);
	    			}
    			}
    		}
    		else{
    			for(var i in data) {
    				newData.title.push(i+"年");
    				newData.value.push(data[i].total);
    			}
    		}
    		return newData;
    	}
    	var totalData=getDataFor();
    	var mouthData=getDataFor(2016,4);
    	
        $(function () {
		    // Create the chart
		    $('#container').highcharts({
		        chart: {
		            type: 'column'
		        },
		        title: {
		            text: '文件上传和修改'
		        },
		        xAxis: {
		            type: 'category'
		        },
		        legend: {
		            enabled: false
		        },
		        plotOptions: {
		            series: {
		                borderWidth: 0,
		                dataLabels: {
		                    enabled: true
		                }
		            }
		        },
		        series: [{
		            name: '上传',
		            colorByPoint: true,
		            data:
		             [{
		                name: 'Animals',
		                y: 5,
		                drilldown: 'animals'
		            }, {
		                name: 'Fruits',
		                y: 2,
		                drilldown: 'fruits'
		            }, {
		                name: 'Cars',
		                y: 4,
		                drilldown: 'cars'
		            }]
		        }],
		        drilldown: {
		            series: [{
		                id: 'animals',
		                data: [
		                    ['Cats', 4],
		                    ['Dogs', 2],
		                    ['Cows', 1],
		                    ['Sheep', 2],
		                    ['Pigs', 1]
		                ]
		            }, {
		                id: 'fruits',
		                data: [{
		                    name: 'Apples',
		                    y: 4
		                },{
		                    name: 'Oranges', 
		                    y:2,
		                    drilldown: 'third-leves'
		                }]
		            }, {
		                id: 'cars',
		                data: [
		                    ['Toyota', 4],
		                    ['Opel', 2],
		                    ['Volkswagen', 2]
		                ]
		            },{
		                id: 'third-leves',
		                data: [
		                    ['Toyota', 4],
		                    ['Opel', 2],
		                    ['Volkswagen', 2]
		                ]
		            }]
		        }
		    });
		});
    </script>
</body>
</html>