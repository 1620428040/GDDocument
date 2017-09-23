### git
[git server 搭建指南](http://www.cnblogs.com/ToDoToTry/p/3956687.html)
[Git 基础图解、分支图解、全面教程、常用命令](http://www.cnblogs.com/cheneasternsun/p/5952830.html)
[在 Linux 下搭建 Git 服务器](http://www.cnblogs.com/dee0912/p/5815267.html)

usage: git [--version] [--exec-path[=GIT_EXEC_PATH]] [--html-path]
           [-p|--paginate|--no-pager] [--no-replace-objects]
           [--bare] [--git-dir=GIT_DIR] [--work-tree=GIT_WORK_TREE]
           [--help] COMMAND [ARGS]

The most commonly used git commands are:                                    常用git命令
   add        Add file contents to the index                                    添加。将文件添加到git库中
   bisect     Find by binary search the change that introduced a bug            二分法。用于通过二分查找来查寻版本的错误
   branch     List, create, or delete branches                                  分支。监听、创建、或删除分支
   checkout   Checkout a branch or paths to the working tree                    检出。在工作树中检出一个分支或路径。用于从历史提交（或者暂存区域）中拷贝文件到工作目录，也可用于切换分支。
   clone      Clone a repository into a new directory                           复制。复制一个版本库到一个新的目录
   commit     Record changes to the repository                                  提交。记录对仓库的修改
   diff       Show changes between commits, commit and working tree, etc        对比。显示不同的提交、工作树之间的区别
   fetch      Download objects and refs from another repository                 提取。命令用于从另一个存储库下载对象和引用
   grep       Print lines matching a pattern                                    查找。使用正则表达式查找
   init       Create an empty git repository or reinitialize an existing one    初始化。创建一个空的git存储库或重新初始化一个现有的git存储库
   log        Show commit logs                                                  日志。
   merge      Join two or more development histories together                   合并。
   mv         Move or rename a file, a directory, or a symlink                  移动、重命名文件、目录或链接
   pull       Fetch from and merge with another repository or a local branch    拉取。从另一个仓库或者本地分支拉取并合并
   push       Update remote refs along with associated objects                  推送。更新远程引用
   rebase     Forward-port local commits to the updated upstream head           变基。
   reset      Reset current HEAD to the specified state                         重置。将当前的HEAD重置到指定的状态
   rm         Remove files from the working tree and from the index             移除。从仓库中移除文件
   show       Show various types of objects                                     显示。显示对象的各种信息
   status     Show the working tree status                                      状态。显示工作树的状态
   tag        Create, list, delete or verify a tag object signed with GPG       标签。创建、列出、删除或验证一个用GPG标记的标签

选项：
--version
--exec-path[=GIT_EXEC_PATH]
--html-path
-p|--paginate|--no-pager
--no-replace-objects
--bare                          使用这个选项，会创建一个只用来提交的仓库
--git-dir=GIT_DIR
--work-tree=GIT_WORK_TREE
--help
--shared

概念：
repository      版本库
branch(master)  分支。默认的分支是master
HEAD            指向当前分支的指针
working tree    工作树。git的提交是以树的形式保存的，每一次提交都会记录上一级树的识别码
remote(origin)  远程仓库。如果是从远程仓库复制来的仓库，默认是origin


### 创建服务器端的版本库用来接收推送
如果远程库不是纯版本库，进行push时会报错
refusing to update checked out branch: refs/heads/master
By default, updating the current branch in a non-bare repository
is denied, because it will make the index and work tree inconsistent
with what you pushed, and will require 'git reset --hard' to match
the work tree to HEAD.

You can set 'receive.denyCurrentBranch' configuration variable to
'ignore' or 'warn' in the remote repository to allow pushing into
its current branch; however, this is not recommended unless you
arranged to update its work tree to match what you pushed in some
other way.

To squelch this message and still keep the default behaviour, set
'receive.denyCurrentBranch' configuration variable to 'refuse'.

To 115.159.22.89:/yjdata/www/www/gittest
! [remote rejected] master -> master (branch is currently checked out)
error: failed to push some refs to 'root@115.159.22.89:/yjdata/www/www/gittest'

根据提示，
在服务器端的版本库目录下运行receive.denyCurrentBranch=ignore
每次提交更新后，需要运行git reset --hard重置HEAD指针


```shell
git init [name] --shared                    创建一个新的仓库
cd [name]
git config receive.denyCurrentBranch ignore 设置允许提交到当前分支
git reset --hard                            强制重置HEAD指针
git add -A                                  添加所有修改到代码库

```