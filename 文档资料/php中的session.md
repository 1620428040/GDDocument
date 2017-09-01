###会话文件
会话保存在sess_开头的文件中
在wampserver中保存在tmp路径下
格式  key|s:7:"the key";



###相关方法
```php
//返回当前会话状态
int session_status ( void )
PHP_SESSION_DISABLED //0,会话是被禁用的。
PHP_SESSION_NONE //1,会话是启用的，但不存在当前会话。
PHP_SESSION_ACTIVE //2,会话是启用的，而且存在当前会话。

//获取/设置当前会话 ID
string session_id ([ string $id ] )

//启动新会话或者重用现有会话
bool session_start ([ array $options = [] ] )

//将session数据写入文件，并且关闭会话。两个函数等效
void session_commit ( void )
void session_write_close ( void )

//释放所有的会话变量
void session_unset ( void )

//销毁一个会话中的全部数据
bool session_destroy ( void )
```


### 实例
### 注意事项：
- 设置`session_id`后必须用`session_start`重启才会生效
- `session_unset`和`session_destroy`必须在会话启动后才会生效(就是说必须放在`session_start`之后)

```php
//session_start可以启动或重启会话，下次访问还可以获取保存在会话中的数据
session_start();
if(!isset($_SESSION["key"])){
    $_SESSION["key"]="the key";
    echo "ready";
    exit;
}
print_r($_SESSION);echo "<br/>";
```

```php
//session_commit会提交并关闭会话，但不会删除$_SESSION中的数据
echo "status:".session_status()."<br/>";
session_commit();
echo "status:".session_status()."<br/>";
print_r($_SESSION);echo "<br/>";
```

```php
//session_unset会删除$_SESSION和session文件中的数据，但是不会关闭会话。
session_start();
session_unset();
print_r($_SESSION);echo "<br/>";
echo "status:".session_status()."<br/>";
```

```php
//session_destroy会关闭会话并删除掉session文件，但不会清空$_SESSION中的数据。
session_start();
session_destroy();
print_r($_SESSION);echo "<br/>";
```

```php
//session_id用来设置会话id
//在不销毁会话文件的前提下，使用给定的key读取相应的session
session_commit();
$_SESSION=null;
session_id($key);
session_start();
```