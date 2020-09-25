#!/usr/bin/env python
# encoding: utf-8
import flask 
import os
cnt=0
for item in "".__class__.__mro__[-1].__subclasses__():
    try:
        cnt2=0
        for i in item.__init__.__globals__:
            if 'eval' in item.__init__.__globals__[i]:
                print(cnt,item,cnt2,i)
            cnt2+=1
        cnt+=1
    except:
        #print("error",cnt,item)
        cnt+=1
        continue