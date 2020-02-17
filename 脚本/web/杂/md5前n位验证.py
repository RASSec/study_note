import hashlib

def cal(arg):
    i=1
    try:
        while True:
            m = hashlib.md5()
            m.update(bytes(str(i),encoding="ascii"))
            m=m.hexdigest()
            if m[:len(arg)]==arg:
                print(i)
                break
            i+=1
    except Exception as ex:
        print(ex)

cal(input().strip())