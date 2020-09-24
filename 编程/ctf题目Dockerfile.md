# Dockerfile

## readflag

```
COPY flag /flag
RUN chown root:root /flag && \
    chmod 000 /flag
RUN echo 'int main() {setuid(0);system("cat /flag");return 0;}' | gcc -xc - -o /readflag 2>/dev/null
RUN chown root:root /readflag && \
    chmod 4001 /readflag
```

