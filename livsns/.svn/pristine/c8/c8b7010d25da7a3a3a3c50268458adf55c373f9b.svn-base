<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once('./lib/infor.class.php');
require_once('./lib/check.class.php');
class inforApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		$this->info = new info();
		$this->check = new check();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		//获取访谈信息时更新用户的在线时间
		if ($this->input['id'] && $this->user['user_id'])
		{
			$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>intval(urldecode($this->input['id']))));
		}
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$this->setXmlNode('interview','item');
		$message_info = $this->info->message_info($this->get_condition(),'',$offset,$count);
		foreach ($message_info as $key=>$val)
		{
			$this->addItem($val);
		}
		$this->output();
	}
	function get_condition()
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
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."interview i WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

}
$out = new inforApi();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'show';
}
$out->$action();
?>