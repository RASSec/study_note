# windows命令记录



## 校验md5

certutil

基本命令：

```shell
CertUtil -hashfile pathToFileToCheck 
[HashAlgorithm]：MD2 MD4 MD5 SHA1 SHA256 SHA384 SHA512
CertUtil -hashfile C:\TEMP\MyDataFile.img MD5
```

