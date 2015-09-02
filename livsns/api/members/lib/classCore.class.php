<?php
class classCore extends InitFrm
{
	private static $_autoLoder = array(
	'core' => array('membersql','members'),//core文件自动引用
	'rootclass' => array('auth','curl','banword','material','feedback'),//根lib类自动引用
	'mapping'  => array('group' => 'member_group'),//类别名自动映射引用
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
		if(in_array($className, self::$_autoLoder['core'])){
			$classFile = CUR_CONF_PATH . 'core/'.$className.'.core.php';			
		}
		elseif(in_array($className, self::$_autoLoder['rootclass'])){
			 $classFile = ROOT_PATH . 'lib/class/'.$className.'.class.php';
		} 
		else {
			if(isset(self::$_autoLoder['mapping'][$className]))
			{
				$className = self::$_autoLoder['mapping'][$className];//类名和文件名映射
			}
			$classFile = CUR_CONF_PATH . 'lib/'.$className.'.class.php';
		}
		if(is_file($classFile))
		{
			class_exists($classname,false) OR include $classFile;
		}
		return true;
	}	
	
}
spl_autoload_register('classCore::autoLoader');
?>