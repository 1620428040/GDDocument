<?php
//判断php版本是否大于等于5.2.9 以前版本的array_unique只需第一个参数 
    $php_version = explode('-', phpversion());
    $php_version = $php_version[0];
    $php_version_ge529 = strnatcasecmp($php_version, '5.2.9') >= 0 ? true : false; //=0表示版本为5.2.9  ＝1表示大于5.2.9 =-1表示小于5.2.9
?>