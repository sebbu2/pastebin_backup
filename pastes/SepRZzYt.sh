#!/bin/bash
echo 1/7
read
#rename to zip
for fic in *.epub;
do
	mv "$fic" "${fic}.zip"
done
#
echo 2/7
read
#unzip
for fic in *.zip;
do
	dir=${fic%%.zip}
	mkdir "$dir"
	cd "$dir"
	unzip ../$fic
	cd ..
done
#
echo 3/7
read
#remove personnal informations
for dir in */
do
	if [ -d ${dir}OEBPS/Text ]
	then
		sed -i -re 's/<!-- (\S+ )+?&lt;[a-zA-Z0-9@._-]+&gt; -->//g' ${dir}OEBPS/Text/*.*htm*
	else
		sed -i -re 's/<!-- (\S+ )+?&lt;[a-zA-Z0-9@._-]+&gt; -->//g' ${dir}OEBPS/*.*htm*
	fi
done
#
echo 4/7
read
#remove customer
for ir in */
do
	if [ -d ${dir}OEBPS/Text ]
	then
		sed -i -re 's/<!-- customer [0-9]+ at [0-9-]+ [0-9:]+ [0-9:.+-]+ -->//g' ${dir}/OEBPS/Text/*.*htm*
	else
		sed -i -re 's/<!-- customer [0-9]+ at [0-9-]+ [0-9:]+ [0-9:.+-]+ -->//g' ${dir}/OEBPS/*.*htm*
	fi
done
#
echo 5/7
read
#zip
for fic in *.epub/
do
	dir="${fic%%/}.zip"
	cd "$fic"
	zip -r -v ../$dir *
	cd ..
done
#
echo 6/7
read
#rename to epub
rm -rf */
for fic in *.zip
do
	mv "$fic" "${fic%%.zip}"
done
#
echo 7/7
echo "done"