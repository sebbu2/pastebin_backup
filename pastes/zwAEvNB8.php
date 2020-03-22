<?php
header('Content-Type: text/plain');
class HasFriends
{
	protected $__friends = array('HasFriends');

	public function __get($key)
	{
		$res=NULL;
		$trace = debug_backtrace();
		$frame=0;
		while($frame < count($trace) && $trace[$frame]['class']==__CLASS__) $frame++;
		if(isset($trace[$frame]['class']) && in_array($trace[$frame]['class'], $this->__friends)) {
			//return $this->$key;
			$rc=new ReflectionClass($this);
			$prop=$rc->getProperty($key);
			$prop->setAccessible(true);
			$res=$prop->getValue($this);
			return $res;
		}
		
		// normal __get() code here
		throw new Exception('Cannot access private property ' . get_class($this) . '::$' . $key . "\r\n", E_USER_ERROR);
	}

	public function __set($key, $value)
	{
		$res=NULL;
		$trace = debug_backtrace();
		$frame=0;
		while($frame < count($trace) && $trace[$frame]['class']==__CLASS__) $frame++;
		if(isset($trace[$frame]['class']) && in_array($trace[$frame]['class'], $this->__friends)) {
			//return $this->$key = $value;
			$rc=new ReflectionClass($this);
			$prop=$rc->getProperty($key);
			$prop->setAccessible(true);
			$res=$prop->setValue($this, $value);
			return $res;
		}
		
		// normal __set() code here
		throw new Exception('Cannot access private property ' . get_class($this) . '::$' . $key . "\r\n", E_USER_ERROR);
	}
	
	public function __call($name, $arguments)
	{
		$res=NULL;
		$trace = debug_backtrace();
		$frame=0;
		while($frame < count($trace) && $trace[$frame]['class']==__CLASS__) $frame++;
		if(isset($trace[$frame]['class']) && in_array($trace[$frame]['class'], $this->__friends)) {
			//return call_user_func_array(array($this, $name), $this->arguments);
			$rc=new ReflectionClass($this);
			$meth=$rc->getMethod($name);
			$meth->setAccessible(true);
			$res=$meth->invokeArgs($this, $arguments);
			return $res;
		}
		// normal __call() code here
		throw new Exception('Cannot call private method ' . get_class($this) . '::' . $name . '()' . "\r\n", E_USER_ERROR);
	}
	
	public static function __callStatic($name, $arguments)
	{
		$trace = debug_backtrace();
		$frame=0;
		while($frame < count($trace) && $trace[$frame]['class']==__CLASS__) $frame++;
		if(isset($trace[$frame]['class']) && in_array($trace[$frame]['class'], $this->__friends)) {
			//return call_user_func_array(array(get_class($this), $name), $this->arguments);
			$rc=new ReflectionClass(get_class($this));
			$meth=$rc->getMethod($name);
			$meth->setAccessible(true);
			$res=$meth->invokeArgs(NULL, $arguments);
			return $res;
		}
		// normal __callStatic() code here
		throw new Exception('Cannot call static private method ' . get_class($this) . '::' . $name . '()' . "\r\n", E_USER_ERROR);
	}
};
class A extends HasFriends
{
	
	public $a = 1;
	protected $b = 2;
	private $c = 3;
	protected $__friends = array('B');
	private function d() { return 4; }
};
class B
{
	public $d = 4;
	protected $e = 5;
	private $f = 6;
	public $a = NULL;
	public function getC()
	{
		return $this->a->c;
	}
	public function g() { return $this->a->d(); }
};
$a = new A();
$b = new B();
$b->a = &$a;
echo 'var_dump($a)='."\r\n";
var_dump($a);
echo 'var_dump($b)='."\r\n";
var_dump($b);
try
{
	echo 'Trying to access A::c'."\r\n";
	var_dump($a->c);
}
catch(Exception $e)
{
	echo $e->getMessage();
}
try
{
	echo 'Trying to access B::a::c'."\r\n";
	var_dump($b->a->c);
}
catch(Exception $e)
{
	echo $e->getMessage();
}
echo 'Accessing B::getC(), which returns B::a::c'."\r\n";
var_dump($b->getC());
try
{
	echo 'Trying to access A::d()'."\r\n";
	var_dump($a->d());
}
catch(Exception $e)
{
	echo $e->getMessage();
}
echo 'Accessing B::g(), which returns B::a::d()'."\r\n";
var_dump($b->g());