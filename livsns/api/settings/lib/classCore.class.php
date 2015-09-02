<?php
class classCore extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public static function autoLoader($className)
	{
		if($className == 'sqlCore')
		{
			$classFile = CUR_CONF_PATH . 'core/'.$className.'.core.php';			
		}
		else $classFile = CUR_CONF_PATH . 'lib/'.$className.'.class.php';
		if(is_file($classFile))
		{
			class_exists($classname) OR include ($classFile);
		}
		return true;
	}	
}
spl_autoload_register('classCore::autoLoader');
?>