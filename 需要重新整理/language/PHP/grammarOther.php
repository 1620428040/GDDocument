<?php
//php中有很多之前没用过的语法，不过不是很常用
//相关链接http://php.net/manual/zh/langref.php

//抛出异常和定义异常处理函数来取代try...catch的写法
function exception_handler($exception) {
  echo "Uncaught exception: " , $exception->getMessage(), "\n";
}
set_exception_handler('exception_handler');
throw new Exception('故意抛出的错误');
echo "Not Executed\n";
?>