#!/bin/env bash
if [ "$#" -eq 1 ]
then
        file=`mktemp`
        curl "$1" | grep -Eo '<script(.*)</script>' | grep mp4 | sed -re 's#<script([^>]+)></script>##g' | sed -re 's#<script([^>]*)>##g' | sed -re 's#;</script>##g' | sed -re 's#^([a-zA-Z0-9_\.]+)=##g' | jq .state.data.video.videos_manifest > $file
        url=`cat $file | jq -r '[.servers[0].streams[] | select( .url != "")] | max_by(.width) | .url'`
        filename=`cat $file | jq -r '[.servers[0].streams[] | select( .url != "")] | max_by(.width) | .filename'`
        filename=${filename%%-v1x.m3u8}
        yt-dlp $url -o $filename
        rm $file
else
        echo "Usage : $0 <hanime_url>"
fi
