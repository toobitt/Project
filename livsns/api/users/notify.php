<?php
/***************************************************************************

*
* $Id: notify.php 17947 2013-02-26 02:57:46Z repheal $
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
			'user_id' => $this->input['user_id'],
			'content' => urldecode($this->input['content']),
			'type' => intval($this->input['type']),
		);

		$user_id = explode(',',$message['user_id']);
		
		$sql = "insert into " . DB_PREFIX . "notify(content,member_id,type,notify_time) value ";
		$sp = '';
		for($i =0;$i<count($user_id);$i++)
		{
			$sql .= $sp . "('".$message['content']."',".$user_id[$i].",".$message['type'].",". TIMENOW.")";
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
		$type = intval($this->input['type']);
		
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
			
			$sql = "update " . DB_PREFIX . "notify set is_read = 1 where id IN (" . $ids . ")";
			$this->db->query($sql);
			$arr = $this->db->query_first('select content from ' . DB_PREFIX . 'notify where id = ' . $nnid);
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
		$message=array
		(
			'user_id' => intval($this->input['user_id']),
			'type' => intval($this->input['type']),
		);
		if( $message['user_id'] < 0 )
		{
			return ;
		}
		else
		{
			//获得已读信息,-1获取该用户全部的已读信息，否则按照类型获取已读信息
			if($message['type']==-1)
			{
				$sql = "select notify_id,type from ".DB_PREFIX."notify_read where member_id=".$message['user_id'] ;
			}	
			else
			{
				$sql = "select notify_id,type from ".DB_PREFIX."notify_read where  member_id=".$message['user_id']." and type = ".$message['type'];
			} 
				
			$result = $this->db->query($sql);
			$read_info = array();
			while($row = $this->db->fetch_array($result))
			{
				$read_info[$row['notify_id']] = 1;
			}
			//获取全部信息,
			if($message['type']==-1)
			{
				$sql = "select * from ".DB_PREFIX."notify where member_id = ".$message['user_id'] . '  order by notify_time desc ' . ' limit ' . $pp*$count . ' , ' . $count;
			}	
			else
			{
				if(!$message['type']==0)
				{
					$extion = " and member_id in (".$message['user_id'].")";
				}
				$sql = "select * from ".DB_PREFIX."notify where type = ".$message['type'].$extion . ' order by notify_time desc ' . ' limit ' . $pp*$count . ',' . $count;
			}
			$result=$this->db->query($sql);
			$info = array();
			//得到全部信息，并且标注已读和未读
			$this->setXmlNode('notifys','notify'); 
			while($row=$this->db->fetch_array($result))
			{
				 
				if($read_info[$row['id']])
				{
					$row['is_read'] =$read_info[$row['id']];
				}
				else 
				{
					$row['is_read'] =0;
				}
				$this->addItem($row);
			}
			$this->output();
			
		}
	}

	public function get_unread()
	{
		$count = intval($this->input['count']) ? intval($this->input['count']) : 0;
		$page = intval($this->input['pp']?$this->input['pp']:0);
		$end = "";
		$offset = $page * $count;
		
		$user_id = intval($this->input['user_id']);
		$type = intval($this->input['type']);
		if(!$user_id)
		{
			return ;
		}
		
		//获得已读信息,-1获取该用户全部的已读信息，否则按照类型获取已读信息
		$extra = '';
		if( $type > 0 )
		{
			$extra = " AND type = " . $type;
		} 
		
		if($type != 0)
		{
			$extra .=" AND member_id=" . $user_id;
		}
	
		if($count)
		{
			$extra .= " ORDER BY notify_time DESC LIMIT ".$offset.",".$count;
		}		
			
		$sql = "select * from ".DB_PREFIX."notify where is_read = 0" . $extra;
		$q = $this->db->query($sql);
		
		$this->setXmlNode('notifys','notify'); 
		while($row=$this->db->fetch_array($q))
		{
			$row['content'] = unserialize($row['content']); 
			$this->addItem($row);
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
		$type = intval($this->input['type']);
		if(!$user_id)
		{
			return ;
		}
		
		//获得已读信息,-1获取该用户全部的已读信息，否则按照类型获取已读信息
		$extra = '';
		if( $type > 0 )
		{
			$extra = " AND type = " . $type;
		} 
		
		if($type != 0)
		{
			$extra .=" AND member_id=" . $user_id;
		}	
			
		$sql = "select * from ".DB_PREFIX."notify where is_read = 1" . $extra;
		$q = $this->db->query($sql);
		
		$this->setXmlNode('notifys','notify'); 
		while($row=$this->db->fetch_array($q))
		{
			if( $row['type'] > 3 )
 			{ 
				$row['content'] = unserialize($row['content']); 
			}  
			$this->addItem($row);
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
			'type' => intval($this->input['type']),
		);
		$user_id = $this->input['user_id'];
		$type = intval($this->input['type']);
		$state = $this->input['state']?1:0; //1---已读总数 //0---未读总数，默认为未读
		
		
		if(!$user_id)
		{
			return ;
		}
		else
		{
			switch($type)
			{
				case -1:
					if($state)
					{
						$extra = " AND is_read = 1";
					}
					else 
					{
						$extra = " AND is_read = 0";
					}
					$sql = "select count(*) as total from ".DB_PREFIX."notify where member_id = " . $user_id . $extra;
					$f = $this->db->query_first($sql);
					$total = $f['total'];
					break;
				case 0:
					if($state)
					{
						$extra = " AND is_read = 1";
					}
					else 
					{
						$extra = " AND is_read = 0";
					}
					$extra = " AND type=0";
					$sql = "select count(*) as total from ".DB_PREFIX."notify where member_id = " . $user_id . $extra;
					$f = $this->db->query_first($sql);
					$total = $f['total'];
					break;
				default:
					if($state)
					{
						$extra = " AND is_read = 1";
					}
					else 
					{
						$extra = " AND is_read = 0";
					}
					$extra = " AND type=" . $type;
					$sql = "select count(*) as total from ".DB_PREFIX."notify where member_id = " . $user_id . $extra;
					$f = $this->db->query_first($sql);
					$total = $f['total'];
					break;
					break;
				
			}
			$this->setXmlNode('totals','total');
			$this->addItem($total);
			$this->output();
		}		
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