#!/bin/env bash
if [ "$#" -ne 2 ]
then
	echo -e "Usage : $0 <file1> <file2>"
else
	ffmpeg -i "$1" -f framecrc out1.crc
	ffmpeg -i "$2" -f framecrc out2.crc
	cat out1.crc | tr -s ' ' | cut -d' ' -f 5 | sort | uniq > 1.txt
	cat out2.crc | tr -s ' ' | cut -d' ' -f 5 | sort | uniq > 2.txt
	diff -sq 1.txt 2.txt
fi
