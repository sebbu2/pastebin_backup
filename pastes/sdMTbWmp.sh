#!/usr/bin/env bash
# https://next.bgm.tv/demo/access-token
token=`cat bangumi_token.txt`
curl -H "Authorization: Bearer $token" "https://api.bgm.tv/v0/me" -o me.json
user=`cat me.json|jq -r .username|dos2unix`
#types : 1 manga 2 anime 3 music 4 game 6 threeD
types=(1 2 3 4 6)
for type in ${types[@]}
do
        offset=0
        num=0
        limit=100
        while true
        do
                curl -H "Authorization: Bearer $token" "https://api.bgm.tv/v0/users/${user}/collections?subject_type=${type}&offset=${offset}&limit=${limit}" -o collections_${type}_${num}.json
                total=`cat collections_${type}_${num}.json | jq -r .total|dos2unix`
                offset2=`cat collections_${type}_${num}.json | jq -r .offset|dos2unix`
                limit2=`cat collections_${type}_${num}.json | jq -r .limit|dos2unix`
                ((num++))
                ((offset+=100))
                sum=`expr $offset2 + $limit2`
                [[ "$sum" -ge "$total" ]] && break 1
        done
done
