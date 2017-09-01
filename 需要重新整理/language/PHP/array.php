<?php
//合并数组的三种方式
$arr=$arr1+$arr2;//有时候会出问题
$arr=array_merge($arr1,$arr2);//合并数组（以后一个数组补充前一个数组中没有的键值）
$arr=array_merge_recursive($arr1,$arr2);
//合并数组，如果两个数组中有相同的键，则在合并后的数组中创建以这个键命名的新数组，并将值都作为数组的元素
?>