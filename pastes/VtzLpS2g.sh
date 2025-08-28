#!/usr/bin/env bash
shopt -s expand_aliases
source ~/.bash_aliases

comment="Documents $(date +%Y%m%d-%H%M%S)"
file=${comment}.rar
echo -n ${comment} > commentaire.txt
touch liste.txt
find . -maxdepth 1 -type f | cut -b 3- | grep -vE '^Documents.*\.rar$' | grep -vE '^SebastienDocument.*\.rar$' | sort -f -V > liste.txt
find backups/ cheats/ | sort -f -V >> liste.txt
rar a "${file}" -k -rr -p -hp -m5 -r- -s- -scFcgl -z"commentaire.txt" @"liste.txt"

comment2="SebastienDocuments $(date +%Y%m%d-%H%M%S)"
file2=${comment2}.rar
echo -n ${comment2} > commentaire2.txt
touch liste2.txt
find Administratif/ "emploi Sebastien"/ SEBASTIENdocuments/ commentaire2.txt liste2.txt 2FA/ codes/ keys/ "old mdp"/ -maxdepth 1 -type f | sort -f -V > liste2.txt
rar a "${file2}" -k -rr -p -hp -m5 -r- -s- -scFcgl -z"commentaire2.txt" @"liste2.txt"
