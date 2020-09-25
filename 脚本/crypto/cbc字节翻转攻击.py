import base64
iv='w+tXOtqCoxQHWWvQOzLYDg=='.decode('base64')
ci="CVWXZDimKKgGoXMsKos0UOHdMzG/d2bB+v1WqC6bOongufcRUyB5fgiiJdlLG1CDwKCfkdXXzCrru0wL2F749g==".decode('base64')
old='a:2:{s:8:"username";s:5:"skctf";s:8:"password";s:5:"skctf";}'[16:32]
new='a:2:{s:8:"username";s:5:"admin";s:8:"password";s:5:"skctf";}'[16:32]
for i in range(16):
        ci=ci[:i]+chr(ord(ci[i])^ord(old[i])^ord(new[i]))+ci[i+1:]
