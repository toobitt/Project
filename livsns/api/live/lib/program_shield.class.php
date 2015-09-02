<?php
/***************************************************************************
* $Id: program_shield.class.php 33155 2013-12-30 04:04:23Z develop_tong $
***************************************************************************/
class programShield extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($channel_id, $dates, $condition, $orderby, $limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_shield ";
		$sql.= " WHERE 1 " . $condition;
		$sql.= " AND channel_id = " . $channel_id . " AND dates = '" . $dates . "' ";
		$sql.= $orderby . $limit;
		$q = $this->db->query($sql);
		
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			
			$row['start'] 		= date('H:i:s', $row['start_time']);
			$row['end'] 		= date('H:i:s', $row['start_time'] + $row['toff']);
			
			$info[] = $row;
		}
		return $info;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "program_shield SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update($data, $id)
	{
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "program_shield WHERE id = " . $id;
		$pre_data = $this->db->query_first($sql_);
		
		$sql = "UPDATE " . DB_PREFIX . "program_shield SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $id;
		$this->db->query($sql);
		
		$this->addLogs('update' , $pre_data , $data , '' , '');
		
		$data['id'] = $id;
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "program_shield WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql_);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "program_shield ";
		$sql.= " WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			$this->addLogs('delete' , $ret , '' , '' , '');
			return true;
		}
		return false;
	}
	
	public function get_shield_by_time($channel_id, $start_time, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "program_shield ";
		$sql.= " WHERE channel_id = " . $channel_id;
		$sql.= " AND start_time <= " . $start_time . " AND start_time + toff >= " . $start_time;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function get_shield_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "program_shield ";
		$sql.= " WHERE id = " . $id;
		$return = $this->db->query_first($sql);
		return $return;
	}

	public function cache_program_shield($channel_id, $dates, $code)
	{
		$program_shield_dir = $this->settings['program_shield_dir'] ? $this->settings['program_shield_dir'] : 'program_shield';
		$dir  = $dates;
		$dir 	  = CACHE_DIR . $program_shield_dir . '/' . $dir;
		$field = '*';
		$filename = $code . '.php';
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "program_shield ";
		$sql.= " WHERE channel_id = " . $channel_id;
		$sql.= " AND dates = '" . $dates . "' AND type=0";
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		$time_zone = array();
		$last_end_time = 0;
		$i = 0;
		foreach ($return AS $v)
		{
			if ($v['start_time'] == $last_end_time)
			{
				$last_end_time = $time_zone[$i]['end_time'] + $v['start_time'] + $v['toff'];
				$time_zone[$i-1]['end_time'] = $last_end_time;
				$time_zone[$i-1]['end_time_format'] = date('Y-m-d H:i:s', $last_end_time);
				$i--;
			}
			else
			{
				$last_end_time = $v['start_time'] + $v['toff'];
				$time_zone[$i] = array(
					'start_time' => $v['start_time'],	
					'end_time' => $last_end_time,	
					'start_time_format' => date('Y-m-d H:i:s', $v['start_time']),	
					'end_time_format' => date('Y-m-d H:i:s', $last_end_time),	
				);
			}
			$i++;
		}
		
		if (!is_dir($dir))
		{
			hg_mkdir($dir);
		}
		if (!$return)
		{
			@unlink($dir . '/' . $filename);
		}
		$content = '<?php
$program_shield = ' . var_export($return, 1) . ';
$program_shield_zone = ' . var_export($time_zone, 1) . ';
?>';
		hg_file_write($dir . '/' . $filename, $content);
		@unlink($dir . '/' . $channel_id . '.php');
		hg_clear_m3u8(DATA_DIR . $code);
	}
}
?>