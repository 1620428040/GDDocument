<?php
//资源 resource 是一种特殊变量，保存了到外部资源的一个引用。
//资源是通过专门的函数来建立和使用的。
$resource=mysql_connect("127.0.0.1","root","123456");//数据库连接是"mysql link"类型的资源
is_resource($resource);//判断是不是资源类型的数据
get_resource_type($resource);//获取资源的类型
?>