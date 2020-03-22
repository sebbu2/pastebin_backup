<?php
function error_handler(int $errno, string $errstr, string $errfile, int $errline, array $errcontext)
{
	var_dump($errno.':'.$errstr);
	var_dump($errfile.':'.$errline);
	//var_dump($errcontext);
	//print "backtrace:\n";
	$backtrace = debug_backtrace();
	array_shift($backtrace);
	foreach($backtrace as $i=>$l){
		print @"[$i] in function <b>{$l['class']}{$l['type']}{$l['function']}</b>";
		if($l['file']) print " in <b>{$l['file']}</b>";
		if($l['line']) print " on line <b>{$l['line']}</b>";
		print "<br/>\n";
	}
	return true;
}
function exception_handler(Throwable $ex)
{
	if(function_exists('fix_error')) {
		$str=fix_error($ex);
		if($str) return false;
	}
	//var_dump($ex);
	$str=get_class($ex);
	echo $str.' ';
	$str=$ex->getCode().':'.$ex->getMessage();
	if(!is_null($str)) echo $str."<br/>\r\n";
	$str=$ex->
	$str=$ex->getFile().':'.$ex->getLine();
	if(!is_null($str)) echo 'on '.$str."<br/>\r\n";
	$str=$ex->getPrevious();
	if(!is_null($str)) echo $str."<br/>\r\n";
	$tr=$ex->getTrace();
	//var_dump($tr);die();
	for($i=0;$i<count($tr);$i++)
	{
		if(array_key_exists('args', $tr[$i])) $tr[$i]['args'] = array_map('gettype', $tr[$i]['args']);
		echo '#'.$i.' ';
		//var_dump($tr[$i]);die();
		$str=$tr[$i]['file'].':'.$tr[$i]['line'].' -> '.$tr[$i]['function'];
		if(array_key_exists('args', $tr[$i])) $str.='('.implode($tr[$i]['args']).')';
		echo $str."<br/>\r\n";
	}
	var_dump('test');//*/
	return false;
	//return true;
}
set_error_handler('error_handler', E_ALL);
set_exception_handler('exception_handler');
try
{
//trigger_error("Cannot divide by zero", E_USER_ERROR);
}
catch(Throwable $e){}
try
{
throw new Exception('my Uncaught Exception');
}
catch(Throwable $e){}
?>