# hexo

## 收藏文章

 https://juejin.im/post/5bebfe51e51d45332a456de0 

 https://zhuanlan.zhihu.com/p/69211731 



## hexo 常见命令



npm install hexo -g #安装Hexo
npm update hexo -g #升级
hexo init #初始化博客

命令简写
hexo n "我的博客" == hexo new "我的博客" #新建文章
hexo g == hexo generate #生成
hexo s == hexo server #启动服务预览
hexo d == hexo deploy #部署

hexo server #Hexo会监视文件变动并自动更新，无须重启服务器
hexo server -s #静态模式
hexo server -p 5000 #更改端口
hexo server -i 192.168.1.1 #自定义 IP
hexo clean #清除缓存，若是网页正常情况下可以忽略这条命令 



## 给文章打上标签

打开标签功能：

```
hexo new page tags
```

这时候你的source/下生成 tags/index.md 文件，我们将其打开，然后把它改成：

```
    type: "tags"
    comments: false
```

这时候你要为你的文章打上标签就可以在文章的头部写上：

```
    tags:
        - Tag1
        - Tag2
        - Tag3
```




## 给你的文章添加分类

分类，归档，是你博客的特性之一。

打开分类功能：

```
hexo new page categories
```

这说你的source目录下生成 categories/index.md 文件，我们将其打开，把它改成：

```
type: "categories"
comments: false
```

这时候你就可以给你的文章归类存档了，使用方式就是在你的文章的头部加上：

```
categories:
	- 分类1
	- 分类2
```

注意：标签和分类要确定你的配置文件 _config.yml 是否有打开了 tag_dir: tags 和 category_dir: categories。





## 添加点击特效

### Hexo主题增加鼠标点击效果



#### 弹字

##### 流程

将以下代码放到主题源文件目录下，如 `/themes/yilia/source/js/` 下新建文件 `click-word.js`，代码见下文。
下载jquery文件（[目前最新版本](https://code.jquery.com/jquery-3.3.1.min.js)），放到同一目录下（个人喜好）。
修改布局文件，`/themes/next/layout/_layout.ejs`，在末尾body中添加

```
<!-- 页面点击特效 --><script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script><script type="text/javascript" src="/js/click-word.js"></script>
```



保存后重新生成即可

##### js代码

```
var a_idx = 0;
jQuery(document).ready(function($) {
    $("body").click(function(e) {
        var a = new Array("富强", "民主", "文明", "和谐", "自由", "平等", "公正" ,"法治", "爱国", "敬业", "诚信", "友善");
        var $i = $("<span />").text(a[a_idx]);
        a_idx = (a_idx + 1) % a.length;
        var x = e.pageX,
        y = e.pageY;
        $i.css({
            "z-index": 999999999999999999999999999999999999999999999999999999999999999999999,
            "top": y - 20,
            "left": x,
            "position": "absolute",
            "font-weight": "bold",
            "color": "#ff6651"
        });
        $("body").append($i);
        $i.animate({
            "top": y - 180,
            "opacity": 0
        },
        1500,
        function() {
            $i.remove();
        });
    });
});
```



### 实现点击出现桃心效果

  

1. 在`/themes/*/source/js/src`下新建文件`click.js`，接着把以下粘贴到`click.js`文件中。 代码如下：

```
!function(e,t,a){function n(){c(".heart{width: 10px;height: 10px;position: fixed;background: #f00;transform: rotate(45deg);-webkit-transform: rotate(45deg);-moz-transform: rotate(45deg);}.heart:after,.heart:before{content: '';width: inherit;height: inherit;background: inherit;border-radius: 50%;-webkit-border-radius: 50%;-moz-border-radius: 50%;position: fixed;}.heart:after{top: -5px;}.heart:before{left: -5px;}"),o(),r()}function r(){for(var e=0;e<d.length;e++)d[e].alpha<=0?(t.body.removeChild(d[e].el),d.splice(e,1)):(d[e].y--,d[e].scale+=.004,d[e].alpha-=.013,d[e].el.style.cssText="left:"+d[e].x+"px;top:"+d[e].y+"px;opacity:"+d[e].alpha+";transform:scale("+d[e].scale+","+d[e].scale+") rotate(45deg);background:"+d[e].color+";z-index:99999");requestAnimationFrame(r)}function o(){var t="function"==typeof e.onclick&&e.onclick;e.onclick=function(e){t&&t(),i(e)}}function i(e){var a=t.createElement("div");a.className="heart",d.push({el:a,x:e.clientX-5,y:e.clientY-5,scale:1,alpha:1,color:s()}),t.body.appendChild(a)}function c(e){var a=t.createElement("style");a.type="text/css";try{a.appendChild(t.createTextNode(e))}catch(t){a.styleSheet.cssText=e}t.getElementsByTagName("head")[0].appendChild(a)}function s(){return"rgb("+~~(255*Math.random())+","+~~(255*Math.random())+","+~~(255*Math.random())+")"}var d=[];e.requestAnimationFrame=function(){return e.requestAnimationFrame||e.webkitRequestAnimationFrame||e.mozRequestAnimationFrame||e.oRequestAnimationFrame||e.msRequestAnimationFrame||function(e){setTimeout(e,1e3/60)}}(),n()}(window,document);

```

1. 在`\themes\*\layout\_layout.swig`文件末尾添加：

```
<!-- 页面点击小红心 -->
<script type="text/javascript" src="/js/src/clicklove.js"></script>


```

##### 


