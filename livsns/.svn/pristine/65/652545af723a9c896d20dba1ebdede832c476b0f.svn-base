<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class UpdateUserCircle extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql = "SELECT id,circle_id FROM ".DB_PREFIX."user WHERE 1";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$circle_id = '';
			$row['circle_id'] = unserialize($row['circle_id']);
			$circle_id = array_keys($row['circle_id']);
			if($circle_id && is_array($circle_id))
			{
				foreach($circle_id as $k => $v)
				{
					$sql = "SELECT * FROM ".DB_PREFIX."user_circle WHERE user_id = ".$row['id']." AND circle_id = " . $v;
					if(!$this->db->query_first($sql))
					{
						$sql = "INSERT INTO ".DB_PREFIX."user_circle(user_id,circle_id) VALUES(".$row['id'].",".$v.")";
						$this->db->query($sql);
						echo "用户id：".$row['id'] . ",圈子id:".$v."<br/>";
					}
				}
			}
		}	
	}
}
$out = new UpdateUserCircle();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
$out->$action(); 
?>