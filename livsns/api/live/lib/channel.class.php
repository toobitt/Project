<?php
/***************************************************************************
* $Id: channel.class.php 46502 2015-07-03 04:15:21Z develop_tong $
* 频道类
***************************************************************************/
class channel extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $offset = 0, $count = 20, $orderby = '')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		
		$orderby = $orderby ? $orderby : " ORDER BY t1.order_id DESC ";
		
		$sql = "SELECT t1.*, t2.name AS node_name FROM " . DB_PREFIX . "channel t1 ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel_node t2 ON t2.id = t1.node_id ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		
		$q = $this->db->query($sql);
		
		$channel_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			$row['logo_square'] 	= unserialize($row['logo_square']);
			$row['stream_name'] 	= unserialize($row['stream_name']);
			$row['column_id'] 		= unserialize($row['column_id']);
			$row['column_url'] 		= unserialize($row['column_url']);
			$row['client_logo'] 	= unserialize($row['client_logo']);
			$row['logo_audio'] 		= unserialize($row['logo_audio']);
			
			$channel_info[$row['id']] = $row;
		}
		
		if (!empty($channel_info))
		{
			$channel_id = array_keys($channel_info);
			
			$channel_stream = $this->get_channel_stream(implode(',', $channel_id));
		}
		
		$return = array();
		foreach ($channel_info AS $k => $v)
		{
			if ($channel_stream[$k])
			{
				$return[] = array_merge($channel_info[$k],$channel_stream[$k]);
			}
			else 
			{
				$return[] = $channel_info[$k];
			}
		}
		
		return $return;
	}
	
	public function detail($id)
	{
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE id IN (" . $id .")";
		}
				
		$sql = "SELECT * FROM " . DB_PREFIX . "channel " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			$row['logo_square'] 	= unserialize($row['logo_square']);
			$row['stream_name'] 	= unserialize($row['stream_name']);
			$row['column_id'] 		= unserialize($row['column_id']);
			$row['column_url'] 		= unserialize($row['column_url']);
			$row['client_logo'] 	= unserialize($row['client_logo']);
			$row['logo_audio'] 		= unserialize($row['logo_audio']);
			
			if(is_array($row['column_id']))
			{
				$column_id = array();
				foreach($row['column_id'] AS $k => $v)
				{
					$column_id[] = $k;
				}
				$column_id = implode(',',$column_id);
				$row['column_id'] = $column_id;
			}
			
			$row['channel_stream'] = $this->get_channel_stream_by_channel_id($row['id']);
			
			return $row;
		}
		return false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "channel t1 WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "channel SET ";
		$space = '';
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
	
	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "channel SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$q = $this->db->query($sql);
		
		if ($data['id'])
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($q))
			{
				$data['affected_rows'] = 1;
			}
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "channel WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			foreach (explode(',', $id) AS $channel_id)
			{
				@unlink(CACHE_DIR . 'channel/' . $channel_id . '.info');
			}
			return true;
		}
		return false;
	}
	
	public function get_channel_info($condition, $offset = 0, $count = 20, $orderby = '', $field = ' * ', $is_stream = 1)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY order_id DESC ";
		
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit; 
		
		$q = $this->db->query($sql);
		
		$channel_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			}
			if ($row['update_time'])
			{
				$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			}
			if ($row['logo_rectangle'])
			{
				$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			}
			if ($row['logo_square'])
			{
				$row['logo_square'] 	= unserialize($row['logo_square']);
			}
			if ($row['stream_name'])
			{
				$row['stream_name'] 	= unserialize($row['stream_name']);
			}
			if ($row['column_id'])
			{
				$row['column_id'] 		= unserialize($row['column_id']);
			}
			if ($row['column_url'])
			{
				$row['column_url'] 		= unserialize($row['column_url']);
			}

			if ($row['client_logo'])
			{
				$row['client_logo'] 	= unserialize($row['client_logo']);
			}
			
			if ($row['logo_audio'])
			{
				$row['logo_audio'] 		= unserialize($row['logo_audio']);
			}
			
			$channel_info[$row['id']] = $row;
		}
		
		if (!empty($channel_info) && $is_stream)
		{
			$channel_id = array_keys($channel_info);
			
			$channel_stream = $this->get_channel_stream(implode(',', $channel_id));
		}
		
		$return = array();
		foreach ($channel_info AS $k => $v)
		{
			if ($channel_stream[$k])
			{
				$return[$k] = array_merge($channel_info[$k],$channel_stream[$k]);
			}
			else 
			{
				$return[$k] = $channel_info[$k];
			}
		}
		
		return $return;
	}
	
	public function get_channel_info_by_id($id, $field = ' * ', $is_stream = 1)
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE id IN (" . $id . ")  ORDER BY order_id DESC"; 
		
		$q = $this->db->query($sql);
		
		$channel_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			}
			if ($row['update_time'])
			{
				$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
			}
			if ($row['logo_rectangle'])
			{
				$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
			}
			if ($row['logo_square'])
			{
				$row['logo_square'] 	= unserialize($row['logo_square']);
			}
			if ($row['stream_name'])
			{
				$row['stream_name'] 	= unserialize($row['stream_name']);
			}
			if ($row['column_id'])
			{
				$row['column_id'] 		= unserialize($row['column_id']);
			}
			if ($row['column_url'])
			{
				$row['column_url'] 		= unserialize($row['column_url']);
			}
			if ($row['client_logo'])
			{
				$row['client_logo'] 	= unserialize($row['client_logo']);
			}
			if ($row['logo_audio'])
			{
				$row['logo_audio'] 		= unserialize($row['logo_audio']);
			}

			$channel_info[$row['id']] = $row;
		}
		
		if (!empty($id) && $is_stream)
		{
			$channel_stream = $this->get_channel_stream($id);
		}
		
		$return = array();
		foreach ($channel_info AS $k => $v)
		{
			if ($channel_stream[$k])
			{
				$return[] = array_merge($channel_info[$k],$channel_stream[$k]);
			}
			else 
			{
				$return[] = $channel_info[$k];
			}
		}
		
		return $return;
	}
	
	public function get_channel_by_id($id, $is_stream = 0, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE id = " . $id;
		
		$row = $this->db->query_first($sql);
		
		if ($row['create_time'])
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
		}
		
		if ($row['update_time'])
		{
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);
		}
		
		if ($row['logo_rectangle'])
		{
			$row['logo_rectangle'] 	= unserialize($row['logo_rectangle']);
		}
		
		if ($row['logo_square'])
		{
			$row['logo_square'] 	= unserialize($row['logo_square']);
		}
		
		if ($row['stream_name'])
		{
			$row['stream_name'] 	= unserialize($row['stream_name']);
		}
		
		if ($row['column_id'])
		{
			$row['column_id'] 		= unserialize($row['column_id']);
		}
		
		if ($row['column_url'])
		{
			$row['column_url'] 		= unserialize($row['column_url']);
		}
		
		if ($row['client_logo'])
		{
			$row['client_logo'] 	= unserialize($row['client_logo']);
		}
		
		if ($row['logo_audio'])
		{
			$row['logo_audio'] 		= unserialize($row['logo_audio']);
		}
		
		if ($is_stream)
		{
			$row['channel_stream'] = $this->get_channel_stream_by_channel_id($id);
		}
		
		return $row;
	}
	
	public function check_channel_code($code, $id = '')
	{
		$sql = "SELECT code FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE code = '" . $code . "' ";
		
		if ($id)
		{
			$sql .= " AND id NOT IN (" . $id . ")";
		}

		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function get_channel_stream($channel_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel_stream ";
		$sql.= " WHERE channel_id IN (" . $channel_id . ")";
		$sql.= " ORDER BY order_id ASC ";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[$row['channel_id']]['channel_stream'][] = $row;
		}
		return $return;
	}
	
	public function get_channel_stream_by_channel_id($channel_id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel_stream ";
		$sql.= " WHERE channel_id = " . $channel_id;
		$sql.= " ORDER BY order_id ASC ";
		
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		return $return;
	}
	
	public function channel_stream_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
		$space = '';
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
	
	public function channel_stream_update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "channel_stream SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$sql .= " WHERE id = " . $data['id'];
		
		$q = $this->db->query($sql);
		
		if ($data['id'])
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($q))
			{
				$data['affected_rows'] = 1;
			}
			return $data;
		}
		return false;
	}
	
	public function channel_stream_delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function get_server_info($server_info)
	{
		$server_info['type'] = $server_info['type'] ? $server_info['type'] : 'wowza';
		$host = $server_info['host'];
		if($server_info['input_port'])
		{
			$host .= ':' . $server_info['input_port'];
		}
		
		$function = 'set_server_' . $server_info['type']; //设置server
		
		$ret_server = $this->$function($server_info);
		$output_append_host = $server_info['output_append_host'];
		
		$input_dir  = $server_info['input_dir'];
		$output_dir = $server_info['output_dir'];
		
		
		$return = array(
			'host'					=> $host,
			'input_dir'				=> $input_dir,
			'output_dir'			=> $output_dir,
			'wowzaip_input'			=> $ret_server['wowzaip_input'],
			'wowzaip_output'		=> $ret_server['wowzaip_output'],
			'rtmp_output'		=> $ret_server['rtmp_output'],
			'output_append_host'	=> $output_append_host,
			'live_host'				=> $ret_server['live_host'],
			'live_wowzaip_output'	=> $ret_server['live_wowzaip_output'],
			'record_host'			=> $ret_server['record_host'],
			'record_wowzaip_output'	=> $ret_server['record_wowzaip_output'],
			'type'					=> $server_info['type'],
		);
		return $return;
	}
	
	private function set_server_nginx($server_info)
	{
		$wowzaip_input  = $server_info['out_host'] ? $server_info['out_host'] : $server_info['host'];
		$rtmp_host  = $server_info['host'] ? $server_info['host'] : $server_info['host'];
		if($server_info['output_port'])
		{
			$wowzaip_input .= ':' . $server_info['output_port'];
			//$rtmp_host .= ':' . $server_info['output_port'];
		}
		$wowzaip_output = $wowzaip_input;
		
		$live_host = $live_wowzaip_output = $server_info['live_host'];
		if($server_info['live_input_port'])
		{
			$live_wowzaip_output = $live_host .= ':' . $server_info['live_input_port'];
		}
		
		$record_host = $record_wowzaip_output = $server_info['record_host'];
		if($server_info['record_input_port'])
		{
			$record_host = $record_wowzaip_output .= ':' . $server_info['live_input_port'];
		}
		
		$return = array(
			'host'					=> $host,
			'wowzaip_input'			=> $wowzaip_input,
			'wowzaip_output'		=> $wowzaip_output,
			'rtmp_output'		=> $rtmp_host,
			'live_host'				=> $server_info['live_host'] ? $live_host : '',
			'live_wowzaip_output'	=> $server_info['live_host'] ? $live_wowzaip_output : '',
			'record_host'			=> $server_info['record_host'] ? $record_host : '',
			'record_wowzaip_output'	=> $server_info['record_host'] ? $record_wowzaip_output : '',
			'server_type'			=> $server_info['server_type'],
			
		);
		return $return;
	}
	private function set_server_wowza($server_info)
	{
		$wowzaip_input  = $server_info['host'] . ':' . $server_info['output_port'];
		$wowzaip_output = $server_info['host'] . ':' . $server_info['output_port'];
		
		$live_host = $server_info['live_host'] . ':' . $server_info['live_input_port'];
		$live_wowzaip_output = $server_info['live_host'] . ':' . $server_info['live_output_port'];
		
		$record_host = $server_info['record_host'] . ':' . $server_info['record_input_port'];
		$record_wowzaip_output = $server_info['record_host'] . ':' . $server_info['record_output_port'];
		
		$return = array(
			'host'					=> $host,
			'wowzaip_input'			=> $wowzaip_input,
			'wowzaip_output'		=> $wowzaip_output,
			'rtmp_output'		=> $wowzaip_output,
			'live_host'				=> $server_info['live_host'] ? $live_host : '',
			'live_wowzaip_output'	=> $server_info['live_host'] ? $live_wowzaip_output : '',
			'record_host'			=> $server_info['record_host'] ? $record_host : '',
			'record_wowzaip_output'	=> $server_info['record_host'] ? $record_wowzaip_output : '',
		);
		return $return;
	}
	
	private function set_server_tvie($server_info)
	{
		$wowzaip_input  = $server_info['host'] . ':' . $server_info['input_port'];
		$wowzaip_output = $wowzaip_input;//$server_info['host'] . ':' . $server_info['input_port'];
		
		$live_host = $server_info['live_host'] . ':' . $server_info['live_input_port'];
		$live_wowzaip_output = $live_host;//$server_info['live_host'] . ':' . $server_info['live_input_port'];
		
		$record_host = $server_info['record_host'] . ':' . $server_info['record_input_port'];
		$record_wowzaip_output = $record_host;//$server_info['record_host'] . ':' . $server_info['record_input_port'];
		
		$return = array(
			'host'					=> $host,
			'wowzaip_input'			=> $wowzaip_input,
			'wowzaip_output'		=> $wowzaip_output,
			'rtmp_output'		=> $wowzaip_output,
			'live_host'				=> $server_info['live_host'] ? $live_host : '',
			'live_wowzaip_output'	=> $server_info['live_host'] ? $live_wowzaip_output : '',
			'record_host'			=> $server_info['record_host'] ? $record_host : '',
			'record_wowzaip_output'	=> $server_info['record_host'] ? $record_wowzaip_output : '',
		);
		return $return;
	}
	
	public function set_url_info_nginx($server_info, $channel_stream)
	{
		$return = array(
			'app_name'		=> $server_info['output_dir'],
			'stream_name'	=> $channel_stream['code'],
			'stream_name1'	=> $channel_stream['stream_name'],
			'm3u8_type'		=> 'm3u8',
			'flv_type'		=> '',
			'flv'			=> 'm3u8',
			'input_port'	=> $server_info['input_port'] ? $server_info['input_port'] : '',
			'output_port'	=> $server_info['output_port'] ? $server_info['output_port'] : '',
			'starttime'		=> TIMENOW . '000',
			'dvr'			=> '',
			'server_type'   => $server_info['server_type'],
		);
		
		return $return;		
	}
	
	public function set_url_info_wowza($server_info, $channel_stream)
	{
		$return = array(
			'app_name'		=> $channel_stream['code'],
			'stream_name'	=> $channel_stream['stream_name'] . $this->settings['wowza']['output']['suffix'],
			'm3u8_type'		=> 'm3u8',
			'flv_type'		=> '',
			'flv'			=> 'flv',
			'output_port'	=> $server_info['output_port'],
			'starttime'		=> TIMENOW . '000',
			'dvr'			=> 'dvr',
		);
		
		return $return;
	}
	
	public function set_url_info_tvie($server_info, $channel_stream)
	{
		$return = array(
			'app_name'		=> $this->settings['tvie']['app_name'] ? $this->settings['tvie']['app_name'] : 'live',
			'stream_name'	=> 'output_' . $channel_stream['code'] . '_' . $channel_stream['stream_name'],
			'm3u8_type'		=> 'manifest.m3u8',
			'flv_type'		=> 'flv',
			'flv'			=> 'flv',
			'output_port'	=> $server_info['input_port'],
			'starttime'		=> '',
			'dvr'			=> '',
		);
		
		return $return;
	}
	
	public function set_stream_url($server_info, $channel_stream)
	{
		$type 					= $server_info['type'];
		$is_rand 				= $server_info['is_rand'];
		$wowzaip_output 		= $server_info['wowzaip_output'];
		$rtmp_output 		= $server_info['rtmp_output'];
		$live_wowzaip_output 	= $server_info['live_wowzaip_output'];
		$record_wowzaip_output 	= $server_info['record_wowzaip_output'];
		$output_append_host 	= $server_info['output_append_host'];
		$function = 'set_url_info_' . $type;
		$set_stream_info = $this->$function($server_info, $channel_stream);
		$app_name 	 = $set_stream_info['app_name'];
		$stream_name = $set_stream_info['stream_name'];
		$m3u8_type	 = $set_stream_info['m3u8_type'];
		$flv_type	 = $set_stream_info['flv_type'];
		$flv		 = $set_stream_info['flv'];
		if ($set_stream_info['output_port'])
		{
			$output_port = ':' . $set_stream_info['output_port'];
		}
		if ($set_stream_info['input_port'])
		{
			$input_port = ':' . $set_stream_info['input_port'];
		}
		$server_type = $server_info['type'];
		$_wowzaip_output  = $is_rand ? $output_append_host[@array_rand($output_append_host, 1)] . $input_port : $rtmp_output . $input_port;
		
		$output_url 	 = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $flv,$server_type);
		$output_url_rtmp = hg_set_stream_url($_wowzaip_output, $app_name, $stream_name . '_' . $set_stream_info['stream_name1'], $flv_type,$server_type, '', '', 'rtmp://');
		
		if ($channel_stream['is_mobile_phone'] || true)
		{
			if ($this->settings['signleliveaddr'])
			{
				$m3u8 = $this->settings['signleliveaddr'] . $app_name . '.stream/playlist.m3u8';
			}
			else
			{
				$m3u8 = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $m3u8_type,$server_type, $starttime, $dvr, 'http://', '/' . $set_stream_info['stream_name1'] . '/live.m3u8');
			}
		}
	
		//直播
		if ($live_wowzaip_output && $channel_stream['is_live'])
		{
			$live_url 	   = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $flv,$server_type);
			$live_url_rtmp = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name . '_' . $set_stream_info['stream_name1'], $flv_type,$server_type, '', '', 'rtmp://');
			
			if ($channel_stream['is_mobile_phone'])
			{
				if ($this->settings['signleliveaddr'])
				{
					$live_m3u8 = $this->settings['signleliveaddr'] . $app_name . '.stream/playlist.m3u8';
				}
				else
				{
					$live_m3u8 = hg_set_stream_url($live_wowzaip_output, $app_name, $stream_name, $m3u8_type,$server_type);
				}
			}
		}
		else 
		{
			$live_url 	   = $output_url;
			$live_url_rtmp = $output_url_rtmp;
			$live_m3u8	   = hg_set_stream_url($wowzaip_output, $app_name, $stream_name, $m3u8_type,$server_type, $starttime, $dvr);
		}
		
		//录制
		$record_stream = array();
		if ($record_wowzaip_output && $channel_stream['is_record'])
		{
			$record_stream['output_url'] 	  = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name, $flv,$server_type);
			$record_stream['output_url_rtmp'] = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name . '_' . $set_stream_info['stream_name1'], $flv_type,$server_type, '', '', 'rtmp://');
			
			if ($channel_stream['is_mobile_phone'])
			{
				if ($this->settings['signleliveaddr'])
				{
					$record_stream['m3u8'] = $this->settings['signleliveaddr'] . $app_name . '.stream/playlist.m3u8';
				}
				else
				{
					$record_stream['m3u8'] = hg_set_stream_url($record_wowzaip_output, $app_name, $stream_name, $m3u8_type,$server_type);
				}
			}
		}
		else 
		{
			$record_stream['output_url'] 	  = $output_url;
			$record_stream['output_url_rtmp'] = $output_url_rtmp;
			$record_stream['m3u8']	 		  = $m3u8;
		}
		
		$return = array(
			'channel_stream' => array(
				'output_url'		=> $output_url,
				'output_url_rtmp'	=> $output_url_rtmp,
				'm3u8'				=> $m3u8,
				'live_url'			=> $live_url,
				'live_url_rtmp'		=> $live_url_rtmp,
				'live_m3u8'			=> $live_m3u8,
			),
			'record_stream'	 => $record_stream,
		);
		
		return $return;
	}
	
	public function get_stream_count($server_id, $channel_id = '')
	{
		$sql  = "SELECT id, stream_count, server_id FROM " . DB_PREFIX . "channel ";
		$sql .= " WHERE 1 AND server_id = " . $server_id;
		
		if ($channel_id)
		{
			$sql .= " AND id NOT IN (" . $channel_id . ")";
		}
		
		$q = $this->db->query($sql);
		
		$stream_count = 0;
		while ($row = $this->db->fetch_array($q))
		{
			$stream_count = $stream_count + $row['stream_count'];
		}
		$return = $stream_count;
		return $return;
	}
	
	/**
	 * 获取图片配置
	 * Enter description here ...
	 */
	public function get_img_url()
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_material']['host'], $this->settings['App_material']['dir']);
		
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_config');
		$retutn = $this->curl->request('configuare.php');
		return $retutn;
	}
	
	public function add_material($file, $id)
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}
		
		$files['Filedata'] = $file;
		$material = $mMaterial->addMaterial($files, $id);
		$return = array();
		if (!empty($material))
		{
			$return['host'] 	= $material['host'];
			$return['dir'] 		= $material['dir'];
			$return['filepath'] = $material['filepath'];
			$return['filename'] = $material['filename'];
		}
		
		return $return;
	}

	public function cache_channel($channel_code)
	{
		$sql  = "SELECT cs.stream_name, cs.bitrate, c.id,c.code,c.name,c.time_shift,c.status,c.table_name, sc.ts_host,sc.out_host FROM " . DB_PREFIX . "channel_stream cs LEFT JOIN " . DB_PREFIX . "channel c ON cs.channel_id = c.id LEFT JOIN " . DB_PREFIX . "server_config sc ON sc.id=c.server_id  WHERE c.code='{$channel_code}' ORDER BY cs.is_default DESC,cs.order_id ASC";
		$q = $this->db->query($sql);
		$channel = array();
		while($r = $this->db->fetch_array($q))
		{
			if ($r['out_host'])
			{
				$r['out_host'] = 'http://' . $r['out_host'] . '/';
			}
			$channel['channel'] = array(
					'id' => $r['id'],	
					'code' => $r['code'],	
					'name' => $r['name'],	
					'time_shift' => $r['time_shift'],
					'status' => $r['status'],
					'table_name' => $r['table_name'],		
					'config' => array('ts_host' => $r['ts_host'], 'out_host' => $r['out_host']),		
			);
			$channel['stream'][$r['stream_name']] = array(
					'bitrate' => $r['bitrate'],
					'stream_name' => $r['stream_name'],
			);
		}
		hg_mkdir(CACHE_DIR . 'channel/');
		//file_put_contents(CACHE_DIR.'channel_info.txt',var_export($sql,1));
		file_put_contents(CACHE_DIR . 'channel/' . $channel['channel']['code'] . '.php', '<?php $channel_info = ' . var_export($channel, 1) . ';?>');
	}
}
?>