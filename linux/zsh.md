# zsh

## 使用技巧

### 目录导航

当你使用命令行的时候，在不同的目录之间切换访问是最常见的工作了。 zsh 提供了一些十分有用的目录导航功能来简化这个操作。这些功能已经集成到 Oh My Zsh 中了， 而你可以用以下命令来启用它

```shell
setopt  autocd autopushd \ pushdignoredups
```

#### 切换目录

 使用了上面的配置后，你就不用输入 `cd` 来切换目录了，只需要输入目录名称，zsh 就会自动切换到这个目录中 `/tmp`=>`cd /tmp`



#### 目录回退

在想要回退的地方输入`-` 并按下tab来选择



`dirs -v `查看浏览目录历史

`~#`(`#`代表目录在列表中的序号)

### 先进的tab补全

在 Oh My Zsh 中，命令补全是默认启用的。要启用它，你只要在 `.zshrc` 文件中添加以下命令：

```
autoload -U compinit
compinit
```



在命令后输入`-`来自动补全选项

### 命令行编辑和历史命令

zsh 的命令行编辑功能也十分有用。默认条件下，它是模拟 emacs 编辑器的。如果你是跟我一样更喜欢用 vi/vim，你可以用以下命令启用 vi 的键绑定。

```
$ bindkey -v
```

如果你使用 Oh My Zsh，`vi-mode` 插件可以启用额外的绑定，同时会在你的命令提示符上增加 vi 的模式提示 —— 这个非常有用。

当启用 vi 的绑定后，你可以在命令行中使用 vi 命令进行编辑。比如，输入 `ESC+/` 来查找命令行记录。在查找的时候，输入 `n` 来找下一个匹配行，输入 `N` 来找上一个。输入 `ESC` 后，常用的 vi 命令都可以使用，如输入 `0` 跳转到行首，输入 `$` 跳转到行尾，输入 `i` 来插入文本，输入 `a` 来追加文本等等，即使是跟随的命令也同样有效，比如输入 `cw` 来修改单词。

除了命令行编辑，如果你想修改或重新执行之前使用过的命令，zsh 还提供几个常用的命令行历史功能。比如，你打错了一个命令，输入 `fc`，你可以在你偏好的编辑器中修复最后一条命令。使用哪个编辑是参照 `$EDITOR` 变量的，而默认是使用 vi。

另外一个有用的命令是 `r`， 它会重新执行上一条命令；而 `r ` 则会执行上一条包含 `WORD` 的命令。

最后，输入两个感叹号（`!!`），可以在命令行中回溯最后一条命令。这个十分有用，比如，当你忘记使用 `sudo` 去执行需要权限的命令时：

```
$ less /var/log/dnf.log
/var/log/dnf.log: Permission denied
$ sudo !!
$ sudo less /var/log/dnf.log
```

这个功能让查找并且重新执行之前命令的操作更加方便。



### 搜索历史命令

`ctrl-r`来搜索历史命令

## 主题

### 个人喜爱

obraun:

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200906210454.png)



Powerlevel10k

### 修改zsh主题

zsh显示格式化说明http://zsh.sourceforge.net/Doc/Release/Prompt-Expansion.html



#### 教程

https://printempw.github.io/zsh-prompt-theme-customization/

#### 常用命令





显示后景色

```bash
spectrum_bls
```

**显示前景色：**

```bash
spectrum_ls
```

## 主题

### windows terminal 无法正常显示powerline

原因：字体不支持，安装支持PL的字体

https://github.com/microsoft/cascadia-code/releases

