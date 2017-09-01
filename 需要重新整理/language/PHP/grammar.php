脚本的执行时间的限制，默认是30秒，设为0则不限制
set_time_limit(0)

命名空间
namespace use require之间的关系
require用来引入文件内容，与file_get_contents函数类似，但file_get_contents函数不是直接执行php文件，而是返回一个字符串
namespace用来定义和引入命名空间
	命名空间可以用namespace引入（但必须在文件最前面，而且这个文件中的代码也被归入这个命名空间了）
		也可以在类名，变量名，函数名等前面直接加上命名空间的名字，而不引入命名空间
use用来给命名空间命别名
	use ... as ...形式
	不加as的话，别名就是命名空间的最后一节

比如
inc.php 文件：
namespace Zend\Http\PhpEnvironment;//定义了一个命名空间
class Bar {}//定义了一个类

其他文件中：
//全称调用
require 'inc.php';
$foo = new \Zend\Http\PhpEnvironment\Bar();
//使用命名空间
namespace Foo; // 调整当前脚本到Foo这个ns域，而且namespace申明必须在第一句
require 'inc.php';
$foo = new Bar();
//使用use命别名
use \Zend\Http\PhpEnvironment as pe;   //定义别名
$foo = new \pe\Bar();                  //用短的别名来代替原来的
//如果省略后面的as ....，那么，就可以直接用最后一节的文字来代替，比如，上面的：
use \Zend\Http\PhpEnvironment;   //定义别名
$foo = new \PhpEnvironment\Bar();      //用短的别名来代替原来的



header("content-type:text/html;charset=utf-8");声明编码类型

print_r()函数用来打印变量的信息，即使用echo会报错

尝试可能出错的代码
try 
{
} 
catch (Exception $e) 
{
return null;
}

ini_get — 获取一个配置选项的值
string ini_get ( string $varname )
get_magic_quotes_gpc()函数，为字符串增加转义字符以避免触发程序（magic_quotes_gpc=On的情况下）

magic_quotes_runtime 值。设定为字符串自动添加转义字符的功能是否打开。0-关闭 1-打开。
get_magic_quotes_runtime()
set_magic_quotes_runtime()

获取字符串长度
strlen($str);

文件读写
$fp=fopen("../cnbruce.txt",'w');打开文件
fclose()关闭文件
readfile()函数，读取整个文件
fgets()函数，读取一行
fgetc()函数，读取一个字符
feof()函数，检测是否到了末尾
fwrite(文件路径,写入内容)，写文件
file_exists()：查看文件是否存在，返回布尔值
filesize()：查看文件大小，可直接echo输出
unlink()：删除文件，注意PHP中没有delete函数。

r    只读——读模式，打开文件，从文件头开始读
r+    可读可写方式打开文件，从文件头开始读写
w    只写——写方式打开文件，同时把该文件内容清空，把文件指针指向文件开始处。如果该文件已经存在，将删除文件已有内容；如果该文件不存在，则建立该文件
w+    可读可写方式打开文件，同时把该文件内容清空，把文件指针指向文件开始处。如果该文件不存在，则建立该文件
a    追加    以只写方式打开文件，把文件指针指向文件末尾处。如果该文件不存在，则建立该文件
a+    追加    以可读可写方式打开文件，把文件指针指向文件末尾处。如果该文件不存在，则建立该文件
b    二进制    用于于其他模式进行连接。建议使用该选项，以获得更大程度的可移植性