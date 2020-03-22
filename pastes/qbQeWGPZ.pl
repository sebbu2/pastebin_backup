#!/usr/bin/perl -w

use strict;
use warnings;

while (<>)
{
	$\="\n";
	s/^\s+//;
	s/\s+$//;
	my $count=0;
	my $black="\e[1;30m",my $red="\e[1;31m",my $green="\e[1;32m",my $yellow="\e[1;33m",my $blue="\e[1;34m",my $magenta="\e[1;35m",my $cyan="\e[1;36m",my $white="\e[1;37m";
	my $nc="\e[0m";
	s#([a-zA-Z]:|)([^:]+):([0-9]+):([0-9]+):#$cyan$1$2$nc:$magenta$3$nc:$yellow$4$nc:#g;
	# s#([^:]+): #$cyan$1$nc: #g;
	my $safechar='a-zA-z0-9_-';
	for ($count = 0; $count < 10; $count++) {
		#print("iter n°", $count, "=", $_, "\n");
		#string
		s/std::basic_string<char, std::char_traits<char>, std::allocator<char> >\s*/std::string/g;
		#set
		s/std::set<(.+?), std::less<\1>, std::allocator<\1> >/std::set<$red$1$nc>/g;
		s/std::set<(.+?), std::less<\1 >, std::allocator<\1 > >/std::set<$1 >/g;
		#multiset
		s/std::multiset<(.+?), std::less<\1>, std::allocator<\1> >/std::multiset<$red$1$nc>/g;
		s/std::multiset<(.+?), std::less<\1 >, std::allocator<\1 > >/std::multiset<$1 >/g;
		#vector
		s/std::vector<(.+?), std::allocator<\1> >/std::vector<$red$1$nc>/g;
		s/std::vector<(.+?), std::allocator<\1 > >/std::vector<$1 >/g;
		#list
		s/std::list<(.+?), std::allocator<\1> >/std::list<$red$1$nc>/g;
		s/std::list<(.+?), std::allocator<\1 > >/std::list<$1 >/g;
		#forward_list
		s/std::forward_list<(.+?), std::allocator<\1> >/std::forward_list<$red$1$nc>/g;
		s/std::forward_list<(.+?), std::allocator<\1 > >/std::forward_list<$1 >/g;
		#deque
		s/std::deque<(.+?), std::allocator<\1> >/std::deque<$red$1$nc>/g;
		s/std::deque<(.+?), std::allocator<\1 > >/std::deque<$1 >/g;
		#stack
		s/std::stack<(.+?), std::deque<\1> >/std::stack<$red$1$nc>/g;
		s/std::stack<(.+?), std::deque<\1 > >/std::stack<$1 >/g;
		#queue
		s/std::queue<(.+?), std::deque<\1> >/std::queue<$red$1$nc>/g;
		s/std::queue<(.+?), std::deque<\1 > >/std::queue<$1 >/g;
		#priority_queue
		s/std::priority_queue<(.+?), std::deque<\1>, std::less<typename std::deque<\1>::value_type >/std::priority_queue<$red$1$nc>/g;
		s/std::priority_queue<(.+?), std::deque<\1 >, std::less<typename std::deque<\1 >::value_type >/std::priority_queue<$1 >/g;
		#priority_queue #without typename
		s/std::priority_queue<(.+?), std::deque<\1>, std::less<std::deque<\1>::value_type >/std::priority_queue<$red$1$nc>/g;
		s/std::priority_queue<(.+?), std::deque<\1 >, std::less<std::deque<\1 >::value_type >/std::priority_queue<$1 >/g;
		#map
		s/std::map<(.+?), (.+?), std::less<\1>, std::allocator<std::pair<const \1, \2> > >/std::map<$red$1$nc, $red$2$nc>/g;
		s/std::map<(.+?), (.+?), std::less<\1 >, std::allocator<std::pair<const \1, \2 > > >/std::map<$1, $2 >/g;
		#map #without const
		s/std::map<(.+?), (.+?), std::less<\1>, std::allocator<std::pair<\1, \2> > >/std::map<$red$1$nc, $red$2$nc>/g;
		s/std::map<(.+?), (.+?), std::less<\1 >, std::allocator<std::pair<\1, \2 > > >/std::map<$1, $2 >/g;
		#unordored_set
		s/std::unordored_set<(.+?), std::hash<\1>, std::equal_to<\1>, std::allocator<\1> >/std::unordored_set<$red$1$nc>/g;
		s/std::unordored_set<(.+?), std::hash<\1 >, std::equal_to<\1 >, std::allocator<\1 > >/std::unordored_set<$1 >/g;
		#unordored_multiset #probably error (second type ?)
		s/std::unordored_multiset<(.+?), (.+?), std::hash<\1>, std::equal_to<\1>, std::allocator<\1> >/std::unordored_multiset<$red$1$nc, $red$2$nc>/g;
		s/std::unordored_multiset<(.+?), (.+?), std::hash<\1 >, std::equal_to<\1 >, std::allocator<\1 > >/std::unordored_multiset<$1, $2 >/g;
		#unordored_map
		s/std::unordored_map<(.+?), (.+?), std::hash<\1>, std::equal_to<\1>, std::allocator<std::pair<const \1, \2> > >/std::unordored_map<$red$1$nc, $red$2$nc>/g;
		s/std::unordored_map<(.+?), (.+?), std::hash<\1 >, std::equal_to<\1 >, std::allocator<std::pair<const \1, \2 > > >/std::unordored_map<$1, $2 >/g;
		#unordored_map #without const
		s/std::unordored_map<(.+?), (.+?), std::hash<\1>, std::equal_to<\1>, std::allocator<std::pair<\1, \2> > >/std::unordored_map<$red$1$nc, $red$2$nc>/g;
		s/std::unordored_map<(.+?), (.+?), std::hash<\1 >, std::equal_to<\1 >, std::allocator<std::pair<\1, \2 > > >/std::unordored_map<$1, $2 >/g;
		#unordored_multimap
		s/std::unordored_multimap<(.+?), (.+?), std::hash<\1>, std::equal_to<\1>, std::allocator<std::pair<const \1, \2> > >/std::unordored_multimap<$red$1$nc, $red$2$nc>/g;
		s/std::unordored_multimap<(.+?), (.+?), std::hash<\1 >, std::equal_to<\1 >, std::allocator<std::pair<const \1, \2 > > >/std::unordored_multimap<$1, $2 >/g;
		#unordored_multimap #without const
		s/std::unordored_multimap<(.+?), (.+?), std::hash<\1>, std::equal_to<\1>, std::allocator<std::pair<\1, \2> > >/std::unordored_multimap<$red$1$nc, $red$2$nc>/g;
		s/std::unordored_multimap<(.+?), (.+?), std::hash<\1 >, std::equal_to<\1 >, std::allocator<std::pair<\1, \2 > > >/std::unordored_multimap<$1, $2 >/g;
		#array
		s/std::array<(.+?), ([0-9]+)>/std::array<$red$1$nc, $red$2$nc>/g;
		#nothing needed for array (contains only class & size)
		
		#colorize native type
		s/([^$safechar])unsigned(?=[^$safechar])/$1${red}unsigned$nc/g;
		s/([^$safechar])long(?=[^$safechar])/$1${red}long$nc/g;
		s/([^$safechar])int(?=[^$safechar])/$1${red}int$nc/g;
		s/([^$safechar])short(?=[^$safechar])/$1${red}short$nc/g;
		s/([^$safechar])char(?=[^$safechar])/$1${red}char$nc/g;
		s/([^$safechar])double(?=[^$safechar])/$1${red}double$nc/g;
		s/([^$safechar])float(?=[^$safechar])/$1${red}float$nc/g;
		s/([^$safechar])void(?=[^$safechar])/$1${red}void$nc/g;
	}
	print;
}