# 阅读笔记

## 资源

源代码：https://github.com/goinaction/code

go代码在线执行和分享：http://play.golang.org/

获取包文档：访问http://golang.org/pkg/fmt/或者在终端输入godoc fmt 来了解更多关于fmt 包的细节。



## 快速开始一个Go程序



Go程序以main函数作为程序入口，如果**main 函数**不在**main 包**里，构建工具就不会生成可执行的文件。



程序中每个代码文件里的init 函数都会在main 函数执行前调用。



所有处于同一个文件夹里的代码文件，必须使用同一个包名。按照惯例，包和文件夹同名。



### import





关键字import 就是导入一段代码

```go
import(
	"log"
    "os"
    _ "github.com/goinaction/code/chapter2/sample/matchers"
    "github.com/goinaction/code/chapter2/sample/search"
)
```



Go 编译器**不允许声明导入某个包却不使用**。**下划线**让编译器接受这类导入，并且调用**对应包内的所有代码文件里定义的init 函数**。

与第三方包不同，从标准库中导入代码时，只需要给出要导入的包名。编译器查找包的时候，
总是会到GOROOT 和GOPATH 环境变量引用的位置去查找



### 变量

```go
var matchers = make(map[string]Matcher)
```

这个变量没有定义在任何函数作用域内，所以会被当成**包级变量**。



在Go 语言里，标识符要么从包里公开，要么不从包里公开。当代码导入了一个包时，程序
可以直接访问这个包中任意一个公开的标识符。**公开标识符以大写字母开头**。**以小写字母开头的**
**标识符是不公开的**，不能被其他包中的代码直接访问。



```go
make(map[string]Matcher)
```



map 是Go 语言里的一个**引用类型**，需要使用make 来构造。如果不先构造map 并将构造后
的值赋值给变量，会在试图使用这个map 变量时收到出错信息。这是因为**map 变量默认的零值**
**是nil**。



```go
feeds, err := RetrieveFeeds()
```


这里可以看到简化变量声明运算符（:=）。这个运算符用于声明一个变量，同时给这个变量赋予初始值

根据经验，如果**需要声明初始值为零值的变量**，应该**使用var 关键字**声明变量；如果提供确切的非零值初始化变量或者使用函数返回值创建变量，应该使用简化变量声明运算符。



### 函数



```go
func Run(searchTerm string)([]*Feed, error) {
```

Go 语言使用关键字func 声明函数,关键字后面紧跟着函数名、参数以及返回值



`defer file.Close()`

关键字 defer 会安排随后的函数调用在函数返回时才执行(在结束时关闭file)



```go
type defaultMatcher struct{}
func (m defaultMatcher) Search(feed *Feed, searchTerm string) ([]*Result, error) {
    return nil, nil
}

```

我们使用一个空结构声明了一个名叫defaultMatcher 的结构类型。空结构
在创建实例时，不会分配任何内存。这种结构很适合创建没有任何状态的类型。对于默认匹配器
来说，不需要维护任何状态，所以我们只要实现对应的接口就行。

defaultMatcher 类型实现Matcher 接口的代码。实现
接口的方法Search 只返回两个nil 值。其他的实现，如RSS 匹配器的实现，会在这个方法里
使用特定的业务逻辑规则来处理搜索。
Search 方法的声明也声明了defaultMatcher 类型的值的接收者



```go
func (m defaultMatcher) Search(feed *Feed, searchTerm string)
// 声明一个指向defaultMatcher 类型值的指针
dm := new(defaultMatch)
// 编译器会解开dm 指针的引用，使用对应的值调用方法
dm.Search(feed, "test")
// 方法声明为使用指向defaultMatcher 类型值的指针作为接收者
func (m *defaultMatcher) Search(feed *Feed, searchTerm string)
// 声明一个defaultMatcher 类型的值
var dm defaultMatch
// 编译器会自动生成指针引用dm 值，使用指针调用方法
dm.Search(feed, "test")
```



```go
// 方法声明为使用指向defaultMatcher 类型值的指针作为接收者
func (m *defaultMatcher) Search(feed *Feed, searchTerm string)
// 通过interface 类型的值来调用方法
var dm defaultMatcher
var matcher Matcher = dm // 将值赋值给接口类型
matcher.Search(feed, "test") // 使用值来调用接口方法
> go build
cannot use dm (type defaultMatcher) as type Matcher in assignment
// 方法声明为使用defaultMatcher 类型的值作为接收者
func (m defaultMatcher) Search(feed *Feed, searchTerm string)
// 通过interface 类型的值来调用方法
var dm defaultMatcher
var matcher Matcher = &dm // 将指针赋值给接口类型
matcher.Search(feed, "test") // 使用指针来调用接口方法
> go build
Build Successful
```





### 切片

切片是一种实现了一个动态数组的引用类型。在Go 语言里可以用切片来操作一组数据



```go
feeds, err := RetrieveFeeds()
if err != nil{
    log.Fatal(err)
}
```



不仅仅是Go语言，很多语言都允许一个函数返回多个值。一般会像RetrieveFeeds函数这
样声明一个函数返回一个值和一个错误值。如果发生了错误，永远不要使用该函数返回的另一个
值这里可以看到简化变量声明运算符（:=）。这个运算符用于声明一个变量，同时给这个变量
。这时必须忽略另一个值，否则程序会产生更多的错误，甚至崩溃。



关键字range 可以用于迭代数组、字符串、切片、映射和通道。使用for range 迭代切片时，每次迭代会返回两个值。第一个值是迭代的元素在切片里的索引位置，第二个值是元素值的一个副本



```go
for key, value := range feeds {
    fmt.Print(key);
    fmt.Print(":");
    fmt.Print(value);
}
```



`result,_:=test()`

下划线标识符的作用是占位符，占据了保存range 调用返回的索引值的变量的位置。如果
要调用的函数返回多个值，而又不需要其中的某个值，就可以使用下划线标识符将其忽略。



```go
matcher, exists := matchers[feed.Type]
if !exists {
    matcher = matchers["default"]
}
```

查找map 里的键时,有两个选择：要么赋值给一个变量，要么为了精确查找，赋值给两个变量。赋值给两个变量时第一个值和赋值给一个变量时的值一样，是map 查找的结果值。如果**指定了第二个值**，就会返回一个布尔标志，来表示**查找的键是否存在于map 里**。**如果这个键不存在，map 会返回其值类型**
**的零值作为返回值，如果这个键存在，map 会返回键所对应值的副本**。





### goroutine

Go 程序终止时，还会关闭所有之前启动且还在运行的goroutine。

非常推荐使用WaitGroup 来跟踪goroutine 的工作是否完成。WaitGroup 是一个计数信号量，我们可以利用它来统计所有的goroutine 是不是都完成了工作



```go
go func(matcher Matcher, feed *Feed) {
    Match(matcher, feed, searchTerm, results)
    waitGroup.Done()
}(matcher, feed)
```

使用关键字go 启动一个goroutine，并对这个goroutine 做并发调度





### 结构

```go
type Feed struct {
    Name string `json:"site"`
    URI string `json:"link"`
    Type string `json:"type"`
}
```

每个字段的声明最后` 引号里的部分被称作标记（tag）。这个标记里描述了JSON 解码的元数据，
用于创建Feed 类型值的切片。每个标记将结构类型里字段对应到JSON 文档里指定名字的字段。



### 接口

```go
type Matcher interface {
    Search(feed *Feed, searchTerm string) ([]*Result, error)
}

```

interface 关键字声明了一个接口，这个接口声明了结构类型或者具名类型需要实现的行为。一个接口的行为最终由在这个接口类型中声明的方法决定。

命名接口的时候，也需要遵守Go 语言的命名惯例。如果接口类型只包含一个方法，那么这
个类型的名字以er 结尾。如果接口类型内部声明了多个方法，其名字需要与其行为关联。



## 打包和工具链

所有的.go 文件，除了空行和注释，都应该在第一行声明自己所属的包。不能把多个包放到同一个目录中，也不能把同一个包的文件分拆到多个不同目录中。



### 包

包名惯例：给包命名的惯例是使用包所在目录的名字。这让用户在导入包的时候，就能清晰地知道包名。



在Go 语言里，命名为main 的包具有特殊的含义。Go 语言的编译程序会试图把这种名字的包编译为二进制可执行文件。**所有用Go 语言编译的可执行程序都必须有一个名叫main 的包**。

当编译器发现某个包的名字为main 时，它一定也会发现名为main()的函数，否则不会创建可执行文件。



### 导入

```go
import (
	"fmt"
)
```

编译器会使用Go 环境变量设置的路径，通过引入的相对路径来查找磁盘上的包。标准库中的包会在安装Go 的位置找到。Go 开发者创建的包会在GOPATH 环境变量指定的目录里查找。GOPATH 指定的这些目录就是开发者的个人工作空间。

如果Go 安装在/usr/local/go，并且环境变量GOPATH 设置为/home/myproject:/home/mylibraries，编译器就会按照下面的顺序查找net/http 包：

/usr/local/go/src/pkg/net/http
/home/myproject/src/net/http
/home/mylibraries/src/net/http



#### 远程导入

`import "github.com/spf13/viper"`

使用go get 来获取这个url的包

#### 命名导入

既需要network/convert 包来转换从网络读取的数据，又需要file/convert 包来转换从文本文件读取的数据时，就会同时导入两个名叫convert 的包

```go
package main
import (
	"fmt"
    myfmt "mylib/fmt"
)
```



**导入的包必须使用否则就会编译失败**

那么如果有时候我们需要导入一个包但是并没有使用到他的标识符呢？

我们可以利用`_`来命名导入

>空白标识符:下划线字符（_）在Go 语言里称为空白标识符，有很多用法。这个标识符用来抛弃不
>想继续使用的值，如给导入的包赋予一个空名字，或者忽略函数返回的你不感兴趣的值。





### 函数init

每个包可以包含任意多个init 函数，这些函数都会在程序执行开始的时候被调用。**所有被编译器发现的init 函数都会安排在main 函数之前执行**。init 函数用在设置包、初始化变量或者其他要在程序运行前优先完成的引导工作。

```go
package main
import (
	"database/sql"
    _ "github.com/goinaction/code/chapter3/dbdriver/postgres"
    
)
func main(){
    sql.Open("postgres","mydb")
}
```



### 使用Go的工具



#### go vet

这个命令不会帮开发人员写代码，但如果开发人员已经写了一些代码，vet 命令会帮开发人
员检测代码的常见错误



#### go fmt

fmt 工具会将开发人员的代码布局成和Go 源代码类似的风格



#### go doc

`go doc tar`:获取tar包的帮助



#### godoc

启动文档服务

`godocc -http=:6060`



### 创建代码库的约定

1. 包应该在代码库的根目录中
2. 包可以非常小
3. 对代码执行go fmt
4. 给代码写文档



### 依赖管理



godep

gb



## 数组切片和映射



### 数组

在Go 语言里，数组是一个长度固定的数据类型，用于存储一段具有相同的类型的元素的连续块。数组存储的类型可以是内置类型，如整型或者字符串，也可以是某种结构类型。



```go
var array [5]int
array := [5]int{10, 20, 30, 40, 50}
array := [...]int{10, 20, 30, 40, 50}//，Go 语言会根据初始化时数组元素的数量来确定该数组的长度
array := [5]int{1: 10, 2: 20}
```



```go
array := [5]int{10, 20, 30, 40, 50}
array[2] = 35

array := [5]*int{0: new(int), 1: new(int)}
*array[0] = 10

```



```go
var array1 [5]string
array2 := [5]string{"Red", "Blue", "Green", "Yellow", "Pink"}
array1 = array2//数组变量的类型包括数组长度和每个元素的类型。只有这两部分都相同的数组，才是类型相同的数组，才能互相赋值

var array1 [3]*string
array2 := [3]*string{new(string), new(string), new(string)}
*array2[0] = "Red"
*array2[1] = "Blue"
*array2[2] = "Green"
array1 = array2
```



多维数组

```go
var array [4][2]int// 声明一个二维整型数组，两个维度分别存储4 个元素和2 个元素
array := [4][2]int{{10, 11}, {20, 21}, {30, 31}, {40, 41}}
array := [4][2]int{1: {20, 21}, 3: {40, 41}}
array := [4][2]int{1: {0: 20}, 3: {1: 41}}

array1 = array2
var array3 [2]int = array1[1]
var value int = array1[1][0]
```



利用数组指针来传递数组加快速度

```go
var array [1e6]int
// 将数组的地址传递给函数foo
foo(&array)
// 函数foo 接受一个指向100 万个整型值的数组的指针
func foo(array *[1e6]int) {
	...
}
```



### 切片

切片是一种数据结构，这种数据结构便于使用和管理数据集合。切片是围绕**动态数组**的概念
构建的，可以按需自动增长和缩小。切片的动态增长是通过内置函数append 来实现的。这个函
数可以快速且高效地增长切片。还可以通过对切片再次切片来缩小一个切片的大小

切片是一个很小的对象，对底层数组进行了抽象，并提供相关的操作方法。切片有3 个字段
的数据结构，这些数据结构包含Go 语言需要操作底层数组的元数据

![image-20201211154255878](https://raw.githubusercontent.com/Explorersss/photo/master/20201211154256.png)

长度和容量的区别：当长度大于容量时，便会扩大容量

不允许创建容量小于长度的切片

#### 初始化

make

```go
slice := make([]string, 5)//当使用make 时，需要传入一个参数，指定切片的长度,如果只指定长度，那么切片的容量和长度相等
slice := make([]int, 3, 5)//创建一个整型切片,其长度为3 个元素，容量为5 个元素
```



切片字面量

```go
slice := []string{"Red", "Blue", "Green", "Yellow", "Pink"}//长度和容量都是5 个元素
slice := []int{10, 20, 30}//其长度和容量都是3 个元素
slice := []string{99: ""}
```



nil切片

```go
var slice []int
slice := make([]int, 0)
slice := []int{}
```





#### 使用切片

赋值和取值与数组的方法完全一样

```go
slice := []int{10, 20, 30, 40, 50}
slice[1] = 25
```



切片：

```go
slice := []int{10, 20, 30, 40, 50}
newSlice := slice[1:3]//创建一个新切片,其长度为2 个元素，容量为4 个元素
```

**执行完切片动作后,我们有了两个切片，它们共享同一段底层数组**，但通过不同的切片会看到底层数组的不同部分





![image-20201211162547373](https://raw.githubusercontent.com/Explorersss/photo/master/20201211162547.png)





对底层数组容量是k 的切片slice[i:j]来说

长度: j - i
容量: k - i



#### 切片增长



使用切片的一个好处是，可以按需增加切片的容量

要使用 append，需要一个被操作的切片和一个要追加的值，返回一个包含修改结果的新切片

又是切片增长会覆盖原来的值如下面的代码：append(newSlice,60),slice[4]也会被改成60，这是因为共享同一个数组，但是如果newSlice的容量不够了，那么就不是共享一个数组，append(newSlice,60)也就不会影响到slice[4]

```go
slice := []int{10, 20, 30, 40, 50}
newSlice := slice[1:3]
newSlice = append(newSlice, 60)
```

![image-20201211173836192](https://raw.githubusercontent.com/Explorersss/photo/master/20201211173922.png)

因为newSlice **在底层数组里还有额外的容量可用**，append 操作**将可用的元素合并到切片的长度**，并对其进行赋值。由于和原始的slice 共享同一个底层数组，slice 中索引为3 的元素的值也被改动了。
如果**切片的底层数组没有足够的可用容量**，append 函数会创建一个新的底层数组，将被引用的现有的值**复制到新数组**里，再追加新的值





在创建切片时，还可以使用之前我们没有提及的第三个索引选项。第三个索引可以用来控制
新切片的容量。其目的并不是要增加容量，而是要限制容量。可以看到，允许限制新切片的容量
为底层数组提供了一定的保护，可以更好地控制追加操作。

```go
slice := source[2:3:4]//将第三个元素切片，并限制容量
//其长度为1 个元素，容量为2 个元素

```

对于 slice[i:j:k] 或 [2:3:4]

长度: j – i 或3 - 2 = 1
容量: k – i 或4 - 2 = 2



设置长度和容量一样：

```go
source := []string{"Apple", "Orange", "Plum", "Banana", "Grape"}
// 对第三个元素做切片，并限制容量
// 其长度和容量都是1 个元素
slice := source[2:3:3]
// 向slice 追加新字符串
slice = append(slice, "Kiwi")
```



此时slice和source并没有共享一个数组，因为容量不够新建了一个数组，并将值拷贝过去

考虑如下代码

```go
package main
import (
	"fmt"
)
func main(){
	slice := []int{1,2,3,4,5}
	test := slice[2:3:3]
	test[0]=999999
	test = append(test,99999)

	fmt.Println(slice)
	fmt.Println(test)
}
```

和

```go
package main
import (
	"fmt"
)
func main(){
	slice := []int{1,2,3,4,5}
	test := slice[2:3:3]
	test = append(test,99999)
    test[0]=999999

	fmt.Println(slice)
	fmt.Println(test)
}
```



#### 迭代切片

range配合关键字for 来迭代切片里的元素

```go
slice := []int{10, 20, 30, 40}
for index, value := range slice {
	fmt.Printf("Index: %d Value: %d\n", index, value)
}
```

**range 创建了每个元素的副本**，而不是直接返回对该元素的引用



```go
for index := 2; index < len(slice); index++ {
	fmt.Printf("Index: %d Value: %d\n", index, slice[index])
}
```

函数len返回切片长度，函数cap返回切片容量



#### 多维切片



```go
slice := [][]int{{10}, {100, 200}}
```

![image-20201211175058975](https://raw.githubusercontent.com/Explorersss/photo/master/20201211175059.png)



### 映射

映射是一种数据结构，用于存储一系列**无序**的键值对。



#### 创建和初始化



```go
dict := make(map[string]int)//第一个是类型是键,第二个类型是值

// 创建一个映射，键和值的类型都是string
// 使用两个键值对初始化映射
dict := map[string]string{"Red": "#da1337", "Orange": "#e95a22"}
```

映射的键可以是**任何值**，只要这个值可以使用==运算符做比较，**除了**切片、函数以及包含切片的结构类型这些**具有引用语义的类型**



#### 使用映射



```go
colors := map[string]string{}
colors["Red"] = "#da1337"
var colors map[string]string // 创建一个nil的映射，对nil 映射赋值时的语言运行时错误
value, exists := colors["Blue"]
if exists {
	fmt.Println(value)
}

value := colors["Blue"]//在 Go 语言里，通过键来索引映射时，即便这个键不存在也总会返回一个值.在这种情况下,返回的是该值对应的类型的零值
// 这个键存在吗？
if value != "" {
	fmt.Println(value)
}
for key, value := range colors {
	fmt.Printf("Key: %s Value: %s\n", key, value)
}

delete(colors, "Coral")//从映射中删除一项

```



#### 在函数间传递映射

在函数间传递映射**并不会制造出该映射的一个副本**。实际上，当传递映射给一个函数，并对这个映射做了修改时，**所有对这个映射的引用都会察觉到这个修改**





## Go 语言的类型系统

### 自定义类型

#### 使用struct关键字



```go
type user struct {
	username string
	password string
}
```

类型的零值为其内每一个元素对应的零值构成

```go
var admin user
u := user{"admin","admin"}
u := user{
    username: "admin",
    passsword: "admin"
}

type privilege struct{
    u user
    privi int
}

admin := privilege{
    u:user{
        "admin",
        "admin"
    }
    privi: 0
}



```

#### 基于其他类型创建



```go
type Duration int64
```

虽然int64 是基础类型，Go 并不认为Duration 和int64 是同一种类型。这两个类型是完全不同的有区别的类型。



### 方法

方法能**给用户定义的类型添加新的行为**。方法实际上也是函数，只是在声明时，**在关键字func 和方法名之间增加了一个参数**



方法的声明：

```go
func (u user)modify(){
    
}
```

或

```go
func (u * user)modify(){
    
}
```

第一种对变量的修改不会应用到接收者，而第二种对变量的修改会应用到接收者

关键字func 和函数名之间的参数被称作接收者，将函数与接收者的类型绑在一起

如果一个函数**有接收者**，这个函数就被称为**方法**

Go 语言里有两种类型的接收者：值接收者(第一种声明)和指针接收者(第二种声明)

如果使用**值接收者**声明方法，调用时**会使用这个值的一个副本来执行**

```go
admin:=user{"admin","admin"}
admin.modify()
//或
admin := &user{"admin","admin"}
admin.modify()
```

对值调用指针接收者方法会做出如下转化

```go
(&admin).modify()
```



对指针调用值接收者方法会做出如下转化

```go
(*admin).modify()
```

Go语言**既允许使用值**，也**允许使用指针来调用方**法，**不必严格符合接收者的类型**。



### 引用传递还是值传递的思考

内置类型通常都是值传递

引用类型：

结构类型：

是使用值接收者还是指针接收者，不应该由该方法是否修改了接收到的值来决定。这个决策
应该基于该类型的本质

这条规则的一个例外是，需要让类型值符合某个接口的时候，即便类型
的本质是非原始本质的，也可以选择使用值接收者声明方



### 接口

如果用户定义的类型实现了某个接口类型声明的一组方法，那么这个用户定义的类型的值就可以赋给这个接口类型的值。

对接口值方法的调用会执行接口值里存储的用户定义的类型的值对应的方法

接口变量的内部布局

![image-20201212193426456](https://raw.githubusercontent.com/Explorersss/photo/master/20201212193426.png)





一个指针赋值给接口之后接口变量的内部布局

![image-20201212193504822](https://raw.githubusercontent.com/Explorersss/photo/master/20201212193504.png)



#### 方法集

方法集定义了一组关联到给定类型的值或者指针的方法。定义方法时使用的**接收者的类型**决定了这个方法是关联到值，还是关联到指针，还是两个都关联。

![image-20201212193711772](https://raw.githubusercontent.com/Explorersss/photo/master/20201212193711.png)

这个规则说，如果**使用指针接收者来实现一个接口**，那么只有指向那个类型的指针才能够实现对应的接口。如果**使用值接收者来实现一个接口**，那么那个类型的值和指针**都**能够实现对应的接口



#### demo



```go
package main

import "fmt"

type user struct {
	username string
	password string
}
type userLogin interface {
	login(string,string)bool
}

func (u * user) login(username string,password string) bool{
	if u.username == username && u.password == password{
		return true
	}
	return false
}
func login(u userLogin,username string,password string) bool{
	return u.login(username,password)
}
func main(){
	u := &user{"admin","123456"}
	var username string
	var password string
	fmt.Print("请输入账号:")
	fmt.Scanf("%s",&username)
	fmt.Print("请输入密码:")
	fmt.Scanf("%s",&password)
	if login(u,username,password){
		fmt.Println("登入成功")
	}else{
		fmt.Println("登入失败")
	}
}
```



### 嵌入类型

Go 语言允许用户扩展或者修改已有类型的行为。通过嵌入类型，与内部类型相关的标识符会提升到外部类型上。这些被提升的标识符就像直接声明在外部类型里的标识符一样，也是外部类型的一部分。

eg

```go
type user struct {
	username string
	password string
}
type admin struct {
	user//嵌入类型
	level int
}
type userShow interface {
	show()
}

func (u user) show() {
	fmt.Printf("this is a user account, username: %s ,password: %s\n",u.username,u.password)
}
func main(){
	u := &admin{user{"admin","123456"},0}

	u.show()//与内部类型相关的标识符会提升到外部类型上
	u.user.show()//也可以通过访问内部类型，再访问对应的行为
}
```



要嵌入一个类型，只需要**声明这个类型的名字**就可以了

对外部类型来说，内部类型总是存在的。这就意味着，虽然没有指定内部类型对应的字段名，还是**可以使用内部类型的类型名**，来访问到内部类型的值。



```go
func showUser(u userShow){
	u.show()
}
func main(){
	u := &admin{user{"admin","123456"},0}
	showUser(u)
}
```

我们将这个外部类型变量的地址传给showUser函数。编译器认为这个指针实现了userShow接口，并接受了这个值的传递。不过如果看一下整个示例程序，就会发现admin 类型并没有实现这个接口。

由于内部类型的提升，**内部类型实现的接口会自动提升到外部类型**。这意味着**由于内部类型的实现，外部类型也同样实现了这个接口**



**重写一个行为**



```go
package main

import "fmt"

type user struct {
	username string
	password string
}
type admin struct {
	user
	level int
}
type userShow interface {
	show()
}

func (u user) show() {
	fmt.Printf("this is a user account, username: %s ,password: %s\n",u.username,u.password)
}

func (u admin) show(){
	u.user.show()
	fmt.Printf("this is a admin account, username: %s ,password: %s\n",u.username,u.password)

}

func showUser(u userShow){
	u.show()
}

func main(){
	u := &admin{user{"admin","123456"},0}

	showUser(u)
}
```

虽然user的show被admin给覆盖了，但是我们仍然能通过`admin.user.show()`来调用



### 公开或未公开的标识符

当一个标识符的名字**以小写字母开头**时，这个标识符就是**未公开**的，即包外的代码不可见。如果一个标识符以**大写字母开头**，这个标识符就是**公开**的，即被包外的代码可见

例子

```go
package main
import(
	"fmt"
    "github.com/goinaction/code/chapter5/listing64/counters"
)
func main(){
    counters := counters.New(1)
}
```



```go
package counters

type alertCounter int;
func New(int value) alertCounter{
    return alertCounter(value)
}
```

在main中直接访问alertCounter会报错

通过调用New返回alertCounter的实例并通过短变量声明操作符赋值，就可以了

要让这个行为可行，需要两个理由。第一，公开或者未公开的标识符，不是一个值。第二，短变量声明操作符，有能力捕获引用的类型，并创建一个未公开的类型的变量。永远不能显式创建一个未公开的类型的变量，不过短变量声明操作符可以这么做



```go
package entities
type User struct{
    Name string
    email string
}
```



```go
package main
import(
	"github.com/goinaction/code/chapter5/listing71/entities"
)
func main(){
    u := entities.User{
        Name:"Bill",
        email:"bill@email.com"
    }
}
```

上述代码会报错，8行的代码试图初始化未公开的字段email


```go
package main

import (
	"github.com/explorersss/myproject/test"
)


func main(){
	u := test.Admin{}
	u.Email = "test"
	u.Name = "test"
}
```

```go
package test
type user struct {
	Name string
	Email string
}
type Admin struct {
	user
}
```

这个代码可以正常运行

声明了一个未公开的结构类型user。这个类型包括两个公开
的字段Name 和Email。在第12 行，声明了一个公开的结构类型Admin。Admin 有一个名为
Rights 的公开的字段，而且嵌入一个未公开的user 类型。

由于内部类型user 是未公开的，这段代码无法直接通过结构字面量的方式初
始化该内部类型。不过，即便内部类型是未公开的，内部类型里声明的字段依旧是公开的。既然
内部类型的标识符提升到了外部类型，这些公开的字段也可以通过外部类型的字段的值来访问。



## 并发

Go语言里的并发指的是能让某个函数独立于其他函数运行的能力。

### 并行与并发

当一个函数创建为goroutine时，Go 会将其视为一个独立的工作单元。这个单元会被调度到可用的逻辑处理器上执行。。这个调度器在操作系统之上，将操作系统的线程与语言运行时的逻辑处理器绑定，并在逻辑处理器上运行goroutine。调度器在任何给定的时间，都会全面控制哪个gorouting要在哪个逻辑处理器上运行。

Go 语言的**并发同步模型来自一个叫作通信顺序进程**（Communicating Sequential Processes，CSP）
的范型（paradigm）

用于在goroutine 之间同步和传递数据的关键数据类型叫作通道（channel）。

Go
语言的运行时会在逻辑处理器上调度goroutine来运行。每个逻辑处理器都分别绑定到单个操作系统线程

![image-20201213144043289](https://raw.githubusercontent.com/Explorersss/photo/master/20201213144043.png)

调度器对可以创建的逻辑处理器的数量没有限制，但语言运行时默认限制每个程序最多创
10 000个线程。

如果希望goroutine并行，必须使用多于一个逻辑处理

![image-20201213144200059](https://raw.githubusercontent.com/Explorersss/photo/master/20201213144200.png)





### goroutine



#### demo

```go
package main
import(
	"fmt"
    "runtime"
    "sync"
)
func main(){
    runtime.GOMAXPROCS(1)//设置逻辑处理器的数量
    //runtime.GOMAXPROCS(runtime.NumCPU())
    var wg sync.WaitGroup// wg 用来等待程序完成
    wg.Add(2)// 计数加 2，表示要等待两个goroutine
    go func() {
        defer wg.Done()// 在函数退出时调用Done 来通知main 函数工作已经完成
        fmt.Print("goroutine 1")
    }()
    go func() {
        defer wg.Done()// 在函数退出时调用Done 来通知main 函数工作已经完成
        fmt.Print("goroutine 2")
    }()
    wg.Wait()// 等待 goroutine 结束
    
}
```

WaitGroup是一个计数信号，可以用来记录并维护运行的goroutine.。**如果WaitGroup的值大于0**，**Wait方法就会阻塞**


defer关键字会修改函数调用时机，在正在执行的函数返回时才真正调用defer声明的函数

**goroutine终止时无法获取函数的返回**



### 竞争状态

如果两个或者多个goroutine 在没有互相同步的情况下，访问某个共享的资源，并试图同时
读和写这个资源，就处于相互竞争的状态，这种情况被称作竞争状态（race candition）

对一个共享资源的**读和写操作必须是原子化**的，换句话说，同一时刻只能有一个goroutine 对共享资源进行读和写操作。

![image-20201213191749490](https://raw.githubusercontent.com/Explorersss/photo/master/20201213191749.png)

#### 竞争检测器

`go build -race`



### 锁住共享资源



#### 原子函数

原子函数能够以很底层的**加锁**机制来同步访问整型变量和指针。



```go
atomic.AddInt64(&counter, 1)// 安全地对counter 加1
atomic.StoreInt64(&shutdown, 1)
atomic.LoadInt64(&shutdown)
```





AddInt64函数会同步整型值的加法，方法是强制同一时刻只能有一个goroutine 运行并完成这个加法操作。



#### 互斥锁

```go
package main

import (
	"fmt"
	"runtime"
	"sync"
)

var counter int
var wg sync.WaitGroup
var mutex sync.Mutex
func increase(id int){
	defer wg.Done()
	mutex.Lock()
	{
		value := counter
		value ++
		runtime.Gosched()
		counter = value
	}
	mutex.Unlock()

}

func init(){
	counter=0
}

func main(){
	wg.Add(2)// 计数加 2，表示要等待两个goroutine
	go increase(0)
	go increase(1)
	wg.Wait()// 等待 goroutine 结束
	fmt.Println(counter)
}
```

对counter 变量的操作在第46 行和第60 行的Lock()和Unlock()函数调用定义的临界
区里被保护起来。使用大括号只是为了让临界区看起来更清晰，并不是必需的。同一时刻只有一
个goroutine 可以进入临界区。之后，直到调用Unlock()函数之后，其他goroutine 才能进入临
界区。



### 通道

在Go 语言里，你不仅可以使用原子函数和互斥锁来保证对共享资源的安全访问以及消除竞争状态，还可以使用通道，通过发送和接收需要共享的资源，在goroutine 之间做同步。

```go
// 无缓冲的整型通道
unbuffered := make(chan int)
// 有缓冲的字符串通道
buffered := make(chan string, 10)
```

使用内置函数make 创建了两个通道，一个无缓冲的通道，一个有缓冲的通道。make 的第一个参数需要是关键字chan，之后跟着允许通道交换的数据的类型。如果创建的是一个有缓冲的通道，之后还需要**在第二个参数指定这个通道的缓冲区的大小**。

向通道发送值

```go
buffered <- "Gopher"
```

接受值

```go
value := <-buffered
```



#### 只读/只写通道

```go
package main

import (
    "fmt"
    "sync"
)
var wg sync.WaitGroup
func main() {
    wg.Add(2)
    c := make(chan int)
    go send(c)
    go recv(c)
    close(c)
    wg.Wait()
}
//只能向chan里写数据
func send(c chan<- int) {
    defer wg.Done()
    for i := 0; i < 10; i++ {
        c <- i
    }
}
//只能取channel中的数据
func recv(c <-chan int) {
    defer wg.Done()
    for i := range c {
        fmt.Println(i)
    }
}
```





#### 无缓冲通道

在接收方和发送方没有同时准备好的情况下，会进入阻塞状态

![image-20201213194510758](https://raw.githubusercontent.com/Explorersss/photo/master/20201213194510.png)



demo

```go
package main

import (
	"fmt"
	"math/rand"
	"sync"
	"time"
)

var wg sync.WaitGroup

func init(){
	rand.Seed(time.Now().UnixNano())
}

func player(name string,ball chan int){
	defer wg.Done()
	for{
		value,ok := <-ball
		if !ok{
			fmt.Printf("%s won this game\n",name)
			return
		}

		if rand.Intn(100)%13 == 0{
			fmt.Printf("%s miss the ball:%d\n",name,value)
			close(ball)
			return
		}
		fmt.Printf("%s hit the ball:%d\n",name,value)
		value++
		ball <- value



	}
}

func main(){
	court := make(chan int)
	wg.Add(2)

	go player("player1",court)
	go player("PLAYER2",court)
	court <-1
	wg.Wait()

}
```



#### 有缓冲的通道

有缓冲的通道（buffered channel）是一种在被接收前能存储一个或者多个值的通道。这种类型的通道并不强制要求goroutine 之间必须同时完成发送和接收。通道会阻塞发送和接收动作的条件也会不同。只有**在通道中没有要接收的值时，接收动作才会阻塞**。只**有在通道没有可用缓冲区容纳被发送的值时，发送动作才会阻塞**

当**通道关闭**后，**goroutine 依旧可以从通道接收数据**，但是不能再向通道里发送数据。



```go
package main

import (
	"fmt"
	"math/rand"
	"sync"
	"time"
)

var wg sync.WaitGroup

const (
	numberGoroutines = 4
	taskLoader = 10
)

func init(){
	rand.Seed(time.Now().UnixNano())
}

func worker(id int,task chan string){
	defer wg.Done()
	for{
		value,ok := <- task
		if !ok{
			fmt.Printf("Worker %d: task complete\n",id)
			return
		}
		fmt.Printf("Worker %d : %s has been completed\n",id,value)

	}
}

func main(){
	wg.Add(numberGoroutines)
	task := make(chan string,taskLoader)
	for i:=0;i<numberGoroutines;i++{
		go worker(i,task)
	}
	for post := 1; post <= taskLoader; post++ {
		task <- fmt.Sprintf("Task : %d", post)
	}
	close(task)


	wg.Wait()

}
```





### select

`select` 是一种与 `switch` 相似的控制结构，与 `switch` 不同的是，`select` 中虽然也有多个 `case`，但是这些 `case` 中的表达式必须都是 Channel的收发操作

```go
func fibonacci(c, quit chan int) {
	x, y := 0, 1
	for {
		select {
		case c <- x:
			x, y = y, x+y
		case <-quit:
			fmt.Println("quit")
			return
		}
	}
}
```

1. `select` 能在 Channel 上进行非阻塞的收发操作；
2. `select` 在遇到多个 Channel 同时响应时会随机挑选 `case` 执行；



在通常情况下，`select` 语句会阻塞当前 Goroutine 并等待多个 Channel 中的一个达到可以收发的状态。但是如果 `select` 控制结构中包含 `default` 语句，那么这个 `select` 语句在执行时会遇到以下两种情况：

1. 当存在可以收发的 Channel 时，直接处理该 Channel 对应的 `case`；
2. 当不存在可以收发的 Channel 是，执行 `default` 中的语句；

```go
func main() {
	ch := make(chan int)
	select {
	case i := <-ch:
		println(i)

	default:
		println("default")
	}
}
```





## 并发模式



### runner

```go
package runner

import (
	"errors"
	"fmt"
	"os"
	"os/signal"
	"sync"
	"time"
)

// Runner runs a set of tasks within a given timeout and can be
// shut down on an operating system interrupt.
type Runner struct {
	// interrupt channel reports a signal from the
	// operating system.
	interrupt chan os.Signal

	// complete channel reports that processing is done.
	complete chan error

	// timeout reports that time has run out.
	timeout <-chan time.Time

	// tasks holds a set of functions that are executed
	// synchronously in index order.
	tasks []func(int)
}

// ErrTimeout is returned when a value is received on the timeout channel.
var ErrTimeout = errors.New("received timeout")

// ErrInterrupt is returned when an event from the OS is received.
var ErrInterrupt = errors.New("received interrupt")

// New returns a new ready-to-use Runner.
func New(d time.Duration) *Runner {
	return &Runner{
		interrupt: make(chan os.Signal, 1),
		complete:  make(chan error),
		timeout:   time.After(d),
	}
}

// Add attaches tasks to the Runner. A task is a function that
// takes an int ID.
func (r *Runner) Add(tasks ...func(int)) {
	r.tasks = append(r.tasks, tasks...)
}

// Start runs all tasks and monitors channel events.
func (r *Runner) Start() error {
	// We want to receive all interrupt based signals.
	signal.Notify(r.interrupt, os.Interrupt)

	// Run the different tasks on a different goroutine.
	go func() {
		r.complete <- r.run()
	}()

	select {
	// Signaled when processing is done.
	case err := <-r.complete:
		return err

	// Signaled when we run out of time.
	case <-r.timeout:
		return ErrTimeout
	}
}

// run executes each registered task.
func (r *Runner) run() error {
	var wg sync.WaitGroup
	wg.Add(len(r.tasks))
	for id, task := range r.tasks {
		// Check for an interrupt signal from the OS.
		if r.gotInterrupt() {
			return ErrInterrupt
		}

		// Execute the registered task.
		go func(id int) {
			defer wg.Done()
			fmt.Println(id)
			task(id)
		}(id)
	}
	wg.Wait()

	return nil
}

// gotInterrupt verifies if the interrupt signal has been issued.
func (r *Runner) gotInterrupt() bool {
	select {
	// Signaled when an interrupt event is sent.
	case <-r.interrupt:
		// Stop receiving any further signals.
		signal.Stop(r.interrupt)
		return true

	// Continue running as normal.
	default:
		return false
	}
}
```







### pool

Go 1.6 之后自带sync.Pool



```go
package pool

import (
	"errors"
	"io"
	"log"
	"sync"
)

// Pool manages a set of resources that can be shared safely by
// multiple goroutines. The resource being managed must implement
// the io.Closer interface.
type Pool struct {
	m         sync.Mutex
	resources chan io.Closer
	factory   func() (io.Closer, error)
	closed    bool
}

// ErrPoolClosed is returned when an Acquire returns on a
// closed pool.
var ErrPoolClosed = errors.New("Pool has been closed.")

// New creates a pool that manages resources. A pool requires a
// function that can allocate a new resource and the size of
// the pool.
func New(fn func() (io.Closer, error), size uint) (*Pool, error) {
	if size <= 0 {
		return nil, errors.New("Size value too small.")
	}

	return &Pool{
		factory:   fn,
		resources: make(chan io.Closer, size),
	}, nil
}

// Acquire retrieves a resource	from the pool.
func (p *Pool) Acquire() (io.Closer, error) {
	select {
	// Check for a free resource.
	case r, ok := <-p.resources:
		log.Println("Acquire:", "Shared Resource")
		if !ok {
			return nil, ErrPoolClosed
		}
		return r, nil

	// Provide a new resource since there are none available.
	default:
		log.Println("Acquire:", "New Resource")
		return p.factory()
	}
}

// Release places a new resource onto the pool.
func (p *Pool) Release(r io.Closer) {
	// Secure this operation with the Close operation.
	p.m.Lock()
	defer p.m.Unlock()

	// If the pool is closed, discard the resource.
	if p.closed {
		r.Close()
		return
	}

	select {
	// Attempt to place the new resource on the queue.
	case p.resources <- r:
		log.Println("Release:", "In Queue")

	// If the queue is already at cap we close the resource.
	default:
		log.Println("Release:", "Closing")
		r.Close()
	}
}

// Close will shutdown the pool and close all existing resources.
func (p *Pool) Close() {
	// Secure this operation with the Release operation.
	p.m.Lock()
	defer p.m.Unlock()

	// If the pool is already close, don't do anything.
	if p.closed {
		return
	}

	// Set the pool as closed.
	p.closed = true

	// Close the channel before we drain the channel of its
	// resources. If we don't do this, we will have a deadlock.
	close(p.resources)

	// Close the resources
	for r := range p.resources {
		r.Close()
	}
}
```





### work

```go
package work

import "sync"

// Worker must be implemented by types that want to use
// the work pool.
type Worker interface {
	Task()
}

// Pool provides a pool of goroutines that can execute any Worker
// tasks that are submitted.
type Pool struct {
	work chan Worker
	wg   sync.WaitGroup
}

// New creates a new work pool.
func New(maxGoroutines int) *Pool {
	p := Pool{
		work: make(chan Worker),
	}

	p.wg.Add(maxGoroutines)
	for i := 0; i < maxGoroutines; i++ {
		go func() {
			for w := range p.work {
				w.Task()
			}
			p.wg.Done()
		}()
	}

	return &p
}

// Run submits work to the pool.
func (p *Pool) Run(w Worker) {
	p.work <- w
}

// Shutdown waits for all the goroutines to shutdown.
func (p *Pool) Shutdown() {
	close(p.work)
	p.wg.Wait()
}
```





## 标准库



### log

demo

```go
// This sample program demonstrates how to use the base log package.
package main

import (
	"log"
)

func init() {
	log.SetPrefix("TRACE: ")
	log.SetFlags(log.Ldate | log.Lmicroseconds | log.Llongfile)
}

func main() {
	// Println writes to the standard logger.
	log.Println("message")

	// Fatalln is Println() followed by a call to os.Exit(1).
	log.Fatalln("fatal message")

	// Panicln is Println() followed by a call to panic().
	log.Panicln("panic message")
}
```



log包的一些常量



```go
const (
// 将下面的位使用或运算符连接在一起，可以控制要输出的信息。没有
// 办法控制这些信息出现的顺序（下面会给出顺序）或者打印的格式
// （格式在注释里描述）。这些项后面会有一个冒号：
// 2009/01/23 01:23:23.123123 /a/b/c/d.go:23: message
// 日期: 2009/01/23
    Ldate = 1 << iota
    // 时间: 01:23:23
    Ltime
    // 毫秒级时间: 01:23:23.123123。该设置会覆盖Ltime 标志
    Lmicroseconds
    // 完整路径的文件名和行号: /a/b/c/d.go:23
    Llongfile
    // 最终的文件名元素和行号: d.go:23
    // 覆盖 Llongfile
    Lshortfile
    // 标准日志记录器的初始值
    LstdFlags = Ldate | Ltime
)
```

log 包有一个很方便的地方就是，**这些日志记录器是多goroutine 安全的。**



#### 定制的日志记录器

```go
package main

import (
	"io"
	"io/ioutil"
	"log"
	"os"
)

var (
	Trace   *log.Logger // Just about anything
	Info    *log.Logger // Important information
	Warning *log.Logger // Be concerned
	Error   *log.Logger // Critical problem
)

func init() {
	file, err := os.OpenFile("errors.txt",
		os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0666)
	if err != nil {
		log.Fatalln("Failed to open error log file:", err)
	}

	Trace = log.New(ioutil.Discard,
		"TRACE: ",
		log.Ldate|log.Ltime|log.Lshortfile)

	Info = log.New(os.Stdout,
		"INFO: ",
		log.Ldate|log.Ltime|log.Lshortfile)

	Warning = log.New(os.Stdout,
		"WARNING: ",
		log.Ldate|log.Ltime|log.Lshortfile)

	Error = log.New(io.MultiWriter(file, os.Stderr),
		"ERROR: ",
		log.Ldate|log.Ltime|log.Lshortfile)
}

func main() {
	Trace.Println("I have something standard to say")
	Info.Println("Special Information")
	Warning.Println("There is something you need to know about")
	Error.Println("Something has failed")
}
```



### json

#### decode

字符串decode

```go
json.Unmarshal([]byte(JSON), &c)
```

io.reader decode

```
err = json.NewDecoder(resp.Body).Decode(&gr)
```

demo(结构体)

```go
package main

import (
	"encoding/json"
	"fmt"
	"log"
)

type Test struct {
	Test int `json:"test"`
}//记得设置为公开标识符


var JSON = `{"test":123123}`

func main() {
	var c Test
	err := json.Unmarshal([]byte(JSON), &c)
	if err != nil {
		log.Println("ERROR:", err)
		return
	}

	fmt.Println(c)
}
```



demo(map)

```go
package main

import (
	"encoding/json"
	"fmt"
	"log"
)

// JSON contains a sample string to unmarshal.
var JSON = `{
	"name": "Gopher",
	"title": "programmer",
	"contact": {
		"home": "415.333.3333",
		"cell": "415.555.5555"
	}
}`

func main() {
	// Unmarshal the JSON string into our map variable.
	var c map[string] interface{}
	err := json.Unmarshal([]byte(JSON), &c)
	if err != nil {
		log.Println("ERROR:", err)
		return
	}

	fmt.Println("Name:", c["name"])
	fmt.Println("Title:", c["title"])
	fmt.Println("Contact")
	fmt.Println("H:", c["contact"].(map[string]interface{})["home"])
	fmt.Println("C:", c["contact"].(map[string]interface{})["cell"])
}
```





#### encode

```go
package main

import (
	"encoding/json"
	"fmt"
	"log"
)

func main() {
	// Create a map of key/value pairs.
	c := make(map[string]interface{})
	c["name"] = "Gopher"
	c["title"] = "programmer"
	c["contact"] = map[string]interface{}{
		"home": "415.333.3333",
		"cell": "415.555.5555",
	}

	// Marshal the map into a JSON string.
	data, err := json.MarshalIndent(c, "", "    ")
	if err != nil {
		log.Println("ERROR:", err)
		return
	}

	fmt.Println(string(data))
}
```



### io.Writer / io.Reader

io.Writer

Write 从p 里向底层的数据流写入len(p)字节的数据。这个方法返回从p 里写出的字节
数（0 <= n <= len(p)），以及任何可能导致写入提前结束的错误。Write 在返回n
< len(p)的时候，必须返回某个非nil 值的error。Write 绝不能改写切片里的数据，
哪怕是临时修改也不行。





io.Reader

(1) Read 最多读入len(p)字节，保存到p。这个方法返回读入的字节数（0 <= n
<= len(p)）和任何读取时发生的错误。即便Read 返回的n < len(p)，方法也可
能使用所有p 的空间存储临时数据。如果数据可以读取，但是字节长度不足len(p)，
习惯上 Read 会立刻返回可用的数据，而不等待更多的数据。
(2) 当成功读取 n > 0 字节后，如果遇到错误或者文件读取完成，Read 方法会返回
读入的字节数。方法可能会在本次调用返回一个非nil 的错误，或者在下一次调用时返
回错误（同时n == 0）。这种情况的的一个例子是，在输入的流结束时，Read 会返回
非零的读取字节数，可能会返回err == EOF，也可能会返回err == nil。无论如何，
下一次调用Read 应该返回0, EOF。
(3) 调用者在返回的n > 0 时，总应该先处理读入的数据，再处理错误err。这样才
能正确操作读取一部分字节后发生的I/O 错误。EOF 也要这样处理。
(4) Read 的实现不鼓励返回0 个读取字节的同时，返回nil 值的错误。调用者需要将
这种返回状态视为没有做任何操作，而不是遇到读取结束。





## 杂

### iota

关键字 iota 在常量声明区里有特殊的作用。这个关键字让编译器为每个常量复制相同的表
达式，直到声明区结束，或者遇到一个新的赋值语句。关键字iota 的另一个功能是，iota 的
初始值为0，之后iota 的值在每次处理为常量后，都会自增1

```go
const (
    Ldate = 1 << iota // 1 << 0 = 000000001 = 1
    Ltime // 1 << 1 = 000000010 = 2
    Lmicroseconds // 1 << 2 = 000000100 = 4
    Llongfile // 1 << 3 = 000001000 = 8
    Lshortfile // 1 << 4 = 000010000 = 16
    
)
```



