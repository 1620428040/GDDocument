### 进入数据库程序所在路径
```
d:
cd D:\wamp64\bin\mysql\mysql5.7.14\bin
```

### 登录数据库
```
mysql -uUSERNAME -pPASSWORD
```
如果密码为空，就不输入密码，然后会弹出一个输入密码的提示。直接点回车，继续

### 修改数据库密码
```
mysqladmin -uUSERNAME -pPASSWORD password NEW_PASSWORD
```

### 操作数据库
- 在数据库中可以直接用SQL语言操作，但是结尾必须加";"
- 操作结束后，用"exit;"退出数据库