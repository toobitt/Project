<?php
class publish_common extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function get_template_style($siteId = '')
	{
		$condition = '';
		if($siteId)
		{
			$condition = " AND site_id = " . $siteId . " OR site_id = 0 ";
		}
		$sql = "SELECT id,title FROM ".DB_PREFIX."template_style WHERE 1 AND state=1 " . $condition;
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
		return $ret;		
	}
}
?>