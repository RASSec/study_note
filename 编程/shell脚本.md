# shell(bash)

```shell
#!/bin/bash
```



“#!” 是一个约定的标记，它告诉系统这个脚本需要什么解释器来执行，即使用哪一种Shell





## 调试

```shell
bash -x test.sh
```

 Shell 就会把我们的脚本文件运行时的细节打印出来了，在出现错误时可以帮助我们排查问题所在。 



## 变量



### 定义变量

```shell
author="严长生"#等号两边不能有空格
echo $author
echo ${author}
```

推荐给所有变量加上花括号`{ }`，这是个良好的编程习惯。

### 修改变量的值

第二次对变量赋值时不能在变量名前加`$`，只有在使用变量时才能加`$`。

### 单引号和双引号的区别

以单引号`' '`包围变量的值时，单引号里面是什么就输出什么，即使内容中有变量和命令（命令需要反引起来）也会把它们原样输出。这种方式比较适合定义显示纯字符串的情况，即不希望解析变量、命令等的场景。如果要让单引号解析`\n\'`之类的则需要在`'前加一个$`,如

```shell
#!/bin/bash
test=$'fuckyou\nhahahha'
echo $test
test='fuckyou\nhahahah'
echo $test

result:
fuckyou hahahha
fuckyou\nhahahah
```







以双引号" "包围变量的值时，输出时会先解析里面的变量和命令，而不是把双引号中的变量名和命令原样输出。这种方式比较适合字符串中附带有变量和命令并且想将其解析后再输出的变量定义。  

### 将命令的结果赋值给变量

Shell 也支持将命令的执行结果赋值给变量，常见的有以下两种方式：

variable=`command`
variable=$(command)

只读变量
  使用 **readonly** 命令可以将变量定义为只读变量，只读变量的值不能被改变。

### 删除变量

```
unset variable_name
```

变量被删除后不能再次使用；unset 命令不能删除只读变量。

### 特殊变量列表特殊变量列表

| 变量 | 含义 |
| ---- | ---- |
| $0     |当前脚本的文件名 |
| $n | 传递给脚本或函数的参数。n 是一个数字，表示第几个参数。例如，第一个参数是$1，第二个参数是$2。 |
| $# | 传递给脚本或函数的参数个数。|
|  $\* | 传递给脚本或函数的所有参数。|
| $@ |  传递给脚本或函数的所有参数。被双引号(" ")包含时，与 $\* 稍有不同，下面将会讲到。|
| $? |上个命令的退出状态，或函数的返回值。 |
| $$ | 当前Shell进程ID。对于 Shell 脚本，就是这些脚本所在的进程ID。 |

### 命令行参数

`运行脚本时传递给脚本的参数称为命令行参数。命令行参数用 $n 表示，例如，$1 表示第一个参数，$2 表示第二个参数，依次类推。`

- `$* 和 $@ 的区别`

  `$* 和 $@ 都表示传递给函数或脚本的所有参数，不被双引号(" ")包含时，都以"$1" "$2" … "$n" 的形式输出所有参数。`

`但是当它们被双引号(" ")包含时，"$*" 会将所有的参数作为一个整体，以"$1 $2 … $n"的形式输出所有参数；"$@" 会将各个参数分开，以"$1" "$2" … "$n" 的形式输出所有参数`。



```shell
#!/bin/bash

echo "The first parameter is $1"
shift
echo "The first parameter is now $1"
#可以看到，在调用 shift 命令后，$1 对应了第二个参数，$2 对应了第三个参数，以此类推。
```





### 命令替换

命令替换的语法：

```
`command`
```

### 变量替换

可以使用的变量替换形式：
| 形式 | 说明 |
| ---- | ----|
| ${var} | 变量本来的值 |
|${var:-word} | 如果变量 var 为空或已被删除(unset)，那么返回 word，但不改变 var 的值。|
| ${var:=word} | 如果变量 var 为空或已被删除(unset)，那么返回 word，并将 var 的值设置为 word。 |
| ${var:?message} | 如果变量 var 为空或已被删除(unset)，那么将消息 message 送到标准错误输出，可以用来检测变量 var 是否可以被正常赋值。若此替换出现在Shell脚本中，那么脚本将停止运行。 |
| ${var:+word} | 如果变量 var 被定义，那么返回 word，但不改变 var 的值。 |

### 关系运算符

关系运算符只支持数字，不支持字符串，除非字符串的值是数字。

| 运算符 | 说明                                                  | 举例                       |
| ------ | ----------------------------------------------------- | -------------------------- |
| -eq    | 检测两个数是否相等，相等返回 true。                   | [ $a -eq $b ] 返回 true。  |
| -ne    | 检测两个数是否相等，不相等返回 true。                 | [ $a -ne $b ] 返回 true。  |
| -gt    | 检测左边的数是否大于右边的，如果是，则返回 true。     | [ $a -gt $b ] 返回 false。 |
| -lt    | 检测左边的数是否小于右边的，如果是，则返回 true。     | [ $a -lt $b ] 返回 true。  |
| -ge    | 检测左边的数是否大等于右边的，如果是，则返回 true。   | [ $a -ge $b ] 返回 false。 |
| -le    | 检测左边的数是否小于等于右边的，如果是，则返回 true。 | [ $a -le $b ] 返回 true。  |


- 布尔运算符
| 运算符 | 说明                                                | 举例                                     |
| ------ | --------------------------------------------------- | ---------------------------------------- |
| !      | 非运算，表达式为 true 则返回 false，否则返回 true。 | [ ! false ] 返回 true。                  |
| -o     | 或运算，有一个表达式为 true 则返回 true。           | [ $a -lt 20 -o $b -gt 100 ] 返回 true。  |
| -a     | 与运算，两个表达式都为 true 才返回 true。           | [ $a -lt 20 -a $b -gt 100 ] 返回 false。 |

- 字符串运算符

| 运算符 | 说明                                      | 举例                     |
| ------ | ----------------------------------------- | ------------------------ |
| =      | 检测两个字符串是否相等，相等返回 true。   | [ $a = $b ] 返回 false。 |
| !=     | 检测两个字符串是否相等，不相等返回 true。 | [ $a != $b ] 返回 true。 |
| -z     | 检测字符串长度是否为0，为0返回 true。     | [ -z $a ] 返回 false。   |
| -n     | 检测字符串长度是否为0，不为0返回 true。   | [ -z $a ] 返回 true。    |
| str    | 检测字符串是否为空，不为空返回 true。     | [ $a ] 返回 true。       |

- 文件测试运算符

| 操作符  | 说明                                                         | 举例                      |
| ------- | ------------------------------------------------------------ | ------------------------- |
| -b file | 检测文件是否是块设备文件，如果是，则返回 true。              | [ -b $file ] 返回 false。 |
| -c file | 检测文件是否是字符设备文件，如果是，则返回 true。            | [ -b $file ] 返回 false。 |
| -d file | 检测文件是否是目录，如果是，则返回 true。                    | [ -d $file ] 返回 false。 |
| -f file | 检测文件是否是普通文件（既不是目录，也不是设备文件），如果是，则返回 true。 | [ -f $file ] 返回 true。  |
| -g file | 检测文件是否设置了 SGID 位，如果是，则返回 true。            | [ -g $file ] 返回 false。 |
| -k file | 检测文件是否设置了粘着位(Sticky Bit)，如果是，则返回 true。  | [ -k $file ] 返回 false。 |
| -p file | 检测文件是否是具名管道，如果是，则返回 true。                | [ -p $file ] 返回 false。 |
| -u file | 检测文件是否设置了 SUID 位，如果是，则返回 true。            | [ -u $file ] 返回 false。 |
| -r file | 检测文件是否可读，如果是，则返回 true。                      | [ -r $file ] 返回 true。  |
| -w file | 检测文件是否可写，如果是，则返回 true。                      | [ -w $file ] 返回 true。  |
| -x file | 检测文件是否可执行，如果是，则返回 true。                    | [ -x $file ] 返回 true。  |
| -s file | 检测文件是否为空（文件大小是否大于0），不为空返回 true。     | [ -s $file ] 返回 true。  |
| -e file | 检测文件（包括目录）是否存在，如果是，则返回 true。          | [ -e $file ] 返回 true。  |

- Shell注释

sh里没有多行注释，只能每一行加一个#号。

### 读取输入到变量

```shell
read input
echo $input
read firstname lastname
echo "Hello $firstname $lastname !"

read -p 'Please enter your name : ' name
echo "Hello $name !"
```

#### 提示 -p

`read -p 'Please enter your name : ' name`

#### 限制字数 -n

`read -p 'Please enter your name (5 characters max) : ' -n 5 name`

#### 限制输入时间 -t

`read -p 'Please enter the code to defuse the bomb (you have 5 seconds) : ' -t 5 code
echo -e "\nBoom !"`



#### 隐藏输入内容 -s



```
用 -s 参数，我们可以隐藏输入内容。一般用不到，但是如果你想要用户输入的是一个密码，那 -s 参数还是有用的。

s 是 secret 的首字母，表示“秘密”。
```



### 数学运算

```shell
#!/bin/bash

let "a = 5"
let "b = 2"
let "c = a + b"

echo "c = $c"
```

| 运算                 | 符号 |
| :------------------- | :--- |
| 加法                 | +    |
| 减法                 | -    |
| 乘法                 | *    |
| 除法                 | /    |
| 幂（乘方）           | **   |
| 余（整数除法的余数） | %    |



> 我们只学习了整数的运算，如果你要做带小数的运算，那么需要用到 bc 命令，可以自己去查阅 ，可以用`man bc`。 



### 环境变量







## Shell字符串

字符串是shell编程中最常用最有用的数据类型（除了数字和字符串，也没啥其它类型好用了），字符串可以用单引号，也可以用双引号，也可以不用引号。单双引号的区别跟PHP类似。

- 单引号

单引号字符串的限制：

``` 
- 单引号里的任何字符都会原样输出，单引号字符串中的变量是无效的；
- 单引号字串中不能出现单引号（对单引号使用转义符后也不行）。
```

- 双引号

>双引号的优点：
>
>- 双引号里可以有变量
>- 双引号里可以出现转义字符

### 拼接字符串

```shell
   your_name="cjb"
   greeting="hello,"$your_name" !"
   greeting_l="hello,${your_name} !"
   echo $greeting $greeting_l
```

### 获取字符串长度

```bash
string="abcd"
echo ${#string} #输出 4
```

### 提取子字符串

```bash
string="alibaba is a great company"
echo ${string:1:4} #输出liba
```

### 查找子字符串

```bash

```



### 字符串替换

```shell
a=test
echo ${a//t/T}#TesT
echo ${a/t/T}#Test
#前者替换所有匹配到的字符串
#后者替换第一个匹配到的字符串

```



### 大小写转化

```shell
#!/bin/bash
var="Hello,Word"
# 把变量中的第一个字符换成大写 
echo ${var^} 
# 把变量中的所有小写字母，全部替换为大写
echo ${var^^}   
# 把变量中的第一个字符换成小写
echo ${var,}
# 把变量中的所有大写字母，全部替换为小写
echo ${var,,}
```





## Shell数组：shell数组的定义、数组长度


  bash支持一维数组（不支持多维数组），并且没有限定数组的大小。类似与C语言，数组元素的下标由0开始编号。获取数组中的元素要利用下标，下标可以是整数或算术表达式，其值应大于或等于0。

- 定义数组
  array_name=(value0 value1 value2 value3)
- 读取数组
      ${array_name[index]}
  使用@ 或 * 可以获取数组中的所有元素，例如：
- 获取数组的长度
  

```
# 取得数组元素的个数
length=${#array_name[@]}# 或者length=${#array_name[*]}
# 取得数组单个元素的长度
lengthn=${#array_name[n]}
但是当它们被双引号(" ")包含时，"${arr[*]}" 会将所有的参数作为一个整体，以"$1 $2 … $n"的形式输出所有参数；"${arr[@]}" 会将各个参数分开，以"$1" "$2" … "$n" 的形式输出所有参数
```



- 获取数组下标数组:

```shell

for i in "${!arr[@]}"; 
do 
    printf "%s\t%s\n" "$i" "${arr[$i]}"
done

```





## shell printf命令：格式化输出语句




printf 命令的语法：

```
printf  format-string  [arguments...]
```

这里仅说明与C语言printf()函数的不同：

- printf 命令不用加括号
- format-string 可以没有引号，但最好加上，单引号双引号均可。
- 参数多于格式控制符(%)时，format-string 可以重用，可以将所有参数都转换。
- arguments 使用空格分隔，不用逗号。

## Shell if else语句

注意: 

- if 和方括号之间要有空格

- `$a == $b` 之间也要有空格

- 要让字符串为空返回假,非空进行比较用

```
123;a=`ls`;b="~";if [ "${a:3:1}"x == "$b"x ]; then sleep 5 ;fi;
```





if 语句通过关系运算符判断表达式的真假来决定执行哪个分支。Shell 有三种 if ... else 语句：

- if ... fi 语句；
- if ... else ... fi 语句；
- if ... elif ... else ... fi 语句。

```bash
if [ expression ]
then
   Statement(s) to be executed if expression is true
fi
```

```bash
if [ expression ];then
   Statement(s) to be executed if expression is true
else
   Statement(s) to be executed if expression is not true
fi
```

```bash
if [ expression 1 ]
then
   Statement(s) to be executed if expression 1 is true
elif [ expression 2 ]
then
   Statement(s) to be executed if expression 2 is true
elif [ expression 3 ]
then
   Statement(s) to be executed if expression 3 is true
else
   Statement(s) to be executed if no expression is true
fi
```

### `&&,!和||`

```shell
#!/bin/bash

if [ $# -ge 1 ] && [ $1 = 'love' ]
then
    echo "Great !"
    echo "You know the password"
else
    echo "You do not know the password"
fi
if [ ! 1 -eq 1 ];then 
	echo never sucess
fi
```





## Shell test命令

Shell中的 test 命令用于检查某个条件是否成立，它可以进行数值、字符和文件三个方面的测试。

- 数值测试

| 参数 | 说明           |
| ---- | -------------- |
| -eq  | 等于则为真     |
| -ne  | 不等于则为真   |
| -gt  | 大于则为真     |
| -ge  | 大于等于则为真 |
| -lt  | 小于则为真     |
| -le  | 小于等于则为真 |



```shell
if [ $num -eq 100 ];then                                                                             ➜ 
	echo your guess is right                                                                     
else                                                                                                 
	echo your guess is wrong                                   
fi  
```





- 文件测试

| 参数                | 说明                                                         |
| ------------------- | ------------------------------------------------------------ |
| -e 文件名           | 如果文件存在则为真                                           |
| -r 文件名           | 如果文件存在且可读则为真                                     |
| -w 文件名           | 如果文件存在且可写则为真                                     |
| -x 文件名           | 如果文件存在且可执行则为真                                   |
| -s 文件名           | 如果文件存在且至少有一个字符则为真                           |
| -d 文件名           | 如果文件存在且为目录则为真                                   |
| -f 文件名           | 如果文件存在且为普通文件则为真                               |
| -c 文件名           | 如果文件存在且为字符型特殊文件则为真                         |
| -b 文件名           | 如果文件存在且为块特殊文件则为真                             |
| -L $file            | 文件是否是一个符号链接文件。L 是 link 的首字母，表示“链接”。 |
| `$file1 -nt $file2` | 文件 file1 是否比 file2 更新。nt 是 newer than 的缩写，表示“更新的”。 |
| `$file1 -ot $file2` | 文件 file1 是否比 file2 更旧。ot 是 older than 的缩写，表示“更旧的”。 |

- 字符串测试

| 参数      | 说明                 |
| --------- | -------------------- |
| =/==      | 等于则为真           |
| !=        | 不相等则为真         |
| -z 字符串 | 字符串长度伪则为真   |
| -n 字符串 | 字符串长度不伪则为真 |

## shell case esac语句

case 语句匹配一个值或一个模式，如果匹配成功，执行相匹配的命令。case语句格式如下：

``` bash
case 值 in
模式1)
    command1
    command2
    command3
    ;;
模式2）
    command1
    command2
    command3
    ;;
*)
    command1
    command2
    command3
    ;;
esac
```



```shell
#!/bin/bash

case $1 in
    "dog" | "cat" | "pig")
        echo "It is a mammal"
        ;;
    "pigeon" | "swallow")
        echo "It is a bird"
        ;;
    *)
        echo "I do not know what it is"
        ;;
esac
```



## 循环

for循环一般格式为：

```
for 变量 in 列表
do
    command1
    command2
    ...
    commandN
done
```



```shell
#!/bin/bash

for animal in 'dog' 'cat' 'pig'
do
    echo "Animal being analyzed : $animal" 
done
listfile=`ls`

for file in $listfile
do
    echo "File found : $file" 
done

for var in ${arr[@]};
do
    echo $var
done
 
遍历（带数组下标）：
for i in "${!arr[@]}"; 
do 
    printf "%s\t%s\n" "$i" "${arr[$i]}"
done
 
遍历（While循环法）：
i=0
while [ $i -lt ${#array[@]} ]
do
    echo ${ array[$i] }
    let i++
done

```



while循环用于不断执行一系列命令，也用于从输入文件中读取数据；命令通常为测试条件。其格式为：

```
while [ 条件测试 ]
do
   Statement(s) to be executed if command is true
done
```

until 循环格式为：

until 循环执行一系列命令直至条件为 true 时停止。until 循环与 while 循环在处理方式上刚好相反。一般while循环优于until循环，但在某些时候，也只是极少数情况下，until 循环更加有用。

```
until [ 条件测试 ]
do
   Statement(s) to be executed until command is true
done
```

- break,continue



### seq

```shell
seq 1 4
1
2
3
4
seq 1 2 4
1
3
for i in `seq 1 4`;do
	echo test
done
```





## 函数

Shell 函数的定义格式如下：

```
function_name () {
    list of commands
    [ return value ]
}
```

如果你愿意，也可以在函数名前加上关键字 function：

```
function function_name {
    list of commands
    [ return value ]
}
```

函数返回值，可以显式增加return语句；如果不加，会将最后一条命令运行结果作为返回值。

像删除变量一样，删除函数也可以使用 unset 命令，不过要加上 .f 选项，如下所示：

```
unset .f function_name
```

在Shell中，调用函数时可以向其传递参数。在函数体内部，通过 `$n `的形式来获取参数的值，例如，`$1`表示第一个参数，`$2`表示第二个参数...
带参数的函数示例：运行脚本：


```bash
#!/bin/bash
funWithParam(){    
	echo "The value of the first parameter is $1 !"    
	echo "The value of the second parameter is $2 !"    
	echo "The value of the tenth parameter is $10 !"    
	echo "The value of the tenth parameter is ${10} !"    echo "The value of the eleventh parameter is ${11} !"    
	echo "The amount of the parameters is $# !"  # 参数个数    echo "The string of the parameters is $* !"  # 传递给函数的所有参数}funWithParam 1 2 3 4 5 6 7 8 9 34 73
```

| 特殊变量 | 说明                                                         |
| -------- | ------------------------------------------------------------ |
| $#       | 传递给函数的参数个数。                                       |
| $*       | 显示所有传递给函数的参数。                                   |
| $@       | 与$*相同，但是略有区别，请查看[Shell特殊变量](http://c.biancheng.net/cpp/view/2739.html)。 |
| $?       | 函数的返回值。                                               |



### 局部变量和全局变量

```shell
var=123
fun_local_var(){
	local var=456
	echo "var in function:$var"
}
fun_local_var
echo "var in outside:$var"
fun_var(){
	var=456
	echo "var in function:$var"
}
echo "var in outside:$var"
#var in function:456
#var in outside:123
#var in function:456
#var in outside:456 
```



### 当函数与命令同名时,利用command来调用命令

```shell
#!/bin/bash

ls () {
    command ls -lh
}

ls
```







## 重定向

| 命令            | 说明                                               |
| --------------- | -------------------------------------------------- |
| command > file  | 将输出重定向到 file。                              |
| command < file  | 将输入重定向到 file。                              |
| command >> file | 将输出以追加的方式重定向到 file。                  |
| n > file        | 将文件描述符为 n 的文件重定向到 file。             |
| n >> file       | 将文件描述符为 n 的文件以追加的方式重定向到 file。 |
| n >& m          | 将输出文件 m 和 n 合并。                           |
| n <& m          | 将输入文件 m 和 n 合并。                           |
| << tag          | 将开始标记 tag 和结束标记 tag 之间的内容作为输入。 |

## Here Document

Here Document 目前没有统一的翻译，这里暂译为”嵌入文档“。Here Document 是 Shell 中的一种特殊的重定向方式，它的基本的形式如下：

```
command << delimiter
document
delimiter
```

它的作用是将两个 delimiter 之间的内容(document) 作为输入传递给 command。
注意：

## /dev/null 文件

如果希望执行某个命令，但又不希望在屏幕上显示输出结果，那么可以将输出重定向到 /dev/null：

```
$ command > /dev/null
```

/dev/null 是一个特殊的文件，写入到它的内容都会被丢弃；如果尝试从该文件读取内容，那么什么也读不到。但是 /dev/null 文件非常有用，将命令的输出重定向到它，会起到”禁止输出“的效果。
如果希望屏蔽 stdout 和 stderr，可以这样写：

`$ **command** > /dev/null 2>&1`

## Shell文件包含

像其他语言一样，Shell 也可以包含外部脚本，将外部脚本的内容合并到当前脚本。
Shell 中包含脚本可以使用：

```bash
. filename

或

source filename
```

两种方式的效果相同，简单起见，一般使用点号(.)，但是注意点号(.)和文件名中间有一空格。

注意：被包含脚本不需要有执行权限。




## [] ,[[]],(())

- []

即为test命令的另一种形式。

但要注意许多：

1.你必须在左括号的右侧和右括号的左侧各加一个空格，否则会报错。

2.test命令使用标准的数学比较符号来表示字符串的比较，而用文本符号来表示数值的比较。很多人会记反了。使用反了，shell可能得不到正确的结果。

3.大于符号或小于符号必须要转义，否则会被理解成重定向。

- [[]]和(())

它们分别是[ ]的针对数学比较表达式和字符串表达式的加强版。

其中(( ))，不需要再将表达式里面的大小于符号转义，除了可以使用标准的数学运算符外，还增加了以下符号：

![](https://img-blog.csdn.net/20150620201701933)

## <<< : Here string

您可以为程序提供预先制作的文本字符串，而不是键入文本。例如，使用bc这样的程序我们可以做bc <<< 5 * 4来获得该特定情况的输出，不需要以交互方式运行bc。





