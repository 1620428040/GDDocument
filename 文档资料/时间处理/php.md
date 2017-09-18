```php
//将时间戳转换成字符串
string date ( string $format [, int $timestamp ] )

//将字符串转换成时间戳
int strtotime ( string $time [, int $now = time() ] )
strtotime("+1 day",$time);//在给定的时间戳上加一天
strtotime($timestr);//将时间字符串转换成时间戳
strtotime("$timestr +$day day");//计算时间并转换成时间戳


//取得一个日期的 Unix 时间戳
int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
```