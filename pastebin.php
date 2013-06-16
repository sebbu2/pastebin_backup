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

if(strlen($api_user_key)==0) {
	var_dump($api_user_key);
	die();
}

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

if(strlen($user_info)==0) {
	var_dump($user_info);
	die();
}

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

if(strlen($user_pastes)==0) {
	var_dump($user_pastes);
	die();
}

$format=array();

if($res=preg_match_all('#<paste_format_short>([^<]+)</paste_format_short>#is', $user_pastes, $matches)) {
	$format=$matches[1];
}
else {
	print('No pastes found.');
	var_dump($user_pastes);
	die();
}

echo <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>My Pastes</title>
<base href="https://github.com/sebbu2/pastebin_backup/blob/master/"/>
</head>
<body>


EOF;

$dates=array();
if($res=preg_match_all('#<paste_date>([^<]+)</paste_date>#is', $user_pastes, $matches)) {
	$dates=$matches[1];
}

$titles=array();
if($res=preg_match_all('#<paste_title>([^<]+)</paste_title>#is', $user_pastes, $matches)) {
	$titles=$matches[1];
}

$pastes=array();

if($res=preg_match_all('#<paste_key>([^<]+)</paste_key>#is', $user_pastes, $matches)) {
	$pastes=$matches[1];
}
else {
	print('No pastes found.');
	die();
}

assert(count($format)==count($pastes)) or die('Invalid pastes list.');

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
		else {
			echo '<a href="'.$filename.'" target="_blank">'.$titles[$i].'</a> '.date('Y-m-d H:i:s',$dates[$i]).' <b>('.$format[$i].')</b><br/>'."\n";
			//echo '<a href="'.$filename.'" target="_blank">'.$titles[$i].'</a> ('.$format[$i].')<br/>'."\n";
		}
	}
}

echo <<<EOF

</body>
</html>
EOF;
?>