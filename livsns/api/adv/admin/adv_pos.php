<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_pos');//模块标识
class adv_pos extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
	function show()
	{
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		else
		{
			$orderby = ' ORDER BY order_id DESC ';
		}
		$condition = $this->get_condition();

		if($this->input['_id'])
		{
			$sql = 'SELECT flag FROM '.DB_PREFIX.'advgroup WHERE id = '.intval($this->input['_id']);
			$group_flag = $this->db->query_first($sql);
			$group_flag = $group_flag['flag'];
			$sql = 'SELECT p.* FROM '.DB_PREFIX.'group_pos gp LEFT JOIN '.DB_PREFIX.'advpos p ON gp.pos_id = p.id WHERE gp.group_flag = "'.$group_flag.'"';
		}
		else
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advpos p WHERE 1';
		}
		$sql .= $condition.$orderby.$limit;
		//$this->errorOutput($this->input['pos_type']);
		$q = $this->db->query($sql);
		$this->setXmlNode('adv_positions','adv_position');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			if($r['is_use'] == 1)
			{
				$r['is_use'] = '是';
			}
			else 
			{
				$r['is_use'] = '否';		
			}
			$r['group_flag'] = unserialize($r['group_flag']);
			$r['ani_name'] = $this->settings['adv_pos_type'][$r['ani_id']];
			if($r['ani_id'] == 2)
			{
				$r['divpos'] = htmlentities(str_replace('{$posid}', $r['id'], HG_FIXED_ADBOX));
			}
			if($r['ani_id'] == 1)
			{
				$r['divpos'] = htmlentities(str_replace('{$posid}', $r['id'], HG_FLOAT_ADBOX));
			}
			$this->addItem($r);
		}
		$this->output();
	}
	
	//获取分组信息
	function get_group()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'advgroup WHERE is_use = 1';
		$q = $this->db->query($sql);
		$group = array();
		while($r = $this->db->fetch_array($q))
		{
			$group[$r['flag']] = $r['name'];
		}
		echo json_encode($group);
	}
	function get_adv_ani()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE is_use = 1';
		$q = $this->db->query($sql);
		$return = array(0=>'无效果');
		while($r = $this->db->fetch_array($q))
		{
			$return[$r['id']] = $r['name'];
		}
		echo json_encode($return);
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND p.zh_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND p.id = '.intval($this->input['id']);
		}
		if($this->input['pos_type']!=-1 && isset($this->input['pos_type']))
		{
			$condition .= ' AND p.ani_id = '.intval($this->input['pos_type']);
		}
		if($this->input['status']!=-1 && isset($this->input['status']))
		{
			$condition .= ' AND p.is_use = '.intval($this->input['status']);
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'advpos WHERE 1 '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function detail()
	{
		
		if($this->input['latest'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advpos ORDER BY id DESC LIMIT 1';
		}
		else
		{
			if(!$this->input['id'])
			{
				$this->errorOutput(NOID);
			}
			$sql = 'SELECT * FROM '.DB_PREFIX.'advpos WHERE id = '.intval(trim(urldecode($this->input['id'])));
		}
		$r = $this->db->query_first($sql);
		$r['para'] = unserialize($r['para']);
		$r['form_style'] = unserialize($r['form_style']);
		$r['group_flag'] = unserialize($r['group_flag']);
		$r['global'] = $r['group_flag']['global']? 1 : 0;
		$this->addItem($r);
		$this->output();
	}
	function getposdiv()
	{
		$posid = intval($this->input['posid']);
		if(!$posid)
		{
			$this->errorOutput(UNKNOWN_ADPOS);
		}
		$divpos = htmlentities(str_replace('{$posid}', $posid, HG_ADBOX));
		$this->addItem($divpos);
		$this->output();
	}
}
$ouput= new adv_pos();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();