<?php
/***************************************************************************

*
* $Id: notify.php 17897 2013-02-25 03:41:13Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class notifyApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	//插入通知信息
	//@return
	public function send()
	{
		if (!$this->input['content'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$message = array
		(
			'content' => urldecode($this->input['content']),
			'type' => trim($this->input['type']),
		);
		$content = json_decode($message['content'],true);
		$sql = "INSERT INTO " . DB_PREFIX . "notify(content,member_id,from_id,type,notify_time) VALUE ";
		$sp = '';
		foreach($content as $k => $v)
		{
			$sql .= $sp . "('" . serialize($v) . "','" . $v['to_id'] . "','" . $v['from_id'] . "','" . $message['type'] . "'," . TIMENOW . ")";
			$sp = ',';
		}

		try{
			$query = $this->db->query($sql);
			$this->addItem(1);
		}catch(Exception $e)
		{
			$this->addItem(0);
		}
		$this->output();
	}
	//插入已读通知信息
	//@return
	public function send_read()
	{
		$t_ime =time();
		$member_id = intval($this->input['member_id']);
		$type = trim($this->input['type']);
		
		$notify_ids = urldecode($this->input['notify_id']);
		$notify_ids = explode(',',$notify_ids);
		$notify_ids = array_filter($notify_ids);
		if(!empty($notify_ids))
		{
			$sp = '';
			$ids = '';
			foreach ($notify_ids as $n_id)
			{	
				$ids .= $sp . $n_id;	
				$sp = ',';
				$nnid = $n_id;
			}
			
			$sql = "UPDATE " . DB_PREFIX . "notify SET is_read = 1 WHERE id IN (" . $ids . ")";
			$this->db->query($sql);
			$arr = $this->db->query_first('SELECT content FROM ' . DB_PREFIX . 'notify WHERE id = ' . $nnid);
			$arr = unserialize($arr['content']);
			$this->setXmlNode('Notices','Notice');
			if(is_array($arr))
			{
				$this->addItem($arr);
				$this->output();
			}
		}	
		else
		{
			$this->errorOutput(OBJECT_NULL); 
		}
		
	}
	//获得用户已读和未读的信息
	public function get()
	{
		$count = intval($this->input['count']) ? intval($this->input['count']) : 50;
		$pp = intval($this->input['pp']) ? intval($this->input['pp']) : 0; 
		$message = array
		(
			'user_id' => intval($this->input['user_id']),
			'type' => trim($this->input['type']),
		);
		if( $message['user_id'] < 0 )
		{
			return ;
		}
		else
		{
			//获取全部信息
			$limit = ' ORDER BY notify_time DESC ' . ' LIMIT ' . $pp . ' , ' . $count;
			$extion = '';
			if($message['type'] == -1)//全部
			{
				$extion = " AND member_id = " . $message['user_id'];
			}
			else
			{
				if($message['type'] != 0)//就不是系统通知
				{
					$extion = " AND member_id IN (" . $message['user_id'] . ")";
				}
				$extion .= " AND type='" . $message['type'] . "'";
			}
			
			$sql = "SELECT * FROM ".DB_PREFIX."notify WHERE 1 " . $extion . $limit;
			$result=$this->db->query($sql);
			$info = array();
			
			$from_id = array();
			while($row=$this->db->fetch_array($result))
			{
				$from_id[] = $row['from_id'];
				$info[] = $row;
			}
			$from_id = array_unique($from_id);
			$user_id = implode(',',$from_id);

			include_once (ROOT_PATH . 'lib/class/member.class.php');
			$member_obj = new member();
			$member = $member_obj->getMemberById($user_id);
			$member = $member[0];
			if(empty($member))
			{
				$member = array();
			}
			//得到全部信息，并且标注已读和未读
			$this->setXmlNode('notifys','notify');
			foreach($info as $k => $v)
			{
				$v['content'] = unserialize($v['content']);
				$v['from'] = array(
					'from_name' => $member[$v['from_id']]['nick_name'],
					'from_avatar' => $member[$v['from_id']]['avatar'],
				);
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function get_unread()
	{
		$count = intval($this->input['count']) ? intval($this->input['count']) : 10;
		$page = intval($this->input['pp']?$this->input['pp']:0);
		$end = "";
		$offset = $page * $count;
		
		$user_id = intval($this->input['user_id']);
		$type = trim($this->input['type']);
		if(!$user_id)
		{
			return ;
		}
		
		//获得已读信息,-1获取该用户全部的已读信息，否则按照类型获取已读信息
		$extra = '';
		if( $type > 0 )
		{
			$extra = " AND type = '" . $type . "'";
		} 
		
		if($type != 0)
		{
			$extra .=" AND member_id=" . $user_id;
		}
	
		if($count)
		{
			$extra .= " ORDER BY notify_time DESC LIMIT ".$offset.",".$count;
		}		
			
		$sql = "SELECT * FROM ".DB_PREFIX."notify WHERE is_read = 0" . $extra;
		$q = $this->db->query($sql);		
		$info = array();
		$from_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$from_id[] = $row['from_id'];
//			$row['content'] = unserialize($row['content']);
			$info[] = $row;
		}
		$from_id = array_unique($from_id);
		$user_id = implode(',',$from_id);

		include_once (ROOT_PATH . 'lib/class/member.class.php');
		$member_obj = new member();
		$member = $member_obj->getMemberById($user_id);
		$member = $member[0];
		if(empty($member))
		{
			$member = array();
		}
		//得到全部信息，并且标注已读和未读
		$this->setXmlNode('notifys','notify');
		foreach($info as $k => $v)
		{
			$v['content'] = unserialize($v['content']);
			$v['from'] = array(
				'from_name' => $member[$v['from_id']]['nick_name'],
				'from_avatar' => $member[$v['from_id']]['avatar'],
			);
			$this->addItem($v);
		}
		$this->output();		
	}
	
	public function get_read()
	{
		$count = intval($this->input['count']) ? intval($this->input['count']) : 0;
		$page = intval($this->input['pp']?$this->input['pp']:0);
		$end = "";
		$offset = $page * $count;
		
		$user_id = intval($this->input['user_id']);
		$type = trim($this->input['type']);
		if(!$user_id)
		{
			return ;
		}
		
		//获得已读信息,-1获取该用户全部的已读信息，否则按照类型获取已读信息
		$extra = '';
		if( $type > 0 )
		{
			$extra = " AND type = '" . $type . "'";
		} 
		
		if($type != 0)
		{
			$extra .=" AND member_id=" . $user_id;
		}	
			
		$sql = "SELECT * FROM ".DB_PREFIX."notify WHERE is_read = 1" . $extra;
		$q = $this->db->query($sql);
		$info = array();
		$from_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$from_id[] = $row['from_id'];
		//	$row['content'] = unserialize($row['content']);
			$info[] = $row;
		}
		$from_id = array_unique($from_id);
		$user_id = implode(',',$from_id);

		include_once (ROOT_PATH . 'lib/class/member.class.php');
		$member_obj = new member();
		$member = $member_obj->getMemberById($user_id);
		$member = $member[0];
		if(empty($member))
		{
			$member = array();
		}
		//得到全部信息，并且标注已读和未读
		$this->setXmlNode('notifys','notify');
		foreach($info as $k => $v)
		{
			$v['content'] = unserialize($v['content']);
			$v['from'] = array(
				'from_name' => $member[$v['from_id']]['nick_name'],
				'from_avatar' => $member[$v['from_id']]['avatar'],
			); 
			$this->addItem($v);
		}
		$this->output();
	}
	
	//获得用户的未读通知数
	public function count()
	{
		$info1 = array();
		$message=array
		(
			'user_id' => $this->input['user_id'],
			'type' => trim($this->input['type']),
		);
		$user_id = $this->input['user_id'];
		$type = trim($this->input['type']);
		$state = $this->input['state']; //1---已读总数 //0---未读总数，默认为未读
		
		$con = ' 1 ';
		if($state >= 0)
		{
			$con .= " AND is_read = " . $state ;
		}

		if(!$user_id)
		{
			return ;
		}
		else
		{
			switch($type)
			{
				case -1:
					$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."notify WHERE " . $con . " AND member_id = " . $user_id;
					$f = $this->db->query_first($sql);
					$total = $f['total'];
					break;
				case 0:
					$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."notify WHERE " . $con . " AND member_id = " . $user_id . "  AND type=0";
					$f = $this->db->query_first($sql);
					$total = $f['total'];
					break;
				default:
					$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."notify WHERE " . $con . " AND member_id = " . $user_id . " AND type='" . $type . "'";
					$f = $this->db->query_first($sql);
					$total = $f['total'];
					break;				
			}
			$this->setXmlNode('totals','total');
			$this->addItem_withkey('total',$total);
			$this->output();
		}		
	}
	
	public function delete()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NEED_LOGIN); //用户未登录
		}
		$id = $this->input['id'] ? $this->input['id'] : 0;
		if(empty($id))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "DELETE FROM " . DB_PREFIX . "notify WHERE id=" . $id;
		$this->db->query($sql);
		$this->addItem($id);
		$this->output();
	}
}
$out = new notifyApi();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>