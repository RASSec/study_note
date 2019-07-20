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
git add xxx
git commit -m "wrote a readme file" #m后面的参数是对提交的说明

```

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