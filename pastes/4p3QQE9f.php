<?php
header('Content-Type: text/html;charset=iso-8859-1'."\r\n");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Index of <?php
$pdir=dirname(realpath(__FILE__));
$dir=$_SERVER['REQUEST_URI'];
/*var_dump($pdir);
var_dump($dir);
var_dump(is_dir($pdir.$dir));
echo '</title></head><body><p>Error</p></body></html>';die();//*/
if( !is_dir($pdir.$dir) && dirname($dir)!='/' && dirname($dir)!='\\' ) {
	//echo '#';
	echo dirname($_SERVER['REQUEST_URI']),'/';
}
else if( is_dir($pdir.$dir) && $dir!='/' && $dir!='\\' ) {
	//echo '##';
	echo $_SERVER['REQUEST_URI'];
}
else {
	//echo '###';
	echo '/';
}
?></title>
<style type="text/css">
img {
	border: 0;
}
img.icon {
	width:	20px;
	height:	20px;
	vertical-align:	middle;
	padding-right:	3px;
}
a {
	
}
</style>
</head>
<body>

<table>
<?php

/*if($_SERVER['REQUEST_URI']==='/listing.php') {
	echo "\tAcc&egrave;s refus&eacute; !","\r\n";
	echo '</table>',"\r\n\r\n";
	echo '</body>',"\r\n",'</html>';
	die();
}//*/

			function strlimit($str, $limit)
			{
				if (mb_strlen($str) > $limit)
					return substr($str, 0, $limit) . '...';
				return $str;
			}

			function render_size($size)
			{
				$return	= '0 B';
				$sizes	= array('B', 'kB', 'MB', 'GB', 'TB');
				$i	= 0;
				while ($size > 0)
				{
					$d	= pow(1024, count($sizes) - $i);
					if ($size >= $d)
						return round($size / $d, 2) . ' ' . $sizes[count($sizes) - $i];
					++$i;
				}
				return $return;
			}

			function get_ext($file)
			{
				if (strpos($file, '.') !== false)
					return substr($file, strrpos($file, '.') + 1, strlen($file) - strrpos($file, '.'));
				return 'none';
			}

$root=dirname(realpath(__FILE__));

			$path	= '.';
			if (isset($_GET['path']))
				$path = $_GET['path'];
			//var_dump($root, realpath($path));
			if(strpos(realpath($path), $root)===FALSE) $path='.';
			$o	= new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::CURRENT_AS_FILEINFO);
			$tmp	= array();
			foreach ($o as $file)
			{
				if (substr($file->getFilename(), 0, 1) == '.')
					continue;
				if (strcmp($file->getFilename(),basename($_SERVER['PHP_SELF']))==0)
					continue;
				//$tmp[($file->isDir() ? '_' : '') . $file->getFilename()] = $file;
				$tmp[$file->getFilename()] = $file;
			}
			//ksort($tmp);
			uksort($tmp,'strnatcasecmp');
			if (basename($path) != '.')
				echo "\t".'<tr><td><a href="?path='.dirname($path).'" /><img src="/parent.png" class="icon" /></a></td><td><a href="?path='.dirname($path).'" />..</a></td></tr>'."\r\n";
			foreach($tmp as $file)
			{
				if ($file->getFilename() == '.')// || get_ext($file->getFilename()) ==  'php')
					continue;
				$file2=$file->getFilename();
				if(!file_exists($path.'/'.$file2)) continue;
				if( substr($file2,0,2)=='.\\' ) $file2=substr($file2,2);
				$file3=rawurlencode($file2);
				echo "\t".'<tr>'."\r\n";
				if ($file->isDir())
					printf("\t\t".'<td><a href="?path='.$path.'/%s"><img src="/folder.png" class="icon"/></a></td>'."\r\n", $file3);
				else
					printf("\t\t".'<td><a href="'.$path.'/%s"><img src="/dl.gif" class="icon" /></a></td>'."\r\n", $file3);
				if ($file->isFile())
					printf("\t\t<td><a href=\"$path/%s\">%s [%s]</a></td>\r\n\t\t\t\t<td>%s</td>\r\n\t\t\t\t<td>%s</td>\r\n", $file3, strlimit($file2, 40), get_ext($file2), render_size($file->getSize()), date('d/m/Y H:i:s', $file->getCTime()));
				else
					printf("\t\t<td><a href=\"?path=$path/%s\">%s</a></td>\r\n\t\t\t\t<td>&nbsp;</td>\r\n\t\t\t\t<td>%s</td>\r\n", $file3, strlimit($file2, 40), date('d/m/Y H:i:s', $file->getCTime()));
				echo "\t".'</tr>'."\r\n";
			}
?></table>

</body>
</html>