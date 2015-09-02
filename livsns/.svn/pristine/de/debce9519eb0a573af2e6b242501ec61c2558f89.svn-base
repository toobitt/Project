<?php
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','auth');
require(ROOT_DIR . 'global.php');
define('SCRIPT_NAME', 'modules');
class modules extends outerReadBase
{
	private $fields = array("id", "name", "mod_uniqueid", "app_uniqueid", "application_id", "host", "dir", "file_name", "file_type", "func_name", "need_auth");
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
		$sql = 'SELECT ' . implode(',',$fields) . ' FROM '.DB_PREFIX.'modules WHERE 1 '.$this->get_conditions().$limit;
		
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	public function get_conditions()
	{
		$conditions = '';
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
		if($this->input['application_id'])
		{
			$ids = explode(',', $this->input['application_id']);
			foreach ($ids as $id)
			{
				if(!intval($id))
				{
					$this->errorOutput(PARAMETER_ERROR);
				}
			}
			$conditions .= ' AND application_id IN('.$this->input['application_id'].')';
		}
		if(trim($this->input['app_uniqueid']))
		{
			$app_uniqueid = implode('","',array_filter(explode(',', $this->input['app_uniqueid'])));
			$conditions .= ' AND app_uniqueid IN("'.$app_uniqueid.'")';
		}
		if(trim($this->input['mod_uniqueid']))
		{
			$mod_uniqueid = implode('","',array_filter(explode(',', $this->input['mod_uniqueid'])));
			$conditions .= ' AND mod_uniqueid IN("'.$mod_uniqueid.'")';
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