<?php
require('./global.php');
define('SCRIPT_NAME', 'datasetapi');
require_once CUR_CONF_PATH.'lib/dataPlant.class.php';
class datasetapi extends adminBase
{
	function __construct()
	{
		parent::__construct();
		$this->plat = new ClassDataPlant();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$offset = $this->input['offset']?intval($this->input['offset']) : 0;
		$count 	= $this->input['count']?intval($this->input['count']) : 10;
		$limit 	= " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$orderby = ' ORDER BY id DESC';
		$data = $this->plat->show($condition, $orderby, $offset, $count);
		/*
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'data_set WHERE 1 ' . $condition . $limit;
		$query = $this->db->query($sql);
		$this->setXmlNode('historys','history');
		while($r = $this->db->fetch_array($query))
		{
			//$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['dbinfo'] = unserialize($r['dbinfo']);
			$r['paras'] = unserialize($r['paras']);
			$this->addItem($r);
		}
		$this->output();
		*/
		if ($data && !empty($data))
		{
			foreach ($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'data_set';
		echo json_encode($this->db->query_first($sql));
	}
	function detail()
	{
		if($this->input['latest'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'data_set ORDER BY id LIMIT 1';
		}
		else
		{
			if(!$this->input['id'])
			{
				$this->errorOutput(NOID);
			}
			//$sql = 'SELECT * FROM '.DB_PREFIX.'data_set WHERE id = '.intval($this->input['id']);
		}
		$this->show();
		//$r = $this->db->query_first($sql);
		//$this->addItem($r);
		//$this->output();
	}
	
	//输出所有配置
	function allConfigs()
	{
		$sql = 'SELECT id, title FROM '.DB_PREFIX.'data_set';
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[$row['id']] = $row['title'];
		}
		$this->addItem($arr);
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';