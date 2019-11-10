# burp

## intruder

### attack type介绍

#### Sniper

使用同一个wordlist分别fuzz不同参数

```
1st request - param1=wordlist[0]&param2=
2nd request - param1=wordlist[1]&param2=
...

After enumerating through param1 with all the payloads from wordlist,

1st request - param1=&param2=wordlist[0]
2nd request - param1=&param2=wordlist[1]
...
```

#### Battering Ram

用一个wordlist来fuzz,每个要fuzz的参数的值相同

```
1st req - param1=wordlist[0]&param2=wordlist[0]
2nd req - param1=wordlist[1]&param2=wordlist[1]
3rd req - param1=wordlist[2]&param2=wordlist[2]
4th req - param1=wordlist[3]&param2=wordlist[3]
5th req - param1=wordlist[4]&param2=wordlist[4]
...
```



#### Pitchfork

同时对多个参数fuzz且使用不同wordlist

```
1st request - param1=wordlist1[0]&param2=wordlist2[0] 
2nd request - param1=wordlist1[1]&param2=wordlist2[1]
...
```

#### Cluster Bomb

对多个参数使用不同wordlist来fuzz,排列组合来fuzz所有可能的组合

```
1st request - param1=wordlist1[0]&param2=wordlist2[0]
2nd request - param1=wordlist1[1]&param2=wordlist2[0]
3rd request - param1=wordlist1[2]&param2=wordlist2[0]
...

After enumerating through param1 with all the payloads from wordlist1,

1st request - param1=wordlist1[0]&param2=wordlist2[1]
2nd request - param1=wordlist1[1]&param2=wordlist2[1]
3rd request - param1=wordlist1[2]&param2=wordlist2[1]
...
```

