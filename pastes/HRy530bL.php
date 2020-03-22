<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
<title>Time Sheet</title>
</head>
<body>

<div><?php
$date_format='d/m/Y, H:i:s';//CAN be changed
$db=mysql_connect('localhost','sebbu','********') or die('access denied !</div></body></html>');//MUST be changed
mysql_select_db('sebbu',$db) or die('couldn&#039;t select database !</div></body></html>');//MUST be changed
function print_duration($time) {
	assert(is_numeric($time));
	$time+=0;
	$str='';
	$seconds=(int)($time%60);
	$minutes=(int)(($time/60)%60);
	$hours=(int)(($time/3600)%24);
	$days=(int)($time/86400);
	if($days>0) $str.=$days.'d';
	if($days>0||$hours>0) $str.=($hours<10?'0'.$hours:$hours).'h';
	if($days>0||$hours>0||$minutes>0) $str.=($minutes<10?'0'.$minutes:$minutes).'m';
	if($days>0||$hours>0||$minutes>0||$seconds>0) $str.=($seconds<10?'0'.$seconds:$seconds).'s';
	return $str;
}
if(array_key_exists('action', $_REQUEST)&&array_key_exists('eid', $_REQUEST)) {
	$action=$_REQUEST['action'];
	$eid=$_REQUEST['eid'];
	assert(is_numeric($_REQUEST['eid'])) or die('eid should be numeric !</div></body></html>');
	$eid+=0;
	if($action=='start') {
		$res=mysql_query('SELECT count(*) FROM `times` WHERE `eid`='.$eid.' AND `end_time` IS NULL ORDER BY `start_time`', $db);
		$nb=mysql_fetch_row($res);
		$nb=$nb[0];
		@assert($nb==0) or die('You can&#039;t start the counter without stopping it first !</div></body></html>');
		$res=mysql_query('INSERT INTO `times` VALUES('.$eid.', '.time().', NULL)', $db);
		@assert($res==1) or die('ERROR : iNSERT INTO failed !</div></body></html>');
		@assert(mysql_affected_rows($db)==1) or die('ERROR : INSERT INTO insered wrong number of lines !</div></body></html>');
		echo 'Counter successfully started !<br/>'."\r\n";
		echo '<a href="javascript:history.go(-1);">Go back</a>.'."\r\n";
	}
	elseif($action=='stop') {
		$res=mysql_query('SELECT count(*) FROM `times` WHERE `eid`='.$eid.' AND `end_time` IS NULL ORDER BY `start_time`', $db);
		$nb=mysql_fetch_row($res);
		$nb=$nb[0];
		@assert($nb==1) or die('You can&#039;t stop the counter without starting it first !</div></body></html>');
		$res=mysql_query('UPDATE `times` SET `end_time`='.time().' WHERE `eid`='.$eid.' AND `end_time` IS NULL', $db);
		@assert($res==1) or die('ERROR : UPDATE failed !</div></body></html>');
		@assert(mysql_affected_rows($db)==1) or die('ERROR : UPDATE updated wrong number of lines : '.mysql_affected_rows($db).'!</div></body></html>');
		echo 'Counter successfully stopped !<br/>'."\r\n";
		echo '<a href="javascript:history.go(-1);">Go back</a>.'."\r\n";
	}
	else {
		echo 'Action should be start or stop.';
	}
}
else if(array_key_exists('eid', $_REQUEST)) {
	$eid=$_REQUEST['eid'];
	assert(is_numeric($_REQUEST['eid'])) or die('eid should be numeric !</div></body></html>');
	$eid+=0;
	echo '<a href="'.$_SERVER['PHP_SELF'].'?eid='.$eid.'&action=start">start</a><br/>'."\r\n";
	echo '<a href="'.$_SERVER['PHP_SELF'].'?eid='.$eid.'&action=stop">stop</a><br/>'."\r\n";
	$res=mysql_query('SELECT `start_time`, `end_time` FROM `times` WHERE `eid`='.$eid.' ORDER BY `start_time`', $db);
	$data=array();
	while(($line=mysql_fetch_row($res))!==FALSE) {
		$data[]=$line;
	}
	//check only one NULL
	$nulls=0;
	foreach($data as $line) {
		if($line[1]===NULL) ++$nulls;
	}
	@assert($nulls<=1) or die ('only one NULL allowed !</div></body></html>');
	//show times
	echo '<table border="1">'."\r\n";
	if(count($data)!=0) {
		echo "\t".'<thead>'."\r\n";
		echo "\t\t".'<tr>'."\r\n";
		echo "\t\t\t".'<th>start time</th>'."\r\n";
		echo "\t\t\t".'<th>end time</th>'."\r\n";
		echo "\t\t".'</tr>'."\r\n";
		echo "\t".'</thead>'."\r\n";
	}
	echo "\t".'<tbody>'."\r\n";
	foreach($data as $line) {
		echo "\t\t".'<tr>'."\r\n";
		echo "\t\t\t".'<td>'.date($date_format, $line[0]).'</td>'."\r\n";
		if($line[1]===NULL) {
			echo "\t\t\t".'<td><i>still counting</i></td>'."\r\n";
		}
		else {
			echo "\t\t\t".'<td>'.date($date_format, $line[1]).'</td>'."\r\n";
		}
		echo "\t\t".'</tr>'."\r\n";
	}
	if(count($data)==0) {
		echo "\t\t".'<tr>'."\r\n";
		echo "\t\t\t".'<td>No times yet</td>'."\r\n";
		echo "\t\t".'</tr>'."\r\n";
	}
	echo "\t".'</tbody>'."\r\n";
	echo '</table>'."\r\n";
	//show total
	$total=0;
	foreach($data as $line) {
		if($line[1]!==NULL) {
			assert($line[1]>$line[0]) or die('end_time should be greater than start_time !</div></body></html>');
			$total+=($line[1]-$line[0]);
		}
		else {
			$total+=(time()-$line[0]);
		}
	}
	if(count($data)>0) {
		echo 'Total time is : '.print_duration($total);
		if($nulls>0) echo ' and counting';
		echo '.';
	}
}
else if(array_key_exists('action', $_REQUEST)) {
	$action=$_REQUEST['action'];
	if($action=='add') {
		echo '<form action="'.$_SERVER['PHP_SELF'].'?action=add2" method="POST">'."\r\n";
		echo 'Employee name :<br/>'."\r\n";
		echo '<input type="text" name="name"/><br/>'."\r\n";
		echo '<br/><input type="submit" value="Submit"/>'."\r\n";
		echo '<br/><input type="reset" value="RESET"/>'."\r\n";
		echo '</form>'."\r\n";
	}
	elseif($action='add2') {
		if(array_key_exists('name', $_REQUEST)) {
			$name=mysql_real_escape_string($_REQUEST['name']);
			$res=mysql_query('INSERT INTO `employee` (`name`) VALUES("'.$name.'")');
			@assert($res==1) or die('ERROR : iNSERT INTO failed !</div></body></html>');
			@assert(mysql_affected_rows($db)==1) or die('ERROR : INSERT INTO insered wrong number of lines !</div></body></html>');
			echo 'Employee successfully added !<br/>'."\r\n";
			echo '<a href="javascript:history.go(-2);">Go back</a>.'."\r\n";
		}
		else {
			echo 'ERROR : name is missing !'."\r\n";
		}
	}
}
else {
	$res=mysql_query('SELECT `eid`, `name` FROM `employee` ORDER BY `name`', $db);
	while(($line=mysql_fetch_row($res))!==FALSE) {
		echo '<a href="'.$_SERVER['PHP_SELF'].'?eid='.$line[0].'">'.$line[1].'</a><br/>';
	}
	echo '<br/><a href="'.$_SERVER['PHP_SELF'].'?action=add">Add new employee</a>';
}
mysql_close($db);
?></div>

</body>
</html>