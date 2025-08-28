<?php
function print_type(&$type) {
	//var_dump($type, $type->getName());
	if (!is_null($type)) {
		if ($type instanceof ReflectionNamedType) {
			echo ($type->allowsNull()?'?':'').$type->getName().' ';
		}
		else if ($type instanceof ReflectionUnionType) {
			$str='';
			foreach($type as $t) {
				$str.=($type->allowsNull()?'?':'').$type->getName().' | ';
			}
			$str=substr($str,0, -3);
			echo $str;
		}
	}
}
function print_argument(&$prm)
{
	if($prm->isOptional()) echo '[';
	//if($prm->allowsNull()) echo 'NULL '; // NOTE : it is now handled by print_type
	//if($prm->canBePassedByValue()) echo 'BYVALUE '; // NOTE : it is the default, opposite is isPassedByReference
	//$type = $prm->getClass(); // NOTE : it has now merged into getType
	/*if(!is_null($type)) echo $c->name.' ';
	else if(method_exists($prm, 'hasType') && $prm->hasType()) echo $prm->getType().' ';
	else if($prm->isArray()) echo 'array ';// else echo 'string '; //*/ // NOTE : it is now merged into getType
	$type=$prm->getType();
	print_type($type);
	if($prm->isPassedByReference()) echo '&';
	echo '$'.$prm->name;//.' ';
	if($prm->isOptional()) {
		if($prm->isDefaultValueAvailable()) {
			$value=$prm->getDefaultValue();
			if(is_array($value)) {
				echo '=array(';
				foreach($value as $key2=>$value2) {
					//echo $key2.' => '.$value2.', ';
					echo $key2.' => '.var_export($value2, true).', ';
				}
				echo ')';
			}
			else {
				//if(!is_null($value)) echo '='.$value;
				if(!is_null($value)) echo '='.var_export($value, true);
				else echo '=null';
			}
		}
		echo ']';
	}
}

function print_function(&$fct)
{
	if(method_exists($fct, 'hasReturnType') && $fct->hasReturnType()) echo $fct->getReturnType().' ';
	echo $fct->name.'(';
	//var_dump($fct->getNumberOfRequiredParameters(),$fct->getNumberOfParameters());
	$prms=$fct->getParameters();
	$prms_c=count($prms);
	for($i=0;$i<$prms_c;++$i) {
		$prm=&$prms[$i];

		print_argument($prm);

		if($i+1<$prms_c) echo ', ';
	}
	echo ')<br/>'."\r\n";
}

function print_class_method(&$mth)
{
	if($mth->isAbstract()) echo 'abstract ';
	//if($mth->isConstructor()) echo 'constr ';
	//if($mth->isDestructor()) echo 'destr ';
	if($mth->isFinal()) echo 'final ';
	if($mth->isPrivate()) echo 'private ';
	if($mth->isProtected()) echo 'protected ';
	if($mth->isPublic()) echo 'public ';
	if($mth->isStatic()) echo 'static ';
	//var_dump($mth, $mth->getReturnType());
	if(method_exists($mth, 'hasReturnType') && $mth->hasReturnType()) {
		//echo $mth->getReturnType().' ';
		$type=$mth->getReturnType();
		print_type($type);
	}
	echo $mth->name.'(';
	$prms=$mth->getParameters();
	$prms_c=count($prms);
	for($j=0;$j<$prms_c;++$j) {
		$prm=&$prms[$j];

		print_argument($prm);

		if($j+1<$prms_c) echo ', ';
	}
	echo ')<br/>'."\r\n";
}

function print_class_properties(&$prop)
{
	$prop->setAccessible(true);
	if($prop->isPrivate()) echo 'private ';
	if($prop->isProtected()) echo 'protected ';
	if($prop->isPublic()) echo 'public ';
	if($prop->isStatic()) echo 'static ';
	echo $prop->name;
	if($prop->isStatic()) echo ' = '.($prop->isDefault()?'<span style="text-color: green">':'<span style="text-color: red">').var_export($prop->getValue(), true).'</span>';
	echo '<br/>'."\r\n";
}
////////////////////////////
if(!isset($action)) $action='list_ext';
if(array_key_exists('action',$_GET)) $action=$_GET['action'];
$class='';
if(array_key_exists('class',$_GET)) $class=$_GET['class'];
$ext='';
if(array_key_exists('ext',$_GET)) $ext=$_GET['ext'];

function cmp_name($ar1, $ar2) {
	return strnatcasecmp($ar1->name, $ar2->name);
}

function cmp_getname($ar1, $ar2) {
	return strnatcasecmp($ar1->getName(), $ar2->getName());
}

$exts=get_loaded_extensions();

if($action=='')
{
	echo 'action must be defined : list_proj or list_ext by default.';
}
else if($action=='list_proj')
{
	if(!isset($classes) || !is_array($classes) || !isset($functions) || !is_array($functions))
	{
		echo 'you must list the $classes and the $functions.<br/>'."\r\n";
		die();
	}
	echo '<h1>Classes</h1>'."\r\n";
	echo "\r\n";
	
	natcasesort($classes);
	foreach($classes as $class) {
		echo '<a href="?action=desc_class&class='.$class.'">'.$class.'</a><br/>'."\r\n";
	}

	echo '<h1>Functions</h1>'."\r\n";
	foreach($functions as $function) {
		$fct=new ReflectionFunction($function);
		print_function($fct);
	}
}
else if($action=='list_ext') {
	echo '<h1>Extensions</h1>'."\r\n";
	echo "\r\n";

	natcasesort($exts);
	foreach($exts as $ext) {
		echo '<a href="?action=desc_ext&ext='.$ext.'">'.$ext.'</a><br/>'."\r\n";
	}
}
elseif($action=='desc_ext'&&$ext!='') {
	$e=new ReflectionExtension($ext);
	echo '<h1>'.$e->getName().'</h1>';
	if($e->getVersion()!=NULL) echo 'Version = '.var_export($e->getVersion(), true);
	echo "\r\n";
	echo "\r\n";

	//$cls=$e->getClassNames();
	$cls=$e->getClasses();
	natcasesort($cls);
	//usort($cls, 'cmp_getname');
	//uasort($cls, 'cmp_getname');
	echo '<h2>Classes</h2>'."\r\n";
	foreach($cls as $cl) {
		$cln=$cl->getName();
		if($cl->isInterface()) echo 'interface ';
		if($cl->isTrait()) echo 'trait ';
		if($cl->isAbstract()) echo 'abstract ';
		if(!$cl->isInstantiable()) echo 'non-instantiable ';
		echo '<a href="?action=desc_class&class='.$cln.'">'.$cln.'</a><br/>'."\r\n";
	}
	echo "\r\n";
	
	$dps=$e->getDependencies();
	echo '<h2>Dependancies</h2>'."\r\n";
	echo '<pre>'.var_export($dps,true).'</pre>'."\r\n";
	
	$fcts=$e->getFunctions();
	natcasesort($fcts);
	//usort($fcts, 'cmp_name');
	//uasort($fcts, 'cmp_name');
	echo '<h2>Functions</h2>'."\r\n";
	foreach($fcts as $fct) {
		print_function($fct);
	}
	echo "\r\n";
	
	$cts=$e->getConstants();//NOTE : Don't sort (constants are grouped)
	echo '<h2>Constants</h2>'."\r\n";
	foreach($cts as $ct=>$vl) {
		echo $ct.' = '.var_export($vl,true).'<br/>'."\r\n";
	}
	echo "\r\n";
	
	$inis=$e->getINIEntries();
	echo '<h2>INI entries</h2>'."\r\n";
	foreach($inis as $ini=>$vl) {
		echo $ini.' = '.var_export($vl,true).'<br/>'."\r\n";
	}
	echo "\r\n";
}
elseif($action=='desc_class'&&$class!='') {
	$cl=new ReflectionClass($class);
	echo '<h2>'.$cl->getName().'</h2>'."\r\n";
	
	$ext=$cl->getExtension();
	if($ext!==NULL) {
		echo 'Defined in extension <a href="?action=desc_ext&ext='.$ext->getName().'">'.$ext->getName().'</a><br/>'."\r\n";
	}
	
	$doc=$cl->getDocComment();
	if($doc!==FALSE) echo substr_replace(array("\r\n"),array('<br/>'."\r\n"),$doc).'<br/>'."\r\n";
	
	echo '<h2>Traits</h2>'."\r\n";
	$ts=$cl->getTraits();
	foreach($ts as $t) {
		//echo $t->getName().'<br/>'."\r\n";
		echo '<a href="?action=desc_class&class='.$t->getName().'">'.$t->getName().'</a><br/>'."\r\n";
	}
	
	echo '<h2>Trait Aliases</h2>'."\r\n";
	$tas=$cl->getTraitAliases();
	foreach($tas as $ta_new => $ta_old) {
		//echo $ta_new->getName().' from '.$ta_old->getName().'<br/>'."\r\n";
		echo $ta_new->getName().' from '.'<a href="?action=desc_class&class='.$ta_old->getName().'">'.$ta_old->getName().'</a><br/>'."\r\n";
	}
	
	echo '<h2>Interfaces</h2>'."\r\n";
	$is=$cl->getInterfaces();
	foreach($is as $i) {
		//echo $i->getName().'<br/>'."\r\n";
		echo '<a href="?action=desc_class&class='.$i->getName().'">'.$i->getName().'</a><br/>'."\r\n";
	}
	
	echo '<h2>Parent classes</h2>'."\r\n";
	//$pc=$cl->getParentClass();
	$pc=$cl;
	while(($pc=$pc->getParentClass())!=NULL) {
	//do {
		echo '<a href="?action=desc_class&class='.$pc->getName().'">'.$pc->getName().'</a>, '."\r\n";
	}
	//while(($pc=$pc->getParentClass())!=NULL);
	
	$cts=$cl->getConstants();//NOTE : Don't sort (constants are grouped)
	echo '<h2>Constants</h2>'."\r\n";
	foreach($cts as $ct=>$vl) {
		echo $ct.' = '.var_export($vl,true).'<br/>'."\r\n";
	}
	echo "\r\n";
	
	echo '<h3>Methods</h3>'."\r\n";
	//$ct=$cl->getConstructor();//NOTE : useless
	$mths=$cl->getMethods();
	natcasesort($mths);
	//usort($mths, 'cmp_name');
	//uasort($mths, 'cmp_name');
	$mths_c=count($mths);
	$inherit=false;
	for($i=0;$i<$mths_c;++$i) {
		$mth=&$mths[$i];
		if(!$inherit && $mth->getDeclaringClass()->getName()!=$cl->getName()) {
			echo '<h3>Inherited Methods</h3>'."\r\n";
			$inherit=true;
		}
		print_class_method($mth);
	}
	
	echo '<h3>Properties</h3>'."\r\n";
	$props=$cl->getProperties();
	$props_c=count($props);
	for($i=0;$i<$props_c;++$i) {
		$prop=&$props[$i];
		print_class_properties($prop);
	}
	
	echo '<h3>Static Properties</h3>'."\r\n";
	$sprops=$cl->getStaticProperties();
	foreach($sprops as $sprop=>$sprop_v) {
		//$sprop_i=new ReflectionProperty($cl->getName(), $sprop);
		//print_class_properties($sprop_i);
		var_dump($sprop);
		//print_r($sprop_v);
		var_export($sprop_v);
	}
	
	echo '<h3>Default Properties</h3>'."\r\n";
	$dps=$cl->getDefaultProperties();
	echo '<pre>';print_r($dps);echo '</pre>'."\r\n";
}
else {
	echo '[ERROR]';
}
?>
