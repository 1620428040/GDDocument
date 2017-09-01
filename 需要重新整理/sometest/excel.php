<?php
/**
 *
 * @copyright 2007-2012 Xiaoqiang.
 * @author Xiaoqiang.Wu <jamblues@gmail.com>
 * @version 1.01
 */

error_reporting(E_ALL);

date_default_timezone_set('Asia/ShangHai');

/** PHPExcel_IOFactory */
require "D:\\zend\\Apache2\\htdocs\\bgmobile\\nccos\\plugins\\PHPExcel\\Classes\\PHPExcel\\IOFactory.php";
$filePath="D:\\system\\Desktop\\question.xlsx";
// Check prerequisites
if (!file_exists($filePath)) {
	exit("not found file.\n");
}

$reader = PHPExcel_IOFactory::createReader('Excel2007');
//设置以Excel5格式(Excel97-2003工作簿)
$PHPExcel = $reader -> load($filePath);
// 载入excel文件
$sheet = $PHPExcel -> getSheet(0);
// 读取第一個工作表
$highestRow = $sheet -> getHighestRow();
// 取得总行数
$highestColumm = $sheet -> getHighestColumn();
// 取得总列数

/** 循环读取每个单元格的数据 */
for ($row = 2; $row <= $highestRow; $row++) {//行数是以第1行开始
	for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
		$dataset[] = $sheet -> getCell($column . $row) -> getValue();
		echo $column . $row . ":" . $sheet -> getCell($column . $row) -> getValue() . "<br />";
	}
}
?>