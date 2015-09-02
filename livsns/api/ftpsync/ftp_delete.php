<?php
define('ROOT_DIR', './../../');
define('MOD_UNIQUEID','ftp_delete');//模块标识
define('SCRIPT_NAME', 'ftp_delete');
require(ROOT_DIR . 'global.php');
class ftp_delete extends adminBase
{
	protected $field;
	function __construct()
	{
		parent::__construct();
		$this->filed = array(
		'id'=>false,
		'app'=>true,
		'filepath'=>true,
		'pathtype'=>false,
		'create_time'=>TIMENOW,
		);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$store_data = array();
		$data = $this->input;
		foreach($this->filed as $field=>$check)
		{
			if(is_bool($check))
			{
				if($check && !$data[$field])
				{
					$this->errorOutput('缺少参数'.$field);
				}
				else
				{
					$store_data[$field] = $data[$field];
				}
			}
			else
			{
				$store_data[$field] = $check;
			}
		}
		$store_data['filepath'] = urldecode($store_data['filepath']);
		$store_data['pathtype'] = $store_data['pathtype'] ? 1 : 0;
		if(!empty($store_data))
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'ftpfile_del('.implode(',', array_keys($store_data)).')';
			$sql .= ' VALUES("'.implode('","', $store_data).'")';
			$this->db->query($sql);
			$id = $this->db->insert_id();
			$store_data['id'] = $id;
		}	
		$this->addItem($store_data);
	}
}
include ROOT_PATH . 'excute.php';
?>