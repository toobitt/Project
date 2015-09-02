<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 325 2011-11-17 02:21:45Z develop_tong $
***************************************************************************/
class Config extends InitFrm
{	
	function __construct()
	{
		parent::__construct();
		$this->db = hg_checkDB();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function fetch($var ='', $group_id = 0)
	{
		$varname = $var;

		$group_id = intval($group_id);
		if ($group_id)
		{
			$cond = ' WHERE group_id=' . $group_id;
		}
		$sql = 'SELECT id, father_id, varname, varvalue, isconst FROM ' . DB_PREFIX . 'settings' . $cond;
		$q = $this->db->query($sql);
		$settings_related = array();
		$specify_id = 0;
		while ($row = $this->db->fetch_array($q))
		{	
			if (!$row['father_id'] && $row['varname'] == $varname)
			{
				$specify_id = $row['id'];
			}
			$settings_related[$row['father_id']][$row['id']] = $row;
			$settings[$row['id']] = $row;
		}
		if ($settings_related[$specify_id])
		{
			$configs = $this->get_child_setting($specify_id, $settings_related);
		}
		else
		{
			$configs[$settings[$specify_id]['varname']] = $settings[$specify_id]['varvalue'];
		}
		return $configs;
	}

	private function get_child_setting($specify_id, $settings_related)
	{
		$configs = array();
		foreach ($settings_related[$specify_id] AS $k => $v)
		{
			if ($settings_related[$k])
			{
				$configs[$v['varname']] = $this->get_child_setting($k, $settings_related);
			}
			else
			{
				$configs[$v['varname']] = $v['varvalue'];
			}
		}
		return $configs;
	}
	
}
?>