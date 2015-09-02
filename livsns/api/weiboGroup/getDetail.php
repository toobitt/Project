<?php
require_once ('./global.php');
define('MOD_UNIQUEID','weibo');
class getDetail extends outerReadBase
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
		if(!$weibo_id)
		{		
			$this->errorOutput(NOWBID);
		}
		if(!$type)
		{
			$this->errorOutput(NOTYPE);
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE type = " . $type ." ORDER BY lastusetime ASC ";
		$ret = $this->db->query($sql);
		$detail = '';
		while($row = $this->db->fetch_array($ret))
		{
			$detail = $this->share->get_detail($row['platid'],$row['plat_token'],$weibo_id);
			if($detail['error'])
			{
				$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = "  . TIMENOW ." WHERE id = " . $row['id'];
				$this->db->query($sql);
				continue;
			}
			if(!is_array($detail))
			{
				continue;
			}	
					
			if(!empty($detail['original_pic']))
			{
				if(is_array($detail['original_pic']))
				{
					foreach ($detail['original_pic'] as $k => $v)
					{	
						$detail['img'][$k]['host'] = $v . "/";
						$detail['img'][$k]['dir'] = '';
						$detail['img'][$k]['filepath'] = '';
						$detail['img'][$k]['filename'] = '';
					}						
				}
				else 
				{
					$detail['original_pic'] = explode('large',$detail['original_pic']);
					$detail['img'][0]['host'] = $detail['original_pic'][0];
					$detail['img'][0]['dir'] = '';
					$detail['img'][0]['filepath'] = '';
					$detail['img'][0]['filename'] = $detail['original_pic'][1];
				}
			}
			if(!empty($detail['retweeted_status']))
			{
				if(!empty($detail['retweeted_status']['original_pic']))
				{
					if(is_array($detail['retweeted_status']['original_pic']))
					{
						foreach ($detail['retweeted_status']['original_pic'] as $k => $v)
						{	
							$detail['retweeted_status']['img'][$k]['host'] = $v . "/";
							$detail['retweeted_status']['img']['dir'] = '';
							$detail['retweeted_status']['img']['filepath'] = '';
							$detail['retweeted_status']['img']['filename'] = '';
						}						
					}
					else 
					{
						$detail['retweeted_status']['original_pic'] = explode('large',$detail['retweeted_status']['original_pic']);
						$detail['retweeted_status']['img'][0]['host'] = $detail['retweeted_status']['original_pic'][0];
						$detail['retweeted_status']['img'][0]['dir'] = '';
						$detail['retweeted_status']['img'][0]['filepath'] = '';
						$detail['retweeted_status']['img'][0]['filename'] = $detail['retweeted_status']['original_pic'][1];
					}
				}
				$detail['retweeted_status']['avatar'] = array('host' => $detail['avatar'],'dir' => '','filepath' => '','filename' => '');
				$detail['retweeted_status']['picsize'] = $detail['picsize'];
			}
			unset($detail['original_pic'],$detail['retweeted_status']['original_pic']);
			$detail['avatar'] = array('host' => $detail['avatar'],'dir' => '','filepath' => '','filename' => '');			
			break;
		}		
		if(isset($detail['error']))
		{
			$this->errorOutput($detail['error']);
		}
		$this->addItem($detail);
		$this->output();
	}
}
$out = new getDetail();
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