<?php
/*
 * 废弃但不要删除
 * 
 * */
require_once('./global.php');
define('MOD_UNIQUEID','adv');//模块标识
define('NOD_UNIQUEID','adv_node');//模块标识
class adv_node extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//输出广告客户端节点
	function show()
	{
		$group_flag = $this->input['fid'];
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['offset'] ? $this->input['count'] : 30;
		$conditions = '';
		$auth_node_pos = $_auth_node_group = array();
		$auth_node = get_auth_group_or_pos(MOD_UNIQUEID, 'show', $this->user);
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $auth_node)
		{
			if(!$group_flag)
			{
				if(!$auth_node)
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
				if($auth_node['first'])
				{
					$conditions .= implode('","', $auth_node['first']);
				}
				if($auth_node['second'])
				{
					if($conditions)
					{
						$conditions .= '","';
					}
					$conditions .= implode('","', $auth_node['second']);
				}
				$conditions = ' AND flag in("'.$conditions.'")';
			}
			else
			{
				if($auth_node['three'][$group_flag])
				{
					$conditions .= ' AND p.id IN('.implode(',', $auth_node['three'][$group_flag]).')';
				}
			}
		}
		if($group_flag)
		{
			$sql = 'SELECT p.id,p.zh_name,gp.group_flag FROM  '.DB_PREFIX.'group_pos gp LEFT JOIN '.DB_PREFIX.'advpos p ON gp.pos_id = p.id WHERE 1 AND  gp.group_flag IN("'.$group_flag.'")'.$conditions;
			$out = array();
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$m = array(
				'id'		=>$row['group_flag'].SPLIT_FLAG.$row['id'],
				"name"		=>$row['zh_name'],
				"fid"		=>$this->input['fid'],
				"depath"	=>2,
				'is_last'	=>1,
				);
				$this->addItem($m);
			}
		}
		else
		{	
			$sql = 'SELECT id,flag,name FROM '.DB_PREFIX.'advgroup where 1 '.$conditions.' limit '.$offset.', '.$count;
			//$this->errorOutput($sql);
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$m = array(
				'id'		=>$row['flag'],
				"name"		=>$row['name'],
				"fid"		=>$this->input['fid'],
				"depath"	=>1,
				'is_last'	=>0,
				);
				$this->addItem($m);
			}
		}
		$this->output();
	}
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			return;
		}
		$ids = explode(',', $ids);
		$group_flag = '';
		$pos = array();
		foreach ($ids as $v)
		{
			if(in_array($v, $this->settings['hg_ad_flag']))
			{
				$group_flag .= '"'.$v.'",';
			}
			else
			{
				$tmp = explode(SPLIT_FLAG, $v);
				$pos[$tmp[0]] = $pos[$tmp[0]] ? $pos[$tmp[0]] : array();
				if(!in_array($tmp[1], $pos[$tmp[0]]))
				{
					$pos[$tmp[0]][]= $tmp[1];
				}
			}
		}
		if($group_flag)
		{
			$group_flag = trim($group_flag, ',');
			$sql = 'SELECT flag,name  FROM '.DB_PREFIX.'advgroup WHERE flag IN('.$group_flag.')';
			$group_flag = array();
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$temp = array('id'=>$row['flag'],'name'=>$row['name']);
				$this->addItem(array($temp));
			}
		}
		if($pos)
		{
			$client_flag = '';
			$pos_ids = '';
			foreach ($pos as $item=>$value)
			{
				$client_flag .= '"' . $item . '",';
				$pos_ids .= implode(',', $value) . ',';
			}
			if($client_flag && $pos_ids);
			{
				$pos_ids = trim($pos_ids, ',');
				$client_flag = trim($client_flag, ',');
				$sql = 'SELECT p.id,p.zh_name,gp.group_flag,p.group_flag as distr FROM  '.DB_PREFIX.'group_pos gp LEFT JOIN '.DB_PREFIX.'advpos p ON gp.pos_id = p.id WHERE gp.group_flag IN('.$client_flag.') and p.id IN('.$pos_ids.')';
			}
			$out = array();
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				if(!in_array($row['id'],$pos[$row['group_flag']]))
				{
					continue;
				}
				$temp = array(
				'id'=>$row['group_flag'].SPLIT_FLAG.$row['id'],
				'name'=>$row['zh_name'],
				);
				$distr = (array)unserialize($row['distr']);
				$temp = array(array('id'=>$row['group_flag'],'name'=>$distr[$row['group_flag']]),$temp);
				$this->addItem($temp);
			}
		}
		$this->output();
	}
}
$ouput= new adv_node();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();