### Alias directories   建立虚拟目录
wampserver允许在菜单里直接创建虚拟目录
选择:任务栏 => wampserver => Apache => Alias directories => Add
在弹出的命令行中输入虚拟目录的地址和真实路径
重启服务器
如果访问虚拟路径显示403错误，点击Edit，在设置文件中添加一行Require all granted


### Put Online/Offline
在比较高的版本上，这个选项放在了任务栏 => wampserver => 右键菜单 => Setting 里