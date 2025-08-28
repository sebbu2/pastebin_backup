#!/bin/bash
LC_ALL=C find . -name '?*.*' -type f -printf '%s.%f\0' |
LC_ALL=C gawk -F . -v RS='\0' '
	{
		if(NF==2) $(NF+1)=" ";	
		s[$NF] += $1;
		n[$NF]++
	}
	END {
		PROCINFO["sorted_in"] = "@val_num_asc"
		for (e in s) printf "%15d %4d %s\n", s[e], n[e], e
	}' |
numfmt --to=iec-i --suffix=B
