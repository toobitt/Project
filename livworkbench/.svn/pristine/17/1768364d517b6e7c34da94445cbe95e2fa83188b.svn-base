<?php
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'region');
define('WITH_DB', 1);
require('./global.php');
class region extends uiBaseFrm
{	
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function province()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'province';
		$query = $this->db->query($sql);
		$provinces = array();
		while ($row = $this->db->fetch_array($query))
		{
			$provinces[] = $row;
		}
		echo json_encode($provinces);
	}
	
	public function city()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->ReportError('NOID');		
		}
		$sql = 'SELECT id,city FROM ' .DB_PREFIX.'city WHERE province_id = '.$id;
		$query = $this->db->query($sql);
		$cities = array();
		while ($row = $this->db->fetch_array($query))
		{
			$cities[] = $row;
		}
		echo json_encode($cities);
	}
	
	public function area()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->ReportError('NOID');		
		}
		$sql = 'SELECT id,area FROM ' .DB_PREFIX.'area WHERE city_id = '.$id;
		$query = $this->db->query($sql);
		$areas = array();
		while ($row = $this->db->fetch_array($query))
		{
			$areas[] = $row;
		}
		echo json_encode($areas);
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>