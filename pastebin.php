<?php
require('config.php');
$api_user_name		= urlencode($api_user_name);
$api_user_password	= urlencode($api_user_password);

if(!file_exists('user_key.txt')) {
	$url			= 'http://pastebin.com/api/api_login.php';
	$ch			= curl_init($url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_dev_key='.$api_dev_key.'&api_user_name='.$api_user_name.'&api_user_password='.$api_user_password.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 0);

	$response 		= curl_exec($ch);
	if(in_array($response, array(
		'Bad API request, use POST request, not GET',
		'Bad API request, invalid api_dev_key',
		'Bad API request, invalid login',
		'Bad API request, account not active',
		'Bad API request, invalid POST parameters',
		))) {
			var_dump($response);
			die();
	}
	//var_dump($response);
	$api_user_key=trim($response);
	file_put_contents('user_key.txt',$api_user_key);
}
else {
	$api_user_key=file_get_contents('user_key.txt');
}
var_dump($api_user_key);
if(strlen($api_user_key)==0) die();
//die();

if(!file_exists('user_info.txt')) {
	$url 			= 'http://pastebin.com/api/api_post.php';
	$ch 			= curl_init($url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=userdetails&api_user_key='.$api_user_key.'&api_dev_key='.$api_dev_key.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 0);

	$response  		= curl_exec($ch);
	if(in_array($response, array(
		'Bad API request, invalid api_option',
		'Bad API request, invalid api_dev_key',
		'Bad API request, invalid api_user_key',
	))) {
			var_dump($response);
			die();
	}
	//echo '<pre>';echo htmlentities($response);echo '</pre>';
	$user_info=trim($response);
	file_put_contents('user_info.txt', $response);
}
else {
	$user_info=file_get_contents('user_info.txt');
}
//die();

if(!file_exists('user_pastes.txt')) {
	$api_results_limit 	= '100';
	$url 			= 'http://pastebin.com/api/api_post.php';
	$ch 			= curl_init($url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=list&api_user_key='.$api_user_key.'&api_dev_key='.$api_dev_key.'&api_results_limit='.$api_results_limit.'');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_NOBODY, 0);

	$response  		= curl_exec($ch);
	if(in_array($response, array(
		'Bad API request, invalid api_option',
		'Bad API request, invalid api_dev_key',
		'Bad API request, invalid api_user_key',
		//'No pastes found.',//good
	))) {
			var_dump($response);
			die();
	}
	//echo '<pre>';echo htmlentities($response);echo '</pre>';
	$user_pastes=trim($response);
	file_put_contents('user_pastes.txt', $response);
}
else {
	$user_pastes=file_get_contents('user_pastes.txt');
}

$format=array();

if($res=preg_match_all('#<paste_format_short>([^<]+)</paste_format_short>#is', $user_pastes, $matches)) {
	$format=$matches[1];
	/*echo '<pre>';var_dump($matches[1]);echo '</pre>';
	die();//*/
}
else {
	print('No pastes found.');
	die();
}

$pastes=array();

if($res=preg_match_all('#<paste_key>([^<]+)</paste_key>#is', $user_pastes, $matches)) {
	$pastes=$matches[1];
	//echo '<pre>';var_dump($matches[1]);echo '</pre>';
}
else {
	print('No pastes found.');
	die();
}

assert(count($format)==count($pastes)) or die('Invalid pastes list.');

//var_dump($format);die();

foreach($format as $k=>$v) {
	if($v=='text') $format[$k]='txt';
	if($v=='bash') $format[$k]='sh';
	if($v=='perl') $format[$k]='pl';
	if($v=='python') $format[$k]='py';
}

foreach($pastes as $i=>$paste) {
	$filename='pastes/'.$paste.'.'.$format[$i];
	if(!file_exists($filename)) {
		$data=file_get_contents('http://pastebin.com/raw.php?i='.$paste);
		var_dump($paste); var_dump($data);
		file_put_contents($filename, $data);
	}
	else {
		$data=file_get_contents($filename);
		if(strlen($data)==0) {
			$data=file_get_contents('http://pastebin.com/raw.php?i='.$paste);
			var_dump($paste);var_dump($data);
			file_put_contents($filename, $data);
		}
	}
}

var_dump('done');

?>