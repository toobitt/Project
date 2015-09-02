<?php
/***************************************************************************
 * $Id: live_control.class.php 19895 2013-04-08 02:42:01Z lijiaying $
 ***************************************************************************/
class liveControl extends InitFrm
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
		$orderby = $orderby ? $orderby : " ORDER BY id DESC ";

		$sql = "SELECT * FROM " . DB_PREFIX . "live_control_stream ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;

		$q = $this->db->query($sql);

		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);

			$return[] = $row;
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

		$sql = "SELECT * FROM " . DB_PREFIX . "live_control_stream " . $condition;
		$row = $this->db->query_first($sql);

		if(is_array($row) && $row)
		{
			$row['create_time'] 	= date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i:s', $row['update_time']);

			return $row;
		}
		return false;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "live_control_stream WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}

	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "live_control_stream SET ";
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
		$sql = "UPDATE " . DB_PREFIX . "live_control_stream SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$sql .= " WHERE id = " . $data['id'];

		$this->db->query($sql);

		if ($data['id'])
		{
			return $data;
		}
		return false;
	}

	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "live_control_stream WHERE id IN (" . $id . ")";

		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}

	public function get_live_control_by_channel_id($channel_id, $field = ' * ', $condition = '')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "live_control_stream ";
		$sql.= " WHERE channel_id IN (" . $channel_id . ")" . $condition;
		$sql.= " ORDER BY id ASC ";

		$q = $this->db->query($sql);

		$return = array();
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

			$return[] = $row;
		}
		return $return;
	}

	public function get_live_control_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "live_control_stream ";
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
			
		return $row;
	}

	public function log_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "live_control_log SET ";
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

	public function get_server_info($server_info)
	{
		if ($server_info['host'])
		{
			$host = $server_info['host'] . ':' . $server_info['input_port'];
			$wowzaip_input  = $server_info['host'];
			$wowzaip_output = $server_info['host'] . ':' . $server_info['output_port'];
			$output_append_host = $server_info['output_append_host'];
		}
		else
		{
			$host = $this->settings['wowza']['live_server']['host'] . ':' . $this->settings['wowza']['live_server']['input_port'];
			$wowzaip_input  = $this->settings['wowza']['live_server']['host'];
			$wowzaip_output = $this->settings['wowza']['live_server']['host'] . ':' . $this->settings['wowza']['live_server']['output_port'];
			$output_append_host = $this->settings['wowza']['output_append_host'];
		}

		$input_dir  = $this->settings['wowza']['live_server']['input_dir'];
		$output_dir = $this->settings['wowza']['live_server']['output_dir'];

		$return = array(
			'host'					=> $host,
			'input_dir'				=> $input_dir,
			'output_dir'			=> $output_dir,
			'wowzaip_input'			=> $wowzaip_input,
			'wowzaip_output'		=> $wowzaip_output,
			'output_append_host'	=> $output_append_host,
		);
		return $return;
	}
	/**
	 *
	 * 直播服务调用 ...
	 * @param int $op 操作方式  1.新增拉取流;2.删除拉取流;3.读取流信息;4.设置时移时长;5.读取时移时长
	 * @param array $para 参数
	 * @return -1.缺少app;-2.缺少name;-3.缺少url;-4.缺少时移时长;其它正常
	 
	public function live_server($op,$para)
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$curl = new curl($this->settings['live_server']['host'], $this->settings['live_server']['dir']);

		if (!$curl)
		{
			return array();
		}
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		if ($para&&is_array($para))
		{
			foreach ($para as $key => $val)
			{
				$curl->addRequestData($key,$val);
			}
		}
		switch ($op)
		{
			case 1:
				if(empty($para['app']))
				{
					return -1;
				}
				elseif(empty($para['name']))
				{
					return -2;
				}
				elseif (empty($para['url']))
				{
					return -3;
				}
				$retutn = $curl->request('add/static_pull');
				break;
			case 2:
				if(empty($para['app']))
				{
					return -1;
				}
				elseif(empty($para['name']))
				{
					return -2;
				}
				$retutn = $curl->request('drop/static_pull');
				break;
			case 3:
				$retutn = $curl->request('get/static_pull');
				break;
			case 4:
				if(empty($para['app']))
				{
					return -1;
				}
				elseif(empty($para['name']))
				{
					return -2;
				}
				elseif(empty($para['playlen']))
				{
					return -4;
				}
				$retutn = $curl->request('set/hls_playlen');
				break;
			case 5:
				$retutn = $curl->request('get/hls_playlen');
				break;
		}
		return $retutn;
	}
	*/
}
?>