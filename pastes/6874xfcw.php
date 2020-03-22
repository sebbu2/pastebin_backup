<?php
//header('Content-Type: text/plain');
$fp=fopen('C:\Users\sebbu\Downloads\test.txt', 'r') or die('failed to open file');
$data=unpack('N', chr(0).fread($fp, 3));
$data=$data[1];
//var_dump($data);
$dim=unpack('n2', fread($fp, 4));
$width=$dim[1];
$height=$dim[2];
//var_dump($width, $height);
$img=imagecreatetruecolor($width, $height);
imageantialias($img, false);
imagealphablending($img, false);
$bg=imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $bg);
for($y=0;$y<$height;++$y) {
	for($x=0;$x<$width;++$x) {
		$pix=unpack('C3', fread($fp, 3));
		$red=$pix[1];
		$green=$pix[2];
		$blue=$pix[3];
		//var_dump($red, $green, $blue);
		//die();
		$col=imagecolorallocate($img, $red, $green, $blue);
		if($col === false) {
			echo 'imagecolorallocate failed for $red='.$red.' $green='.$green.' $blue='.$blue."\n";
			die();
		}
		imagesetpixel($img, $x, $y, $col);
	}
}
imagealphablending($img, true);
imagepng($img, 'C:\Users\sebbu\Downloads\test.png');
echo 'image created<br/>'."\n";
fclose($fp);
?>