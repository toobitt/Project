<?php
define('MOD_UNIQUEID','auth');
require_once('global.php');
define('SCRIPT_NAME', 'saveMenu');

class saveMenu extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function update_app()
	{
		$app_unique = isset($this->input['apps']) ? trim(urldecode($this->input['apps'])) : '';
		$sql = 'UPDATE ' . DB_PREFIX . 'admin SET `app_unique` = "' . $app_unique . '" 
		WHERE id = ' . $this->user['user_id'];
		$ret = $this->db->query($sql);
		$this->addItem($ret);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
?>