<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/interview_old.class.php';
define('MOD_UNIQUEID','index_old');//模块标识
class index_old extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->int = new interviewInfo_old();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$condition = $this->get_condition();
		$order = '';
		$data = $this->int->show($condition,'',$offset,$count);
		foreach ($data as $key=>$val)
		{
			$this->addItem($val);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if ($this->input['id'])
		{
			$condition .= ' AND i.id = '.intval(urldecode($this->input['id']));
		}
		if ($this->input['type'])
		{
			switch (intval(urldecode($this->input['type'])))
			{
				case 1:$condition .= ' AND i.start_time<'.TIMENOW.' AND i.end_time>'.TIMENOW;break;
				case 2:$condition .= ' AND i.start_time>'.TIMENOW;break;
				case 3:$condition .= ' AND i.is_lishi=1';break;
				
			}
			
		}
		if ($this->input['title'])
		{
			
			$condition .= ' AND i.title LIKE "%'.intval(urldecode($this->input['title'])).'%"';
		}
		if ($this->input['description'])
		{
			
			$condition .= ' AND i.description LIKE "%'.intval(urldecode($this->input['description'])).'%"';
		}
		if ($this->input['start_time'])
		{
			$condition .= ' AND i.start_time >'.strtotime(intval(urldecode($this->input['start_time'])));
		}
		if ($this->input['end_time'])
		{
			$condition .= ' AND i.end_time <'.strtotime(intval(urldecode($this->input['end_time'])));
			
		}
		return $condition;
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."interview i WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function detail()
	{
		
	}
}
$out = new index_old();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'show';
}
$out->$action();