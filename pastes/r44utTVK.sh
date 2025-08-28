#!/usr/bin/env bash
# shopt -s expand_aliases
formatver() {
	sed -re 's/^v?(.*)$/\1/' | tr -d '\r\n'
}
github_get_ver() {
	gh release -R "$@" view --json tagName -q ".tagName" | formatver
}
leadzero() {
	sed -re 's/\.0+/./'
}
skip_line_get_version() {
	grep -E '^[a-zA-Z0-9]' | grep -Eo '([0-9][^[:space:]]+)'
}
if false
then
echo gallery-dl.exe
if [ -f gallery-dl.exe ]
then
	repo="mikf/gallery-dl"
	v1=`./gallery-dl.exe --version | formatver`
	v2=`gh release -R "$repo" view --json tagName -q ".tagName" | formatver`
	echo "compare '$v1' to '$v2'"
	if [ `pysemver compare "$v1" "$v2"` -eq "-1" ]
	then
		gh release -R "$repo" download --clobber -p gallery-dl.exe -p gallery-dl.exe.sig
	fi
else
	 gh release -R "$repo" download --skip-existing -p gallery-dl.exe -p gallery-dl.exe.sig
fi
gpg --allow-non-selfsigned-uid --allow-weak-digest-algos --allow-weak-key-signatures --auto-key-retrieve --verify gallery-dl.exe.sig gallery-dl.exe
fi
if false
then
echo youtube-dl.exe
if [ -f youtube-dl.exe ]
then
	repo="ytdl-org/ytdl-nightly"
	v1=`./youtube-dl.exe --version | formatver | leadzero`
	v2=`github_get_ver "$repo" | leadzero`
	echo "compare '$v1' to '$v2'"
	if [ `pysemver compare "$v1" "$v2"` -eq "-1" ]
	then
		gh release -R "$repo" download --clobber -p youtube-dl.exe -p SHA2-512SUMS
		mv SHA2-512SUMS youtube-dl_SHA2-512SUMS
		grep youtube-dl.exe youtube-dl_SHA2-512SUMS > a && mv a youtube-dl_SHA2-512SUMS
	fi
else
	gh release -R "$repo" download --skip-existing -p youtube-dl.exe -p SHA2-512SUMS
	mv SHA2-512SUMS youtube-dl_SHA2-512SUMS
	grep youtube-dl.exe youtube-dl_SHA2-512SUMS > a && mv a youtube-dl_SHA2-512SUMS
fi
grep youtube-dl.exe youtube-dl_SHA2-512SUMS
rhash -P -c youtube-dl_SHA2-512SUMS
fi
if false
then
echo yt-dlp.exe
if [ -f yt-dlp.exe ]
then
	repo="yt-dlp/yt-dlp"
	v1=`./yt-dlp.exe --version | formatver | leadzero`
	v2=`github_get_ver "$repo" | leadzero`
	echo "compare '$v1' to '$v2'"
	if [ `pysemver compare "$v1" "$v2"` -eq "-1" ]
	then
		gh release -R "$repo" download --clobber -p yt-dlp.exe -p SHA2-512SUMS -p SHA2-512SUMS.sig
		mv SHA2-512SUMS yt-dlp_SHA2-512SUMS
		mv SHA2-512SUMS.sig yt-dlp_SHA2-512SUMS.sig
		#grep yt-dlp.exe yt-dlp_SHA2-512SUMS > a
	fi
else
	gh release -R "$repo" download --skip-existing -p yt-dlp.exe -p SHA2-512SUMS -p SHA2-512SUMS.sig
	mv SHA2-512SUMS yt-dlp_SHA2-512SUMS
	mv SHA2-512SUMS.sig yt-dlp_SHA2-512SUMS.sig
	#grep yt-dlp.exe yt-dlp_SHA2-512SUMS > a
fi
gpg --allow-non-selfsigned-uid --allow-weak-digest-algos --allow-weak-key-signatures --auto-key-retrieve --verify yt-dlp_SHA2-512SUMS.sig yt-dlp_SHA2-512SUMS
#if [ -f a ]; then mv a yt-dlp_SHA2-512SUMS; fi
rhash -P -c yt-dlp_SHA2-512SUMS
fi
if true
then
echo lncrawl.exe
if [ -f lncrawl.exe ]
then
	repo="dipu-bd/lightnovel-crawler"
	v1=`./lncrawl.exe --version | skip_line_get_version | formatver | leadzero`
	v2=`github_get_ver "$repo" | leadzero`
	echo "compare '$v1' to '$v2'"
	if [ `pysemver compare "$v1" "$v2"` -eq "-1" ]
	then
		gh release -R "$repo" download --clobber -p lncrawl.exe
	fi
else
	gh release -R "$repo" download --skip-existing -p lncrawl.exe
fi
fi
#done
