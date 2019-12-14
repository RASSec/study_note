# python pickle



## pickle介绍



### pickle的大致过程

以Foo类为例

1. 提取出Foo类中的所有attribute(从`__dict__`中获得)将其转化为键值对
2. 写入对象类名
3. 写入第一步生成的键值对

### unpickle的大致过程

1. 获取pickle流
2. 重新构建属性列表
3. 根据保存的类名来创建对象
4. 将属性列表恢复到对象中



### pvm组成(解析pickle)

1. 指令解释器
   最后一步一定是返回栈顶元素
2. 栈 
3. memo(临时保存数据)
   用类似list的方式来读取和储存数据,以字典方式实现
   如p100,意为把栈顶元素保存到memo中索引为100



### pvm指令格式

1. pvm的操作码只有一个字节

2. 需要参数的操作码,要在每一个参数后面加上换行符
3. 从pickle流中读取数据,并加载到栈上



## 如何生成pickle

### 操作码

#### 加载数据



| 操作码 | 助记    | 加载到栈上的数据类型 | 示例        |
| ------ | ------- | -------------------- | ----------- |
| S      | string  | String               | S'foo'\n    |
| V      | unicode | unicode              | Vfo\u006f\n |
| I      | int     | int                  | I42\n       |
|        |         |                      |             |

#### 修改栈/memo

| 操作码            | 助记 | 描述                       | 示例   |
| ----------------- | ---- | -------------------------- | ------ |
| (                 | MARK | 向栈中加入一个标记         | (      |
| 0                 | POP  | 弹出栈顶元素并丢弃         | 0      |
| p`<memo_index>`\n | PUT  | 复制栈顶元素到memo中       | p101\n |
| g`<memo_index>`\n | GET  | 将memo中指定元素拷贝到栈顶 | g101\n |



#### 生成/修改列表,字典,元组

| 操作码 | 助记    | 描述                                                         | 示例                                                    |
| ------ | ------- | ------------------------------------------------------------ | ------------------------------------------------------- |
| l      | 列表    | 将栈顶到遇到的第一个mask之间的元素到一个列表,并将这个列表放入栈中 | (S'string'\nl                                           |
| t      | 元组    | 将栈顶到遇到的第一个mask之间的元素放到一个元组中,并将这个元组放入栈中 | (S'string'\nS'string2'\nt                               |
| d      | 字典    | 将栈顶到遇到的第一个mask之间的元素放到一个字典中,并将这个字典放入栈中 | (S'key1'\nS'value1'\nS'key2'\nS'value2'\nd              |
| s      | SETITEM | 从栈出弹出三个值:字典,键,值,将键值对合并到字典中             | (S'key1'\nS'val1'\nS'key2'\nI123\ndS'key3'\nS'val 3'\ns |
|        |         |                                                              |                                                         |

#### pickle 流生成元组的过程

- 生成元组的指令

  ```
  (S'str1'
  S'str2'
  I1234
  t
  ```

- 生成元组的过程图

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vcyoggx7j310z0fs74s.jpg)



#### 加载对象

| 操作码 | 助记   | 描述                                                         | 示例                          |
| ------ | ------ | ------------------------------------------------------------ | ----------------------------- |
| c      | GLOBAL | 需要两个参数(module,class)来创建对象,并将其放到栈中          | cos\nsystem\n                 |
| R      | REDUCE | 弹出一个参数元组和一个可调用对象（可能是由GLOBAL加载的），将参数应用于可调用对象并将结果压入栈中 | cos\nsystem\n(S'sleep 10'\ntR |

#### 加载对象过程图

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vebew5nvj312p0j8jto.jpg)



![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vebmas4ij31350k30v4.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vebufeq6j313g0mfq6a.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vec1wavgj312r0i3tb6.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9agy1g9vec9lsjpj31350iggo8.jpg)

![image.png](https://ws1.sinaimg.cn/large/006pWR9aly1g9ved0gxklj31490l7q6n.jpg)





