### Alias directories   建立虚拟目录
wampserver允许在菜单里直接创建虚拟目录
选择:任务栏 => wampserver => Apache => Alias directories => Add
在弹出的命令行中输入虚拟目录的地址和真实路径
重启服务器
如果访问虚拟路径显示403错误，点击Edit，在设置文件中添加一行Require all granted


### 虚拟主机
```
<VirtualHost *:80>
    DocumentRoot /yjdata/www/www/
	ProxyPassMatch ^/(.*\.php)$ fcgi://127.0.0.1:10000/yjdata/www/www/$1
	DirectoryIndex index.html index.php
</VirtualHost>
```


### Put Online/Offline
在比较高的版本上，这个选项放在了任务栏 => wampserver => 右键菜单 => Setting 里


### php.ini修改
实际生效的php.ini文件的路径为：wamp64\bin\php\phpX.X.X\php.ini
wamp刚安装时，默认在页面上显示错误，需要设置：display_errors = Off