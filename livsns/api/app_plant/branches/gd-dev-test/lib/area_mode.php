<?php
class area_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "province";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	public function getCity($province_code = '')
	{
		if(!$province_code)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "city WHERE provincecode = '" .$province_code. "' ";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	
	public function getDistrict($city_code = '')
	{
		if(!$city_code)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "area WHERE citycode = '" .$city_code. "' ";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
}
?>