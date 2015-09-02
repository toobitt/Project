<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','get_road_weibo');
class getWeibo extends cronBase
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
	
	
	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '取路况微博队列',
				'brief' => '取路径微博队列队列',
				'space' => '600',	//运行时间间隔，单位秒
				'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function  get_weibolist()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."queue_user ORDER BY since_time ASC LIMIT 0,1";
		$user = $this->db->query_first($sql);
		if(!empty($user))
		{
            $sql = "DELETE FROM " . DB_PREFIX ."queue_user WHERE id = " . $user['id'];
            $this->db->query($sql); 
            		    
			$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE type = " . $user['group_id'] ." ORDER BY lastusetime ASC ";
			$ret = $this->db->query($sql);
			while($info = $this->db->fetch_array($ret))
			{	
				$data = $this->share->get_user_timeline($info['appid'],$info['platid'],'',$user['name'],$info['plat_token'],$user['since_id'],1,50);
				print_r($data);
				$since_id = '';
				if(!empty($data))
				{
					if($data['error'])
					{
						if($data['error'] != 'empty')
						{
							$sql = "UPDATE " . DB_PREFIX ."plat_token SET lastusetime = "  . TIMENOW ." WHERE id = " . $info['id'];
							$this->db->query($sql);
						}
						continue;
					}
					if(!is_array($data))
					{
						continue;
					}
					$since_id = $user['since_id'];
					foreach($data as $kk=>$vv)
					{
                        if (!$vv['id'] || ($vv['id'] <= $user['since_id']))
                        {
                            continue;
                        }
                        
                        if ($vv['id'] > $since_id) {
                            $since_id = $vv['id'];
                        }
							
						$img = array();
						if(!empty($vv['original_pic']))
						{
							if(is_array($vv['original_pic']))
							{
								$img = array(
									'host' => $vv['original_pic'][0],
									'dir'  => '',
									'filepath' => '',
									'filename' => '',
								);
							}
							else 
							{
								$vv['original_pic'] = explode('large',$vv['original_pic']);
								$img = array(
									'host' => $vv['original_pic'][0],
									'dir'  => '',
									'filepath' => '',
									'filename' => $vv['original_pic'][1],
								);
							}
						}
						$sql = "INSERT INTO " . DB_PREFIX ."road(uid,content,pic,picsize,source,create_time,user_name,state)VALUES" .
								"(".$user['original_id'].",'".addslashes($vv['text'])."','".addslashes(json_encode($img))."','".addslashes(json_encode($vv['picsize']))."','".addslashes($vv['from'])."','".$vv['created_at']."','".$vv['screen_name']."','".$this->get_status_setting('create')."')";
						$this->db->query($sql);						
						$id = $this->db->insert_id();
						$sql = "UPDATE " . DB_PREFIX ."road SET orderid = " . $id ." WHERE id = " . $id;
						$this->db->query($sql);
					}
					if ($since_id)
					{
                        $this->db->update_data('since_id='. $since_id, 'user', 'id=' . $user['original_id']);
                        $this->db->update_data('since_id='.$since_id, 'queue_user', 'original_id = ' . $user['original_id']);              
					}
					break;
				}
				else
				{	
					break;		
				}
			}	
            echo '<br/>' . $user['id'] . '~~~---' . $user['name'];	
		}
		else
		{
			$this->user_queue();
            $this->get_weibolist();
		}	
	}
	
	public function  user_queue()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."user WHERE 1 AND status = 1 ORDER BY last_time ASC LIMIT 0,1";
		$info = $this->db->query_first($sql);
		if($info)
		{	
            $this->db->update_data('last_time = '.TIMENOW, 'user','id = ' . $info['id']);
            $info['original_id'] = $info['id'];
            unset($info['id']);
            $this->db->insert_data($info, 'queue_user');
            echo $info['name'] . '已放入队列';            
		}
		else
		{
			echo '暂无用户';
		}
		return true;
	}
	
}
$out = new getWeibo();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_weibolist';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
