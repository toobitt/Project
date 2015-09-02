<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class GetCircleUser extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function detail(){}
	public function count(){}	
	public function show()
	{
		$circleId = intval($this->input['circle_id']);
		if($circleId)
		{
			$sql = "SELECT user_id FROM ".DB_PREFIX."user_circle WHERE circle_id = " . $circleId;
			$q = $this->db->query($sql);
			$userIds = array();
			while($row = $this->db->fetch_array($q))
			{
				$userIds[] = $row['user_id'];
			}
			$userIds = implode(',',$userIds);
		}
		if($userIds)
		{
			$cond = " AND id IN(".$userIds.")";
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 20 ;	
		$data_limit = " LIMIT " . $offset ."," . $count;		
		$sql = "SELECT user_info,name,avatar FROM ".DB_PREFIX."user WHERE status = 1" . $cond . $data_limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['user_info'] = unserialize($row['user_info']);
			if($row['user_info'])
			{
				$row['user_info']['created_at'] = date('Y-m-d H:i:s',$row['user_info']['created_at']);		
			}
			else
			{
				$row['user_info']['name'] = $row['name'];
				$row['user_info']['avatar'] = unserialize($row['avatar']);					
			}
			$this->addItem($row['user_info']);
		}	
		$this->output();			
	}
}
$out = new GetCircleUser();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>