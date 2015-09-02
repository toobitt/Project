<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class getUser extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/share.class.php');
		$this->share = new share();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function detail(){}
	public function count(){}	
	public function show()
	{
		$uname = $this->input['uname'];
		$type = $this->input['type'];
		if(!$uname)
		{		
			$this->errorOutput(NONAME);
		}
		if(!$type)
		{
			$this->errorOutput(NOTYPE);
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE type = " . $type ." ORDER BY lastusetime ASC ";
		$plat_token = $this->db->query($sql);
		while($row = $this->db->fetch_array($plat_token))
		{
			$user_info = $this->share->get_user('',$uname,$row['plat_token']);
			$user_info = $user_info[0];
			if($user_info['error'])
			{
				$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = "  . TIMENOW ." WHERE id = " . $row['id'];
				$this->db->query($sql);
				continue;				
			}
			$user_info['avatar'] = array('host' => $user_info['avatar'],'dir' => '','filepath' => '','filename' => '');
			break;
		}	
		if(isset($user_info['error']))
		{
			$this->errorOutput($user_info['error']);
		}			
		$this->addItem($user_info);
		$this->output();
	}
}
$out = new getUser();
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