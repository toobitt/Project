<?php
define('MOD_UNIQUEID','thread');
require('./global.php');
define('SCRIPT_NAME', 'thread');
require_once (CUR_CONF_PATH . 'lib/attach.class.php');
class thread extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		$this->attachlib = new attach();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$limit = '';
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		if($offset || $count)
		{
			$limit = " limit $offset,$count";
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'thread WHERE 1 '.$this->get_conditions() . ' ORDER BY create_time desc ' .$limit;

		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['format_create_time'] = hg_tran_time($row['create_time']);
			$row['format_update_time'] = hg_tran_time($row['update_time']);
			$row['avatar'] = ($tmp = unserialize($row['avatar'])) ? $tmp : array();
			//$this->addItem($row);
			$thread[] = $row;
			$aid[] = $row['aid'];
		}
		if($aid)
		{
			$aid = array_filter(array_unique(explode(',',implode($aid, ','))));
			if($aid)
			{
				$material = $this->attachlib->get_attach_by_aid(implode(',',$aid), true);
			}
		}
		if($thread)
		{
			foreach ($thread  as $key=>$val)
			{
				$aid_array = $val['aid'] ? explode(',', $val['aid']) : array();
				$val['materail'] = array();
				if($aid_array)
				{
					$aid_array = array_flip($aid_array);
					$val['materail'] = array_values(array_intersect_key($material,$aid_array));
				}
				$this->addItem($val);
			}
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
		switch(intval($this->input['type']))
		{
			case 1:
				{
					$conditions .= ' AND user_id =  ' . $this->user['user_id'];
					break;
				}
			case 2:
				{
					$conditions .= ' AND user_id !=  ' . $this->user['user_id'];
					break;
				}
			case 3:
				{
					$this->show_comment();
					break;
				}
		}
		if($this->input['topic_id'])
		{
			$conditions .= ' AND tid =  ' . intval($this->input['topic_id']);
		}
		return $conditions;
	}
	function show_comment()
	{
		$limit = '';
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		if($offset || $count)
		{
			$limit = " limit $offset,$count";
		}
		$order_by = ' ORDER BY create_time DESC ';
		$where = ' WHERE 1 ';
		if($this->input['topic_id'])
		{
			$where .= ' AND tid = ' . intval($this->input['topic_id']);
		}
		//if($this->input['thid'])
		//{
		//	$where .= ' AND thid = ' . intval($this->input['thid']);
		//}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'comment ' . $where . $order_by . $limit;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['create_time_format'] = date('Y-m-d H:i', $row['create_time']);
			$this->addItem($row);
		}
		$this->output();
	}
	public function detail()
	{
		$sql = 'SELECT * FROM '  . DB_PREFIX . 'thread WHERE id = '.intval($this->input['id']);
		$data = $this->db->query_first($sql);
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'thread WHERE 1 ' . $this->get_conditions();
		exit(json_encode($this->db->query_first($sql)));
	}
}
include(ROOT_PATH . 'excute.php');