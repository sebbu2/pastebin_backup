<?php
if(true) { //config
$current_url='http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page&p=2#my_anchor';
$hrefs=array(
	'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page&p=2#my_anchor' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page&p=2#my_anchor',
	'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page&p=2' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page&p=2',
	'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page',
	'http://my_domain.tld/dir1/dir2/my_file.my_ext' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext',
	'http://my_domain.tld/dir3/dir4/my_file.my_ext' => 'http://my_domain.tld/dir3/dir4/my_file.my_ext',
	'?page=my_2nd_page#my_2nd_anchor' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_2nd_page#my_2nd_anchor',
	'#my_2nd_anchor' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext?page=my_page&p=2#my_2nd_anchor',
	'?p=1' => 'http://my_domain.tld/dir1/dir2/my_file.my_ext?p=1',
	'my_2nd_file.my_ext' => 'http://my_domain.tld/dir1/dir2/my_2nd_file.my_ext',
	'my_2nd_file.my_ext?p=1' => 'http://my_domain.tld/dir1/dir2/my_2nd_file.my_ext?p=1',
	'my_2nd_file.my_ext#test' => 'http://my_domain.tld/dir1/dir2/my_2nd_file.my_ext#test',
	'my_2nd_file.my_ext?p=1#test' => 'http://my_domain.tld/dir1/dir2/my_2nd_file.my_ext?p=1#test',
	'../another_file.my_ext' => 'http://my_domain.tld/dir1/another_file.my_ext',
	'../../../../../file.ext' => 'http://my_domain.tld/file.ext',
	'/image.jpg' => 'http://my_domain.tld/image.jpg',
	'/../image.jpg' => 'http://my_domain.tld/image.jpg',
	'/dir1/image.jpg' => 'http://my_domain.tld/dir1/image.jpg',
	'/dir1/../image.jpg' => 'http://my_domain.tld/image.jpg',
	'/dir1/../../image.jpg' => 'http://my_domain.tld/image.jpg',
	'/dir1/../dir2/../image.jpg' => 'http://my_domain.tld/image.jpg',
	'/dir1/../dir2/image.jpg' => 'http://my_domain.tld/dir2/image.jpg',
);
$urls = array(
	"protocol://my_domain.tld/dir1/dir2/test.htm" => "protocol://my_domain.tld/dir1/dir2/test.htm",
	"protocol://my_domain.tld/dir1/dir2/../test.htm" => "protocol://my_domain.tld/dir1/test.htm",
	"protocol://my_domain.tld/dir1/dir2/../../test.htm" => "protocol://my_domain.tld/test.htm",
	"protocol://my_domain.tld/dir1/dir2/../../../../../../../test.htm" => "protocol://my_domain.tld/test.htm",
	"protocol://my_domain.tld/../../../../../../../test.htm" => "protocol://my_domain.tld/test.htm",
	"protocol://my_domain.tld/../../../../../../../dir1/dir2/test.htm" => "protocol://my_domain.tld/dir1/dir2/test.htm",
	"protocol://my_domain.tld/../../../../../../../dir1/../dir2/test.htm" => "protocol://my_domain.tld/dir2/test.htm",
	"protocol://my_domain.tld/../../../../../../../dir1/../dir2/../test.htm" => "protocol://my_domain.tld/test.htm",
	"protocol://user:pass@my_domain.tld/dir1/dir2/test.htm" => "protocol://user:pass@my_domain.tld/dir1/dir2/test.htm",
);
}
function url_array_to_string($array) {
	$string='';
	if(array_key_exists('scheme', $array)) $string.=$array['scheme'].'://';
	if(array_key_exists('user', $array)) $string.=$array['user'];
	if(array_key_exists('pass', $array)) $string.=':'.$array['pass'];
	if(array_key_exists('user', $array) || array_key_exists('pass', $array)) $string.='@';
	if(array_key_exists('host', $array)) $string.=$array['host'];
	if(array_key_exists('port', $array)) $string.=':'.$array['port'];
	if(array_key_exists('path', $array)) $string.=$array['path'];
	if(array_key_exists('query', $array)) $string.='?'.$array['query'];
	if(array_key_exists('fragment', $array)) $string.='#'.$array['fragment'];
	return $string;
}
//static $flags=HTTP_URL_JOIN_PATH;
//static $flags=HTTP_URL_JOIN_PATH|HTTP_URL_JOIN_QUERY;
function relative_to_absolute($href, $current_url) {
	global $flags;
	$orig=parse_url($current_url);
	$href=parse_url($href);
	$link=array();
	if(array_key_exists('scheme', $href)) $link['scheme']=$href['scheme'];
	else $link['scheme']=$orig['scheme'];
	if(array_key_exists('user', $href)) $link['user']=$href['user'];
	else if(array_key_exists('user',$orig)) $link['user']=$orig['user'];
	if(array_key_exists('pass', $href)) $link['pass']=$href['pass'];
	else if(array_key_exists('pass',$orig)) $link['pass']=$orig['pass'];
	if(array_key_exists('host', $href)) $link['host']=$href['host'];
	else $link['host']=$orig['host'];
	if(array_key_exists('path',$href)) {
		if($href['path'][0]!='/') {
			$link['path']=substr($orig['path'],0,strrpos($orig['path'],'/')).'/'.$href['path'];
		}
		else {
			$link['path']=$href['path'];
		}
	}
	else {
		$link['path']=$orig['path'];
	}
	if(!array_key_exists('scheme',$href) && !array_key_exists('host',$href) && !array_key_exists('path',$href)) {
		if(array_key_exists('query', $href)) $link['query']=$href['query'];
		else $link['query']=$orig['query'];
	}
	else {
		if(array_key_exists('query', $href)) $link['query']=$href['query'];
	}
	if(array_key_exists('fragment',$href)) $link['fragment']=$href['fragment'];
	return url_array_to_string($link);
}
function absolute1($url) {
	$ar=parse_url($url);
	while(substr($ar['path'],0,4)=='/../') $ar['path']=substr($ar['path'],3);
	while(strpos($ar['path'],'../')!==FALSE) {
		$ar['path']=preg_replace('#/([^/]+)/../#','/', $ar['path']);
		while(substr($ar['path'],0,4)=='/../') $ar['path']=substr($ar['path'],3);
	}
	$link=url_array_to_string($ar);
	return $link;
}
function absolute2($url) {
	$ar=parse_url($url);
	$ar['path']=preg_replace('#^/(../)+#','/', $ar['path']);
	while(strpos($ar['path'],'../')!==FALSE) {
		$ar['path']=preg_replace('#/([^/]+)/../#','/', $ar['path']);
		while(substr($ar['path'],0,4)=='/../') $ar['path']=substr($ar['path'],3);
	}
	$link=url_array_to_string($ar);
	return $link;
}
echo '<h1>Relative to Absolute</h1>'."\r\n";
if(true) { //relative to absolute
	$total=count($hrefs);
	$i=0;
	$success=0;
	 foreach($hrefs as $k=>$v) {
		echo '<span style="color: blue">ORIGINAL URL</span> : ';
		echo $current_url.'<br/>'."\r\n";
		echo '<span style="color: blue">LINK</span> : ';
		echo $k.'<br/>'."\r\n";
		echo '<span style="color: blue">SIMPLIFIED URL</span> : ';
		echo '=> '.$v.'<br/>'."\r\n";
		$res=relative_to_absolute($k, $current_url);
		echo '<span style="color: '.(($res==$v)?'green':'red').'">RESULT 1 URL</span> : ';
		echo $res.'<br/>'."\r\n";
		$res=absolute1($res);
		echo '<span style="color: '.(($res==$v)?'green':'red').'">RESULT 2 URL</span> : ';
		echo $res.'<br/>'."\r\n";
		$res=($res==$v);
		echo $i.' '.($res?'OK':'FAIL').'<br/>'."\r\n";
		if($res) $success++;
		$i++;
	}
	echo 'total : '.$total.'<br/>'."\r\n";
	echo 'success : '.$success.'<br/>'."\r\n";
	echo 'failure : '.($total-$success).'<br/>'."\r\n";
	echo 'score : '.number_format(($success/$total)*100,2).' %<br/>'."\r\n";
}
echo '<h1>Absolute1</h1>'."\r\n";
if(true) { //absolute1
	$total=count($urls);
	$i=0;
	$success=0;
	 foreach($urls as $k=>$v) {
		echo '<span style="color: blue">ORIGINAL URL</span> : ';
		echo $k.'<br/>'."\r\n";
		echo '<span style="color: blue">SIMPLIFIED URL</span> : ';
		echo '=> '.$v.'<br/>'."\r\n";
		$res=absolute1($k);
		echo '<span style="color: '.(($res==$v)?'green':'red').'">RESULT URL</span> : ';
		echo $res.'<br/>'."\r\n";
		$res=($res==$v);
		echo $i.' '.($res?'OK':'FAIL').'<br/>'."\r\n";
		if($res) $success++;
		$i++;
	}
	echo 'total : '.$total.'<br/>'."\r\n";
	echo 'success : '.$success.'<br/>'."\r\n";
	echo 'failure : '.($total-$success).'<br/>'."\r\n";
	echo 'score : '.number_format(($success/$total)*100,2).' %<br/>'."\r\n";
}
echo '<h1>Absolute2</h1>'."\r\n";
if(true) { //absolute2
	$total=count($urls);
	$i=0;
	$success=0;
	 foreach($urls as $k=>$v) {
		echo '<span style="color: blue">ORIGINAL URL</span> : ';
		echo $k.'<br/>'."\r\n";
		echo '<span style="color: blue">SIMPLIFIED URL</span> : ';
		echo '=> '.$v.'<br/>'."\r\n";
		$res=absolute2($k);
		echo '<span style="color: '.(($res==$v)?'green':'red').'">RESULT URL</span> : ';
		echo $res.'<br/>'."\r\n";
		$res=($res==$v);
		echo $i.' '.($res?'OK':'FAIL').'<br/>'."\r\n";
		if($res) $success++;
		$i++;
	}
	echo 'total : '.$total.'<br/>'."\r\n";
	echo 'success : '.$success.'<br/>'."\r\n";
	echo 'failure : '.($total-$success).'<br/>'."\r\n";
	echo 'score : '.number_format(($success/$total)*100,2).' %<br/>'."\r\n";
}
?>