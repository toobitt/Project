<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_effect');//模块标识
class adv_animation extends adminReadBase
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
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE 1'.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('animations','animation');
		$map = array(0=>'图片',1=>'flash',2=>'视频',3=>'文字');
		while($r = $this->db->fetch_array($q))
		{
			$r['is_use'] =$r['is_use'] ? '是' : '否';
			if($r['float_fixed'] == 1)
			{
				$r['float_fixed'] = '浮动';
			}else if($r['float_fixed'] == 2)
			{
				$r['float_fixed'] = '固定';
			}
			//$r['is_use'] = ? : 
			$this->addItem($r);
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'animation';
		echo json_encode($this->db->query_first($sql));
	}
	function detail()
	{
		if($this->input['latest'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'animation ORDER BY id LIMIT 1';
		}
		else
		{
			if(!$this->input['id'])
			{
				$this->errorOutput(NOID);
			}
			$sql = 'SELECT * FROM '.DB_PREFIX.'animation WHERE id = '.intval($this->input['id']);
		}
		$r = $this->db->query_first($sql);
		$r['para'] = unserialize($r['para']);
		$r['form_style'] = unserialize($r['form_style']);
		$this->addItem($r);
		$this->output();
	}
}
$ouput= new adv_animation();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();