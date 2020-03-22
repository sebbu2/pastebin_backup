<?php
function cp_to_utf8($text) {
	$out='';
	$size=strlen($text);
	$pos=0;
	$count=0;
	$nb=0;
	$nb2=0;
	$nb3=0;
	$nb4=0;
	$c='';
	while($pos<$size) {
		$nb=ord($text[$pos]);
		if($nb<=0x7F) {
			//UTF8 1byte
			$out.=chr($nb);
			++$pos;
		}
		else if(($nb & 0xE0) == 0xC0) {
			// 110x xxxx
			$nb2=ord($text[$pos+1]);
			assert( ($nb2&0xBF)==$nb2 ) or die('error2');
			$c=( ($nb & 0x1F) << 6 ) | ($nb2 & 0x3F);
			assert( $c<256 || ( (($c>>8)==0xFF) & ($c&0xFFFF00FF<256) ) );
			$out.=chr($c);
			$pos+=2;
		}
		else if(($nb & 0xF0) == 0xE0) {
			// 1110 xxxx
			$nb2=ord($text[$pos+1]);
			$nb3=ord($text[$pos+2]);
			assert( ($nb2&0xBF)==$nb2 ) or die('error3a');
			assert( ($nb3&0xBF)==$nb3 ) or die('error3b');
			$c=( ($nb & 0x1F) << 12 ) | ( ($nb2 & 0x3F) << 6 ) | ($nb3 & 0x3F);
			assert( ($c<256) || ( (($c>>8)==0xFF) && (($c&0xFFFF00FF)<256) ) ) or die(var_dump($c, true));
			$out.=chr($c);
			$pos+=3;
		}
		else if(($nb & 0xF8) == 0xF0) {
			// 1111 0xxx
			$nb2=ord($text[$pos+1]);
			$nb3=ord($text[$pos+2]);
			$nb4=ord($text[$pos+3]);
			assert( ($nb2&0xBF)==$nb2 ) or die('error4a');
			assert( ($nb3&0xBF)==$nb3 ) or die('error4b');
			assert( ($nb4&0xBF)==$nb4 ) or die('error4c');
			$c=( ($nb & 0x1F) << 18 ) | ( ($nb2 & 0x3F) << 12 ) | ( ($nb3 & 0x3F) << 6 ) | ($nb4 & 0x3F);
			assert( ($c<256) || ( (($c>>8)==0xFF) && (($c&0xFFFF00FF)<256) ) ) or die(var_dump($c, true));
			$out.=chr($c);
			$pos+=3;
		}
	}
	return $out;
}
?>