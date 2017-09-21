CREATE TABLE IF NOT EXISTS `alipay` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `trade_no` varchar(20) NOT NULL,
  `alipay_trade_no` varchar(30) DEFAULT NULL,
  `trade_type` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `total` float(11,2) NOT NULL,
  `description` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `return_status` varchar(20) DEFAULT NULL,
  `return_time` datetime DEFAULT NULL,
  `notify_status` varchar(20) DEFAULT NULL,
  `notify_time` datetime DEFAULT NULL,
  `trade_data` text,
  `submit` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;