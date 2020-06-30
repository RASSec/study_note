# vue



## vue cli

 https://cli.vuejs.org/zh/guide/ 



```shell
npm install -g @vue/cli # 安装
npm install -g @vue/cli-service-global# 支持vue serve命令
vue serve #运行当前目录下的main.js或者App.vue
vue serve filename 
vue build #将目标文件构建成一个生产环境的包并用来部署
vue create hello-world #创建hello-world项目
vue ui #以图形化界面创建和管理项目
vue add eslint # 安装并调用 vue-cli-plugin-eslint
vue add @foo/bar # @foo/vue-cli-plugin-bar

```



## vue实例

```javascript
var vm = new Vue({
  // 选项 https://cn.vuejs.org/v2/api/#%E9%80%89%E9%A1%B9-%E6%95%B0%E6%8D%AE
})
```

 当一个 Vue 实例被创建时，它将 `data` 对象中的所有的 property 加入到 Vue 的**响应式系统**中。当这些 property 的值发生改变时，视图将会产生“响应”，即匹配更新为新的值。 



### 注意

在vue的生命周期函数内不能使用箭头函数，因为它是没有this的，而vue需要this



## vue attribute



### 所有的vue属性

```
v-bind:attribute
v-on:event
v-html #用参数来代替当前标签
v-once #一次性地插值，当数据改变时，插值处的内容不会更新。
v-for
v-else
v-if
v-else-if
v-show # 用于根据条件展示元素的选项是 v-show 指令。不同的是带有 v-show 的元素始终会被渲染并保留在 DOM 中。v-show 只是简单地切换元素的 CSS property display。
v-model
```

标签中可以执行一句话表达式

 当 `v-if` 与 `v-for` 一起使用时，`v-for` 具有比 `v-if` 更高的优先级。 

### 例子

#### 用`key` 管理可复用的元素

 Vue 会尽可能高效地渲染元素，通常会复用已有元素而不是从头开始渲染，如：

```html
<div id="app">
            <template v-if="loginType === 'username'">
                <label>Username</label>
                <input placeholder="Enter your username">
              </template>
              <template v-else>
                <label>Email</label>
                <input placeholder="Enter your email address">
            </template>
            <button v-on:click="change">aaaaaaa</button>
        </div>
          
        <script>
            var app = new Vue({
            el:"#app",
            data: {
                loginType:"username"
            },
            methods:{
                change:function(){
                    if(app.loginType==="username"){
                       app.loginType="Email";
                    }else{
                        app.loginType="username"
                    }
                }
            }
            })
        </script>
```

当输入指之后按切换按钮，输入框里的指并不会被清空，改的仅仅只是placehold

为了让vue不重复渲染input,我们需要给input加上key属性

```html
<template v-if="loginType === 'username'">
  <label>Username</label>
  <input placeholder="Enter your username" key="username-input">
</template>
<template v-else>
  <label>Email</label>
  <input placeholder="Enter your email address" key="email-input">
</template>
```

这样按切换按钮后就会清空输入框



#### v-for

##### 列表

```html
<ul id="example-1">
  <li v-for="item in items" :key="item.message">
    {{ item.message }}
  </li>
</ul>
<script>
var example1 = new Vue({
  el: '#example-1',
  data: {
    items: [
      { message: 'Foo' },
      { message: 'Bar' }
    ]
  }
})
</script>
```



 也可以用 `of` 替代 `in` 作为分隔符 

 在 `v-for` 块中，我们可以访问所有父作用域的 property。`v-for` 还支持一个可选的第二个参数，即当前项的索引。 



```html
<ul id="example-2">
  <li v-for="(item, index) in items">
    {{ parentMessage }} - {{ index }} - {{ item.message }}
  </li>
</ul>
<script>
var example2 = new Vue({
  el: '#example-2',
  data: {
    parentMessage: 'Parent',
    items: [
      { message: 'Foo' },
      { message: 'Bar' }
    ]
  }
})
</script>
```



##### 对象

可以用 `v-for` 来遍历一个对象的 property。

```html
<ul id="v-for-object" class="demo">
  <li v-for="value in object">
    {{ value }}
  </li>
</ul>

```



```javascript
new Vue({
  el: '#v-for-object',
  data: {
    object: {
      title: 'How to do lists in Vue',
      author: 'Jane Doe',
      publishedAt: '2016-04-10'
    }
  }
})
```



你也可以提供第二个的参数为 property 名称 (也就是键名)：

```html
<div v-for="(value, name) in object">
  {{ name }}: {{ value }}
</div>
```

还可以用第三个参数作为索引：

```html
<div v-for="(value, name, index) in object">
  {{ index }}. {{ name }}: {{ value }}
</div>
```



##### 在 `v-for` 里使用值范围

`v-for` 也可以接受整数。在这种情况下，它会把模板重复对应次数。

```html
<div>
  <span v-for="n in 10">{{ n }} </span>
</div>
```





#### v-on

#####  访问原始的 DOM 事件 

有时也需要在内联语句处理器中访问原始的 DOM 事件。可以用特殊变量 `$event` 把它传入方法：

```html
<button v-on:click="warn('Form cannot be submitted yet.', $event)">
  Submit
</button>
// ...
methods: {
  warn: function (message, event) {
    // 现在我们可以访问原生事件对象
    if (event) {
      event.preventDefault()
    }
    alert(message)
  }
}
```



##### 事件修饰符

Vue.js 为 `v-on` 提供了**事件修饰符**。之前提过，修饰符是由点开头的指令后缀来表示的。

- `.stop`
- `.prevent`
- `.capture`
- `.self`
- `.once`
- `.passive`

```
<!-- 阻止单击事件继续传播 -->
<a v-on:click.stop="doThis"></a>

<!-- 提交事件不再重载页面 -->
<form v-on:submit.prevent="onSubmit"></form>

<!-- 修饰符可以串联 -->
<a v-on:click.stop.prevent="doThat"></a>

<!-- 只有修饰符 -->
<form v-on:submit.prevent></form>

<!-- 添加事件监听器时使用事件捕获模式 -->
<!-- 即内部元素触发的事件先在此处理，然后才交由内部元素进行处理 -->
<div v-on:click.capture="doThis">...</div>

<!-- 只当在 event.target 是当前元素自身时触发处理函数 -->
<!-- 即事件不是从内部元素触发的 -->
<div v-on:click.self="doThat">...</div>
```

使用修饰符时，顺序很重要；相应的代码会以同样的顺序产生。因此，用 `v-on:click.prevent.self` 会阻止**所有的点击**，而 `v-on:click.self.prevent` 只会阻止对元素自身的点击。



##### 按键修饰符



```
<input v-on:keyup.enter="submit">
```

你可以直接将 [`KeyboardEvent.key`](https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key/Key_Values) 暴露的任意有效按键名转换为 kebab-case 来作为修饰符。

```
<input v-on:keyup.page-down="onPageDown">
```

在上述示例中，处理函数只会在 `$event.key` 等于 `PageDown` 时被调用。



#### v-model

v-model 指令在表单 `<input>、<textarea> 及 <select>` 元素上创建双向数据绑定。 它负责监听用户的输入事件以更新数据 

`v-model` 在内部为不同的输入元素使用不同的 property 并抛出不同的事件：

- text 和 textarea 元素使用 `value` property 和 `input` 事件；
- checkbox 和 radio 使用 `checked` property 和 `change` 事件；
- select 字段将 `value` 作为 prop 并将 `change` 作为事件。

> 对于需要使用[输入法](https://zh.wikipedia.org/wiki/输入法) (如中文、日文、韩文等) 的语言，你会发现 `v-model` 不会在输入法组合文字过程中得到更新。如果你也想处理这个过程，请使用 `input` 事件。 



##### 文本

```html
<input v-model="message" placeholder="edit me">
<p>Message is: {{ message }}</p>
```

##### 多行文本

```html
<span>Multiline message is:</span>
<p style="white-space: pre-line;">{{ message }}</p>
<br>
<textarea v-model="message" placeholder="add multiple lines"></textarea>
```



##### 复选框

```html
<input type="checkbox" id="checkbox" v-model="checked">
<label for="checkbox">{{ checked }}</label>
```

多个复选框添加到同一数组中

```html
<div id='example-3'>
  <input type="checkbox" id="jack" value="Jack" v-model="checkedNames">
  <label for="jack">Jack</label>
  <input type="checkbox" id="john" value="John" v-model="checkedNames">
  <label for="john">John</label>
  <input type="checkbox" id="mike" value="Mike" v-model="checkedNames">
  <label for="mike">Mike</label>
  <br>
  <span>Checked names: {{ checkedNames }}</span>
</div>
```

##### 单选按钮

```html
<div id="example-4">
  <input type="radio" id="one" value="One" v-model="picked">
  <label for="one">One</label>
  <br>
  <input type="radio" id="two" value="Two" v-model="picked">
  <label for="two">Two</label>
  <br>
  <span>Picked: {{ picked }}</span>
</div>
```



##### 选择框

单选时：

```html
<div id="example-5">
  <select v-model="selected">
    <option disabled value="">请选择</option>
    <option>A</option>
    <option>B</option>
    <option>C</option>
  </select>
  <span>Selected: {{ selected }}</span>
</div>
```



多选时：

```html
<div id="example-6">
  <select v-model="selected" multiple style="width: 50px;">
    <option>A</option>
    <option>B</option>
    <option>C</option>
  </select>
  <br>
  <span>Selected: {{ selected }}</span>
</div>
```



##### 修饰符

```html
<!--.lazy -->
<!--在默认情况下，v-model 在每次 input 事件触发后将输入框的值与数据进行同步 (除了上述输入法组合文字时)。你可以添加 lazy 修饰符，从而转为在 change 事件_之后_进行同步： -->
<input v-model.lazy="msg">
<!--.number -->
<!--如果想自动将用户的输入值转为数值类型，可以给 v-model 添加 number 修饰符-->
<input v-model.number="age" type="number">
<!--.trim -->
<!--如果要自动过滤用户输入的首尾空白字符，可以给 v-model 添加 trim 修饰符-->
<input v-model.trim="msg">
```



##### 注意

> 在文本区域插值 (`<textarea>{{text}}</textarea>`) 并不会生效，应用 v-model 来代替。



>如果 `v-model` 表达式的初始值未能匹配任何选项，`<select>` 元素将被渲染为“未选中”状态。在 iOS 中，这会使用户无法选择第一个选项。因为这样的情况下，iOS 不会触发 change 事件。因此，更推荐像上面这样提供一个值为空的禁用选项。 











###  动态参数

 从 2.6.0 开始，可以用方括号括起来的 JavaScript 表达式作为一个指令的参数 

```javascript
<!--
注意，参数表达式的写法存在一些约束，如之后的“对动态参数表达式的约束”章节所述。
-->
<a v-bind:[attributeName]="url"> ... </a>
<a v-on:[eventName]="doSomething"> ... </a>
```



### 注意

 在 DOM 中使用模板时 (直接在一个 HTML 文件里撰写模板)，还需要避免使用大写字符来命名键名，因为浏览器会把 attribute 名全部强制转为小写 

```
<!--
在 DOM 中使用模板时这段代码会被转换为 `v-bind:[someattr]`。
除非在实例中有一个名为“someattr”的 property，否则代码不会工作。
-->
<a v-bind:[someAttr]="value"> ... </a>
```



### 缩写

 Vue 为 `v-bind` 和 `v-on` 这两个最常用的指令，提供了特定简写 

```html
<!-- 完整语法 -->
<a v-bind:href="url">...</a>

<!-- 缩写 -->
<a :href="url">...</a>

<!-- 动态参数的缩写 (2.6.0+) -->
<a :[key]="url"> ... </a>

<!-- 完整语法 -->
<a v-on:click="doSomething">...</a>

<!-- 缩写 -->
<a @click="doSomething">...</a>

<!-- 动态参数的缩写 (2.6.0+) -->
<a @[event]="doSomething"> ... </a>
```



## 计算属性和侦听器



### 计算属性

```html
<div id="example">
  {{ message.split('').reverse().join('') }}
</div>
```



在传参中定义计算方法



```html
<div id="example">
  <p>Original message: "{{ message }}"</p>
  <p>Computed reversed message: "{{ reversedMessage }}"</p>
</div>

```

```javascript
var vm = new Vue({
  el: '#example',
  data: {
    message: 'Hello'
  },
  computed: {
    // 计算属性的 getter
    reversedMessage: function () {
      // `this` 指向 vm 实例
      return this.message.split('').reverse().join('')
    }
  }
})
```



### 计算属性的 setter

```javascript
computed: {
  fullName: {
    // getter
    get: function () {
      return this.firstName + ' ' + this.lastName
    },
    // setter
    set: function (newValue) {
      var names = newValue.split(' ')
      this.firstName = names[0]
      this.lastName = names[names.length - 1]
    }
  }
}
```

### 侦听器

```html
<div id="watch-example">
  <p>
    Ask a yes/no question:
    <input v-model="question">
  </p>
  <p>{{ answer }}</p>
</div>
<!-- 因为 AJAX 库和通用工具的生态已经相当丰富，Vue 核心代码没有重复 -->
<!-- 提供这些功能以保持精简。这也可以让你自由选择自己更熟悉的工具。 -->
<script src="https://cdn.jsdelivr.net/npm/axios@0.12.0/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.13.1/lodash.min.js"></script>
<script>
var watchExampleVM = new Vue({
  el: '#watch-example',
  data: {
    question: '',
    answer: 'I cannot give you an answer until you ask a question!'
  },
  watch: {
    // 如果 `question` 发生改变，这个函数就会运行
    question: function (newQuestion, oldQuestion) {
      this.answer = 'Waiting for you to stop typing...'
      this.debouncedGetAnswer()
    }
  },
  created: function () {
    // `_.debounce` 是一个通过 Lodash 限制操作频率的函数。
    // 在这个例子中，我们希望限制访问 yesno.wtf/api 的频率
    // AJAX 请求直到用户输入完毕才会发出。想要了解更多关于
    // `_.debounce` 函数 (及其近亲 `_.throttle`) 的知识，
    // 请参考：https://lodash.com/docs#debounce
    this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
  },
  methods: {
    getAnswer: function () {
      if (this.question.indexOf('?') === -1) {
        this.answer = 'Questions usually contain a question mark. ;-)'
        return
      }
      this.answer = 'Thinking...'
      var vm = this
      axios.get('https://yesno.wtf/api')
        .then(function (response) {
          vm.answer = _.capitalize(response.data.answer)
        })
        .catch(function (error) {
          vm.answer = 'Error! Could not reach the API. ' + error
        })
    }
  }
})
</script>
```





## 踩坑



### 添加jQuery到vue

 [https://pjchender.github.io/2017/09/26/vue-%E5%9C%A8-vue-%E4%B8%AD%E4%BD%BF%E7%94%A8%EF%BC%88es6-import%EF%BC%89-bootstrap4-%E5%92%8C-jquery/](https://pjchender.github.io/2017/09/26/vue-在-vue-中使用（es6-import）-bootstrap4-和-jquery/) 



在 Vue项目中要进行 webpack 的设定，需要在根目录中新增一支名为 `vue.config.js` 的文件（放在和 `package.json` 同一层）：

```javascript

const webpack = require('webpack');

module.exports = {
  configureWebpack: {
    plugins: [
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'windows.jQuery': 'jquery',
      }),
    ],
  },
};
```

設定好了之後，在 Vue 專案中，就可以在需要使用 jQuery 的地方匯入 `$` 就可以了：

```
import $ from 'jquery';
```





## 组件

```html
// 定义一个名为 button-counter 的新组件
Vue.component('button-counter', {
  data: function () {
    return {
      count: 0
    }
  },
  template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
})
```

 组件是可复用的 Vue 实例，且带有一个名字：在这个例子中是 ` <button-counter `。我们可以在一个通过 `new Vue` 创建的 Vue 根实例中，把这个组件作为自定义元素来使用 

```html
<div id="components-demo">
  <button-counter></button-counter>
</div>
```

 因为组件是可复用的 Vue 实例，所以它们与 `new Vue` 接收相同的选项，例如 `data`、`computed`、`watch`、`methods` 以及生命周期钩子等。仅有的例外是像 `el` 这样根实例特有的选项。 

### 复用组件

可以将组件进行任意次数的复用：

```
<div id="components-demo">
  <button-counter></button-counter>
  <button-counter></button-counter>
  <button-counter></button-counter>
</div>
```



#### data必须是一个函数

当我们定义这个 `<button-counter>` 组件时，你可能会发现它的 `data` 并不是像这样直接提供一个对象：

```
data: {
  count: 0
}
```

取而代之的是，**一个组件的 `data` 选项必须是一个函数**，因此每个实例可以维护一份被返回对象的独立的拷贝：

```
data: function () {
  return {
    count: 0
  }
}
```

如果 Vue 没有这条规则，点击一个按钮就可能会影响到*其它所有实例*

### 组件注册

#### 全局注册

```javascript
Vue.component('my-component-name', {
  // ... 选项 ...
})
```

 这些组件是**全局注册的**。也就是说它们在注册之后可以用在任何新创建的 Vue 根实例 (`new Vue`) 的模板中。 



#### 局部注册



可以通过一个普通的 JavaScript 对象来定义组件：

```javascript
var ComponentA = { /* ... */ }
var ComponentB = { /* ... */ }
var ComponentC = { /* ... */ }
```

然后在 `components` 选项中定义你想要使用的组件：

```javascript
new Vue({
  el: '#app',
  components: {
    'component-a': ComponentA,
    'component-b': ComponentB
  }
})
```

对于 `components` 对象中的每个 property 来说，其 property 名就是自定义元素的名字，其 property 值就是这个组件的选项对象。

注意**局部注册的组件在其子组件中\*不可用\***。例如，如果你希望 `ComponentA` 在 `ComponentB` 中可用，则你需要这样写：

```
var ComponentA = { /* ... */ }

var ComponentB = {
  components: {
    'component-a': ComponentA
  },
  // ...
}
```





```javascript
import ComponentA from './ComponentA.vue'

export default {
  components: {
    ComponentA
  },
  // ...
}
```



#### 模块系统

##### 在模块系统中局部注册

推荐创建一个 `components` 目录，并将每个组件放置在其各自的文件中。

然后你需要在局部注册之前导入每个你想使用的组件。例如，在一个假设的 `ComponentB.js` 或 `ComponentB.vue` 文件中：

```
import ComponentA from './ComponentA'
import ComponentC from './ComponentC'

export default {
  components: {
    ComponentA,
    ComponentC
  },
  // ...
}
```

现在 `ComponentA` 和 `ComponentC` 都可以在 `ComponentB` 的模板中使用了。



##### 全局注册

如果你恰好使用了 webpack (或在内部使用了 webpack 的 [Vue CLI 3+](https://github.com/vuejs/vue-cli))，那么就可以使用 `require.context` 只全局注册这些非常通用的基础组件。这里有一份可以让你在应用入口文件 (比如 `src/main.js`) 中全局导入基础组件的示例代码：

```
import Vue from 'vue'
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'

const requireComponent = require.context(
  // 其组件目录的相对路径
  './components',
  // 是否查询其子目录
  false,
  // 匹配基础组件文件名的正则表达式
  /Base[A-Z]\w+\.(vue|js)$/
)

requireComponent.keys().forEach(fileName => {
  // 获取组件配置
  const componentConfig = requireComponent(fileName)

  // 获取组件的 PascalCase 命名
  const componentName = upperFirst(
    camelCase(
      // 获取和目录深度无关的文件名
      fileName
        .split('/')
        .pop()
        .replace(/\.\w+$/, '')
    )
  )

  // 全局注册组件
  Vue.component(
    componentName,
    // 如果这个组件选项是通过 `export default` 导出的，
    // 那么就会优先使用 `.default`，
    // 否则回退到使用模块的根。
    componentConfig.default || componentConfig
  )
})
```

记住**全局注册的行为必须在根 Vue 实例 (通过 `new Vue`) 创建之前发生**。[这里](https://github.com/chrisvfritz/vue-enterprise-boilerplate/blob/master/src/components/_globals.js)有一个真实项目情景下的示例。

### 通过Prop向子组件传递数据

在组件中定义来创建自定义attribute,eg:

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>vueTest</title>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    </head>
    <body>
        <div id="app">
            <prop-test v-for="title in titles"  v-bind:title="title"></prop-test>
        </div>
          
        <script>
            Vue.component('prop-test', {
                props:['title'],
                data: function () {
                    return {}
                },
                template: '<h1>{{ title }}</h1>'
            })
            var app = new Vue({
                el:"#app",
                data: {
                    titles:['test1','test1','test1']
                }
            
            })
        </script>
    </body>
</html>

```

![](https://raw.githubusercontent.com/Explorersss/photo/master/20200529114620.png)



### 监听子组件事件



有时我们会需要父组件向子组件传递事件函数

1. 在父组件中将函数绑定到attribute上
2. 子组件调用`$emit('attribute')`



例如我们可能会引入一个辅助功能来放大博文的字号，同时让页面的其它部分保持默认的字号。

在其父组件中，我们可以通过添加一个 `postFontSize` 数据 property 来支持这个功能：

```
new Vue({
  el: '#blog-posts-events-demo',
  data: {
    posts: [/* ... */],
    postFontSize: 1
  }
})
```

它可以在模板中用来控制所有博文的字号：

```
<div id="blog-posts-events-demo">
  <div :style="{ fontSize: postFontSize + 'em' }">
    <blog-post
      v-for="post in posts"
      v-bind:key="post.id"
      v-bind:post="post"
    ></blog-post>
  </div>
</div>
```

现在我们在每篇博文正文之前添加一个按钮来放大字号：

```
Vue.component('blog-post', {
  props: ['post'],
  template: `
    <div class="blog-post">
      <h3>{{ post.title }}</h3>
      <button>
        Enlarge text
      </button>
      <div v-html="post.content"></div>
    </div>
  `
})
```

问题是这个按钮不会做任何事：

```
<button>
  Enlarge text
</button>
```

当点击这个按钮时，我们需要告诉父级组件放大所有博文的文本。幸好 Vue 实例提供了一个自定义事件的系统来解决这个问题。父级组件可以像处理 native DOM 事件一样通过 `v-on` 监听子组件实例的任意事件：

```
<blog-post
  ...
  v-on:enlarge-text="postFontSize += 0.1"
></blog-post>
```

同时子组件可以通过调用内建的 [**`$emit`** 方法](https://cn.vuejs.org/v2/api/#vm-emit)并传入事件名称来触发一个事件：

```
<button v-on:click="$emit('enlarge-text')">
  Enlarge text
</button>
```

有了这个 `v-on:enlarge-text="postFontSize += 0.1"` 监听器，父级组件就会接收该事件并更新 `postFontSize` 的值



#### 使用事件抛出一个值

有的时候用一个事件来抛出一个特定的值是非常有用的。例如我们可能想让 `` 组件决定它的文本要放大多少。这时可以使用 `$emit` 的第二个参数来提供这个值：

```
<button v-on:click="$emit('enlarge-text', 0.1)">
  Enlarge text
</button>
```

然后当在父级组件监听这个事件的时候，我们可以通过 `$event` 访问到被抛出的这个值：

```
<blog-post
  ...
  v-on:enlarge-text="postFontSize += $event"
></blog-post>
```

或者，如果这个事件处理函数是一个方法：

```
<blog-post
  ...
  v-on:enlarge-text="onEnlargeText"
></blog-post>
```

那么这个值将会作为第一个参数传入这个方法：

```
methods: {
  onEnlargeText: function (enlargeAmount) {
    this.postFontSize += enlargeAmount
  }
}
```



#### 在组件上使用v-model

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>vueTest</title>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    </head>
    <body>
        <div id="app">
            <div :style="{ fontSize: postFontSize + 'em' }">
            <input type="text" v-model="message">
            </div>
        </div>
        
        <script>
            var s="";
            Vue.component('custom-input', {
                props: ['value'],
                template: `
                    <input
                    v-bind:value="value"
                    v-on:input="$emit('input', $event.target.value)"
                    >
                `
                })
            var app = new Vue({
                el:"#app",
                data: {
                    message:s,
                    postFontSize:"1"
                },
                watch:{
                    message: function (newQuestion, oldQuestion) {
                        console.log(newQuestion)
                    }
                }
            
            })
        </script>
    </body>
</html>

```

详情原因看文档

### 插槽

在子组件相应的位置拼接子组件的文本内容

eg.

` <alert-box>  Something bad happened. </alert-box> `=>`<Strong>Error!</Strong>Something bad happened.`

此时需要用到的就是插槽

```javascript
Vue.component('alert-box', {
  template: `
    <div class="demo-alert-box">
      <strong>Error!</strong>
      <slot></slot>
    </div>
  `
})
```





### 动态组件

```html
<!-- 组件会在 `currentTabComponent` 改变时改变 -->
<component v-bind:is="currentTabComponent"></component>
<!-- component会被替换成currentTabComponent组件 -->
```







### 注意

每个vue组件只能有一个根元素





有些 HTML 元素，诸如 `table`、`ul`、`ol` 和 `select`，对于哪些元素可以出现在其内部是有严格限制的。而有些元素，诸如 `li`、 `option`和 `tr`，只能出现在其它某些特定的元素内部。

这会导致我们使用这些有约束条件的元素时遇到一些问题。例如：

```
<table>
  <blog-post-row></blog-post-row>
</table>
```

这个自定义组件 `` 会被作为无效的内容提升到外部，并导致最终渲染结果出错。幸好这个特殊的 `is` attribute 给了我们一个变通的办法：

```
<table>
  <tr is="blog-post-row"></tr>
</table>
```

需要注意的是**如果我们从以下来源使用模板的话，这条限制是\*不存在\*的**：

- 字符串 (例如：`template: '...'`)
- [单文件组件 (`.vue`)](https://cn.vuejs.org/v2/guide/single-file-components.html)
- `<script type="text/x-template">`





## 文档

 https://cn.vuejs.org/v2/guide 





