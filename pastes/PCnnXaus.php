<?php
//header('Content-Type: text/plain');
//var_dump(ReflectionProperty::IS_PUBLIC, ReflectionProperty::IS_PROTECTED, ReflectionProperty::IS_PRIVATE, ReflectionProperty::IS_STATIC);
class Debug {
	public function __debugInfo()
	{
		$rc = new ReflectionObject($this);
		$class = $rc->getName();
		$props = $rc->getProperties();
		$props = array_filter($props, function($elem) use($class) { return ($elem->getDeclaringClass()->getName()==$class); });
		$rc2=$rc;
		while($rc2=$rc2->getParentClass())
		{
			$__class = $rc2->getName();
			$props2 = $rc2->getProperties();
			$props2 = array_filter($props2, function($elem) use($__class) { return ($elem->getDeclaringClass()->getName()==$__class); });
			$props = array_merge($props, $props2);
		}
		$ar = array();
		$props2=array();
		foreach($props as $prop)
		{
			if($prop->isStatic()) continue;//ignore
			if(!$prop->isDefault()) { $props2[]=$prop; continue; }//next loop
			$prefix = ($prop->isProtected()? "\0*\0" :($prop->isPrivate()?"\0". $prop->getDeclaringClass()->getName() ."\0":''));
			if(!$prop->isPrivate() && (array_key_exists($prop->getName(),$ar) || array_key_exists("\0*\0".$prop->getName(),$ar))) continue; // ignore if key already exists, except private
			$prop->setAccessible(true); $ar[ $prefix.$prop->getName() ] = $prop->getValue($this);
		}
		foreach($props2 as $prop)
		{
			$prefix=($prop->isProtected()?"\0*\0":($prop->isPrivate()?"\0".$class."\0":''));
			$prop->setAccessible(true); $ar[ $prefix.$prop->getName() ] = $prop->getValue($this);
		}
		return $ar;
	}
};

// echo 'normal class'."\r\n";
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
// var_dump($test1);
// echo 'normal class with complete debugInfo'."\r\n";
class MyTestClass3 extends Debug
{
	public $a = 1;
	protected $b = 2;
	private $c = 3;
	public static $z = 1;
	protected static $y = 2;
	private static $x = 3;
}
$test3=new MyTestClass3();
// var_dump($test3);
// echo 'first herited class'."\r\n";
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
// var_dump($test4);
// echo 'first herited class with complete debugInfo'."\r\n";
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
// var_dump($test5);
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
var_dump($test7);//*/
echo 'class using second inherited class'."\r\n";
class MyTestClassBis_1
{
	public $a = NULL;
	protected $b = NULL;
	private $c = NULL;
	public function __construct()
	{
		global $test7,$test6;
		$this->a = $test7;
		$this->b = $test6;
		$this->c = $test7;
		$this->{1} = "un";
		self::$x = $test7;
		self::$y = $test7;
		self::$z = $test7;
	}
	static public $z = NULL;
	static protected $y = NULL;
	static private $x = NULL;
};
$test8 = new MyTestClassBis_1();
var_dump($test8);
echo 'class using second inherited class, with complete debugInfo'."\r\n";
class MyTestClassBis_1b extends Debug
{
	public $a = NULL;
	protected $b = NULL;
	private $c = NULL;
	public function __construct()
	{
		global $test7,$test6;
		$this->a = $test7;
		$this->b = $test6;
		$this->c = $test7;
		$this->{1} = "un";
		self::$x = $test7;
		self::$y = $test7;
		self::$z = $test7;
	}
	static public $z = NULL;
	static protected $y = NULL;
	static private $x = NULL;
};
$test8b = new MyTestClassBis_1b();
var_dump($test8b);
echo 'second class using second inherited class'."\r\n";
class MyTestClassBis_2 extends MyTestClassBis_1
{
	
};
$test9 = new MyTestClassBis_2();
var_dump($test9);
echo 'second class using second inherited class, with complete debugInfo'."\r\n";
class MyTestClassBis_2b extends MyTestClassBis_1b
{
	
};
$test9b = new MyTestClassBis_2b();
var_dump($test9b);
