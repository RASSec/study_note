# ctf常见解密脚本

## base64decode+异或

```python
import string
from base64 import *
b=b64decode("aWdxNDs6NDFSOzFpa1I1MWliT08w")
data=list(b)
print(data)
for k in range(256):
    key=""
    for i in range(len(data)):
        key+=(chr(data[i]^k))
    print(key)
```

