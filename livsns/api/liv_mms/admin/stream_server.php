<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4234 2011-07-28 05:14:16Z repheal $
***************************************************************************/
require('global.php');
class streamServerApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示
	 */
	function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select * from " . DB_PREFIX . "stream_server";		
		$sql .= ' where 1 '.$this->get_condition();
		$q = $this->db->query($sql);
		$this->setXmlNode('channel' , 'info');
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		$this->output();
		
	}
	
	/**
	 * 取单条信息
	 */
	function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in(' . $id .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "stream_server " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('stream_server' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('服务器不存在');	
		} 	
	}
	
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "stream_server AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
	
	/**
	 * Enter description here ...
	 */
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$this->input['start_time'] = strtotime(urldecode($this->input['start_time']));
		    $a = $this->input['start_time'];
			if(isset($this->input['end_time']) && !empty($this->input['end_time']))
			{
				$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
				$condition .= 'and create_time between '.$this->input['start_time'].' and '.$this->input['end_time'];
			}
			else
			{
				$condition .= 'and create_time > '.$this->input['start_time'];
			}
		}
		if(!$this->input['start_time'] && isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
			$condition .= 'and create_time < '.$this->input['end_time'];
		}
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and name like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		return $condition;
	}
}

$out = new streamServerApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>