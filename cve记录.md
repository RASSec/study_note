# cve记录

## shellshock

https://blog.csdn.net/Anprou/article/details/72819989

### 利用条件

1. 受影响bash版本

- Bash 4.3 Patch 25 （含）以前版本
- Bash 4.2 Patch 48 （含）以前版本
- Bash 4.1 Patch 12 （含）以前版本
- Bash 4.0 Patch 39 （含）以前版本
- Bash 3.2 Patch 52 （含）以前版本
- Bash 3.1 Patch 18 （含）以前版本
- Bash 3.0 Patch 17 （含）以前版本
- Bash 2.0.5b Patch 8 （含）以前版本
- Bash 1.14.7 （含）以前版本

2. 包含系统环境变量

### 利用方式

向环境变量写入:`xxxx= (){ :; };echo hello;`



