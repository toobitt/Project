<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','road_home_timeline');
class getWeiboByHomeTimeline extends cronBase
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
				'name' => '获取微博(关注人)',
				'brief' => '获取微博(关注人)',
				'space' => '600',	//运行时间间隔，单位秒
				'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	//用获取关注人接口取用户微博	
	//从队列中取出用户并关注此用户 从home_timeline接口获取微博  
	public function  get_weibolist()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."queue_user ORDER BY last_time ASC LIMIT 0,1";
		$user = $this->db->query_first($sql);
		if(!empty($user))
		{
            $sql = "DELETE FROM " . DB_PREFIX ."queue_user WHERE id = " . $user['id'];
            $this->db->query($sql);
            
            //取出用户表中最大的since_id
            $sql = "SELECT since_id FROM ".DB_PREFIX."user WHERE group_id =".$user['group_id'] ." ORDER BY since_id DESC LIMIT 1";
            $max_since_id_user = $this->db->query_first($sql);
            $max_since_id = $max_since_id_user['since_id'];
            print_r($max_since_id);
            //取出用户表中最大的since_id
                 		    	
			$sql = "SELECT * FROM " . DB_PREFIX ."plat_token WHERE type = " . $user['group_id'] ." ORDER BY lastusetime ASC ";
			$ret = $this->db->query($sql);
			while($info = $this->db->fetch_array($ret))
			{
				//先用授权人账号关注这个用户
				$friend = $this->share->friendships_create($info['appid'],$info['platid'],$user['uid'],$user['name'],$info['plat_token']);
				print_r($friend);
				//或者授权人关注人的微博
				$data = $this->share->get_home_timeline($info['appid'], $info['platid'],$info['plat_token'], $max_since_id , 1, 200);
				print_r($data);
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
					
					//循环取出微博列表中所有的用户id		
					$user_ids = array();
					foreach ($data as $kk => $vv) {
						$user_ids[] = $vv['uid'];
					}	
					//查询出存在的用户id
					$user_ids = implode("','", (array)$user_ids);
//					print_r($user_ids);
					$sql = "SELECT * FROM ".DB_PREFIX."user WHERE group_id = ".$user['group_id']." AND uid IN('".$user_ids."') AND status = 1";
					$q  = $this->db->query($sql);
					$exists_user = array();
					while($row = $this->db->fetch_array($q)) {
						$exists_user[$row['uid']] = $row;
					}
					$exists_user_ids = array_keys($exists_user);	
					//查询出存在的用户id	
					
					$data_num = count($data);
					for ($kk = $data_num - 1; $kk >= 0 ;$kk--) {
//					foreach($data as $kk=>$vv)
//					{	
						$vv = $data[$kk];					
						if (!$vv['id'] || !in_array($vv['uid'], $exists_user_ids)) {
							continue;
						}
						
						$current_user = $exists_user[$vv['uid']];
						$since_id = $current_user['since_id'];
						
						if ($vv['id'] <= $since_id) {
							continue;
						}
						
                        if ($vv['id'] > $since_id) {
                           $exists_user[$vv['uid']]['since_id'] = $since_id = $vv['id'];
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
								"(".$current_user['id'].",'".addslashes($vv['text'])."','".addslashes(json_encode($img))."','".addslashes(json_encode($vv['picsize']))."','".addslashes($vv['from'])."','".$vv['created_at']."','".$vv['screen_name']."','".$this->get_status_setting('create')."')";
						$this->db->query($sql);								
						$id = $this->db->insert_id();
						$sql = "UPDATE " . DB_PREFIX ."road SET orderid = " . $id ." WHERE id = " . $id;
						$this->db->query($sql);							
						if ($since_id) {
	                        $this->db->update_data('since_id='. $since_id, 'user', 'id=' . $current_user['id']);
	                        $this->db->update_data('since_id='.$since_id, 'queue_user', 'original_id = ' . $current_user['id']);
						}							
					}
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
        if ($info) {   
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
$out = new getWeiboByHomeTimeline();
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
