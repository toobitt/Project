<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class getUserTimeline extends outerReadBase
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
		$name = $this->input['name'];
		$type = $this->input['type'];
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 20 ;
		if(!$name)
		{
			$this->errorOutput(NONAME);
		}
		if(!$type)
		{
			$this->errorOutput(NOTYPE);
		}
		if($type == 3)
		{
			$since_id = $this->input['since_id'];
			$page = $this->input['since_time'];	
		}
		else if($type == 1)
		{
			$since_id = '';
			$page = ceil($offset/$count) + 1 ;			
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE type = " . $type ." ORDER BY lastusetime ASC ";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$ret = $this->share->get_user_timeline($row['appid'],$row['platid'],'',$name,$row['plat_token'],$since_id,$page,$count);
			if($ret['error'])
			{
				if($ret['error'] != 'empty')
				{
					$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = "  . TIMENOW ." WHERE id = " . $row['id'];
					$this->db->query($sql);
				}
				continue;
			}
			if(!is_array($ret))
			{
				continue;
			}	

			foreach ($ret as $kk => $vv)
			{
				if(!empty($vv['original_pic']))
				{
					if(is_array($vv['original_pic']))
					{
						foreach ($vv['original_pic'] as $k => $v)
						{	
							$ret[$kk]['img'][$k]['host'] = $v . "/";
							$ret[$kk]['img'][$k]['dir'] = '';
							$ret[$kk]['img'][$k]['filepath'] = '';
							$ret[$kk]['img'][$k]['filename'] = '';
						}						
					}
					else 
					{
						$vv['original_pic'] = explode('large',$vv['original_pic']);
						$ret[$kk]['img'][0]['host'] = $vv['original_pic'][0];
						$ret[$kk]['img'][0]['dir'] = '';
						$ret[$kk]['img'][0]['filepath'] = '';
						$ret[$kk]['img'][0]['filename'] = $vv['original_pic'][1];
					}
				}
				if(!empty($vv['retweeted_status']))
				{
					if(!empty($vv['retweeted_status']['original_pic']))
					{
						if(is_array($vv['retweeted_status']['original_pic']))
						{
							foreach ($vv['retweeted_status']['original_pic'] as $k => $v)
							{	
								$ret[$kk]['retweeted_status']['img'][$k]['host'] = $v . "/";
								$ret[$kk]['retweeted_status']['img']['dir'] = '';
								$ret[$kk]['retweeted_status']['img']['filepath'] = '';
								$ret[$kk]['retweeted_status']['img']['filename'] = '';
							}						
						}
						else 
						{
							$vv['retweeted_status']['original_pic'] = explode('large',$vv['retweeted_status']['original_pic']);
							$ret[$kk]['retweeted_status']['img'][0]['host'] = $vv['retweeted_status']['original_pic'][0];
							$ret[$kk]['retweeted_status']['img'][0]['dir'] = '';
							$ret[$kk]['retweeted_status']['img'][0]['filepath'] = '';
							$ret[$kk]['retweeted_status']['img'][0]['filename'] = $vv['retweeted_status']['original_pic'][1];
						}
					}
					$ret[$kk]['retweeted_status']['picsize'] = $ret[$kk]['picsize'];
					$ret[$kk]['retweeted_status']['avatar'] = array('host' => $ret[$kk]['retweeted_status']['avatar'],'dir' => '','filepath' => '','filename' => '');
				}
				$ret[$kk]['avatar'] = array('host' => $ret[$kk]['avatar'],'dir' => '','filepath' => '','filename' => '');
				unset($ret[$kk]['original_pic'],$ret[$kk]['retweeted_status']['original_pic']);
			}
			break;
		}
		if(isset($ret['error']))
		{
			$this->errorOutput($ret['error']);	
		}		
		foreach ($ret as $kk => $vv)
		{
			$this->addItem($vv);
		}		
		$this->output();
	}
}
$out = new getUserTimeline();
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