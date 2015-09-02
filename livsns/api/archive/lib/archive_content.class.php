<?php
require_once(CUR_CONF_PATH.'lib/archive_common.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class archiveContent extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->common = new archiveCommon();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count,$table_name, $archive_id)
	{
		if (!$table_name || !$archive_id)
		{
			return false;
		}
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT tb.*,s.name FROM '.DB_PREFIX.$table_name.' tb 
				LEFT JOIN '.DB_PREFIX.'archive_sort s ON tb.sort_id = s.id
				WHERE 1 AND tb.archive_id = '.$archive_id.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d H:i', $row['create_time']);
			$k[] = $row;
		}
		return $k;	
	}
	
	public function count($condition, $table_name, $archive_id)
	{
		if (!$table_name || !$archive_id)
		{
			return false;
		}
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.$table_name.' tb WHERE 1 AND tb.archive_id = '.$archive_id.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	
	public function detail($id)
	{
		
	}
	
	public function delete($ids,$archive_id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'archive WHERE id = '.$archive_id;
		$archive = $this->db->query_first($sql);
		$table = $archive['table_name'];
		if ($this->common->check_table_is_exist($table))
		{
			$sql = 'DELETE FROM '.DB_PREFIX.$table.' WHERE id IN ('.$ids.')';
			$this->db->query($sql);
			$this->common->check_table_is_empty($table);
		}
		return $ids;
	}
	
	public function get_tableName_by_id($archiveId)
	{
		if (!$archiveId)
		{
			return false;
		}
		$sql = 'SELECT table_name FROM '.DB_PREFIX.'archive WHERE id = '.$archiveId;
		$ret = $this->db->query_first($sql);
		$table_name = $ret['table_name'];		
		$check = $this->common->check_table_is_exist($table_name);
		if (!$check)
		{
			return false;
		}
		return $table_name;
	}
	
	public function recover_content($ids, $archive_id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'archive WHERE id = ' . $archive_id;
		$archiveInfor = $this->db->query_first($sql);
		if (empty($archiveInfor))
		{
			return false;
		}
				
		$sql = 'SELECT * FROM '.DB_PREFIX.'archive_sort WHERE id = '.$archiveInfor['sort_id'];
		$sortInfor = $this->db->query_first($sql);
		if (empty($sortInfor))
		{
			return false;
		}
		
		$app = $this->common->get_app_infor($sortInfor['app_mark'], $sortInfor['module_mark']);		
		if (empty($app))
		{
			return false;
		}
		$appInfor = array(
			'app_mark'=>$sortInfor['app_mark'],
			'module_mark'=>$sortInfor['module_mark'],
			'filename'=>$app['file_name'],
		);
		$ret = $this->common->recover_content($ids, $appInfor, $archiveInfor['table_name']);
		if (!$ret)
		{
			return false;
		}
		$res = $this->delete($ids, $archive_id);
		if (!$res)
		{
			return false;
		}
		return $ids;
	}
}