<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_customer');//模块标识
class adv_user extends adminReadBase
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
			$orderby = ' ORDER BY create_time DESC ';
		}
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'advcustomer WHERE 1'.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('adv_customer','adv_customer');
		while($r = $this->db->fetch_array($q))
		{
			//$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();

	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'advcustomer '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND user_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		if($this->input['start_time'])
		{
			if($this->input['end_time'])
			{
				$condition .= 'AND create_time  between '.strtotime(urldecode($this->input['start_time'])).' AND '.strtotime(urldecode($this->input['end_time']));
			}
			else
			{
				$condition .= 'AND create_time  > '.strtotime(urldecode($this->input['start_time']));
			}
		}
		else if($this->input['end_time'])
		{
			$condition .= 'AND create_time  < '.strtotime(urldecode($this->input['end_time']));
		}
		return $condition;
	}
	function detail()
	{
		
		if(!$this->input['id'])
		{
			return;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'advcustomer WHERE id = '.intval($this->input['id']);
		
		$r = $this->db->query_first($sql);
		$this->addItem($r);
		$this->output();
		
	}
}
$ouput= new adv_user();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();