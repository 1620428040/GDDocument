<?php
define("lb", "<br/>");

// 检测变量是否为数字或数字字符串
echo is_numeric("3424").lb;//1

//检测变量是否是整数
echo is_int("3424").lb;//0
echo is_int(3424).lb;//1
//is_integer是is_int的别名

echo is_double(242432456546546456).lb;//1
echo is_float("32423.432").lb;//0
echo is_float(32423.432).lb;//1
?>