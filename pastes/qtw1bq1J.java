#
# A fatal error has been detected by the Java Runtime Environment:
#
#  EXCEPTION_ACCESS_VIOLATION (0xc0000005) at pc=0x04ab3203, pid=11272, tid=14124
#
# JRE version: 7.0_03-b05
# Java VM: Java HotSpot(TM) Client VM (22.1-b02 mixed mode, sharing windows-x86 )
# Problematic frame:
# C  0x04ab3203
#
# Failed to write core dump. Minidumps are not enabled by default on client versions of Windows
#
# If you would like to submit a bug report, please visit:
#   http://bugreport.sun.com/bugreport/crash.jsp
# The crash happened outside the Java Virtual Machine in native code.
# See problematic frame for where to report the bug.
#

---------------  T H R E A D  ---------------

Current thread (0x045dbc00):  JavaThread "Thread-8" [_thread_in_native, id=14124, stack(0x05ac0000,0x05b10000)]

siginfo: ExceptionCode=0xc0000005, ExceptionInformation=0x00000008 0x04ab3203

Registers:
EAX=0x007e3d10, EBX=0x045dbd28, ECX=0x77b37076, EDX=0x00000001
ESP=0x05b0f340, EBP=0x05b0f368, ESI=0x00000017, EDI=0x04581618
EIP=0x04ab3203, EFLAGS=0x00010202

Top of Stack: (sp=0x05b0f340)
0x05b0f340:   00000018 04581618 00df3e44 a399c8d8
0x05b0f350:   00000000 00cb0000 00000000 76920000
0x05b0f360:   34676120 045dbc00 05b0f7a8 04ab32a8
0x05b0f370:   00d4014c 0596ecd0 00d437e0 05b0f324
0x05b0f380:   5b1dc1f4 043376d4 00000000 5b1dc7c7
0x05b0f390:   1eca5808 1865a3f7 045dbc00 05b0f418
0x05b0f3a0:   5b1ec304 00000148 1865a3f7 39621b3c
0x05b0f3b0:   39445cc8 5b1e70d6 05b0f3cc 5b1e70f2 

Instructions: (pc=0x04ab3203)
0x04ab31e3:   
[error occurred during error reporting (printing registers, top of stack, instructions near pc), id 0xc0000005]

Register to memory mapping:

EAX=0x007e3d10 is an unknown value
EBX=0x045dbd28 is an unknown value
ECX=0x77b37076 is an unknown value
EDX=0x00000001 is an unknown value
ESP=0x05b0f340 is pointing into the stack for thread: 0x045dbc00
EBP=0x05b0f368 is pointing into the stack for thread: 0x045dbc00
ESI=0x00000017 is an unknown value
EDI=0x04581618 is an unknown value


Stack: [0x05ac0000,0x05b10000],  sp=0x05b0f340,  free space=316k
Native frames: (J=compiled Java code, j=interpreted, Vv=VM code, C=native code)
C  0x04ab3203
C  0x04ab32a8
j  com.sun.jna.NativeLibrary.findSymbol(JLjava/lang/String;)J+0
j  com.sun.jna.NativeLibrary.getSymbolAddress(Ljava/lang/String;)J+24
j  com.sun.jna.Function.<init>(Lcom/sun/jna/NativeLibrary;Ljava/lang/String;I)V+51
j  com.sun.jna.NativeLibrary.getFunction(Ljava/lang/String;I)Lcom/sun/jna/Function;+56
j  com.sun.jna.NativeLibrary.getFunction(Ljava/lang/String;Ljava/lang/reflect/Method;)Lcom/sun/jna/Function;+67
j  com.sun.jna.Library$Handler.invoke(Ljava/lang/Object;Ljava/lang/reflect/Method;[Ljava/lang/Object;)Ljava/lang/Object;+228
j  $Proxy6.SetThreadExecutionState(I)V+19
J  jd.plugins.optional.antistandby.JDAntiStandbyThread.run()V
V  [jvm.dll+0x123c0a]
V  [jvm.dll+0x1c9e8e]
V  [jvm.dll+0x123df3]
V  [jvm.dll+0x123e57]
V  [jvm.dll+0xcd90f]
V  [jvm.dll+0x14394f]
V  [jvm.dll+0x1720b9]
C  [msvcr100.dll+0x5c6de]  endthreadex+0x3a
C  [msvcr100.dll+0x5c788]  endthreadex+0xe4
C  [kernel32.dll+0x4d309]  BaseThreadInitThunk+0x12
C  [ntdll.dll+0x41603]  RtlInitializeExceptionChain+0x63
C  [ntdll.dll+0x415d6]  RtlInitializeExceptionChain+0x36

Java frames: (J=compiled Java code, j=interpreted, Vv=VM code)
j  com.sun.jna.NativeLibrary.findSymbol(JLjava/lang/String;)J+0
j  com.sun.jna.NativeLibrary.getSymbolAddress(Ljava/lang/String;)J+24
j  com.sun.jna.Function.<init>(Lcom/sun/jna/NativeLibrary;Ljava/lang/String;I)V+51
j  com.sun.jna.NativeLibrary.getFunction(Ljava/lang/String;I)Lcom/sun/jna/Function;+56
j  com.sun.jna.NativeLibrary.getFunction(Ljava/lang/String;Ljava/lang/reflect/Method;)Lcom/sun/jna/Function;+67
j  com.sun.jna.Library$Handler.invoke(Ljava/lang/Object;Ljava/lang/reflect/Method;[Ljava/lang/Object;)Ljava/lang/Object;+228
j  $Proxy6.SetThreadExecutionState(I)V+19
J  jd.plugins.optional.antistandby.JDAntiStandbyThread.run()V
v  ~StubRoutines::call_stub

---------------  P R O C E S S  ---------------

Java Threads: ( => current thread )
  0x0546bc00 JavaThread "Thread-4613" [_thread_blocked, id=6768, stack(0x06400000,0x06450000)]
  0x05469400 JavaThread "CaptchaDialogQueue" daemon [_thread_blocked, id=1708, stack(0x068b0000,0x06900000)]
  0x045de400 JavaThread "ThrottlecConnectionManager" [_thread_blocked, id=15404, stack(0x06770000,0x067c0000)]
  0x045e1000 JavaThread "SpeedMeterCache" [_thread_blocked, id=10384, stack(0x05cd0000,0x05d20000)]
  0x045dfc00 JavaThread "FilePackageInfoCache" daemon [_thread_blocked, id=6376, stack(0x05f30000,0x05f80000)]
  0x045df400 JavaThread "DownloadLinkInfoCache" daemon [_thread_blocked, id=11900, stack(0x05030000,0x05080000)]
  0x045df000 JavaThread "LinkGrabberView: infoupdate" [_thread_blocked, id=10752, stack(0x05dc0000,0x05e10000)]
  0x045de800 JavaThread "DownloadView: infoupdate" [_thread_blocked, id=15296, stack(0x05e80000,0x05ed0000)]
  0x045ddc00 JavaThread "StatusBarPremiumUpdateThread" [_thread_blocked, id=10188, stack(0x04900000,0x04950000)]
  0x045dc400 JavaThread "PremiumStatusUpdateTimer" [_thread_blocked, id=15764, stack(0x05080000,0x050d0000)]
  0x045dd400 JavaThread "FavIconLoader" daemon [_thread_blocked, id=15060, stack(0x04e90000,0x04ee0000)]
  0x045dd000 JavaThread "ClipboardHandler" [_thread_in_native, id=13516, stack(0x05d40000,0x05d90000)]
  0x045dc800 JavaThread "Thread-11" daemon [_thread_blocked, id=14944, stack(0x051c0000,0x05210000)]
=>0x045dbc00 JavaThread "Thread-8" [_thread_in_native, id=14124, stack(0x05ac0000,0x05b10000)]
  0x045db800 JavaThread "DestroyJavaVM" [_thread_blocked, id=9676, stack(0x01b10000,0x01b60000)]
  0x045db000 JavaThread "TimerQueue" daemon [_thread_blocked, id=13452, stack(0x050e0000,0x05130000)]
  0x04739000 JavaThread "SyntheticaCleanerThread" daemon [_thread_blocked, id=6272, stack(0x05170000,0x051c0000)]
  0x042c5000 JavaThread "AWT-EventQueue-0" [_thread_blocked, id=11684, stack(0x03f40000,0x03f90000)]
  0x042bd800 JavaThread "AWT-Shutdown" [_thread_blocked, id=16004, stack(0x04b80000,0x04bd0000)]
  0x042bd000 JavaThread "Java2D Disposer" daemon [_thread_blocked, id=2592, stack(0x04b10000,0x04b60000)]
  0x0457e400 JavaThread "SingleAppInstance: JD" daemon [_thread_in_native, id=13176, stack(0x049f0000,0x04a40000)]
  0x04564c00 JavaThread "EventSender:watchDog" [_thread_blocked, id=16200, stack(0x04a60000,0x04ab0000)]
  0x04564400 JavaThread "EventSender:runDog" [_thread_blocked, id=6452, stack(0x04950000,0x049a0000)]
  0x00e01000 JavaThread "Service Thread" daemon [_thread_blocked, id=11548, stack(0x04040000,0x04090000)]
  0x00dfec00 JavaThread "C1 CompilerThread0" daemon [_thread_blocked, id=10236, stack(0x03ff0000,0x04040000)]
  0x00dfa400 JavaThread "Attach Listener" daemon [_thread_blocked, id=9204, stack(0x04110000,0x04160000)]
  0x00df7000 JavaThread "Signal Dispatcher" daemon [_thread_blocked, id=13104, stack(0x03e90000,0x03ee0000)]
  0x00de6000 JavaThread "Finalizer" daemon [_thread_blocked, id=16032, stack(0x040b0000,0x04100000)]
  0x00de1400 JavaThread "Reference Handler" daemon [_thread_blocked, id=4308, stack(0x03f90000,0x03fe0000)]

Other Threads:
  0x00ddf400 VMThread [stack: 0x03ef0000,0x03f40000] [id=3584]
  0x00e13800 WatcherThread [stack: 0x03df0000,0x03e40000] [id=8852]

VM state:not at safepoint (normal execution)

VM Mutex/Monitor currently owned by a thread: None

Heap
 def new generation   total 39616K, used 33581K [0x13e40000, 0x16930000, 0x1e8e0000)
  eden space 35264K,  91% used [0x13e40000, 0x15dc19a0, 0x160b0000)
  from space 4352K,  30% used [0x160b0000, 0x161f9cd0, 0x164f0000)
  to   space 4352K,   0% used [0x164f0000, 0x164f0000, 0x16930000)
 tenured generation   total 87824K, used 45746K [0x1e8e0000, 0x23ea4000, 0x33e40000)
   the space 87824K,  52% used [0x1e8e0000, 0x2158c868, 0x2158ca00, 0x23ea4000)
 compacting perm gen  total 27136K, used 27040K [0x33e40000, 0x358c0000, 0x37e40000)
   the space 27136K,  99% used [0x33e40000, 0x358a8038, 0x358a8200, 0x358c0000)
    ro space 10240K,  42% used [0x37e40000, 0x3827db28, 0x3827dc00, 0x38840000)
    rw space 12288K,  54% used [0x38840000, 0x38ebe400, 0x38ebe400, 0x39440000)

Code Cache  [0x01cc0000, 0x027b0000, 0x03cc0000)
 total_blobs=5708 nmethods=5428 adapters=212 free_code_cache=21650Kb largest_free_block=22111936

Dynamic libraries:
0x00ee0000 - 0x00f0f000 	C:\Program Files\Java\jre7\bin\javaw.exe
0x77ad0000 - 0x77bf8000 	C:\Windows\system32\ntdll.dll
0x76920000 - 0x769fc000 	C:\Windows\system32\kernel32.dll
0x76190000 - 0x76256000 	C:\Windows\system32\ADVAPI32.dll
0x776b0000 - 0x77773000 	C:\Windows\system32\RPCRT4.dll
0x766c0000 - 0x7675d000 	C:\Windows\system32\USER32.dll
0x76670000 - 0x766bb000 	C:\Windows\system32\GDI32.dll
0x74e40000 - 0x74fde000 	C:\Windows\WinSxS\x86_microsoft.windows.common-controls_6595b64144ccf1df_6.0.6002.18305_none_5cb72f2a088b0ed3\COMCTL32.dll
0x76a00000 - 0x76aaa000 	C:\Windows\system32\msvcrt.dll
0x77600000 - 0x77659000 	C:\Windows\system32\SHLWAPI.dll
0x77c30000 - 0x77c4e000 	C:\Windows\system32\IMM32.DLL
0x77c60000 - 0x77d28000 	C:\Windows\system32\MSCTF.dll
0x77c50000 - 0x77c59000 	C:\Windows\system32\LPK.DLL
0x77940000 - 0x779bd000 	C:\Windows\system32\USP10.dll
0x5d430000 - 0x5d4ee000 	C:\Program Files\Java\jre7\bin\msvcr100.dll
0x5b1c0000 - 0x5b4e8000 	C:\Program Files\Java\jre7\bin\client\jvm.dll
0x73c90000 - 0x73c97000 	C:\Windows\system32\WSOCK32.dll
0x77c00000 - 0x77c2d000 	C:\Windows\system32\WS2_32.dll
0x763f0000 - 0x763f6000 	C:\Windows\system32\NSI.dll
0x74990000 - 0x749c2000 	C:\Windows\system32\WINMM.dll
0x76400000 - 0x76545000 	C:\Windows\system32\ole32.dll
0x779c0000 - 0x77a4d000 	C:\Windows\system32\OLEAUT32.dll
0x74950000 - 0x7498e000 	C:\Windows\system32\OLEACC.dll
0x76180000 - 0x76187000 	C:\Windows\system32\PSAPI.DLL
0x75170000 - 0x7517c000 	C:\Program Files\Java\jre7\bin\verify.dll
0x73000000 - 0x73020000 	C:\Program Files\Java\jre7\bin\java.dll
0x72fe0000 - 0x72ff3000 	C:\Program Files\Java\jre7\bin\zip.dll
0x72f80000 - 0x72f94000 	C:\Program Files\Java\jre7\bin\net.dll
0x75830000 - 0x7586b000 	C:\Windows\system32\mswsock.dll
0x758a0000 - 0x758a5000 	C:\Windows\System32\wship6.dll
0x75130000 - 0x7513f000 	C:\Program Files\Java\jre7\bin\nio.dll
0x75380000 - 0x75385000 	C:\Windows\System32\wshtcpip.dll
0x5b6d0000 - 0x5b812000 	C:\Program Files\Java\jre7\bin\awt.dll
0x74e00000 - 0x74e3f000 	C:\Windows\system32\uxtheme.dll
0x10000000 - 0x10005000 	C:\Program Files\Unlocker\UnlockerHook.dll
0x76ae0000 - 0x775f1000 	C:\Windows\system32\SHELL32.dll
0x6d970000 - 0x6d97c000 	C:\Windows\system32\DWMAPI.DLL
0x72f40000 - 0x72f60000 	C:\Program Files\Java\jre7\bin\sunec.dll
0x74740000 - 0x7474f000 	C:\Windows\system32\NLAapi.dll
0x759e0000 - 0x759f9000 	C:\Windows\system32\IPHLPAPI.DLL
0x759a0000 - 0x759d5000 	C:\Windows\system32\dhcpcsvc.DLL
0x75c40000 - 0x75c6c000 	C:\Windows\system32\DNSAPI.dll
0x75fe0000 - 0x75ff4000 	C:\Windows\system32\Secur32.dll
0x75990000 - 0x75997000 	C:\Windows\system32\WINNSI.DLL
0x75960000 - 0x75982000 	C:\Windows\system32\dhcpcsvc6.DLL
0x724a0000 - 0x724af000 	C:\Windows\system32\napinsp.dll
0x72460000 - 0x72472000 	C:\Windows\system32\pnrpnsp.dll
0x72490000 - 0x72498000 	C:\Windows\System32\winrnr.dll
0x77660000 - 0x776a9000 	C:\Windows\system32\WLDAP32.dll
0x72480000 - 0x7248c000 	C:\Windows\system32\wshbth.dll
0x76260000 - 0x763ea000 	C:\Windows\system32\SETUPAPI.dll
0x718f0000 - 0x71911000 	C:\Program Files\Bonjour\mdnsNSP.dll
0x72600000 - 0x72606000 	C:\Windows\system32\rasadhlp.dll
0x63760000 - 0x637ee000 	C:\Program Files\Java\jre7\bin\mlib_image.dll
0x666f0000 - 0x66714000 	C:\Program Files\Java\jre7\bin\dcpr.dll
0x65fe0000 - 0x6600a000 	C:\Program Files\Java\jre7\bin\fontmanager.dll
0x66bc0000 - 0x66bf1000 	C:\Program Files\Java\jre7\bin\t2k.dll
0x75120000 - 0x75129000 	C:\Program Files\Java\jre7\bin\sunmscapi.dll
0x75aa0000 - 0x75b92000 	C:\Windows\system32\CRYPT32.dll
0x75c00000 - 0x75c12000 	C:\Windows\system32\MSASN1.dll
0x76000000 - 0x7601e000 	C:\Windows\system32\USERENV.dll
0x754d0000 - 0x7550b000 	C:\Windows\system32\rsaenh.dll
0x6a2e0000 - 0x6a342000 	C:\Windows\system32\mscms.dll
0x70260000 - 0x702a2000 	C:\Windows\system32\WINSPOOL.DRV
0x6b920000 - 0x6b958000 	C:\Windows\system32\icm32.dll
0x778a0000 - 0x77924000 	C:\Windows\system32\CLBCatQ.DLL
0x74200000 - 0x742f4000 	C:\Windows\system32\WindowsCodecs.dll
0x75f00000 - 0x75f2c000 	C:\Windows\system32\apphelp.dll
0x6ccc0000 - 0x6cce2000 	C:\Program Files\Alwil Software\Avast5\ashShell.dll
0x6dca0000 - 0x6dec7000 	C:\Windows\system32\msi.dll
0x06080000 - 0x061fe000 	C:\Program Files\Livedrive\LivedriveExtensions.dll
0x6c250000 - 0x6c2d7000 	C:\Windows\WinSxS\x86_microsoft.vc80.crt_1fc8b3b9a1e18e3b_8.0.50727.6195_none_d09154e044272b9a\MSVCP80.dll
0x71d00000 - 0x71d9b000 	C:\Windows\WinSxS\x86_microsoft.vc80.crt_1fc8b3b9a1e18e3b_8.0.50727.6195_none_d09154e044272b9a\MSVCR80.dll
0x6cda0000 - 0x6cdbf000 	C:\Windows\system32\EhStorShell.dll
0x74810000 - 0x748cb000 	C:\Windows\system32\PROPSYS.dll
0x6bc80000 - 0x6be9f000 	C:\Program Files\Microsoft Office\Office12\GrooveShellExtensions.dll
0x6bb80000 - 0x6bc73000 	C:\Program Files\Microsoft Office\Office12\GrooveUtil.DLL
0x76550000 - 0x7666b000 	C:\Windows\system32\WININET.dll
0x77930000 - 0x77933000 	C:\Windows\system32\Normaliz.dll
0x76760000 - 0x76918000 	C:\Windows\system32\iertutil.dll
0x77780000 - 0x77891000 	C:\Windows\system32\urlmon.dll
0x6cd90000 - 0x6cd97000 	C:\Program Files\Microsoft Office\Office12\GrooveNew.DLL
0x75450000 - 0x75458000 	C:\Windows\system32\VERSION.dll
0x6cc80000 - 0x6cc9b000 	C:\Windows\WinSxS\x86_microsoft.vc80.atl_1fc8b3b9a1e18e3b_8.0.50727.6195_none_d1cb102c435421de\ATL80.DLL
0x75440000 - 0x75445000 	C:\Windows\system32\MSImg32.dll
0x73740000 - 0x737c5000 	C:\Windows\WinSxS\x86_microsoft.windows.common-controls_6595b64144ccf1df_5.82.6002.18305_none_88f3a38569c2c436\comctl32.dll
0x61fd0000 - 0x61ff5000 	C:\Program Files\Java\jre7\bin\jpeg.dll
0x732e0000 - 0x7330c000 	C:\Program Files\Notepad++\plugins\nppplugin_solutiontools.dll

VM Arguments:
jvm_args: -Xmx512m -Dsun.java2d.d3d=false 
java_command: c:\JDownloaderBETA\JDownloader.jar -branch NIGHTLY
Launcher Type: SUN_STANDARD

Environment Variables:
CLASSPATH=.;.;C:\PROGRA~1\JMF21~1.1E\lib\sound.jar;C:\PROGRA~1\JMF21~1.1E\lib\jmf.jar;C:\PROGRA~1\JMF21~1.1E\lib;
PATH=C:\Program Files\Common Files\Microsoft Shared\Windows Live;C:\Windows\system32;C:\Windows;C:\Windows\System32\Wbem;C:\Windows\System32\WindowsPowerShell\v1.0;C:\strawberry-perl-5.12.1.0\c\bin;C:\strawberry-perl-5.12.1.0\perl\site\bin;C:\strawberry-perl-5.12.1.0\perl\bin;C:\Program Files\Windows Live\Shared;C:\Program Files\QuickTime\QTSystem;C:\Program Files\GPAC;C:\Program Files\Calibre2\;C:\Tcl\bin;C:\Program Files\Common Files\Microsoft Shared\Windows Live;C:\Windows\system32;C:\Windows;C:\Windows\System32\Wbem;C:\Windows\System32\WindowsPowerShell\v1.0;C:\strawberry-perl-5.12.1.0\c\bin;C:\strawberry-perl-5.12.1.0\perl\site\bin;C:\strawberry-perl-5.12.1.0\perl\bin;C:\Program Files\Windows Live\Shared;C:\Program Files\QuickTime\QTSystem;C:\Program Files\GPAC;C:\Program Files\Calibre2\;Q:\GNUstep\GNUstep\System\Tools;Q:\GNUstep\mingw\bin;Q:\GNUstep\bin;C:\Program Files\Java\jre7\bin
USERNAME=sebbu
OS=Windows_NT
PROCESSOR_IDENTIFIER=x86 Family 6 Model 15 Stepping 10, GenuineIntel



---------------  S Y S T E M  ---------------

OS: Windows Vista Build 6002 Service Pack 2

CPU:total 2 (2 cores per cpu, 1 threads per core) family 6 model 15 stepping 10, cmov, cx8, fxsr, mmx, sse, sse2, sse3, ssse3

Memory: 4k page, physical 2094544k(499684k free), swap 7283156k(2652728k free)

vm_info: Java HotSpot(TM) Client VM (22.1-b02) for windows-x86 JRE (1.7.0_03-b05), built on Feb  3 2012 20:43:37 by "java_re" with unknown MS VC++:1600

time: Thu May 17 13:54:22 2012
elapsed time: 479310 seconds