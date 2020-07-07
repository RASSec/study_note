var logurl = (xhr,url)=>{
	if(xhr.status==200 && xhr.readyState == 4){
		console.log("success:"+url);
	}
}

var testurl= (url)=>{
	var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function(){logurl(xmlhttp,url);};
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
for(var i=0;i<255;i++){
	var url = "http://192.168.43."+i+":80";
	testurl(url);
}