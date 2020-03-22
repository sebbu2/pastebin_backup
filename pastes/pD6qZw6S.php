<!DOCTYPE html>
<html>
<head>
<title>test HTML > TABLE</title>
<style type="text/css">
TABLE
{
	border: 1px black solid;
	border-collapse: collapse;
}
TR
{
	height: 32px;
}
TD
{
	width: 32px;
	text-align: center;
}
</style>
</head>
<body>

<?php
$data=file_get_contents('input.html');

preg_match_all('#<tr>(.*)</tr>#sU', $data, $matches);

$row=count($matches[0]);
$col=-1;

$arr=array();
for($i=0;$i<$row;$i++) $arr[$i]=array();

$i=0;
foreach($matches[1] as $data2)
{
	$j=0;
	$k=0;
	preg_match_all('#<td(?:\s*(rowspan)="([^"]*)"|\s*(colspan)="([^"]*)")*>(.*)</td>#sU', $data2, $matches2);
	if($col==-1) $col=count($matches[0]);
	for($k=0;$k<count($matches2[0]);$k++)
	{
		$row2=1;
		$col2=1;
		while(array_key_exists($j, $arr[$i]) && $arr[$i][$j]!==false) $j++;
		$data2 = $matches2[5][$k];
		if($matches2[1][$k]=='rowspan') $row2=$matches2[2][$k];
		if($matches2[1][$k]=='colspan') $col2=$matches2[2][$k];
		if($matches2[3][$k]=='rowspan') $row2=$matches2[4][$k];
		if($matches2[3][$k]=='colspan') $col2=$matches2[4][$k];
		for($a=0;$a<$row2;$a++)
		{
			for($b=0;$b<$col2;$b++)
			{
				$arr[$i+$a][$j+$b]=$data2;
			}
		}
		$j++;
	}
	$i++;
}

//tri du tableau (les clés sont pas dans le bon ordre)
ksort($arr);
for($i=0;$i<count($arr);$i++) ksort($arr[$i]);

//var_dump($arr);

echo '<table border="1">'."\r\n";
foreach($arr as $line)
{
	echo "\t".'<tr>'."\r\n";
	foreach($line as $cell)
	{
		echo "\t\t".'<td>'.$cell.'</td>'."\r\n";
	}
	echo "\t".'</tr>'."\r\n";
}
echo '</table>'."\r\n";
?>

</body>
</html>