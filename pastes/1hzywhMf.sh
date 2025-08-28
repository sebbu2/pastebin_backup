#!/bin/bash
CD=$PWD
NAME1=x86_64-pc-cygwin
NAME2=x86_64-w64-mingw32
NAME3=i686-pc-cygwin
NAME4=i686-w64-mingw32
DIR1=/usr/$NAME1/bin/
DIR2=/usr/$NAME2/bin/
DIR3=/usr/$NAME3/bin/
DIR4=/usr/$NAME4/bin/
BINUTILS="addr2line.exe ar.exe as.exe c++filt.exe dlltool.exe dllwrap.exe elfedit.exe gprof.exe ld.bfd.exe ld.exe nm.exe objcopy.exe objdump.exe ranlib.exe readelf.exe size.exe strings.exe strip.exe windmc.exe windres.exe"
GCC="cc c89 c99 cpp.exe gcc.exe gcc-ar.exe gcc-nm.exe gcc-ranlib.exe gcov.exe gcov-tool.exe c++ g++"
#if false; then
	#binutils gcc g++
	for fic in $BINUTILS $GCC
	do
		echo "$fic"
		if [[ -f $NAME1-$fic ]]
		then
			if [ -f "/usr/bin/$fic" -a ! -L "/usr/bin/$fic" ]
			then
				rm -i /usr/bin/$fic
			else
				continue;
			fi;
		fi;
		ln /usr/bin/$fic /usr/bin/$NAME1-$fic
		rm -i /usr/bin/$fic
	done
#fi;
#binutils gcc g++
for fic in $BINUTILS $GCC
do
	name=${fic/.exe}
	echo $name
	alternatives --install /usr/bin/$fic $name /usr/bin/$NAME1-$fic 20
	alternatives --install /usr/bin/$fic $name /usr/bin/$NAME2-$fic 10
	alternatives --install /usr/bin/$fic $name /usr/bin/$NAME3-$fic 10
	alternatives --install /usr/bin/$fic $name /usr/bin/$NAME4-$fic 10
done
