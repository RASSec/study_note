# flask

## console pin值的生成分析

用vscode调试后

发现关键代码

`env\Lib\site-packages\werkzeug\debug\__init__.py`

```python
def get_pin_and_cookie_name(app):
    """Given an application object this returns a semi-stable 9 digit pin
    code and a random key.  The hope is that this is stable between
    restarts to not make debugging particularly frustrating.  If the pin
    was forcefully disabled this returns `None`.

    Second item in the resulting tuple is the cookie name for remembering.
    """
    pin = os.environ.get("WERKZEUG_DEBUG_PIN")
    rv = None
    num = None

    # Pin was explicitly disabled
    if pin == "off":
        return None, None

    # Pin was provided explicitly
    if pin is not None and pin.replace("-", "").isdigit():
        # If there are separators in the pin, return it directly
        if "-" in pin:
            rv = pin
        else:
            num = pin

    modname = getattr(app, "__module__", app.__class__.__module__)

    try:
        # getuser imports the pwd module, which does not exist in Google
        # App Engine. It may also raise a KeyError if the UID does not
        # have a username, such as in Docker.
        username = getpass.getuser()
    except (ImportError, KeyError):
        username = None

    mod = sys.modules.get(modname)

    # This information only exists to make the cookie unique on the
    # computer, not as a security feature.
    probably_public_bits = [
        username,
        modname,
        getattr(app, "__name__", app.__class__.__name__),
        getattr(mod, "__file__", None),
    ]

    # This information is here to make it harder for an attacker to
    # guess the cookie name.  They are unlikely to be contained anywhere
    # within the unauthenticated debug page.
    private_bits = [str(uuid.getnode()), get_machine_id()]

    h = hashlib.md5()
    for bit in chain(probably_public_bits, private_bits):
        if not bit:
            continue
        if isinstance(bit, text_type):
            bit = bit.encode("utf-8")
        h.update(bit)
    h.update(b"cookiesalt")

    cookie_name = "__wzd" + h.hexdigest()[:20]

    # If we need to generate a pin we salt it a bit more so that we don't
    # end up with the same value and generate out 9 digits
    if num is None:
        h.update(b"pinsalt")
        num = ("%09d" % int(h.hexdigest(), 16))[:9]

    # Format the pincode in groups of digits for easier remembering if
    # we don't have a result yet.
    if rv is None:
        for group_size in 5, 4, 3:
            if len(num) % group_size == 0:
                rv = "-".join(
                    num[x : x + group_size].rjust(group_size, "0")
                    for x in range(0, len(num), group_size)
                )
                break
        else:
            rv = num

    return rv, cookie_name

```

rv就是我们要找的pin值,`rv<-num`,num是哈希值,影响他的主要是`private_bits = [str(uuid.getnode()), get_machine_id()]`和`probably_public_bits `

合起来就是

```python
    probably_public_bits = [
        username,
        #username=getpass.getuser() or None
        #按顺序查看'LOGNAME', 'USER', 'LNAME', 'USERNAME'这几个环境变量
        #在docker环境下就有可能为None
        modname,
        #modname=sys.modules.get(modname)
        #modname="flask.app"
        getattr(app, "__name__", app.__class__.__name__),
        #这个值为Flask
        getattr(mod, "__file__", None),
        #这个值为flask/app.py的绝对路径
        #mod = sys.modules.get(modname)
    ]
    private_bits = [
        str(uuid.getnode()), 
        #网卡mac地址(10进制)
        #/sys/class/net/eth0/address
        #根据自己的实际情况调一调
        get_machine_id()
        #linux先读取/etc/machine-id读取到直接返回,如果没有再读取/proc/sys/kernel/random/boot_id
        #docker环境直接有所不同,读取/proc/self/cgroup.
        #try:
#             with open("/proc/self/cgroup") as f:
#                 value = f.readline()
#         except IOError:
#             pass
#         else:
#             value = value.strip().partition("/docker/")[2]

#             if value:
#                 return value
        #当初看别人的代码分析被这个坑了半天
        #windows和mac的自己看吧
        
    ]
```



### get_machine_id()

windows和mac部分的代码

```python
def get_machine_id():
    def _generate():
    # On OS X we can use the computer's serial number assuming that
        # ioreg exists and can spit out that information.
        try:
            # Also catch import errors: subprocess may not be available, e.g.
            # Google App Engine
            # See https://github.com/pallets/werkzeug/issues/925
            from subprocess import Popen, PIPE

            dump = Popen(
                ["ioreg", "-c", "IOPlatformExpertDevice", "-d", "2"], stdout=PIPE
            ).communicate()[0]
            match = re.search(b'"serial-number" = <([^>]+)', dump)
            if match is not None:
                return match.group(1)
        except (OSError, ImportError):
            pass

        # On Windows we can use winreg to get the machine guid
        wr = None
        try:
            import winreg as wr
        except ImportError:
            try:
                import _winreg as wr
            except ImportError:
                pass
        if wr is not None:
            try:
                with wr.OpenKey(
                    wr.HKEY_LOCAL_MACHINE,
                    "SOFTWARE\\Microsoft\\Cryptography",
                    0,
                    wr.KEY_READ | wr.KEY_WOW64_64KEY,
                ) as rk:
                    machineGuid, wrType = wr.QueryValueEx(rk, "MachineGuid")
                    if wrType == wr.REG_SZ:
                        return machineGuid.encode("utf-8")
                    else:
                        return machineGuid
            except WindowsError:
                pass

    _machine_id = rv = _generate()
    return rv

```

