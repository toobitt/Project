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
class contentApi extends BaseFrm
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
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$order = $this->input['order'] ? $this->input['order'] : ' create_time ';
		$sort = $this->input['sort'] ? $this->input['sort'] : ' DESC ';
		$orderby = ' ORDER BY '.$order.$sort;
		$this->setXmlNode('message','item');
		$content_info = $this->info->content_info($this->get_condition(),$orderby,$offset,$count);	
		foreach ($content_info as $key=>$val)
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
		
		if ($this->input['question'])
		{
			
			$condition .= ' AND question LIKE "%'.urldecode($this->input['question']).'%"';
		}
		if ($this->input['user_name'])
		{
			
			$condition .= ' AND user_name LIKE "%'.urldecode($this->input['user_name']).'%"';
		}
		if ($this->input['state'])
		{
			$condition.= ' AND state='.intval(urldecode($this->input['state']));
		}	
		return $condition;
	}
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."records  WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

}
$out = new contentApi();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'show';
}
$out->$action();
?>