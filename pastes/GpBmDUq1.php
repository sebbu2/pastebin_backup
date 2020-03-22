<?php

Class TestFonction
{
	public function function01(string $arg1)
	{
		echo 'Hello '.$arg1.'!'."\r\n";
	}
	
	public function function02(string $arg1)
	{
		echo 'Bye '.$arg1.'!'."\r\n";
	}
};

$ar=array('function01','function02');

$tf=new TestFonction();

foreach($ar as $fct)
{
	$tf->{$fct}('sebbu');
}
?>