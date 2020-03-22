<?php
namespace perso {
	class A
	{
		private $a;
		private static $b;
		public function __construct()
		{
			//$this->a=new \A();
			$class='\A';
			$args=func_get_args();
			if(version_compare(phpversion(), '5.6.0', '>=')){
				$instance = new $class(...$args);
			}
			else {
				$reflect  = new ReflectionClass($class);
				$instance = $reflect->newInstanceArgs($args);
			}
			$this->a=$instance;
			self::$b=get_class($this->a);
		}
		public function __get(string $name)
		{
			$res=null;
			if($name=='v')
			{
				var_dump('v pre');
			}
			if(property_exists($this->a, $name))
			{
				$res=$this->a->$name;
			}
			else $res=null;
			if($name=='v')
			{
				var_dump('v post');
			}
			return $res;
		}
		public function __set(string $name, $value)
		{
			if(property_exists($this->a, $name))
			{
				$this->a->$name=$value;
			}
			return $this;
		}
		public function __call(string $name, array $arguments)
		{
			$res=null;
			if($name=='m') var_dump('m pre');
			if(method_exists($this->a, $name))
			{
				$res=call_user_func_array(array($this->a, $name), $arguments);
			}
			if($name=='m') var_dump('m post');
			return $res;
		}
		public static function __callStatic(string $name, array $arguments)
		{
			$res=null;
			if($name=='q') var_dump('q pre');
			if(method_exists(self::$b, $name))
			{
				$res=call_user_func_array(array(self::$b, $name), $arguments);
			}
			if($name=='q') var_dump('q post');
			return $res;
		}
		//cheating by exception handling
		public static function __getStatic(string $name)
		{
			$res=null;
			$class=(new \ReflectionClass(__CLASS__))->getShortName();
			if(property_exists($class, $name))
			{
				$res=$class::$$name;
			}
			return $res;
		}
		public static function __setStatic(string $name, $value)
		{
			$class=(new \ReflectionClass(__CLASS__))->getShortName();
			if(property_exists($class, $name))
			{
				$class::$$name=$value;
			}
			return $class;
		}
	};
}
namespace {
	function is_empty($str)
	{
		return !empty($str);
	}
	function fix_error(Throwable $ex)
	{
		//var_dump($ex);
		$str=$ex->getMessage();
		if(strpos($str, 'Access to undeclared static property: ')!==0) return false;
		$str=explode(':', $str);
		array_shift($str);
		$str=array_map('trim', $str);
		$str=array_filter($str, 'is_empty');
		if(strpos($str[0],'perso\\')===0) $class=substr($str[0], 6);
		else $class=$str[0];
		$prop=substr($str[2], 1);
		//var_dump($class::$$prop);
		//var_dump(call_user_func(array($str[0], '__getStatic'), $prop));
		var_dump(call_user_func(array($str[0], '__getStatic'), $prop));
		//var_dump('hello world');
		return true;
	}
}
?>