<?php
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','auth');
require(ROOT_DIR . 'global.php');
define('SCRIPT_NAME', 'applications');
class applications extends outerReadBase
{
	private $fields = array('id', 'name','bundle','version','host', 'port', 'dir', 'admin_dir');
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
		$sql = 'SELECT ' . implode(',',$fields) . ' FROM '.DB_PREFIX.'apps WHERE 1 '.$this->get_conditions() . ' ORDER BY order_id ASC, id ASC' .$limit;
		
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['host'] = $this->settings['hostprefix'] . $row['host'];
			$this->addItem($row);
		}
		$this->output();
	}
	public function get_conditions()
	{
		$conditions = ' AND status=1';
		if($this->input['id'])
		{
			$ids = explode(',', $this->input['id']);
			foreach ($ids as $id)
			{
				if(!intval($id))
				{
					$this->errorOutput(PARAMETER_ERROR);
				}
			}
			$conditions .= ' AND id IN('.$this->input['id'].')';
		}
		if($this->input['pub_source'])
		{
			$conditions .= ' AND pub_source = 1 ';
		}
		if($this->input['use_message'])
		{
			$conditions .= ' AND use_message = 1 ';
		}
		if($this->input['use_material'])
		{
			$conditions .= ' AND use_material = 1 ';
		}
		if($this->input['use_textsearch'])
		{
			$conditions .= ' AND use_textsearch = 1 ';
		}
		if($this->input['use_logs'])
		{
			$conditions .= ' AND use_logs = 1 ';
		}
		if($this->input['use_recycle'])
		{
			$conditions .= ' AND use_recycle = 1 ';
		}
		if($this->input['use_access'])
		{
			$conditions .= ' AND use_access = 1 ';
		}
		if($this->input['use_catalog'])
		{
			$conditions .= ' AND use_catalog = 1 ';
		}
		if($this->input['use_performance'])
		{
			$conditions .= ' AND use_performance = 1 ';
		}
		if(trim($this->input['app_uniqueid']))
		{
			$app_uniqueid = implode('","',array_filter(explode(',', $this->input['app_uniqueid'])));
			$conditions .= ' AND bundle IN("'.$app_uniqueid.'")';
		}
		return $conditions;
	}
	public function detail()
	{
	}
	public function count()
	{
	}
}
include(ROOT_PATH . 'excute.php');