# git 的使用

## 推荐网站
https://www.liaoxuefeng.com/wiki/896043488029600
https://git-scm.com/book/zh/v2

## git的简单使用

### 创建版本库

```shell
mkdir xxx
cd xxx
git init
```

### 把文件添加到版本库

``` shell
git add xxx #将文件提交至暂存区
git commit -m "wrote a readme file" #m后面的参数是对提交的说明
#将 暂存区的文件提交到分支里

```

### 查看最新版本与工作区的区别

`git diff HEAD -- readme.txt`

---

## 时光穿梭机

### 版本回退

``` shell
git log #查看历史记录，参数--pretty=oneline将信息浓缩在一行
```

git中HEAD表示当前版本

HEAD^表示上一个版本

HEAD^^ 表示上上个版本

``` SHELL
git reset --hard HEAD^ # 返回上一个版本
git reset --hard 版本的部分版本号 # 恢复为那个版本
git reflog # 你的每一个命令,可以用来恢复历史版本
```



#### 小结

- `HEAD`指向的版本就是当前版本，因此，Git允许我们在版本的历史之间穿梭，使用命令`git reset --hard commit_id`。
- 穿梭前，用`git log`可以查看提交历史，以便确定要回退到哪个版本。
- 要重返未来，用`git reflog`查看命令历史，以便确定要回到未来的哪个版本。

### 工作区和暂存区

#### 工作区和版本库

- 仓库设置的目录就是工作区

- 版本库：工作区下的.git

#### 暂存区

![](https://www.liaoxuefeng.com/files/attachments/919020037470528/0)

当我们 `add` 文件时，文件会从工作区上传到 stage 即为暂存区

当我们 commit 时,会把修改内容都上传到分支

### 撤销修改

- 当你改乱了工作区某个文件的内容，想直接丢弃工作区的修改时，用命令`git checkout -- file`。

- 当你不但改乱了工作区某个文件的内容，还添加到了暂存区时，想丢弃修改，分两步，第一步用命令`git reset HEAD <file>`，就回到了场景1，第二步按场景1操作。

- 已经提交了不合适的修改到版本库时，想要撤销本次提交，参考[版本回退](https://www.liaoxuefeng.com/wiki/896043488029600/897013573512192)一节，不过前提是没有推送到远程库。

### 删除文件

``` shell
# 删除本地文件
rm xxx
# 删除本地和版本库文件
rm xxx
git commit -m ""
#误删后恢复
rm xxx #误删
git checkout -- xxx

```

## 远程仓库

### 与github建立ssh连接

1. 查看在用户主目录下，看看有没有.ssh目录，如果有，再看看这个目录下有没有`id_rsa`和`id_rsa.pub`这两个文件，如果已经有了，可直接跳到下一步。如果没有，打开Shell（Windows下打开Git Bash），创建SSH Key：

   ``ssh-keygen -t rsa -C "youremail@example.com"`

2. 登陆GitHub，打开“Account settings”，“SSH Keys”页面：

   然后，点“Add SSH Key”，填上任意Title，在Key文本框里粘贴`id_rsa.pub`文件的内容

### 添加远程库

1. 在github上创建一个新的仓库
2. 按照github上的提示完成操作

### 将本地`master`分支的最新修改推送至GitHub

```shell
 git push origin master
```

### 从远程库克隆

```shell
git clone git@github.com:michaelliao/gitskills.git
```

## 分支管理

### 创建与合并分支

- 查看分支：`git branch`

- 创建分支：`git branch <name>`

- 切换分支：`git checkout <name>`

- 创建+切换分支：`git checkout -b <name>`

- 合并某分支到当前分支：`git merge <name>`

- 删除分支：`git branch -d <name>`

### 解决冲突

当Git无法自动合并分支时，就必须首先解决冲突。解决冲突后，再提交，合并完成。

解决冲突就是把Git合并失败的文件手动编辑为我们希望的内容，再提交。

用`git log --graph`命令可以看到分支合并图。

### Bug分支

1. 储存工作现场: `git stash`
2. 创建分支，并开始修复bug,合并分支并删除临时分支
3. 恢复工作现场:

```shell
git stash list#查看工作现场
git stash apply # 恢复工作区但stash内容并不删除
git stash drop # 删除stash list里的内容
git stash apply # 恢复的同时把stash内容删了
```

### 多人协作

- 创建远程非主分支 `git checkout -b dev origin/dev`

- 因此，多人协作的工作模式通常是这样：

  1. 首先，可以试图用`git push origin <branch-name>`推送自己的修改；
  2. 如果推送失败，则因为远程分支比你的本地更新，需要先用`git pull`试图合并；
  3. 如果合并有冲突，则解决冲突，并在本地提交；
  4. 没有冲突或者解决掉冲突后，再用`git push origin <branch-name>`推送就能成功！

  如果`git pull`提示`no tracking information`，则说明本地分支和远程分支的链接关系没有创建，用命令`git branch --set-upstream-to <branch-name> origin/<branch-name>`。

  这就是多人协作的工作模式，一旦熟悉了，就非常简单。

#### 小结

- 查看远程库信息，使用`git remote -v`；
- 本地新建的分支如果不推送到远程，对其他人就是不可见的；
- 从本地推送分支，使用`git push origin branch-name`，如果推送失败，先用`git pull`抓取远程的新提交；
- 在本地创建和远程分支对应的分支，使用`git checkout -b branch-name origin/branch-name`，本地和远程分支的名称最好一致；
- 建立本地分支和远程分支的关联，使用`git branch --set-upstream branch-name origin/branch-name`；
- 从远程抓取分支，使用`git pull`，如果有冲突，要先处理冲突。

### 整理提交历史

```shell
git rebase
git log --graph --pretty=oneline --abbrev-commit
```

## 标签管理

### 创建标签

- 命令`git tag <tagname>`用于新建一个标签，默认为`HEAD`，也可以指定一个commit id；
- 命令`git tag -a <tagname> -m "blablabla..."`可以指定标签信息；
- 命令`git tag`可以查看所有标签。

### 操作标签

- 命令`git push origin <tagname>`可以推送一个本地标签；
- 命令`git push origin --tags`可以推送全部未推送过的本地标签；
- 命令`git tag -d <tagname>`可以删除一个本地标签；
- 命令`git push origin :refs/tags/<tagname>`可以删除一个远程标签。

## 忽略特殊文件

- 忽略文件的原则是：

1. 忽略操作系统自动生成的文件，比如缩略图等；
2. 忽略编译生成的中间文件、可执行文件等，也就是如果一个文件是通过另一个文件自动生成的，那自动生成的文件就没必要放进版本库，比如Java编译产生的`.class`文件；
3. 忽略你自己的带有敏感信息的配置文件，比如存放口令的配置文件。

- 不需要从头写`.gitignore`文件，GitHub已经为我们准备了各种配置文件，只需要组合一下就可以使用了。所有配置文件可以直接在线浏览：https://github.com/github/gitignore
- 忽略某些文件时，需要编写`.gitignore`；
- `.gitignore`文件本身要放到版本库里，并且可以对`.gitignore`做版本管理！

- 有些时候，你想添加一个文件到Git，但发现添加不了，原因是这个文件被`.gitignore`忽略了,如果你确实想添加该文件，可以用`-f`强制添加到Git：`git add -f App.class`

## 配置命令别名

`git config --global alias.别名 '替换内容'`

配置所在文件 .git/config 和 *.gitconfig* 

推荐命令别名:

```
git config --global alias.lg "log --color --graph --pretty=format:'%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset' --abbrev-commit"
```



## 搭建Git服务器

在[远程仓库](https://www.liaoxuefeng.com/wiki/896043488029600/896954117292416)一节中，我们讲了远程仓库实际上和本地仓库没啥不同，纯粹为了7x24小时开机并交换大家的修改。

GitHub就是一个免费托管开源代码的远程仓库。但是对于某些视源代码如生命的商业公司来说，既不想公开源代码，又舍不得给GitHub交保护费，那就只能自己搭建一台Git服务器作为私有仓库使用。

搭建Git服务器需要准备一台运行Linux的机器，强烈推荐用Ubuntu或Debian，这样，通过几条简单的`apt`命令就可以完成安装。

假设你已经有`sudo`权限的用户账号，下面，正式开始安装。

第一步，安装`git`：

```
$ sudo apt-get install git
```

第二步，创建一个`git`用户，用来运行`git`服务：

```
$ sudo adduser git
```

第三步，创建证书登录：

收集所有需要登录的用户的公钥，就是他们自己的`id_rsa.pub`文件，把所有公钥导入到`/home/git/.ssh/authorized_keys`文件里，一行一个。

第四步，初始化Git仓库：

先选定一个目录作为Git仓库，假定是`/srv/sample.git`，在`/srv`目录下输入命令：

```
$ sudo git init --bare sample.git
```

Git就会创建一个裸仓库，裸仓库没有工作区，因为服务器上的Git仓库纯粹是为了共享，所以不让用户直接登录到服务器上去改工作区，并且服务器上的Git仓库通常都以`.git`结尾。然后，把owner改为`git`：

```
$ sudo chown -R git:git sample.git
```

第五步，禁用shell登录：

出于安全考虑，第二步创建的git用户不允许登录shell，这可以通过编辑`/etc/passwd`文件完成。找到类似下面的一行：

```
git:x:1001:1001:,,,:/home/git:/bin/bash
```

改为：

```
git:x:1001:1001:,,,:/home/git:/usr/bin/git-shell
```

这样，`git`用户可以正常通过ssh使用git，但无法登录shell，因为我们为`git`用户指定的`git-shell`每次一登录就自动退出。

第六步，克隆远程仓库：

现在，可以通过`git clone`命令克隆远程仓库了，在各自的电脑上运行：

```
$ git clone git@server:/srv/sample.git
Cloning into 'sample'...
warning: You appear to have cloned an empty repository.
```

剩下的推送就简单了。

## git 设置代理

代理格式 `[protocol://][user[:password]@]proxyhost[:port]`
参考 https://git-scm.com/docs/git-config

设置 HTTP 代理：

```
git config --global http.proxy http://127.0.0.1:8118
git config --global https.proxy http://127.0.0.1:8118
```

设置 SOCKS5 代理：

```
git config --global http.proxy socks5://127.0.0.1:1080
git config --global https.proxy socks5://127.0.0.1:1080
```

Git 取消代理设置：

```
git config --global --unset http.proxy
git config --global --unset https.proxy
```

## git的坑

1. git问题：fatal：HttpRequestException encountered

解决：Github 禁用了TLS v1.0 and v1.1，必须更新Windows的git凭证管理器 
通过此网址https://github.com/Microsoft/Git-Credential-Manager-for-Windows/releases/



