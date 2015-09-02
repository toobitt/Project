<?php
class ClassDataPlant extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	//获取配置
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'data_set WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$configs = array();
		$relyids = array(); 
		while ($row = $this->db->fetch_array($query))
		{
			$row['dbinfo'] = @unserialize($row['dbinfo']) ? unserialize($row['dbinfo']) : '';
			$row['paras'] = @unserialize($row['paras']) ? unserialize($row['paras']) : '';
			$row['apiurl'] = @unserialize($row['apiurl']) ? unserialize($row['apiurl']) : '';
			$row['beforeimport'] = @unserialize($row['beforeimport']) ? unserialize($row['beforeimport']) : '';
			$row['format_runtime'] = $row['runtime'] ? date('Y-m-d H:i:s', $row['runtime']) : '';
			if ($row['relyonids'])
			{
				$relyids[$row['id']] = $row['relyonids'];
			}
			$configs[$row['id']] = $row;
		}
		return $configs;
	}
	
	
}