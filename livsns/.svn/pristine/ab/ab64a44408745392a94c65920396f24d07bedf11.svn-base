<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/

	define('ROOT_DIR', '../../');
	require(ROOT_DIR . 'global.php');
	define('MOD_UNIQUEID','tuji');
	class tuji_sort extends outerReadBase
	{
		function __construct()
		{
			parent::__construct();
		}
		function __destruct()
		{
			parent::__destruct();
		}
		function show()
		{
			$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
			$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
			$limit = " limit {$offset}, {$count}";
			$condition = $this->get_condition();
			$sql = "SELECT * FROM ".DB_PREFIX.'tuji_sort WHERE 1 '.$condition.$limit;
			//exit($sql);
			$q = $this->db->query($sql);
			$this->setXmlNode('sorts','item');
			while($r = $this->db->fetch_array($q))
			{
				$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
				$this->addItem($r);
			}
			$this->output();
		}
		function get_condition()
		{
			$condition = '';
			if($this->input['id'])
			{
				$condition .= ' AND id = '.intval(urldecode($this->input['id']));
			}
			if($this->input['name'])
			{
				$condition .= ' AND sort_name LIKE "%'.urldecode($this->input['name']).'%"';
			}
			if($this->input['desc'])
			{
				$condition .= ' AND sort_desc LIKE "%'.urldecode($this->input['desc']).'%"';
			}
			if($this->input['time'])
			{
				//时间格式Y-m-d
				$condition .= ' AND create_time > "'.intval(strtotime(urldecode($this->input['time']))).'"';
			}
			return $condition;
		}
		function count()
		{
			$sql = "SELECT COUNT(*) as total FROM ".DB_PREFIX.'tuji_sort WHERE 1 '.$this->get_condition();
			echo json_encode($this->db->query_first($sql));		
		}
		function detail()
		{
			if(!$this->input['id'])
			{
				$this->errorOutput(NOID);
			}
			$this->show();
		}
	}
	$out = new tuji_sort();
	$action = $_INPUT['a'];
	if(!$_INPUT['a'])
	{
		$action = 'show';
	}
	$out->$action();
?>