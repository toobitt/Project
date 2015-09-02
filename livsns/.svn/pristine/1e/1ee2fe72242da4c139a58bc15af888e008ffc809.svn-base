<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH.'lib/interview_old.class.php';
define('MOD_UNIQUEID','interviewcon_old');//模块标识
class content_old extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->int = new interviewInfo_old();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$offset = $this->input['offset']?intval($this->input['offset']):0;
		$count = $this->input['count']?intval($this->input['count']):10;
		$order = $this->input['order'] ? $this->input['order'] : ' reply_time ';
		$sort = $this->input['sort'] ? ' '.$this->input['sort'] : ' ASC ';
		$orderby = ' ORDER BY '.$order.$sort;
		$this->setXmlNode('message','item');
		$content_info = $this->int->content_info($this->get_condition(),$orderby,$offset,$count);
		$this->addItem($content_info);
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
		if (isset($this->input['state']))
		{
			/*
			if ($this->input['interview_id'])
			{
				$state = intval($this->input['state']);
				$interview_id = intval($this->input['interview_id']);
				if ($state==0)
				{
					$condition.= ' AND state=0';
					//$condition.= ' AND state=0 ORDER BY id ASC';
				}
				else if($state == 2)
				{
					$role = $this->int->role($this->user['user_id'], $interview_id);
					if($role==3)
					{
						$condition .= ' AND state != 0 AND state != 3 AND guests_id=' . $this->user['user_id'];
						//$condition .= ' AND state != 0 AND state != 3 AND guests_id=' . $this->user['user_id'] . ' ORDER BY audit_time ASC';
					}else{
						$condition .= ' AND state != 0 AND state != 3';
						//$condition .= ' AND state != 0 AND state != 3 ORDER BY audit_time ASC';
					}
				}
				else if($state == 3)
				{
					$condition .= ' AND state=3 ';
				}
			}else 
			{
			*/
				$condition.= ' AND state='.intval($this->input['state']);
			//}
		}
		if ($this->input['time'])
		{
			$condition.= ' AND reply_time >'.intval($this->input['time']);
		}	
		return $condition;
	}
	
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."records  WHERE 1 AND reply_record_id = 0 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	function detail()
	{
		
	}

}
$out = new content_old();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'show';
}
$out->$action();
?>