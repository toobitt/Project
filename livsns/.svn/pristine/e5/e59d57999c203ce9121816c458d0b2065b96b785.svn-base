<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'DatabaseRecord');
class DatabaseRecord extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		global $gDBconfig;
		$dbconfig = $gDBconfig;
		$database_use = $dbconfig['database'];
		
		$content = @file_get_contents('conf/config.php');
		if (!$content)
		{
			$this->errorOutput('CONFIG_FILE_GONE');
		}
		if (!is_writeable('conf/config.php'))
		{
			$this->errorOutput('CONFIG_FILE_NOT_ALLOW_WRITE');
		}
		
		
		$db = array();
		$db['host'] = $dbconfig['host'];
		$db['user'] = $dbconfig['user'];
		$db['pass'] = $dbconfig['pass'];
		$db['dbprefix'] = DB_PREFIX;
		$db['database'] = $this->settings['database_no_use'] ? $this->settings['database_no_use'] : 'dev_gongjiao';
		$db['pconncet'] = $dbconfig['pconnect'];
		
		$string = "\$gDBconfig = array(
	'host'     => '{$db['host']}',
	'user'     => '{$db['user']}',
	'pass'     => '{$db['pass']}',
	'database' => '{$db['database']}',
	'charset'  => 'utf8',
	'pconnect' => '{$db['pconncet']}',
);";
		
		if ($dbconfig)
		{
			$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
		}
		
		$basesetting = array(
			'database_no_use' => $database_use,
		);
		if ($basesetting)
		{
			foreach($basesetting AS $k => $v)
			{
				if (is_array($v))
				{
					$vs = var_export($v, 1);
				}
				else
				{
					$vs = "'$v'";
				}
				if (isset($this->settings[$k]))
				{
					$content = preg_replace("/\\\$gGlobalConfig\['{$k}'\]\s*=\s*(.*?;)/is", "\$gGlobalConfig['{$k}'] =  " . $vs . ';', $content);
				}
				else
				{
					$content = preg_replace("/\?>\n*\s*$/is", "\n\$gGlobalConfig['{$k}'] =  " . $vs . ";\n?>", $content);
				}
			}
		}
		
		$write = @file_put_contents('conf/config.php', $content);
		$this->addItem_withkey('database', $database_use);
		
		$this->output();
	}
	
	
	public function update_ok()
	{
		
	}
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
	public function detail()
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>