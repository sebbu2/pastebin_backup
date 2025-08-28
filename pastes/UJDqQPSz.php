<?php

/* config */

$username = 'waybones92%40gmail.com';
$password = 'raiden123';

/* xdebug */

ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

/* context */

$useragent = 'Mozilla';
$opts=array(
        'http'=>array(
				'follow_location' => 0,
                'user_agent'=>$useragent,
				'ignore_errors' => 1,
        ),
        'ssl'=>array(
                'allow_self_signed'=>true,
        ),
);
$opts['http']['header']='';
$ctx = stream_context_create($opts);

/* header */

header('Content-type: text/html');
//header('Content-type: text/plain');

/* url */

$url0 = 'http://www.sneakersnstuff.com/en';
$url1 = 'http://www.sneakersnstuff.com/en/authentication/login';
$data = file_get_contents($url0, false, $ctx);

/* retr cookies */

$headers=$http_response_header;
$cookies=array();
foreach($headers as $header)
{
	if(strpos($header, 'Set-Cookie: ')===0)
	{
		//$cookies[] = substr($header, 12);
		$cookies[] = substr($header, 12, strpos($header, ';')-12);
	}
}
if(array_key_exists('debug', $_REQUEST))
{
	var_dump($cookies);
	//die();
}

/* show data */

//var_dump($data);die();
//echo $data;die();

/* retr login form */

preg_match_all('#<form(?:.*)(?:(action="([^"]*)")?|(method="([^"]*)")?)*(.*)</form>#sU', $data, $matches);
$matches=$matches[0];
function only_login_form($data) {
	return strpos($data, 'login-form')!==false;
}
$matches=array_filter($matches, 'only_login_form');
//var_dump($matches);

/* retr input */

foreach($matches as $matche) {
	
	preg_match_all('#<(?:input|form)(?:.*)>#sU', $matche, $matches2);
	$matches2=$matches2[0];
	if(array_key_exists('debug', $_REQUEST))
	{
		var_dump($matches2);
		//die();
	}
}

/* use input */

$opts['http']['method'] = 'POST';
$opts['http']['content'] = 'ReturnUrl=&username='.$username.'&password='.$password; // modify that line for a for over <input's that replace thoses 2 php var
//$opts['http']['content'] = 'ReturnUrl=&amp;username='.$username.'&amp;password='.$password; // modify that line for a for over <input's that replace thoses 2 php var
//$opts['http']['content'] = 'ReturnUrl=; username='.$username.'; password='.$password; // modify that line for a for over <input's that replace thoses 2 php var
$opts['http']['header'] = array('Content-type: application/x-www-form-urlencoded', 'Cookie: '.implode('; ',$cookies));

foreach($matches2 as $matche2)
{
	if(strpos($matche2, '_AntiCsrfToken')!==false)
	{
		$opts['http']['content'] .= '&_AntiCsrfToken='.substr($matche2, 50, -2);
	}
}

if(array_key_exists('debug', $_REQUEST))
{
	var_dump($opts);
}

$ctx = stream_context_create($opts);

$data2 = file_get_contents($url1, false, $ctx);

/* retr cookies */

$headers=$http_response_header;
$cookies=array();
foreach($headers as $header)
{
	if(array_key_exists('debug', $_REQUEST))
	{
		var_dump($header);
	}
	if(strpos($header, 'Set-Cookie: ')===0)
	{
		//$cookies[] = substr($header, 12);
		$cookies[] = substr($header, 12, strpos($header, ';')-12);
	}
}
if(array_key_exists('debug', $_REQUEST))
{
	var_dump($cookies);
	//die();
}

$data2 = str_replace('<head>', '<head><base href="http://www.sneakersnstuff.com/">', $data2);

if(!array_key_exists('debug', $_REQUEST))
{
	//echo $data2;die();
}

$opts['http']['method'] = 'GET';
$opts['http']['content'] = '';
$opts['http']['header'] = array('Cookie: '.implode('; ',$cookies));
$ctx = stream_context_create($opts);

$data3 = file_get_contents($url0, false, $ctx);

/* retr cookies */

$headers=$http_response_header;
$cookies=array();
foreach($headers as $header)
{
	if(strpos($header, 'Set-Cookie: ')===0)
	{
		//$cookies[] = substr($header, 12);
		$cookies[] = substr($header, 12, strpos($header, ';')-12);
	}
}
if(array_key_exists('debug', $_REQUEST))
{
	var_dump($cookies);
	//die();
}

/* show data */

$data3 = str_replace('<head>', '<head><base href="http://www.sneakersnstuff.com/">', $data3);

//var_dump($data3);die();
if(!array_key_exists('debug', $_REQUEST))
{
	echo $data3;die();
}

?>
