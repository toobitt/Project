<?php
require ('./global.php');
define('MOD_UNIQUEID','station_img');
define('SCRIPT_NAME', 'getStationImg');
class getStationImg extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function detail(){}
	public function count(){}	
	
	public function show()
	{
		if(!$this->input['station_id'])
		{
			return FALSE;
		}
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT id,host,dir,filepath,filename FROM " . DB_PREFIX . "material WHERE 1 " . $cond . $limit; 
		
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		//站点id
		if($this->input['station_id'])
		{
			$condition .=" AND cid = " . intval($this->input['station_id']);
		}
		
		$condition .= " AND isdel=1";
		return $condition ;
	}
}
include(ROOT_PATH . 'excute.php');
?>