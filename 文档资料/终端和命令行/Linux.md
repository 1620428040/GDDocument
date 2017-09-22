## Linux
Linux是一套免费使用和自由传播的类Unix操作系统，是一个基于POSIX和UNIX的多用户、多任务、支持多线程和多CPU的操作系统。

### 发行版
Linux的发行版说简单点就是将Linux内核与应用软件做一个打包。
目前市面上较知名的发行版有：Ubuntu、RedHat、CentOS、Debian、Fedora、SuSE、OpenSUSE、TurboLinux、BluePoint、RedFlag、Xterm、SlackWare等。

### 服务/守护进程
许多程序需要在后台运行。它们在Windows叫做"服务"（service），在Linux就叫做"守护进程"（daemon）。

### 运行级别
Linux允许为不同的场合，分配不同的开机启动程序，这就叫做"运行级别"（runlevel）。也就是说，启动时根据"运行级别"，确定要运行哪些程序。
- 运行级别0：系统停机状态，系统默认运行级别不能设为0，否则不能正常启动
- 运行级别1：单用户工作状态，root权限，用于系统维护，禁止远程登陆
- 运行级别2：多用户状态(没有NFS)
- 运行级别3：完全的多用户状态(有NFS)，登陆后进入控制台命令行模式
- 运行级别4：系统未使用，保留
- 运行级别5：X11控制台，登陆后进入图形GUI模式
- 运行级别6：系统正常关闭并重启，默认运行级别不能设为6，否则不能正常启动

### 用户登录系统
一般来说，用户的登录方式有三种：
1. 命令行登录
2. ssh登录
3. 图形界面登录
通过终端登录时，Linux的账号验证程序是login

### 关机指令
```shell
sync 将数据由内存同步到硬盘中。
shutdown 关机指令，你可以man shutdown 来看一下帮助文档。例如你可以运行如下命令关机：
    shutdown –h 10 ‘This server will shutdown after 10 mins’ 这个命令告诉大家，计算机将在10分钟后关机，并且会显示在登陆用户的当前屏幕中。
    Shutdown –h now 立马关机
    Shutdown –h 20:25 系统会在今天20:25关机
    Shutdown –h +10 十分钟后关机
    Shutdown –r now 系统立马重启
    Shutdown –r +10 系统十分钟后重启
reboot 就是重启，等同于 shutdown –r now
halt 关闭系统，等同于shutdown –h now 和 poweroff
```

### 系统目录结构
- bin
bin是Binary的缩写, 这个目录存放着最经常使用的命令。
- boot
这里存放的是启动Linux时使用的一些核心文件，包括一些连接文件以及镜像文件。
- dev
dev是Device(设备)的缩写, 该目录下存放的是Linux的外部设备，在Linux中访问设备的方式和访问文件的方式是相同的。
- etc
这个目录用来存放所有的系统管理所需要的配置文件和子目录。
- home
用户的主目录，在Linux中，每个用户都有一个自己的目录，一般该目录名是以用户的账号命名的。
- lib
这个目录里存放着系统最基本的动态连接共享库，其作用类似于Windows里的DLL文件。几乎所有的应用程序都需要用到这些共享库。
- lost+found
这个目录一般情况下是空的，当系统非法关机后，这里就存放了一些文件。
- media
linux系统会自动识别一些设备，例如U盘、光驱等等，当识别后，linux会把识别的设备挂载到这个目录下。
- mnt
系统提供该目录是为了让用户临时挂载别的文件系统的，我们可以将光驱挂载在/mnt/上，然后进入该目录就可以查看光驱里的内容了。
- opt
 这是给主机额外安装软件所摆放的目录。比如你安装一个ORACLE数据库则就可以放到这个目录下。默认是空的。
- proc
这个目录是一个虚拟的目录，它是系统内存的映射，我们可以通过直接访问这个目录来获取系统信息。
这个目录的内容不在硬盘上而是在内存里，我们也可以直接修改里面的某些文件，比如可以通过下面的命令来屏蔽主机的ping命令，使别人无法ping你的机器：
echo 1 > /proc/sys/net/ipv4/icmp_echo_ignore_all
- root
该目录为系统管理员，也称作超级权限者的用户主目录。
- sbin
s就是Super User的意思，这里存放的是系统管理员使用的系统管理程序。
- selinux
这个目录是Redhat/CentOS所特有的目录，Selinux是一个安全机制，类似于windows的防火墙，但是这套机制比较复杂，这个目录就是存放selinux相关的文件的。
- srv
该目录存放一些服务启动之后需要提取的数据。
- sys
这是linux2.6内核的一个很大的变化。该目录下安装了2.6内核中新出现的一个文件系统 sysfs 。
sysfs文件系统集成了下面3种文件系统的信息：针对进程信息的proc文件系统、针对设备的devfs文件系统以及针对伪终端的devpts文件系统。
该文件系统是内核设备树的一个直观反映。
当一个内核对象被创建的时候，对应的文件和目录也在内核对象子系统中被创建。
- tmp
这个目录是用来存放一些临时文件的。
- usr
这是一个非常重要的目录，用户的很多应用程序和文件都放在这个目录下，类似与windows下的program files目录。
    - usr/bin
    系统用户使用的应用程序。
    - usr/sbin
    超级用户使用的比较高级的管理程序和系统守护程序。
    - usr/src
    内核源代码默认的放置目录。
- var
这个目录中存放着在不断扩充着的东西，我们习惯将那些经常被修改的目录放在这个目录下。包括各种日志文件。

### 使用SSH进行远程登录
Linux系统中是通过ssh服务实现的远程登录功能，默认ssh服务端口号为 22
SSH也可以使用sftp传输文件
Window系统上 Linux 远程登录客户端有SecureCRT, Putty, SSH Secure Shell等

### 文件基本属性
dr-xr-xr-x  文件类型+属主权限+属组权限+其他用户权限
#### 在Linux中第一个字符代表这个文件是目录、文件或链接文件等等。
当为[ d ]则是目录
当为[ - ]则是文件；
若是[ l ]则表示为链接文档(link file)；
若是[ b ]则表示为装置文件里面的可供储存的接口设备(可随机存取装置)；
若是[ c ]则表示为装置文件里面的串行端口设备，例如键盘、鼠标(一次性读取装置)。
#### 权限
rwx 表示读写执行，有此项权限时显示对应的字符，没有权限用-表示
chmod [-R] 777 [文件或目录]  递归的赋予所有人该目录的所有权限

### 文件与目录管理
ls: 列出目录
ll:列出目录详细信息(等同于ls -l)
cd：切换目录
pwd：显示目前的目录
mkdir：创建一个新的目录
rmdir：删除一个空的目录
cp: 复制文件或目录(cp /test1/file1 /test3/file2 -r)
rm: 移除文件或目录
chmod:修改文件的权限

### Linux服务器配置
Apache等程序一般会安装在/usr/local/目录下
Apache的命令行可执行程序是/bin/apachectl
cd 到apachectl所在的目录下，
```shell
./apachectl start
./apachectl stop
./apachectl restart
```

MySQL
通过mysqld服务控制
```shell
ps -ef      查看正在运行的服务
service mysqld start
service mysqld stop
service mysqld restart
```

通过脚本或者可执行文件
```shell
$mysql_dir/bin/mysqladmin -uroot -p shutdown
$mysql_dir/bin/mysqld_safe &
```

[备份和恢复](http://www.cnblogs.com/feichexia/p/MysqlDataBackup.html)
```shell
mysqldump -uroot -pPassword --all-databases > [dump file]
mysqldump -uroot -pPassword [database name] > [dump file]
mysql -uroot -pPassword [database name] < [backup file name]
```


### 安装软件
[yum](http://www.runoob.com/linux/linux-yum.html)
yum [options] [command] [package ...]
options：可选，选项包括-h（帮助），-y（当安装过程提示选择全部为"yes"），-q（不显示安装的过程）等等。
command：要进行的操作。check update update install update list remove search clean
package操作的对象。
```shell
yum install git
```


### git
[在 Linux 下搭建 Git 服务器](http://www.cnblogs.com/dee0912/p/5815267.html)

usage: git [--version] [--exec-path[=GIT_EXEC_PATH]] [--html-path]
           [-p|--paginate|--no-pager] [--no-replace-objects]
           [--bare] [--git-dir=GIT_DIR] [--work-tree=GIT_WORK_TREE]
           [--help] COMMAND [ARGS]

The most commonly used git commands are:
   add        Add file contents to the index
   bisect     Find by binary search the change that introduced a bug
   branch     List, create, or delete branches
   checkout   Checkout a branch or paths to the working tree
   clone      Clone a repository into a new directory                           复制一个版本库到一个新的目录
   commit     Record changes to the repository
   diff       Show changes between commits, commit and working tree, etc
   fetch      Download objects and refs from another repository
   grep       Print lines matching a pattern
   init       Create an empty git repository or reinitialize an existing one    创建一个空的git存储库或重新初始化一个现有的git存储库
   log        Show commit logs
   merge      Join two or more development histories together
   mv         Move or rename a file, a directory, or a symlink
   pull       Fetch from and merge with another repository or a local branch
   push       Update remote refs along with associated objects
   rebase     Forward-port local commits to the updated upstream head
   reset      Reset current HEAD to the specified state
   rm         Remove files from the working tree and from the index
   show       Show various types of objects
   status     Show the working tree status
   tag        Create, list, delete or verify a tag object signed with GPG


--bare  使用这个选项，会创建一个只用来提交的仓库

```shell
git init gitzc  创建一个新的仓库
```