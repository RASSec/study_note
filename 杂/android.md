# android

## 开启shell

连接电脑开启usb调试后，运行如下命令

`adb shell`



## 安装busybox



```
adb push ~/Desktop/busybox-armv6l /mnt/sdcard
mount|grep -v "/system/" | grep system
mount -o remount,rw /dev/block/sda14 /system
```



## 交叉编译

原本是想在安卓中利用make来安装软件，但是安卓毕竟只有linux 内核而已，直接make来安装的话会少很多依赖，所以决定通过交叉编译来安装软件

