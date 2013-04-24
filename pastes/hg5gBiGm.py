#!/usr/bin/env python
# -*- encoding: utf-8 -*-

import sys #stdout, stderr, argc, argc
import os #listdir
import posixpath #join, splitext
import xml.parsers.expat #EXPAT
import base64
import zlib
import gzip
import struct #unpack, pack
import StringIO #StringIO

HANDLER_NAMES = [
	'StartElementHandler', 'EndElementHandler',
	'CharacterDataHandler', 'ProcessingInstructionHandler',
	'UnparsedEntityDeclHandler', 'NotationDeclHandler',
	'StartNamespaceDeclHandler', 'EndNamespaceDeclHandler',
	'CommentHandler', 'StartCdataSectionHandler',
	'EndCdataSectionHandler',
	'DefaultHandler', 'DefaultHandlerExpand',
	#'NotStandaloneHandler',
	'ExternalEntityRefHandler'
	]

dump_all = False # wall of text

class State(object):
	pass
State.INITIAL = State()
State.LAYER = State()
State.DATA = State()
State.FINAL = State()

class ContentHandler:

	__slots__ = (
		'p', # Expat object
		'state', # state of collision info
		'encoding', # encoding of layer data
		'compression', # compression of layer data
		'mtime', # date of gzip content
		'width', # width of the map
		'height', # height of the map
		'buffer', # characters within a section
		'gids', # list of the tiles of the layer
	)

	def __init__(self):
		self.state = State.INITIAL
		self.p = xml.parsers.expat.ParserCreate()
		self.p.ordered_attributes = 1
		self.p.StartElementHandler = self.start_element
		self.p.EndElementHandler = self.end_element
		self.p.CharacterDataHandler = self.char_data
		self.width = 0
		self.height = 0
		self.encoding = ''
		self.compression = ''
		self.buffer = bytearray()
		pass

	# 3 handler functions
	def start_element(self, name, attrs):
		if dump_all:
			sys.stdout.write('<%s' % name)
			if len(attrs)>0:
				#sys.stdout.write(' %s' % ' '.join('%s="%s"' % (k,v) for k,v in attr.items()))
				sys.stdout.write(' %s' % ' '.join('%s="%s"' % (attrs[2*i], attrs[2*i+1]) for i in range(len(attrs)/2)))
			sys.stdout.write('>')
		if self.state is State.INITIAL:
			if name == u'map':
				for i in range(len(attrs)/2):
					if attrs[2*i] == u'width':
						self.width = int(attrs[2*i+1])
					elif attrs[2*i] == u'height':
						self.height = int(attrs[2*i+1])
			self.state = State.LAYER
		elif self.state is State.LAYER:
			if name == u'layer':
				for i in range(len(attrs)/2):
					if attrs[2*i] == u'width':
						if self.width <> int(attrs[2*i+1]):
							sys.stderr.write('partial layer not supported yet.\n')
							return
					elif attrs[2*i] == u'height':
						if self.height <> int(attrs[2*i+1]):
							sys.stderr.write('partial layer not supported yet.\n')
							return
				self.state = State.DATA
				self.gids = []
		elif self.state is State.DATA:
			if name == u'data':
				self.encoding = ''
				self.compression = ''
				for i in range(len(attrs)/2):
					if attrs[2*i] == u'encoding':
						self.encoding = attrs[2*i+1]
					elif attrs[2*i] == u'compression':
						self.compression = attrs[2*i+1]
			elif name == u'tile':
				for i in range(len(attrs)/2):
					if attrs[2*i] == u'encoding':
						self.gids.append(int(attrs[2*i+1]))

	def end_element(self, name):
		if dump_all:
			sys.stdout.write('<%s>' % name)
		if self.state is State.DATA:
			if name == u'data':
				if self.encoding not in (u'', u'csv', u'base64', u'xml'):
					print('Bad encoding:', self.encoding)
				if self.compression not in (u'', u'none', u'zlib', u'gzip'):
					print('Bad compression:', self.compression)
				#decoding start
				if self.encoding == u'base64':
					data2 = base64.b64decode(str(self.buffer))
					#print(data2)
					#print(' '.join( '%d' % ord(data2[i]) for i in range(10)))
					if self.compression == u'zlib':
						data = zlib.decompress(data2)
					elif self.compression == u'gzip':
						#data = zlib.decompressobj().decompress('x\x9c' + data2[10:-8])
						out = StringIO.StringIO()
						out.write(data2)
						out.seek(0) #needed
						f = gzip.GzipFile(fileobj=out, mode='rb')
						#f._init_read()
						#f._read_gzip_header()
						data = f.read()
						self.mtime = f.mtime
						#print('mtime = %d' % self.mtime)
						f.close()
						out.close()
					for i in range(self.width*self.height):
						gid = int(struct.unpack('<I',data[i*4:i*4+4])[0])
						#print('%s' % data[i*4:i*4+4])
						#print('%d' % gid)
						self.gids.append(gid)
				#decoding end
				if len(self.gids) != self.width*self.height:
					sys.stderr.write('ERROR : incorrect gid count.\n')
					return
				#print('"%s" "%s"' % (self.encoding, self.compression))
				#coding start
				data = ''
				for i in range(len(self.gids)):
					data += struct.pack('<I', self.gids[i])
				if len(data) <> self.width*self.height*4:
					sys.stderr.write('ERROR : incorrect data packing.\n')
					return
				if self.encoding == u'base64':
					if self.compression == u'zlib':
						data = zlib.compress(data)
					elif self.compression == u'gzip':
						out = StringIO.StringIO()
						f = gzip.GzipFile(fileobj=out, mode='w', compresslevel=6, mtime=self.mtime)
						f.write(data)
						f.close()
						data = out.getvalue()
						#print(' '.join( '%d' % ord(data2[i]) for i in range(10)))
						#print(' '.join( '%d' % ord(data[i]) for i in range(10)))
						data=data[:8]+chr(0)+chr(3)+data[10:]
					data = base64.b64encode(data)
					if data <> self.buffer:
						print('%d %d' % (len(data), len(self.buffer)))
						print('%s' % data)
						print('%s' % self.buffer)
						sys.stderr.write('ERROR : decompress + compress returned different data.\n')
						#return
				#coding end
				self.buffer = bytearray()
				print('layer parsed.')
				self.state = State.LAYER

	def char_data(self, data):
		if dump_all:
			sys.stdout.write('%s' % data)
		if self.state is State.DATA:
			if len(data.strip()) > 0:
				self.buffer += data.strip().encode('ascii')

	def Parse(self, data, isfinal=1):
		self.p.Parse(data, isfinal)

	def ParseFile(self, file):
		self.p.ParseFile(file)

CLIENT_MAPS = 'maps'
SERVER_WLK = 'data'
SERVER_NPCS = 'npc'

def main(argv):
	if len(argv) != 3:
		#sys.stderr.write('ERROR: incorrect number of arguments\n')
		sys.stderr.write('USAGE: sax.py CLIENT_DIR SERVER_DIR\n')
		return
	_, client_data, server_data = argv
	tmx_dir = posixpath.join(client_data, CLIENT_MAPS)
	wlk_dir = posixpath.join(server_data, SERVER_WLK)
	npc_dir = posixpath.join(server_data, SERVER_NPCS)
	for arg in os.listdir(tmx_dir):
		base, ext = posixpath.splitext(arg)
		if ext == '.tmx':
			tmx = posixpath.join(tmx_dir, arg)
			print('Parsing %s' % tmx)
			with open(tmx, 'r') as input:
				p = ContentHandler()
				p.ParseFile(input)
				del p
				#return

if __name__ == '__main__':
	main(sys.argv)
