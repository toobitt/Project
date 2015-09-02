<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','weibogroup');
class getWeibo extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/share.class.php');
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->share = new share();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
				'mod_uniqueid' => MOD_UNIQUEID,
				'name' => '微博圈取微博队列',
				'brief' => '微博圈取微博队列',
				'space' => '600',	//运行时间间隔，单位秒
				'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}	
	public function  get_weibolist()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."queue_user ORDER BY last_time ASC LIMIT 0,1";
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
								foreach ($vv['original_pic'] as $k => $v)
								{	
									$img[$k]['host'] = $v . "/";
									$img[$k]['dir'] = '';
									$img[$k]['filepath'] = '';
									$img[$k]['filename'] = '';
								}						
							}
							else 
							{
								$vv['original_pic'] = explode('large',$vv['original_pic']);
								$img[0]['host'] = $vv['original_pic'][0];
								$img[0]['dir'] = '';
								$img[0]['filepath'] = '';
								$img[0]['filename'] = $vv['original_pic'][1];
							}
						}
						if(empty($vv['video']))
						{
							$vv['video'] = array();
						}
						if(empty($vv['music']))
						{
							$vv['music'] = array();
						}
						$source_info = array();
						if(!empty($vv['retweeted_status']))
						{
							$source_info = array(
								'weibo_id' => $vv['retweeted_status']['id'],
								'text' => $vv['retweeted_status']['text'],
								'comefrom' => $vv['retweeted_status']['from'],
								'fromurl' => $vv['retweeted_status']['fromurl'],
								'video' => $vv['retweeted_status']['video'],
								'music' => $vv['retweeted_status']['music'],
								'picsize' => $vv['picsize'],
								'create_time' => $vv['retweeted_status']['created_at'],
								'uname' => $vv['retweeted_status']['name'],
								'nick' => $vv['retweeted_status']['screen_name'],
								'avatar' => array('host'=>$vv['retweeted_status']['avatar'],'dir' =>'','filepath' => '','filename' => ''),
								'reposts_count' => $vv['retweeted_status']['reposts_count'],
								'comments_count' => $vv['retweeted_status']['comments_count'],					
							);
							if(!empty($vv['retweeted_status']['original_pic']))
							{
								if(is_array($vv['retweeted_status']['original_pic']))
								{
									foreach ($vv['retweeted_status']['original_pic'] as $k => $v)
									{	
										$source_info['img'][$k]['host'] = $v . "/";
										$source_info['img'][$k]['dir'] = '';
										$source_info['img'][$k]['filepath'] = '';
										$source_info['img'][$k]['filename'] = '';
									}						
								}
								else 
								{
									$vv['retweeted_status']['original_pic'] = explode('large',$vv['retweeted_status']['original_pic']);
									$source_info['img'][0]['host'] = $vv['retweeted_status']['original_pic'][0];
									$source_info['img'][0]['dir'] = '';
									$source_info['img'][0]['filepath'] = '';
									$source_info['img'][0]['filename'] = $vv['retweeted_status']['original_pic'][1];
								}
							}
						}
						//取出用户的圈子
						$circle_id = '';
						if(!empty($user['circle_id'])){
							$circle_id = unserialize($user['circle_id']);
							if($circle_id){
								$circle_id = array_keys($circle_id);
							}
						}
						$sql = "INSERT INTO " . DB_PREFIX. "weibo(uid, weibo_id, type, text,img,picsize,video,music,comefrom,fromurl,source_info,create_time,uname,nick,reposts_count,comments_count,distribute_count,status)VALUES 
						(".$user['original_id'].", '".$vv['id'] ."', ".$user['group_id'].", '".addslashes($vv['text'])."', '".addslashes(serialize($img))."', '".addslashes(serialize($vv['picsize']))."', '".addslashes(serialize($vv['video']))."', '".addslashes(serialize($vv['music']))."','".$vv['from']."','".$vv['fromurl']."','".addslashes(serialize($source_info))."', '".$vv['created_at']."','".$vv['name']."','".$vv['screen_name']."','".$vv['reposts_count']."', '".$vv['comments_count']."',".count($circle_id).",'".$this->get_status_setting('create') ."')";
						$this->db->query($sql);
							
						//写入微博分发表
						$weibo_id = $this->db->insert_id();
						if(!empty($circle_id))
						{			
							$sql = "INSERT INTO " . DB_PREFIX ."weibo_circle (uid, circle_id, weibo_id, create_time ) VALUES ";
							$space = '';
							foreach($circle_id as $k => $v)
							{
								$sql .= $space . "(".$user['original_id'].", ".$v.",".$weibo_id.",".$vv['created_at'].")";
								$space = ',';	
							}
				       	 	$this->db->query($sql);
						}
					}
					if ($since_id) {
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
