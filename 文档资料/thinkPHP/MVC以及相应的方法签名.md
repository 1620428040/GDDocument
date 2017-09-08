thinkPHP使用MVC模式，但是可以MVC都有默认的代码，所以可以省略一部分或几部分。
MVC会增加代码的复杂程度，
但是如果业务逻辑足够复杂，且需要同时提供view和api，或者经常变动,添加Model会比较简单
并且Model提供的自动验证、自动完成能减少需要写的代码

## 常用方法签名
### Model
```php
protected $_validate        =   array();  // 自动验证定义
protected $_auto            =   array();  // 自动完成定义
protected $_map             =   array();  // 字段映射定义
protected $_scope           =   array();  // 命名范围定义
// 链操作方法列表
protected $methods          =   array('strict','order','alias','having','group','lock','distinct','auto','filter','validate','result','token','index','force');

//利用__call方法实现一些特殊的Model方法
public function __call($method,$args) {
// 连贯操作的实现
// 统计查询的实现
// 根据某个字段获取记录
// 根据某个字段获取记录的某个值

//自动检测数据表信息
protected function _checkTableInfo()
//获取字段信息并缓存
public function flush()

//设置数据对象的值
public function __set($name,$value)
//获取数据对象的值
public function __get($name)
//检测数据对象的值
public function __isset($name)
//销毁数据对象的值
public function __unset($name)

// 回调方法 初始化模型
protected function _initialize()

//对保存到数据库的数据进行处理
protected function _facade($data)
// 写入数据前的回调方法 包括新增和更新
protected function _before_write(&$data)

//创建数据对象 但不保存到数据库
public function create($data='',$type='')
//新增数据
public function add($data='',$options=array(),$replace=false)
// 插入数据前的回调方法
protected function _before_insert(&$data,$options)
// 插入成功后的回调方法
protected function _after_insert($data,$options)
public function addAll($dataList,$options=array(),$replace=false)
//通过Select方式添加记录
public function selectAdd($fields='',$table='',$options=array())

//保存数据
public function save($data='',$options=array())
// 更新数据前的回调方法
protected function _before_update(&$data,$options)
// 更新成功后的回调方法
protected function _after_update($data,$options)

//删除数据
public function delete($options=array())
// 删除数据前的回调方法
protected function _before_delete($options)
// 删除成功后的回调方法
protected function _after_delete($data,$options)

//查询数据集
public function select($options=array())
// 查询成功后的回调方法
protected function _after_select(&$resultSet,$options)
//查询数据
public function find($options=array())
// 查询成功的回调方法
protected function _after_find(&$result,$options)

//生成查询SQL 可用于子查询
public function buildSql()
//分析表达式
protected function _parseOptions($options=array())
// 表达式过滤回调方法
protected function _options_filter(&$options)
//数据类型检测
protected function _parseType(&$data,$key)
//数据读取后的处理
protected function _read_data($data)

protected function returnResult($data,$type='')

//处理字段映射
public function parseFieldsMap($data,$type=1)

//获取一条记录的某个字段值
public function getField($field,$sepa=null)
//设置记录的某个字段值,支持使用数据库字段和方法
public function setField($field,$value='')
//字段值增长
public function setInc($field,$step=1,$lazyTime=0)
//字段值减少
public function setDec($field,$step=1,$lazyTime=0)
//延时更新检查 返回false表示需要延时,否则返回实际写入的数值
protected function lazyWrite($guid,$step,$lazyTime)

// 自动表单令牌验证
public function autoCheckToken($data)
// TODO  ajax无刷新多次提交暂不能满足
//使用正则验证数据
public function regex($value,$rule)
//自动表单处理
private function autoOperation(&$data,$type)
//自动表单验证
protected function autoValidation($data,$type)
//验证表单字段 支持批量验证,如果批量验证返回错误的数组信息
protected function _validationField($data,$val)
//根据验证因子验证字段
protected function _validationFieldItem($data,$val)
//验证数据 支持 in between equal length regex expire ip_allow ip_deny
public function check($value,$rule,$type='regex')

//存储过程返回多数据集
public function procedure($sql, $parse = false)
//SQL查询
public function query($sql,$parse=false)
//执行SQL语句
public function execute($sql,$parse=false)
//解析SQL语句
protected function parseSql($sql,$parse)
//返回最后执行的sql语句
public function getLastSql()
// 鉴于getLastSql比较常用 增加_sql 别名
public function _sql()
//获取执行的SQL语句
public function fetchSql($fetch=true)

//切换当前的数据库连接
public function db($linkNum='',$config='',$force=false)
// 数据库切换后回调方法
protected function _after_db()

//得到当前的数据对象名称
public function getModelName()
//得到完整的数据表名
public function getTableName()

//启动事务
public function startTrans()
//提交事务
public function commit()
//事务回滚
public function rollback()

//返回模型的错误信息
public function getError()
//返回数据库的错误信息
public function getDbError()
//返回最后插入的ID
public function getLastInsID()
//获取主键名称
public function getPk()
//获取数据表字段信息
public function getDbFields()
//设置数据对象值
public function data($data='')
//指定当前的数据表
public function table($table)

//USING支持 用于多表删除
public function using($using)
//查询SQL组装 join
public function join($join,$type='INNER')
//查询SQL组装 union
public function union($union,$all=false)
//查询缓存
public function cache($key=true,$expire=null,$type='')
//指定查询字段 支持字段排除
public function field($field,$except=false)
//调用命名范围
public function scope($scope='',$args=NULL)
//指定查询条件 支持安全过滤
public function where($where,$parse=null)
//指定查询数量
public function limit($offset,$length=null)
//指定分页
public function page($page,$listRows=null)
//查询注释
public function comment($comment)

//参数绑定
public function bind($key,$value=false)
//设置模型的属性值
public function setProperty($name,$value)
```


### View
```php
//模板变量赋值
public function assign($name,$value='')
//取得模板变量的值
public function get($name='')

//加载模板和页面输出 可以返回输出内容
public function display($templateFile='',$charset='',$contentType='',$content='',$prefix='')
//输出内容文本可以包括Html
private function render($content,$charset='',$contentType='')
//解析和获取模板内容 用于输出
public function fetch($templateFile='',$content='',$prefix='')

//自动定位模板文件
public function parseTemplate($template='')
//获取当前的模板路径
protected function getThemePath($module=MODULE_NAME)

//设置当前输出的模板主题
public function theme($theme)
//获取当前的模板主题
private function getTemplateTheme()
```


### Controller
- Controller的含义等同于之前版本的Action

```php
//魔术方法 有不存在的操作的时候执行
public function __call($method,$args)
// 如果定义了_empty操作 则调用
// 检查是否存在默认模版 如果有直接输出模版
//Ajax方式返回数据到客户端
protected function ajaxReturn($data,$type='',$json_option=0)
//模板显示 调用内置的模板引擎显示方法
protected function display($templateFile='',$charset='',$contentType='',$content='',$prefix='')
//输出内容文本可以包括Html 并支持内容解析
protected function show($content,$charset='',$contentType='',$prefix='')
//获取输出页面内容
protected function fetch($templateFile='',$content='',$prefix='')
//创建静态页面
protected function buildHtml($htmlfile='',$htmlpath='',$templateFile='')
//模板主题设置
protected function theme($theme)
//模板变量赋值
protected function assign($name,$value='')
public function __set($name,$value)
//取得模板显示变量的值
public function get($name='')
public function __get($name)
//检测模板变量的值
public function __isset($name)
//操作错误跳转的快捷方法
protected function error($message='',$jumpUrl='',$ajax=false)
//操作成功跳转的快捷方法
protected function success($message='',$jumpUrl='',$ajax=false)
//Action跳转(URL重定向） 支持指定模块和延时跳转
protected function redirect($url,$params=array(),$delay=0,$msg='')
//默认跳转操作 支持错误导向和正确跳转
private function dispatchJump($message,$status=1,$jumpUrl='',$ajax=false)
```