<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:  $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class getMsgApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_msg()
	{
		if (!$this->user['user_id'])
		{ 
			$this->errorOutput(NEED_LOGIN);//用户未登录
		}
		$this->setXmlNode("Messages","Message");
		$ssql = 'select sid,rtime from ' . DB_PREFIX . 'pm_user where uid = ' . $this->user['user_id'] . ' and new = 1';
		
		$queryid = $this->db->query($ssql);
		$u_sid = array();
		while (false != ($rows = $this->db->fetch_array($queryid)))
		{
			$u_sid[$rows['sid']] = $rows;
		}
		
		if (!empty($u_sid))
		{
			$sids = array_keys($u_sid);
			$sids = implode(',',$sids);
				
			if (!intval($this->input['all']))
			{
				$case = ' and pm.stime > case ';
				foreach($u_sid as $sid => $info)
				{
					$case .= ' when pm.sid = ' . $sid . ' then ' . $info['rtime'] . ' ';
				}
				$case .= ' end ';
			}
			else
			{
				$case = '';
			}
			$sql = 'select pm.*,s.sessionId,pm_s.type,pm_s.ids,pm_u.new from ' . DB_PREFIX . 'pm pm left join ' . DB_PREFIX . 's_pm s on pm.sid=s.sid left join ' . DB_PREFIX .'pm_session pm_s on pm.sid=pm_s.sid left join ' . DB_PREFIX . 'pm_user pm_u on pm_u.sid=pm.sid where pm.sid in(' . $sids .') and pm_u.new=1 ' . $case . ' order by pm.stime asc';
			
			$query = $this->db->query($sql);
			$message = array();
			
			while (false != ($rows = $this->db->fetch_array($query)))
			{   
				if ($rows['type'] == 0)
				{
					$fromwho = explode(',',$rows['ids']);
					$kk = array_search($this->user['user_id'],$fromwho);
					if($kk)
					{
						unset($fromwho[$kk]);
					}
					$fromwho = array_pop($fromwho);//取出发信息人的id   
				}
				else
				{
					$fromwho = '群聊';
				}
				$message[$rows['sessionId']]['fromwhotitle'] = $fromwho;
				$message[$rows['sessionId']]['rnew'] = $rows['new'];
				$message[$rows['sessionId']]['content'][] = array(
					'pid' => $rows['pid'],
					'content' => $rows['content'],
					'stime' => date("m-d H:i" , $rows['stime']),
					'cfromwho' => $rows['fromwho'],
				);
			}
			$this->addItem($message); 
			$this->output();	
		}	 
	}
	
	function get_msg_count()
	{
		if (!$this->user['user_id'])
		{ 
			$this->errorOutput(NEED_LOGIN);//用户未登录
		}
		$this->setXmlNode("Messages","Message");
		$sql = 'select sid,rtime from ' . DB_PREFIX . 'pm_user where uid = ' . $this->user['user_id'] . ' and new = 1';
		$q = $this->db->query($sql);
		$sids = array();
		while($row = $this->db->fetch_array($q))
		{
			$sids[] = $row['sid'];
		}
		$i = 0;
		$sids = array_unique($sids);
		if(!empty($sids))
		{
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "pm WHERE sid IN(" . implode(',',$sids) . ") AND fromID != " . $this->user['user_id'] . " AND rtime=0";
			$f = $this->db->query_first($sql);
			$this->addItem($f); 
			$this->output();
		}	
	}
}


$out = new getMsgApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'get_msg';
}
$out->$action();
?>