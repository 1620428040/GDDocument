<?php
//当使用ob_end_clean关闭缓冲区后，先输出内容，再设置响应头就会出现警告
//Cannot modify header information - headers already sent
ob_end_clean();
echo "this is test";
header("LOCATION http://www.baidu.com");
?>