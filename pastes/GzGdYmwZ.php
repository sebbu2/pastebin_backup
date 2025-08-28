<?php
if(PHP_OS==='Windows_NT' || PHP_OS==='WINNT') chdir('D:/htdocs/iBooks');
else if(PHP_OS==='Linux') chdir('/z/ebooks/iBooks');
chdir('Z:\ebooks\iBooks-metadata\unc');

function compare_name($str1, $str2) {
	if(substr($str1,0,2)=='._') $str1=substr($str1, 2);
	if(substr($str2,0,2)=='._') $str2=substr($str2, 2);
	return strnatcasecmp($str1, $str2);
}
function check_xpath_result($res)
{
	if(!(is_array($res))) return false;
	if(!(count($res)==1)) return false;
	if(!(is_object($res[0]))) return false;
	if(!(get_class($res[0])=='SimpleXMLElement')) return false;
	if(!((int)$res[0]->count==0)) return false;
	if(!(count($res[0]->attributes())==0)) return false;
	if(!(count($res[0]->children())==0)) return false;
	if(!(in_array($res[0]->getName(),array('string','integer')))) return false;
	return true;
}
function check_xpath_result_details($res)
{
	var_dump(!(is_array($res)));
	var_dump(count($res),1);
	var_dump(!(is_object($res[0])));
	var_dump(get_class($res[0]),'SimpleXMLElement');
	var_dump((int)$res[0]->count,0);
	var_dump(count($res[0]->attributes()),0);
	var_dump(count($res[0]->children()),0);
	var_dump($res[0]->getName(),'string|integer');
}
function zip_add_recur($zip, $glob, $exclude=array()) {
	if(!is_object($zip)||get_class($zip)!=='ZipArchive') return false;
	$source=realpath('.');
	$sources[]=$source;
	$source=str_replace('\\', '/', $source);
	$sources[]=$source;
	//var_dump($sources);die();
	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	foreach($files as $f)
	{
		if( in_array($f->getFilename(), array('.', '..')) ) continue;
		$f2=$f->getPathname();
		$f2=realpath($f2);
		$f3=$f2;
		$f3=str_replace($sources[0].'\\', '', $f3);
		$f3=str_replace($sources[1].'/', '', $f3);
		if( in_array($f3, $exclude)) continue;
		$f3=str_replace('\\','/',$f3);
		//var_dump($f3);//die();
		//print_r($f3."\n");
		if(!file_exists($f2)||!is_readable($f2)) die('unreadable file "'.$f3.'".');
		if(is_dir($f2)) $res=$zip->addEmptyDir($f3);
		else if(is_file($f2)) $res=$zip->addFile($f2, $f3);
		if(!$res) return $res;
	}
	$res=$zip->close();
	return $res;
}

$ar = glob('*', GLOB_MARK);
//$ar = array_merge ( $ar, glob('.*', GLOB_MARK) );
usort($ar, 'compare_name');
//var_dump($ar);
foreach($ar as $fn) {
	//if(is_dir($fn)) if(file_exists(substr($fn,0,-1).'.epub')) rename(substr($fn,0,-1).'.epub', '../iBooks-metadata/'.substr($fn,0,-1).'.epub');
	//if(is_dir($fn)) if(file_exists(substr($fn,0,-1).'.ibooks')) rename(substr($fn,0,-1).'.ibooks', '../iBooks-metadata/'.substr($fn,0,-1).'.ibooks');
	if(!is_dir($fn)) continue;
	if(substr($fn,-1)!=='/') $fn.='/';
	if(substr($fn,0,1)=='_') continue;
	$mt=file_get_contents($fn.'mimetype');
	$mt=rtrim($mt);
	$mt_valid=array('application/epub+zip', 'application/x-ibooks+zip');
	if(!in_array($mt, $mt_valid)) {
		var_dump($fn, $mt);
		die();
	}
	$data=file_get_contents($fn.'iTunesMetadata.plist');
	$data_valid=array('<?xml ');
	if(!in_array(substr($data, 0, 6), $data_valid)) {
		var_dump($fn, substr($data, 0, 6));
		die();
	}
	//var_dump($fn);
	$itemId=0;
	$publisher='';
	$author='';
	$name='';
	$name2='';
	$subtitle='';
	$year='';
	$ext='';
	$mimetype='';
	$xml = simplexml_load_string($data);
	$res2 = $xml->xpath('//key[. = "itemId"]/following::node()[2]');
	if(check_xpath_result($res2)) $itemId=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "publisher"]/following::node()[2]');
	if(check_xpath_result($res2)) $publisher=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "artistName"]/following::node()[2]');
	if(check_xpath_result($res2)) $author=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "itemName"]/following::node()[2]');
	if(check_xpath_result($res2)) $name=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "sortName"]/following::node()[2]');
	if(check_xpath_result($res2)) $name2=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "subtitle"]/following::node()[2]');
	if(check_xpath_result($res2)) $subtitle=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "year"]/following::node()[2]');
	if(check_xpath_result($res2)) $year=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "fileExtension"]/following::node()[2]');
	if(check_xpath_result($res2)) $ext=strval($res2[0]);
	$res2 = $xml->xpath('//key[. = "mime-type"]/following::node()[2]');
	if(check_xpath_result($res2)) $mimetype=trim(strval($res2[0]));
	//var_dump($publisher, $author, $name, $name2, $subtitle, $year, $ext, $mimetype);
	if($itemId=='630526236') $author='Various Authors';
	if($itemId=='853028807') $name='Grimm_s Fairy Tales';
	//$ext='epub';
	$filename=$publisher.' - '.$author.' - '.$name.' ['.$itemId.'].'.$ext;
	$filename=str_replace(array('\'\'','\''), '_', $filename);
	$filename=str_replace(array(':','"','/','\\','&'),'-', $filename);
	$filename=str_replace(array('<','>','|','?','*'),'', $filename);
	$filename=str_replace('\'\'', '\'', $filename);
	$filename=str_replace('  ',' ',$filename);
	//var_dump($author, $name);
	var_dump($filename);
	/*$zip = new ZipArchive;
	if(!is_object($zip)) {
		var_dump('zip error : '.(int)$zip);
		die();
	}
	if(PHP_VERSION>=80000) if(($res=$zip->getStatusString())!=='No error') var_dump($res);
	$res = $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
	if($res!==true) {
		$det=array(ZipArchive::ER_EXISTS => 'ZipArchive::ER_EXISTS', ZipArchive::ER_INCONS => 'ZipArchive::ER_INCONS', ZipArchive::ER_INVAL => 'ZipArchive::ER_INVAL', ZipArchive::ER_MEMORY => 'ZipArchive::ER_MEMORY', ZipArchive::ER_NOENT => 'ZipArchive::ER_NOENT', ZipArchive::ER_NOZIP => 'ZipArchive::ER_NOZIP', ZipArchive::ER_OPEN => 'ZipArchive::ER_OPEN', ZipArchive::ER_READ => 'ZipArchive::ER_READ', ZipArchive::ER_SEEK => 'ZipArchive::ER_SEEK',);
		var_dump('zip error : '.(int)$res);
		if(array_key_exists($res, $det)) var_dump($det[$res]);
		die();
	}
	if(($res=$zip->getStatusString())!=='No error') var_dump($res);
	chdir($fn);
	if(!file_exists('mimetype')||!is_readable('mimetype')) die('unreadable file.');
	$res=$zip->addFile('mimetype');
	//$res=$zip->addFile('../mimetype', 'mimetype');
	if($res!==true) {
		var_dump('zip error : '.(int)$res);
		die();
	}
	if(($res=$zip->getStatusString())!=='No error') var_dump($res);
	//$ar2=get_class_methods($zip);sort($ar2);var_dump($ar2);
	$res=$zip->setCompressionIndex(0, ZipArchive::CM_STORE);
	if($res!==true) {
		var_dump('zip error : '.(int)$res);
		if(($res=$zip->getStatusString())!=='No error') var_dump($res);
		die();
	}
	$res=zip_add_recur($zip, '*', array('mimetype'));
	chdir('..');//*/
	while(in_array(substr($fn, -1),array('/','\\'))) $fn=substr($fn, 0, -1);
	$res = rename($fn.'.'.$ext, $filename);
	if($res)
	{
		rename($fn, '__delete/'.$fn);
	}
	else {
		var_dump('error '.(int)$res.' with '.$fn.'.');
		die();
	}
	//die();
}
