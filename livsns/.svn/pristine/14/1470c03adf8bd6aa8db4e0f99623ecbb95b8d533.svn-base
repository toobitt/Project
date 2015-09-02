<?php
require('global.php');
define('MOD_UNIQUEID','access');
define(SCRIPT_NAME,'EditDel');
class EditDel extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$cid = urldecode($this->input['cid']);		
		if(!$cid)
		{
			$this->errorOutput('no cid');
		}
		$sql = "UPDATE ".DB_PREFIX."nums SET del = 1 WHERE cid IN(".$cid.")";
		$this->db->query($sql);		
		include_once(CUR_CONF_PATH . 'lib/cache.class.php');
		$cache = new CacheFile();
		$table = $cache->get_cache('access_table_name');	
		$table = convert_table_name($table);	
		if($table)
		{
			$table_str = implode(',', $table);
		}	
		$sql = "ALTER TABLE ".DB_PREFIX."merge UNION(".$table_str.")";
		$this->db->query($sql);		
		$sql = "UPDATE ".DB_PREFIX."merge SET del = 1 WHERE cid IN(".$cid.")";
		$this->db->query($sql);
		exit('sucess');
	}
	
	
	public function create(){}
	public function update(){}
	public function delete(){}		
	
}
require(ROOT_PATH . 'excute.php');
?>
