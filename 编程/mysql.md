# mysql

## 设置

vim /etc/mysql/mysql.conf.d/mysqld.cnf

### 修改密码

```mysql
set password for root@localhost = password('123');
```

## 允许远程连接

```
GRANT ALL  PRIVILEGES ON *.* TO 'root'@'%'IDENTIFIED BY 'password' WITH GRANT OPTION;
```

删除配置文件中的bind_address和skip_networking