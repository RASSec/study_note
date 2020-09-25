arr= [ 'collections.OrderedDict'  ,  'collections.defaultdict'  ,  'collections.Counter'  ,  'enum._EnumDict'  ,  'werkzeug.datastructures.TypeConversionDict'  ,  'werkzeug.datastructures.ImmutableDict'  ,  'werkzeug.datastructures._CacheControl'  ,  'werkzeug.datastructures.CallbackDict'  ,  'werkzeug.datastructures.Authorization'  ,  'werkzeug.datastructures.WWWAuthenticate'  ,  'email._encoded_words._QByteMap'  ,  'blinker.base.Namespace'  ,  'flask.config.Config'  ,  'StgDict'  ]
f=open("result.txt","w")
cnt=0
for i in arr:
    a=i.split(".")
    e='__import__("{}")'.format(a[0])
    for j in a[1:]:
        e=e+"."+j
    try:
        s=eval(e)
    except:
        pass
    print(s)
    item=s
    
    # try:
    try :
        if 'os' in item.__init__.__globals__:
            f.write(str(cnt)+str(item))
            print("error"+str(cnt)+str(item))
        cnt+=1
    except AttributeError as e:
        print(e)
    except:
        f.write("error"+str(cnt)+str(item))
        print("error"+str(cnt)+str(item))
        cnt+=1
        continue
f.close()