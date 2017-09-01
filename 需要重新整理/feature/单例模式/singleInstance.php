<?php
//php中的单例模式，范例，注意变量和函数都有static属性
//类的定义和继承class ... extends ... 继承可以省略
//类中的变量必须声明private public protected或是static 
class Test extends MySQLClass{
	private static $Instance = null;
	public static function getInstance() {
        if (self::$Instance === null) {
            $className = __CLASS__;//获取所在的类
            self::$Instance = new $className();//可以以变量作为函数名，并执行
        }
        return self::$Instance;
    }
}
?>