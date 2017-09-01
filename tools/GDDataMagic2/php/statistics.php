<?php
date_default_timezone_set('PRC');

class statistics{
	public $dataList;
	function createEnumForYear($year){
		$yearEnum=array("total"=>0,"details"=>array());
		for($i=1;$i<=12;$i++){
			$yearEnum["details"][$i]=array("total"=>0);
			$yearEnum["details"][$i]["details"]=array_fill(1, cal_days_in_month(CAL_GREGORIAN, $i, $year), 0);
		}
		return $yearEnum;
	}
	//根据字符串字段中的日期编码
	function byDateCode($field){
		global $statistics;
		$statistics=array();
		foreach($this->dataList as $i => $data){
			preg_replace_callback("/^([0-9]{4})([0-9]{2})([0-9]{2})/", function($matches){
				global $statistics;
				$year=intval($matches[1]);
				$month=intval($matches[2]);
				$day=intval($matches[3]);
				if($year>2100||$year<1900||$month>12||$month<=0||$day<=0||$day>cal_days_in_month(CAL_GREGORIAN, $month, $year)){
					return $matches[0];
				}
				if(!isset($statistics[$year])){
					$statistics[$year]=$this->createEnumForYear($year);
				}
				$statistics[$year]["details"][$month]["details"][$day]++;
				$statistics[$year]["details"][$month]["total"]++;
				$statistics[$year]["total"]++;
				return $matches[0];
			}, $data[$field]);
		}
		return $statistics;
	}
	function byDate($field){
		$statistics=array();
		foreach($this->dataList as $i => $data){
			$year=intval(date("Y",$data[$field]));
			$month=intval(date("m",$data[$field]));
			$day=intval(date("d",$data[$field]));
			if(!isset($statistics[$year])){
				$statistics[$year]=$this->createEnumForYear($year);
			}
			$statistics[$year]["details"][$month]["details"][$day]++;
			$statistics[$year]["details"][$month]["total"]++;
			$statistics[$year]["total"]++;
		}
		return $statistics;
	}
	function byType($field){
		$statistics=array();
		foreach($this->dataList as $i => $data){
			$type=(isset($data[$field])&&$data[$field]!=="")?$data[$field]:"other";
			if(!isset($statistics[$type])){
				$statistics[$type]=0;
			}
			$statistics[$type]++;
		}
		return $statistics;
	}
}
?>