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
class picApi extends BaseFrm
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
		if ($this->input['interview_id'] && $this->user['user_id'])
		{
			$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>intval($this->user['user_id']),
			'interview_id'=>intval(urldecode($this->input['interview_id']))));
		}
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$this->setXmlNode('pic','item');
		
		$pic_info = $this->info->pic_info($this->get_condition(),'',$offset,$count);
		foreach ($pic_info as $key=>$val)
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
			$condition .= ' AND id = '.intval(urldecode($this->input['id']));
		}
		if ($this->input['interview_id'])
		{
			$condition .= ' AND interview_id = '.intval(urldecode($this->input['interview_id']));
		}
		
		if ($this->input['name'])
		{
			
			$condition .= ' AND name LIKE "%'.intval(urldecode($this->input['name'])).'%"';
		}
		if ($this->input['show_pos'])
		{
			
			$condition .= ' AND show_pos = '.intval(urldecode($this->input['show_pos']));
		}
		if ($this->input['file_type'])
		{
			
			$condition .= ' AND file_type = '.urldecode($this->input['file_type']);
		}
		
		return $condition;
	}
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."files  WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

}
$ouput= new picApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>