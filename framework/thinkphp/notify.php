<?php
//直接绑定支付宝回调的操作
$_GET['m'] = 'Home';
$_GET['c'] = 'Alipay';
$_GET['a'] = 'notify';

include 'index.php';