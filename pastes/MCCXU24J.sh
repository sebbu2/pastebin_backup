#!/usr/bin/env bash
if [ "$#" -ne 1 ]
then
        echo "Usage: $0 <server>"
        exit
fi
CD=$PWD
cd -P /xchatlogs/..
sed -nr "/^N=${1}/ { :l /^\\s*[^#].*/ p; n; /^N=/ q; b l; }" ./servlist.conf | grep '^J=' | cut -b 3- | tr -d '\r' | sort | tr '\n' ','
cd $CD
