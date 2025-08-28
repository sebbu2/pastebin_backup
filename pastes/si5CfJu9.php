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
require('table-parser.php');

$data=file_get_contents('input.html');

$table = new table_parser($data);

$arr = $table->parse();

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
