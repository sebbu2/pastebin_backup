#!/usr/bin/perl -w

use strict;
use warnings;
use integer;
use assertions;

my $count=0;

my $num_args = $#ARGV;
++$num_args;
print 'args=',$num_args,"\n";
if ($num_args != 1) {
  print "\nUsage: ",$0," filename\n";
  exit;
}
#print $ARGV[1];
print "Thanks, you gave me $num_args command-line arguments:\n";
my $argnum;
foreach $argnum (0 .. $#ARGV) {
  print "\t$ARGV[$argnum]\n";
}
#exit;

open(FILE,$ARGV[0]) || die "Sorry, I couldn't open $ARGV[0]\n";
my $line;
my $size;
#while ()
{
	$size=read(FILE, $line, 4) or die "Sorry, I couldn't read $ARGV[0]\n";
	die "bad header 1\n" if ($size!=4);
	$size=read(FILE, $line, 4);
	$line=unpack('V',$line);#little-endian
	die "bad header 2\n" if ($line!=0);
	$size=read(FILE, $line, 4);
	my $blks=unpack('V',$line);#little-endian
	print "number of 'blocks' : $blks\n";
	$size=read(FILE, $line, 4);
	my $elmts=unpack('V',$line);#little-endian
	print "number of element in  1st 'block' : $elmts\n";
	my $filename;
	my @filenames;
	print "\n";
	#for(my $i = 0; $i < $elmts; $i++) {
	foreach my $i (1 .. $elmts) {
		$size=read(FILE, $line, 4);
		$line=unpack('V',$line);#little-endian
		#print "size=$line\n";
		$size=read(FILE, $filename, $line);#size = previous read
		#print "filename=$filename\n";
		push(@filenames, $filename);
	}
	#print "\n";
	for(my $i = 0; $i < $elmts; $i++) {
		print $filenames[$i],"\n";
	}
	my $fsize;
	my @fsizes;
	for(my $i = 0; $i < $elmts; $i++) {
		$size=read(FILE, $line, 4);
		$line=unpack('V',$line);#little-endian
		push(@fsizes, $line);
	}
	print "\n";
	for(my $i = 0; $i < $elmts; $i++) {
		print $fsizes[$i],"\n";
	}
	print "\n";
	my $data;
	my $curpos;
	my $line2;#skip
	foreach my $i (0 .. $elmts-1) {
		#$curpos = tell(FILE);
		#print "curpos = $curpos\n";
		if( $filenames[$i] ne '.' && $filenames[$i] ne '..' ) {
			open(FILE2, ">", $filenames[$i]) || die "Sorry, I couldn't open $filenames[$i] for writing\n";
		}
		else {
			open(FILE2, ">", $filenames[$i].'txt') || die "Sorry, I couldn't open $filenames[$i].txt for writing\n";
		}
		$size=read(FILE, $line, 4);
		$line=unpack('V',$line);#little-endian
		die "bad data header 1\n" if ($line!=1);
		$size=read(FILE, $line, 4);
		$line=unpack('l',$line);#little-endian
		if( $line < 0 || $line > 2147483648) {
			#comportement spécial
			$size=read(FILE, $line, 4);
			$line=unpack('l',$line);#little-endian
			#print "data size = ",$line,"\n";
			$size=read(FILE, $line2, 4);
			$line2=unpack('l',$line2);#little-endian
			seek(FILE, $line2, 1);#SEEK_CUR
		}
		#else {
			#print "data size = ",$line,"\n";
			$size=read(FILE, $data, $line);#size = previous read
			print FILE2 $data;
		#}
		close(FILE2);
	}
	$curpos = tell(FILE);
	print "curpos 2 = $curpos\n";
	exit;
}
close(FILE);