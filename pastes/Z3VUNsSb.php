<?php
var_dump(ReflectionProperty::IS_PUBLIC, ReflectionProperty::IS_PROTECTED, ReflectionProperty::IS_PRIVATE, ReflectionProperty::IS_STATIC);
echo 'normal class'."\r\n";
class MyTestClass1
{
	public $a = 1;
	protected $b = 2;
	private $c = 3;
	public static $z = 1;
	protected static $y = 2;
	private static $x = 3;
}
$test1=new MyTestClass1();
var_dump($test1);
echo 'normal class with naive __debugInfo'."\r\n";
class MyTestClass2
{
	public $a = 1;
	protected $b = 2;
	private $c = 3;
	public static $z = 1;
	protected static $y = 2;
	private static $x = 3;
	public function __debugInfo()
	{
		$rc = new ReflectionClass($this);
		$props = $rc->getProperties(); // BUG ? static members re always returned if public/protected/private
		$ar = array();
		foreach($props as $prop)
		{
			// $ar[ $prop->getName() ] = $this->{$prop->getName()}; // NOTE : works for now...
			$prop->setAccessible(true); $ar[ $prop->getName() ] = $prop->getValue($this);
		}
		return $ar;
	}
}
$test2=new MyTestClass2();
var_dump($test2);
var_dump(get_object_vars($test2));
echo 'normal class with complete __debugInfo'."\r\n";
class MyTestClass3
{
	public $a = 1;
	protected $b = 2;
	private $c = 3;
	public static $z = 1;
	protected static $y = 2;
	private static $x = 3;
	public function __debugInfo()
	{
		$rc = new ReflectionClass($this);
		$props = $rc->getProperties();
		$class = $rc->getName();
		$ar = array();
		foreach($props as $prop)
		{
			if($prop->isStatic()) continue; // BUG ?
			if($prop->getDeclaringClass()->getName()!==get_class($this)) continue; // fix because of workaround further down
			$pri=$prop->isPrivate();
			$pro=$prop->isProtected();
			// NOTE : protected is \0*\0, private is \0class\0
			//$prefix=($pro?"\0*\0":($pri?"\0".get_class($this)."\0":'')); // NOTE : did work, but...
			$prefix=($pro?"\0*\0":($pri?"\0".$prop->getDeclaringClass()->getName()."\0":''));
			//$ar[ $prefix.$prop->getName() ] = $this->{$prop->getName()}; // NOTE : still work here, but... (see down)
			$prop->setAccessible(true); $ar[ $prefix.$prop->getName() ] = $prop->getValue($this);
		}
		// BUG ? : private members of parents classes are missing
		$rc2=$rc;
		while($rc2=$rc2->getParentClass())
		{
			$props2 = $rc2->getProperties();
			$class2 = $rc2->getName();
			foreach($props2 as $prop2)
			{
				if($prop2->isStatic()) continue; // BUG ?
				if($prop2->getDeclaringClass()->getName() !== $class2) continue;
				$pri=$prop2->isPrivate();
				$pro=$prop2->isProtected();
				$prefix=($pri?"\0$class2\0":($pro?"\0*\0":''));
				$prop2->setAccessible(true); $ar[ $prefix. $prop2->getName() ] = $prop2->getValue($this); // NOTE : no other way, direct access through $this doesn't work anymore
			}
		}
		return $ar;
	}
}
$test3=new MyTestClass3();
var_dump($test3);
echo 'first herited class'."\r\n";
class MyTestClass4 extends MyTestClass1
{
	public $d = 4;
	protected $e = 5;
	private $f = 6;
	public static $w = 4;
	protected static $v = 5;
	private static $u = 6;
}
$test4 = new MyTestClass4();
var_dump($test4);
echo 'first herited class with complete debugInfo'."\r\n";
class MyTestClass5 extends MyTestClass3
{
	public $d = 4;
	protected $e = 5;
	private $f = 6;
	public static $w = 4;
	protected static $v = 5;
	private static $u = 6;
}
$test5 = new MyTestClass5();
var_dump($test5);
echo 'second herited class'."\r\n";
class MyTestClass6 extends MyTestClass4
{
	public $g = 7;
	protected $h = 8;
	private $i = 9;
	public static $t = 7;
	protected static $s = 8;
	private static $r = 9;
}
$test6 = new MyTestClass6();
var_dump($test6);
echo 'second herited class with complete debugInfo'."\r\n";
class MyTestClass7 extends MyTestClass5
{
	public $g = 7;
	protected $h = 8;
	private $i = 9;
	public static $t = 7;
	protected static $s = 8;
	private static $r = 9;
}
$test7 = new MyTestClass7();
var_dump($test7);
