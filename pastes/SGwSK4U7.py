#!/usr/bin/env python
# -*- coding: utf-8 -*-

# https://animelon.com/api/languagevideo/findByVideo?videoId=5aac0620b6b8f36034bd33b8&learnerLanguage=en&subs=1&cdnLink=1&viewCounter=1

# https://www.openssl.org/docs/manmaster/man3/EVP_BytesToKey.html
# http://joelinoff.com/blog/?p=885
# http://downloads.joelinoff.com/mycrypt.py
# https://stackoverflow.com/a/16761459/694473
# https://crypto.stackexchange.com/a/35614
# http://grepcode.com/file/repo1.maven.org/maven2/ca.juliusdavies/not-yet-commons-ssl/0.3.9/org/apache/commons/ssl/OpenSSL.java#469
# https://stackoverflow.com/a/12221931/694473
# https://stackoverflow.com/a/13923114/694473

import base64
import json
from Crypto.Cipher import AES
from hashlib import md5
import binascii

def derive_key_and_iv(password, salt, key_length, iv_length):
	d = d_i = ''.encode('cp437', 'ignore')
	while len(d) < key_length + iv_length:
		d_i = md5(d_i + password + salt).digest().decode('cp437', 'ignore').encode('cp437', 'ignore')
		d += d_i
	return d[:key_length], d[key_length:key_length+iv_length]

with open('bla.json') as f:
	jcon = json.load(f)
	bla = jcon['resObj']['subtitles'][4]['content']['romajiSub']
	blo = base64.b64decode(bla[8:-5])
	with open('bla.enc', 'wb') as f1:
		f1.write(blo)
	
	pwd = bla[0:8][::-1]
	#pwd = bla[7::-1]
	with open('key.txt', 'w') as f2:
		f2.write(pwd)
	pwd = pwd.encode('ascii', 'ignore') # otherwise cause issues with MD5 digest
	print("passphrase : %s" % pwd)
	
	salt=blo[8:16]
	salt2 = binascii.hexlify(salt).decode()
	with open('salt.txt', 'w') as f3:
		f3.write(salt2)
	print("salt : %s" % salt2)
	
	key_length = 32
	bs = AES.block_size # 16
	pwd = pwd.decode('cp437', 'ignore').encode('cp437', 'ignore')
	salt = salt.decode('cp437', 'ignore').encode('cp437', 'ignore')
	key, iv = derive_key_and_iv(pwd, salt, key_length, bs)
	
	key2 = binascii.hexlify(key)
	iv2 = binascii.hexlify(iv)
	print("key : %s" % key2)
	print("iv : %s" % iv2)
	
	cipher = AES.new(key, AES.MODE_CBC, iv)
	msg = cipher.decrypt(blo[16:])
	
	with open('bla.srt', 'wb') as f4:
		f4.write(msg)

#with open('bla.json') as f:
#	jcon = json.load(f)
#	bla = jcon['resObj']['subtitles'][4]['content']['romajiAndTimes']
#	for item in bla:
#		print(item['startTime'], ' '.join(item['tokenized']))
