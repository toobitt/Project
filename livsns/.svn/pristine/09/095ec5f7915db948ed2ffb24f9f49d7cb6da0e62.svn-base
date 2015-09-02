<?php
require('./global.php');
define('MOD_UNIQUEID','preferences');
define('SCRIPT_NAME', 'preferences');
class preferences extends adminBase
{
	private $fields = array('id,admin_id','message','status', 'title','admin_settings','flag','title', 'create_time', 'update_time', 'ip');
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
		if(!$this->input['fields'])
		{
			$fields = $this->fields;
		}
		else
		{
			$fields = array_intersect($this->fields, explode(',', $this->input['fields']));
			if(!$fields)
			{
				$this->errorOutput(FIELDS_EMPTY);
			}
		}
		$limit = '';
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 0;
		if($offset || $count)
		{
			$limit = " limit $offset,$count";
		}
		$sql = 'SELECT ' . implode(',',$fields) . ' FROM '.DB_PREFIX.'admin_settings WHERE 1 ' . $this->get_conditions() . ' ORDER BY id ASC, id ASC' .$limit;
		
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['admin_settings'] = json_decode($row['admin_settings']);
			$this->addItem($row);
		}
		$this->output();
	}
	public function get_conditions()
	{
		$conditions = '';
		
		if($this->input['title'])
		{
			$conditions .= ' AND title like "%'.$this->input['title'].'%" ';
		}
		if($this->input['flag'])
		{
			$conditions .= ' AND flag = "'.$this->input['flag'].'" ';
		}
		if(isset($this->input['status']))
		{
			$conditions .= ' AND status = "'.$this->input['status'].'" ';
		}
		if($this->input['id'])
		{
			$conditions .= ' AND id IN('.urldecode($this->input['id']).') ';
		}
		if($this->input['admin_id'])
		{
			$conditions .= ' AND admin_id = "'.intval($this->input['admin_id']).'" ';
		}
		return $conditions;
	}
	public function detail()
	{
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX  .  'admin_settings where 1 ' . $this->get_conditions();
		exit(json_encode($this->db->query_first($sql)));
	}
	public function get_specify_settings()
	{
		$field = $this->input['search_field'];
		$value = $this->input[$field];
		if(!$field)
		{
			return;
		}
		$sql = 'SELECT sid FROM ' . DB_PREFIX . 'admin_settings_search WHERE `key`="' . $field . '" AND value="'.addslashes($value).'"';
		$info = $this->db->query_first($sql);
		if($info)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'admin_settings WHERE id=' .$info['sid'];
			$settings = $this->db->query_first($sql); 			
			if($settings)
			{
				$settings['admin_settings'] = json_decode($settings['admin_settings'],1);
				$this->addItem($settings);
				$this->output();
			}
			
		}
	}
}
include(ROOT_PATH . 'excute.php');