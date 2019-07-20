# git 的使用

---

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

