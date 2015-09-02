<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class getComment extends outerReadBase
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
		$weibo_id = $this->input['weibo_id'];
		$type = $this->input['type'];
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 20;
		if(!$weibo_id)
		{		
			$this->errorOutput(NOWBID);
		}
		if(!$type)
		{
			$this->errorOutput(NOTYPE);
		}
		if($type == 3)
		{
			$since_id = $this->input['since_id'];
			$since_time = $this->input['since_time'];
			$page = 1;
		}
		else if ($type == 1)
		{
			$since_id = '';
			$since_time = '';
			$page = ceil($offset/$count) + 1 ;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE type = " . $type ." ORDER BY lastusetime ASC ";
		$ret = $this->db->query($sql);
		$comments = '';
		while($row = $this->db->fetch_array($ret))
		{
			$comments = $this->share->get_comment($row['platid'],$row['plat_token'],$weibo_id,$since_id,$page,$count,$since_time);
			if($comments['error'])
			{
				$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = "  . TIMENOW ." WHERE id = " . $row['id'];
				$this->db->query($sql);
				continue;
			}
			
			if(!is_array($comments))
			{
				continue;
			}
			
			foreach ($comments as $kk => $vv)
			{
				if(!empty($vv['image']))
				{
					if(is_array($vv['image']))
					{
						foreach ($vv['image'] as $k => $v)
						{	
							$comments[$kk]['img'][$k]['host'] = $v . "/";
							$comments[$kk]['img'][$k]['dir'] = '';
							$comments[$kk]['img'][$k]['filepath'] = '';
							$comments[$kk]['img'][$k]['filename'] = '';
						}						
					}
					else 
					{
						$vv['image'] = explode('large',$vv['image']);
						$comments[$kk]['img'][0]['host'] = $vv['image'][0];
						$comments[$kk]['img'][0]['dir'] = '';
						$comments[$kk]['img'][0]['filepath'] = '';
						$comments[$kk]['img'][0]['filename'] = $vv['image'][1];
					}
				}
				if(!empty($vv['status']))
				{
					if(!empty($vv['status']['image']))
					{
						if(is_array($vv['status']['image']))
						{
							foreach ($vv['status']['image'] as $k => $v)
							{	
								$comments[$kk]['status']['img'][$k]['host'] = $v . "/";
								$comments[$kk]['status']['img'][$k]['dir'] = '';
								$comments[$kk]['status']['img'][$k]['filepath'] = '';
								$comments[$kk]['status']['img'][$k]['filename'] = '';
							}						
						}
						else 
						{
							$vv['status']['image'] = explode('large',$vv['status']['image']);
							$comments[$kk]['status']['img'][0]['host'] = $vv['status']['image'][0];
							$comments[$kk]['status']['img'][0]['dir'] = '';
							$comments[$kk]['status']['img'][0]['filepath'] = '';
							$comments[$kk]['status']['img'][0]['filename'] = $vv['status']['image'][1];
						}
					}
					if($comments[$kk]['status']['user'])
					{
						$comments[$kk]['status']['user']['avatar'] = array('host' => $comments[$kk]['status']['user']['avatar'],'dir' =>'','filepath' => '','filename' => '');
					}
				}
				if($comments[$kk]['user'])	
				{
					$comments[$kk]['user']['avatar'] = array('host' => $comments[$kk]['user']['avatar'],'dir' => '','filepath' => '','filename' => '');
				}
				unset($comments[$kk]['image'],$comments[$kk]['status']['image']);			
			}
			break;
		}	
		if(isset($comments['error']))
		{
			$this->errorOutput($comments['error']);
		}
		$this->addItem($comments);
		$this->output();
	}
}
$out = new getComment();
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