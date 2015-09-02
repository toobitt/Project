<?php
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/appstore_frm.php');
class used extends appstore_frm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$app = $this->input['app'];
		if ($app)
		{
			$cond = ' WHERE app_uniqueid=\'' . $app . "'";
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_apps' . $cond;		
		$q = $this->db->query($sql);
		$versions = array();
		while($r = $this->db->fetch_array($q))
		{
			$versions[$r['app_uniqueid']][$r['version']] = $r['version'];
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'apps' . $cond;		
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$versions[$r['app_uniqueid']][$r['version']] = $r['version'];
			$versions[$r['app_uniqueid']][$r['pre_version']] = $r['pre_version'];
		}
		echo json_encode($versions);
	}
	protected function verifyToken()
	{
	}
}
$module = 'used';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>