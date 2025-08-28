#/bin/bash
/usr/lib/gcc/x86_64-pc-cygwin/6.4.0/cc1.exe -quiet -E test.c -o test.i
/usr/lib/gcc/x86_64-pc-cygwin/6.4.0/cc1.exe -quiet -fpreprocessed test.i -o test.S
/usr/x86_64-pc-cygwin/bin/as.exe --gdwarf2 -o test.o test.S
/usr/lib/gcc/x86_64-pc-cygwin/6.4.0/collect2.exe -o test.exe test.o /usr/lib/crt0.o -lcygwin -lkernel32
