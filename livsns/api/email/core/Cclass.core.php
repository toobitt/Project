<?php
class Cclass extends InitFrm
{
	private static $_autoLoder = array(
	'core' => array('Csql','Cemail'),
	'rootclass' => array('auth','curl','banword','material'),
	);
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
		if($className!='Cclass'&&in_array($className, self::$_autoLoder['core'])){
			$classFile = CUR_CONF_PATH . 'core/'.$className.'.core.php';			
		}
		elseif(in_array($className, self::$_autoLoder['rootclass'])){
			 $classFile = ROOT_PATH . 'lib/class/'.$className.'.class.php';
		} 
		else {
			$classFile = CUR_CONF_PATH . 'lib/'.$className.'.class.php';
		}
		if(is_file($classFile))
		{
			class_exists($classname) OR include ($classFile);
		}
		return true;
	}	
	
}
spl_autoload_register('Cclass::autoLoader');
?>