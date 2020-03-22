<?php

//*//better with file (array) than file_get_contents (string)
//$data=file_get_contents('http://api.nzbmatrix.com/v1.1/search.php?search=warhammer&username=nice925&apikey=611fea5b5586ccc04a25015ea6503f00');
//$data=file_get_contents('cache/nzbmatrix-warhammer.dat');
//$data=file('http://api.nzbmatrix.com/v1.1/search.php?search=warhammer&num=10&username=nice925&apikey=611fea5b5586ccc04a25015ea6503f00', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
$data=file('cache/nzbmatrix-warhammer.dat', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);

echo chr(033),'[31m','NZBMATRIX',chr(033),'[0m',chr(10);

if(1) {//ini-like
	$result=array();
	$i=0;
	$cur=true;
	$result[$i]=array();
	
	//foreach(explode(chr(10), $data) as $line) {
	foreach($data as $line) {
		if(substr($line, -1)==';') {
			$line=substr($line, 0, -1);
		}
		//if(strlen($line)==0) continue;
		if($line=='|') {
			++$i;
			$cur=false;
			continue;
		}
		$val=explode(':', $line, 2);
		if(!$cur) {
			$result[$i]=array();
			$cur=true;
		}
		if($cur) {
			switch($val[0]) {
				case 'NZBID':
					$result[$i]['id']=$val[1];
					break;
				case 'NZBNAME':
					$result[$i]['name']=$val[1];
					break;
				case 'CATEGORY':
					$result[$i]['cat']=$val[1];
					break;
				case 'SIZE':
					$result[$i]['size']=$val[1];
					break;
				default:
					//$result[$i][$val[0]]=$val[1];
					break;
			}
		}
	}
	
	var_dump(count($result));
	var_dump($result[0]);
	//var_dump($result);
}
/*
link
http://nzbmatrix.com/nzb-download.php?id=1395565&name=Warhammer%2040%2C000%20%20%20%5BHorus%20Heresy%5D%20%20%20The%20Crimson%20Fist%20%20%20John%20French%20%28html%29
http://api.nzbmatrix.com/v1.1/download.php?id={NZBID}&username={USERNAME}&apikey={APIKEY}
*/

//die();//*/

//*
//$data=file_get_contents('http://api.omgwtfnzbs.com/xml/?search=ncis&user=ceny&api=2ef248c04979d6b3a7b283ec3dc32cca');
$data=file_get_contents('cache/omgwtfnzbs-ncis.dat');

echo chr(033),'[31m','OMGWTFNZBS',chr(033),'[0m',chr(10);

if(1) {//xml
	$result=array();
	$i=0;
	
	$xml=simplexml_load_string($data);
	
	foreach($xml->search_req->post as $elem) {
		$result[$i]=array(
			'id'=>(string)$elem->nzbid,
			'name'=>(string)$elem->release,
			'cat'=>(string)$elem->categoryid,
			'catname'=>(string)$elem->cattext,
			'size'=>(string)$elem->sizebytes,
			//'group'=>(string)$elem->group,
			//'usenetage'=>(string)$elem->usenetage,
			//'details'=>(string)$elem->details,
			//'getnzb'=>(string)$elem->getnzb,
		);
		++$i;
	}
	
	var_dump(count($result));
	var_dump($result[0]);
	//var_dump($result);
}
/*
getnzb
*/

//die();//*/

//*
//$data=file_get_contents('http://85.214.105.230/search/scripts/search.php?keywords=Musketiere');
$data=file_get_contents('cache/gotnzb4u-musketiere.dat');

echo chr(033),'[31m','GOTNZB4U',chr(033),'[0m',chr(10);

if(1) {//json
	$data=str_replace(array(',)',',]',',}'),array(')',']','}'),$data);
	//$data=str_replace('\'','"',$data);
	
	//var_dump($data);
	
	$result=json_decode($data, true);//array
	//$result=json_decode($data);//object
	
	var_dump(count($result));
	var_dump($result[0]);
	//var_dump($result);
}
/*
http://85.214.105.230/nzb.php?id={nID}&section={strSection}&key={search.strKey}
*/

die();//*/
?>