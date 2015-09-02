<?php

class cache extends InitFrm
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	function check_cache($cache_name = '',$force = '',$cache_dir = CACHE_DIR)
	{
		$cache_file = $cache_dir . $cache_name; 
		if(!file_exists($cache_file) || $force)
		{
			$material_type = $this->recache($cache_name,$cache_dir);
		}
		else
		{
			$material_type = file_get_contents($cache_file);
			$material_type = unserialize($material_type);
		}
		return $material_type;
	}


	function recache($cache_name,$cache_dir = CACHE_DIR)
	{
		if(empty($cache_name))
		{
			return false;
		}
		$material_type = $this->get_material_type();
		hg_mkdir($cache_dir);
		$cache_file = $cache_dir . $cache_name;
		hg_file_write($cache_file,serialize($material_type));
		return $material_type;
	}	
	
	private function get_material_type()
	{
		$sql = "SELECT mark,expand,code FROM ".DB_PREFIX."affix_setting WHERE mark != ''";
		$ret = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($ret))
		{
			$info[$row['mark']][$row['expand']]= array('code' => $row['code']);
		}
		return $info;
	}	
}
?>