<?php
require_once('./global.php');
define('SCRIPT_NAME', 'preferences_update');
define('MOD_UNIQUEID','preferences_update');
class preferences_update extends adminBase
{	

	protected $value = array(
	'id'		=>	'',
	'admin_id'	=>	'auto',
	'title'		=>	'',
	'admin_settings'		=>	'',
	'flag'	=>	'',
	'create_time'		=>	'auto',
	'update_time'		=>	'auto',
	//'need_auth'	=>	'',
	'ip'	=>	'',
	'status'=>'',
	);
	protected $admin_settings = array();
	function __construct()
	{
		parent::__construct();
	}
	
	
	protected $check_field = array(
	'title', 'admin_settings', 'flag',
	);
	protected function verify_data_integrity()
	{
		foreach ($this->value as $key=>$value)
		{
			if(in_array($key, $this->check_field))
			{
				if(!$this->input[$key])
				{
					$this->errorOutput($key . ' ' . EMPTY_FIELD);
				}
			}
			if($value == 'auto')
			{
				switch ($key)
				{
					case 'admin_id':
					{
						$this->value[$key] = $this->user['user_id'];
						break;
					}
					case 'create_time':
					{
						$this->value[$key] = TIMENOW;
						break;
					}
					case 'update_time':
					{
						$this->value[$key] = TIMENOW;
						break;
					}
				}
			}
			else
			{
				$this->value[$key] = addslashes(urldecode($this->input[$key]));
				if($key == 'admin_settings')
				{
					$this->admin_settings = json_decode(urldecode($this->input['admin_settings']),1);
				}
			}
		}
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NO_ID);
		}
		$ids = array();
		$sql = 'SELECT id FROM '.DB_PREFIX.'admin_settings WHERE id IN('.$this->input['id'].') AND admin_id =' . $this->user['user_id'];
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$ids[] = $row['id'];
		}
		if($ids)
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'admin_settings_search WHERE sid IN('.implode(',', $ids).')';
			$this->db->query($sql);
			
			$sql = 'DELETE FROM '.DB_PREFIX.'admin_settings WHERE id IN('.implode(',',$ids).')';
			$this->db->query($sql);
		}
		
		$this->addItem($this->input['id']);
		$this->output();
	}
	function show()
	{
		$this->verify_data_integrity();
		$this->excute_sql();
		$this->value['admin_settings']  = $this->admin_settings;
		$this->addItem($this->value);
		$this->output();
	}
	protected function excute_sql()
	{
		if($this->value['status'])
		{
			if($this->input['status_relation'] == 'mutex')
			{
				$this->db->query('UPDATE ' . DB_PREFIX . 'admin_settings SET status = 0 WHERE status = ' . $this->input['status'] . ' AND admin_id='.$this->user['user_id'] . ' AND flag="'.$this->value['flag'].'"');
			}
		}
		if(!$this->value['id'])
		{
			$a = 'INSERT INTO ';
			$where = '';
			unset($this->value['update_time']);
		}
		else
		{
			$record_info = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'admin_settings WHERE id = '.$this->value['id'] . ' AND admin_id='.$this->user['user_id']);
			$admin_settings = json_decode($record_info['admin_settings'],1);
			if($admin_settings)
			{
				foreach($admin_settings as $key=>$val)
				{
					if(!isset($this->admin_settings[$key]))
					{
						$this->admin_settings[$key] = $val;
					}
				}
				$this->value['admin_settings'] = addslashes(json_encode($this->admin_settings));
			}
			$a = 'UPDATE ';
			$where = ' WHERE id = ' . $this->value['id'] . ' AND admin_id='.$this->user['user_id'];
			unset($this->value['create_time']);
		}
		$sql = $a . DB_PREFIX . 'admin_settings SET ';
		foreach ($this->value as $k=>$v)
		{
			if($k=='id')
			{
				continue;
			}
			$sql .= " `{$k}` = \"{$v}\",";
		}
		$sql = trim($sql, ',') . $where;
		$this->db->query($sql);
		if(!$where)
		{
			$this->value['id'] = $this->db->insert_id();
		}
		if($this->input['search_field'])
		{
			$search_field = explode(',', $this->input['search_field']);
			if(!$where)
			{
				$sid = $this->db->insert_id();
			}
			else
			{
				$this->db->query('DELETE FROM ' . DB_PREFIX . 'admin_settings_search WHERE sid='.$this->value['id']);
				$sid = $this->value['id'];
			}
			$sql = 'INSERT INTO ' . DB_PREFIX . 'admin_settings_search values';
			foreach ($search_field as $key=>$val)
			{
				$sql  .= '(null,'.$sid.',"'.$val.'","'.$this->admin_settings[$val].'"),';
			}
			$this->db->query(trim($sql,','));
		}
	}
	public function update_specify_field()
	{
		$sid = intval($this->input['id']);
		$user_id = $this->user['user_id'];
		$field = $this->input['update_field'];
		$value = $this->input['update_value'];
		$flag = $this->input['flag'];
		if(!$sid || !$user_id || !$field || !$flag)
		{
			$this->errorOutput('缺少必须参数');
		}
		$is_search_field = intval($this->input['is_search_field']);
		$where = ' WHERE id = '.$sid . ' AND  admin_id = '.$user_id . ' AND flag="'.$flag.'"';
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'admin_settings ' . $where;
		$settings = $this->db->query_first($sql);
		if(!$settings)
		{
			$this->errorOutput('纪录不存在');
		}
		$admin_settings = json_decode($settings['admin_settings'],1);
		$admin_settings[$field] = $value;
		$admin_settings = json_encode($admin_settings);
		$sql = 'UPDATE . ' . DB_PREFIX . 'admin_settings SET admin_settings="'.addslashes($admin_settings).'"'.$where;
		$this->db->query($sql);
		if($is_search_field)
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'admin_settings_search SET value="'.addslashes($value).'" WHERE `key`="'.$field.'" AND sid='.$settings['id'];
			$this->db->query($sql);
		}
		$settings['admin_settings'] = $admin_settings;
		$this->addItem($settings);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');