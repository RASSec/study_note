# markdown

## 流程图

### 实例

```flow
st=>start: c代码
o1=>operation: 预处理
o2=>operation: 编译
o3=>operation: 汇编
o3=>operation: 连接
y=>operation: 预处理文件
h=>operation: asm代码(汇编代码)
o=>operation: 二进制文件
e=>end: 可执行文件

st(right)->o1->y(right)->o2->h(right)->o3->o(right)->e

```

### 流程图语法介绍

流程图语法分两个部分，一个是声明元素，一个是定义流程

#### 声明元素

语法：

```
tag=>type: content :>url
```

1. `tag` 设置元素名称

2. `=>` 元素定义符

3. ```
   type:
   ```

    

   设置元素类型，共分6种：

   - **start**：开始，圆角矩形
   - **end**：结束，圆角矩形
   - **operation**：操作/行动方案，普通矩形
   - **subroutine**：子主题/模块，双边线矩形
   - **condition**：条件判断/问题审核，菱形
   - **inputoutput**：输入输出，平行四边形

4. `content` 设置元素显示内容，中英均可

5. `:>url` 设置元素连接，可选，后接 [blank] 可以新建窗口打开

提示：注意空格，`=>` 前后都不能接空格；`type:` 后必须接空格；`:>` 是语法标记，中间不能有空格

#### 定义流程

语法：

```
tag1(branch,direction)->tag2
```

1. `->` 流程定义符，连接两个元素
2. `branch` 设置 condition 类型元素的两个分支，有 `yes`/`no` 两个值，其他元素无效
3. `direction` 定义流程走线方向，有 `left`/`right`/`top`/`bottom` 四个值，所有元素有效，此项配置可选
   （PS: 此属性目前有一定几率触发图形错位，刷新即可）

小提示：

- 继续注意空格，`->` 前后都不能有空格

- 由于 condition 类型有两个分支，我们一般遇到 condition 元素就换行书写，比如：

  ```
    st->op1-c2
    c2(yes)->io->e
    c2(no)->op2->e
  ```