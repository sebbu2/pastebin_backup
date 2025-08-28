#!/bin/bash
# https://imgur.com/account/settings/apps
token=`cat imgur_token.txt`
num_per_page=50
echo me
curl -H "Authorization: Bearer $token" 'https://api.imgur.com/3/account/me' -o imgur_account_me.json
echo images
id=0
while true
do
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/account/me/images/${id}" -o imgur_account_images_${id}.json
        num=`cat imgur_account_images_${id}.json | jq -r '.data|length'`
        ((id++))
        [[ "$num" -lt "$num_per_page" ]] && break
done
echo 'favorites & submissions'
curl -H "Authorization: Bearer $token" 'https://api.imgur.com/3/account/me/gallery_favorites' -o imgur_account_gallery_favorites.json
curl -H "Authorization: Bearer $token" 'https://api.imgur.com/3/account/me/favorites' -o imgur_account_favorites.json
curl -H "Authorization: Bearer $token" 'https://api.imgur.com/3/account/me/submissions' -o imgur_account_submissions.json
echo albums
id=0
while true
do
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/account/me/albums/${id}" -o imgur_account_albums_${id}.json
        num=`cat imgur_account_albums_${id}.json | jq -r '.data|length'`
        ((id++))
        [[ "$num" -lt "$num_per_page" ]] && break
done
echo albums ids
id=0
while true
do
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/account/me/albums/ids/${id}" -o imgur_account_albums_ids_${id}.json
        num=`cat imgur_account_albums_ids_${id}.json | jq -r '.data|length'`
        ((id++))
        [[ "$num" -lt "$num_per_page" ]] && break
done
echo done

ids=`cat imgur_account_albums_ids_*.json | jq -r '.data[]'|dos2unix`
for id in $ids
do
        echo $id
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/account/sebbu/album/${id}" -o "imgur_account_album_${id}.json"
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/album/${id}" -o "imgur_album_${id}.json"
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/album/${id}/images" -o "imgur_album_${id}_images.json"
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/gallery/album/${id}" -o "imgur_gallery_album_${id}.json"
        curl -H "Authorization: Bearer $token" "https://api.imgur.com/3/gallery/image/${id}" -o "imgur_gallery_image_${id}.json"
        #read
done
