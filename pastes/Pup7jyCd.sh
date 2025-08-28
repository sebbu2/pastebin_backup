#!/bin/bash

#whatsapp
echo -e "\e[1;31mwhatsapp\e[0m"
url=`curl -s https://api.github.com/repos/hoehermann/purple-gowhatsapp/issues/comments/617638174 | jq -r .body | grep -Pio '(?<=\()(https?://[^)]+)(?=\))'`
curl -z libgowhatsapp.zip -o libgowhatsapp.zip -R "$url"
#facebook
echo -e "\e[1;31mfacebook\e[0m"
curl -z libjson-glib-1.0a.dll -o libjson-glib-1.0a.dll -R https://github.com/dequis/purple-facebook/releases/download/downloads/libjson-glib-1.0.dll
curl -z libfacebook.dll -o libfacebook.dll -R https://dequis.org/libfacebook.dll
#discord
echo -e "\e[1;31mdiscord\e[0m"
curl -z libdiscord.dll -o libdiscord.dll -R https://eion.robbmob.com/libdiscord.dll
#skype
echo -e "\e[1;31mskype\e[0m"
curl --etag-save json2-etag.txt --etag-compare json2-etag.txt -z libjson-glib-1.0b.dll -o libjson-glib-1.0b.dll -R https://raw.githubusercontent.com/EionRobb/skype4pidgin/master/skypeweb/libjson-glib-1.0.dll
curl -z libskypeweb.dll -o libskypeweb.dll -R https://github.com/EionRobb/skype4pidgin/releases/latest/download/libskypeweb.dll
#hangouts
echo -e "\e[1;31mhangouts\e[0m"
curl -z libhangouts.dll -o libhangouts.dll -R http://eion.robbmob.com/libhangouts.dll
#steam (pp/old)
echo -e "\e[1;31msteam (old)\e[0m"
curl -z libsteam.zip -o libsteam.zip -R https://github.com/seishun/SteamPP/releases/latest/download/libsteam.zip
#steam
echo -e "\e[1;31msteam\e[0m"
#curl -z libjson-glib-1.0c.dll -o libjson-glib-1.0c.dll https://github.com/EionRobb/pidgin-opensteamworks/raw/master/steam-mobile/libjson-glib-1.0.dll
curl --etag-save json3-etag.txt --etag-compare json3-etag.txt -z libjson-glib-1.0c.dll -o libjson-glib-1.0c.dll -R https://raw.githubusercontent.com/EionRobb/pidgin-opensteamworks/master/steam-mobile/libjson-glib-1.0.dll
curl -z libsteam.dll -o libsteam.dll -R https://github.com/EionRobb/pidgin-opensteamworks/releases/latest/download/libsteam.dll
#battle classic (dead)
#battle.net
echo -e "\e[1;31mbattle\e[0m"
curl -z libprotobuf-c-1.dll -o libprotobuf-c-1.dll -R https://eion.robbmob.com/libprotobuf-c-1.dll
curl -z libbattlenet.dll -o libbattlenet.dll -R https://eion.robbmob.com/libbattlenet.dll
#mattermost
echo -e "\e[1;31mmattermost\e[0m"
curl -z libmattermost.dll -o libmattermost.dll -R https://github.com/EionRobb/purple-mattermost/releases/latest/download/libmattermost.dll
#matrix
echo -e "\e[1;31mmatrix/riot\e[0m"
curl -z libmatrix.dll -o libmatrix.dll -R https://eion.robbmob.com/purple-matrix/libmatrix.dll
curl -z libmatrix-e2e.dll -o libmatrix-e2e.dll -R https://eion.robbmob.com/libmatrix-e2e.dll
#telegram (old, current)
echo -e "\e[1;31mtelegram (old, current)\e[0m"
name=`curl -s https://api.github.com/repos/majn/telegram-purple/releases |  jq -r '.[0].assets[]|select(.name|endswith("_nopng.exe"))|.name'`
url=`curl -s https://api.github.com/repos/majn/telegram-purple/releases |  jq -r '.[0].assets[]|select(.name|endswith("_nopng.exe"))|.browser_download_url'`
curl -R -o "$name" -z "$name" -R "$url"
7z e -aoa -bb0 "$name" '$_4_/plugins/libtelegram.dll' >/dev/null
#telegram (new, in progress)
echo -e "\e[1;31mtelegram (new, in progress)\e[0m"
name=`curl -s https://api.github.com/repos/ars3niy/tdlib-purple/releases |  jq -r '.[0].assets[]|select( .name| (endswith(".exe") or endswith(".dll")) )|.name'`
url=`curl -s https://api.github.com/repos/ars3niy/tdlib-purple/releases |  jq -r '.[0].assets[]|select( .name| (endswith(".exe") or endswith(".dll")) )|.browser_download_url'`
curl -R -o "$name" -z "$name" -R "$url"
7z e -aoa -bb0 "$name" '$_4_/plugins/libtelegram-tdlib.dll' >/dev/null
#slack
echo -e "\e[1;31mslack\e[0m"
curl -z libslack.dll -o libslack.dll -R https://eion.robbmob.com/libslack.dll
#rocket
echo -e "\e[1;31mrocket\e[0m"
curl -z libjson-glib-1.0.dll -o libjson-glib-1.0.dll -R https://eion.robbmob.com/libjson-glib-1.0.dll
curl -z librocketchat.dll -o librocketchat.dll -R https://eion.robbmob.com/librocketchat.dll
#fin
echo -e "\e[1;31mfin\e[0m"
