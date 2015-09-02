<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update.php 6644 2012-05-04 02:24:58Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class updateApi extends BaseFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 发布一条点滴信息
	*/
	public function update()
	{
		include_once(ROOT_DIR . 'lib/class/settings.class.php');
		$setting = new settings();
		$result_setttings = $setting->getMark('mblog');
		if(!empty($result_setttings) && $result_setttings['state'])
		{
			$this->errorOutput('微博发布已关闭');
		}
		if (!$this->input['user_id'])
		{
			$userinfo = $this->mUser->verify_credentials($this->input['user'], $this->input['pass']);
			if(!$userinfo['id'])
			{
				$this->errorOutput(USENAME_NOLOGIN);
			}
		}
		else
		{
			$userinfo['id'] = $this->input['user_id'];
		}
		$text = urldecode($this->input['text']);
		$source = urldecode($this->input['source']);
		if (intval($source) == 1)
		{
			$source = 'iphone客户端';
		}
		elseif (intval($source) == 2)
		{
			$source = 'Android客户端';
		}
		
		
		if($this->input['id'])
		{
			$id = $this->input['id'];
			$sql = "SELECT * 
				FROM ".DB_PREFIX."status s
				LEFT JOIN ".DB_PREFIX."status_extra e ON e.status_id = s.id WHERE id = ".$id;
			$query =  $this->db->query($sql);
			
			$statusinfo = $this->db->fetch_array($query);
			$last_userid = $statusinfo['member_id'];			
			$reply_status_id = $statusinfo['reply_status_id'];
			$reply_user_id = $statusinfo['reply_user_id'];
			
			if(!$statusinfo)
			{
				$this->errorOutput(OBJECT_NULL);//对象为空
			}
			
			$sql = "SELECT transmit_count,reply_count,reply_status_id,reply_user_id
					FROM ".DB_PREFIX."status s 
					LEFT JOIN ".DB_PREFIX."status_extra e 
					ON e.status_id = s.id 
					WHERE status_id = ".$id;
			$first = $this->db->query_first($sql);
			$textF = explode("//",$text);
			
			if(empty($textF[0]))
			{
				//更新扩展表中的转发次数
				$info = array(
					'member_id' => $userinfo['id'],
					'text' => $text,
					'create_at' => TIMENOW,
					'ip' => hg_getip(),
					'location' => '地址',
					'status' => $status,
					'source' => $source,				
				);
				$statusinfo = $this->insert($info , 1);
				
				if(!$statusinfo)
				{
					return false;
				}
				else 
				{
					$transmit_count = $first['transmit_count'] + 1;
					$sql = "UPDATE ".DB_PREFIX."status_extra 
					SET 
						transmit_count = ".$transmit_count." 
					WHERE status_id = ".$id;
					$this->db->query($sql);
					$this->verify($statusinfo['text'],$statusinfo['id']);		
					if($reply_status_id)
					{	
						$sql = "UPDATE ".DB_PREFIX."status 
						SET 
							reply_status_id = ".$reply_status_id.",
							reply_user_id = ".$reply_user_id." 
						WHERE id = ".$statusinfo['id'];
						$this->db->query($sql);			
						$statusinfo['reply_status_id'] = $reply_status_id;
						$statusinfo['reply_user_id'] = $reply_user_id;
						$sql = "UPDATE ".DB_PREFIX."status_extra 
								SET 
									transmit_count = transmit_count+1 
								WHERE status_id = ".$reply_status_id;
						$this->db->query($sql);
					}
					else 
					{
						$sql = "UPDATE ".DB_PREFIX."status 
						SET 
							reply_status_id = ".$id.",
							reply_user_id = ".$last_userid." 
						WHERE id = ".$statusinfo['id'];
						$this->db->query($sql);						
						$statusinfo['reply_status_id'] = $id;
						$statusinfo['reply_user_id'] = $last_userid;
					}
					$statusinfo['transmit_count'] = $transmit_count;
					$this->setXmlNode('status','info');
					$this->addItem($statusinfo);
									
					return $this->output();	
				}
			}
			else 
			{
				//更新扩展表的回复次数
				if(mb_strlen($text) <= WORDS_NUM)
				{
					$info = $this->verifyUrlBanword($text,$userinfo['id'],$source);
					$statusinfo = $this->insert($info);
					if(!$statusinfo)
					{
						$this->errorOutput(FAILED);
					}
					else 
					{						
						$reply_count = $first['reply_count'] + 1;
						$sql = "UPDATE ".DB_PREFIX."status_extra 
						SET 
							reply_count = ".$reply_count." 
						WHERE status_id = ".$id;
						$this->db->query($sql);	
						$this->verify($statusinfo['text'],$statusinfo['id']);						
						if($reply_status_id)
						{	
							$sql = "UPDATE ".DB_PREFIX."status 
							SET 
								reply_status_id = ".$reply_status_id.",
								reply_user_id = ".$reply_user_id." 
							WHERE id = ".$statusinfo['id'];
							$this->db->query($sql);						
							$statusinfo['reply_status_id'] = $reply_status_id;
							$statusinfo['reply_user_id'] = $reply_user_id;
							$sql = "UPDATE ".DB_PREFIX."status_extra 
								SET 
									reply_count = reply_count + 1 
								WHERE status_id = ".$reply_status_id;
							$this->db->query($sql);
						}
						else 
						{
							$sql = "UPDATE ".DB_PREFIX."status 
							SET 
								reply_status_id = ".$id.",
								reply_user_id = ".$last_userid." 
							WHERE id = ".$statusinfo['id'];
							$this->db->query($sql);						
							$statusinfo['reply_status_id'] = $id;
							$statusinfo['reply_user_id'] = $last_userid;
						}
						$statusinfo['reply_count'] = $reply_count;
						$this->setXmlNode('status','info');
						$this->addItem($statusinfo);
						return $this->output();	
					}
				}
				else 
				{
					$this->errorOutput(FAILED);
				}
			}	
		}
		else
		{
			if(!$this->input['text'])
			{
				$this->errorOutput(OBJECT_NULL);//对象为空
			}
			else
			{
				if(mb_strlen($text) <= WORDS_NUM)
				{
					$info = $this->verifyUrlBanword($text,$userinfo['id'],$source);					
					$statusinfo = $this->insert($info);
					
					$this->verify($statusinfo['text'],$statusinfo['id']);						
					$this->setXmlNode('status','info');
					$this->addItem($statusinfo);
					
					//file_put_contents('f:/show.php', serialize($statusinfo));
					
					$this->output();			
				}
				else 
				{
					$this->errorOutput(FAILED);
				}		
			}
		}		
	}
	
	/**
	* 验证text中搜含是否含有话题,用户名
	* @param $text 传入话题
	* @param $id 用户ID
	*/
	private function verify($text,$id)
	{	
		$pattern = "/@([\x{4e00}-\x{9fa5}0-9A-Za-z_-]+)[\s:：,，.。\'‘’\"“”、！!]/iu";  //这里牵扯到用户名命名规则问题
		if(preg_match_all($pattern,$text,$username))
		{			
			foreach($username[1] as $value)
			{
				$name[] = $value; 
			}
			$screen_name = implode(",", $name);
			$userInfo = $this->mUser->getUserByName($screen_name);
			if($userInfo)
			{
				include_once(ROOT_PATH . 'lib/class/notify.class.php');
				$notify = new notify();
				foreach($userInfo as $key => $value)
				{
					$ids[] = $value['id'];
				}
				$sql = "INSERT IGNORE INTO ".DB_PREFIX."status_member 
				(status_id,member_id)
				VALUES";		
				foreach($ids as $value)
				{
					$sql.= "(".$id.",
					".$value."),";
					$idd.=$value.",";
				}
				$idd = rtrim($idd,",");
				$content = array('title'=>'提到我的','page_link' => SNS_MBLOG . 'atme.php');
				$notyfy_arr = $notify->notify_send($idd,serialize($content),3); //提到我的通知
				$sql = rtrim($sql,",");			
				$this->db->query($sql);
			}			
		}
		$pattern = "/#([\x{4e00}-\x{9fa5}0-9A-Za-z_-]+)[\s#]/iu";
		 //这里牵扯到话题规则问题
		if(preg_match_all($pattern,$text,$topic))
		{
			foreach ($topic[1] as $key => $value)
			{
				$topics[] = "'".strtoupper($value)."'";	
				$title[] = $value; 		
			}			
			$topics = implode(",",$topics);	
			$sql = "SELECT * FROM ".DB_PREFIX."topic WHERE title IN(".$topics.")";			
			$query = $this->db->query($sql);
			while($array = $this->db->fetch_array($query))
			{
				$topicid[] = $array['id'];
				$data[] = $array;
				$topictitle[] = $array['title'];
			}		
			if(!count($topictitle))
			{
				$newtitle = $title;				
			}
			else
			{
				if(count($topictitle)<=count($title))
				{
					$newtitle = array_diff($title,$topictitle);
				}
				else
				{
					$newtitle = array_diff($topictitle,$title);
				}
			}
			if($newtitle)
			{
				$topicids = array();
				foreach($newtitle as $value)
				{
					$value = str_replace("#","",trim($value));
					$sql = "INSERT INTO ".DB_PREFIX."topic(
				title,
				relate_count,
				status
				)
				VALUES('".$value."',1,0)";
					$this->db->query($sql);
					$topicids[] = $this->db->insert_id();
				}
				$this->updateStatusTopic($topicids);
			}
			else
			{
				foreach($data as $value)
				{
					$relate_count = $value['relate_count']+1;
					$sql ="UPDATE ".DB_PREFIX."topic 
					SET relate_count = ".$relate_count." 
					WHERE id=".$value['id'];
					$this->db->query($sql);
				}
				$this->updateStatusTopic($topicid);
			}
		}
		return $text;
	}
	
	/**
	* 更新status_topic表中数据
	* @param $topicid array 传入话题ID
	*/
	private function updateStatusTopic($topicid)
	{
		$status_id = $this->input['id'];
		if($status_id)
		{
			$sql = "INSERT IGNORE INTO ".DB_PREFIX."status_topic
			(
				topic_id,
				status_id
			) 
			VALUES";
			foreach ($topicid as $value)
			{
				$sql.= "(".$value.",".$status_id."),";
			}
			$sql = rtrim($sql,",");
			$this->db->query($sql);
		}
	}
	
	/**
	* 点滴插入一条记录
	* @param $info array 传入值
	* @param $type 插入记录的类型  (0 : 发送一条新点滴  1 ：转发点滴)  
	* @return $statusinfo  array 点滴信息
	*/
	private function insert($info , $type=0)
	{
		
		$media_type = urldecode($this->input['type']);		
		$pic_id = $this->input['pic_id'] ? $this->input['pic_id'] : 0;
		$ip = $this->input['ip'] ? $this->input['ip'] : hg_getip();
		$create_at = $this->input['time'] ? $this->input['time'] : TIMENOW;
		
		$info['ip'] = $ip;		
		$info['create_at'] = $create_at;			
		$location = $this->mUser->get_location();
		if($location)
		{
			$lat = $location[0]['lat'];
			$lng = $location[0]['lng'];
		}
		$type_arr = array('pic'=>"",'video'=>"");
		str_replace("0","",$media_type, $pic);
		str_replace("1","",$media_type, $video);			
		$sql = "INSERT ".DB_PREFIX."status(
					member_id,
					text,
					pic,
					video,
					create_at,
					ip,
					location,
					source,
					status,
					bans,
					lat,
					lng
					)
					VALUES(
					".$info['member_id'].",
					'".$info['text']."',
					".($pic?1:0).",
					".($video?1:0).",
					".$info['create_at'].",
					'".$info['ip']."',
					'".$info['location']."',
					'".$info['source']."',
					'".$info['status']."',
					'".$info['bans']."',
					'" . $lat . "',
					'" . $lng . "'					
					)
					";
		
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = "INSERT ".DB_PREFIX."status_extra(
			status_id
		)
		values
		(
			".$id."
		)";
				
		$this->db->query($sql);
		$statusinfo = array(
			"id" => $id,
			"member_id" => $info['member_id'],
			"text" => $info['text'],
			"status" => $info['status'],
			"reply_status_id" => 0,
			"reply_user_id" => 0,
			"source" => $info['source'],
			"create_at" => $info['create_at'],
			"ip" => $info['ip'],
			"location" => $info['location'],
			"transmit_count" => 0,
			"reply_count" => 0,
			"comment_count" =>0,
			"lat" => $lat,
			"lng" => $lng
		);
		$this->ConnectQueue();	
		$v = $info['member_id'] . "," . $id;
		$this->queue->set(STATUS_QUEUE , $v); //进入队列
		
		/**
		 * 添加发送微博积分
		 */
		$this->mUser->add_credit_log(SENT_STATUS);
							
		return $statusinfo;
	}
	
	/**
	 * 获取图片的路径
	 */
	
	
	/**
	* 验证内容，是否含有屏蔽词语，是否含有短网址
	* @param $text 传入值
	* @param $memberid
	* @param $source
	* @return $info array 
	*/
	private function verifyUrlBanword($text,$memberid,$source)
	{
		include_once(ROOT_DIR . 'lib/class/banword.class.php');
		$banword = new banword();
		$status = 0;
		$banwords = $banword->banword(urlencode($text));

		if($banwords && $banwords != 'null') //暂时先定义为没关键词
		{
		 	$status = 1;	
			$banwords = implode(',', $banwords);
		}
		else
		{
			$banwords = '';
		}
		include_once(ROOT_DIR . 'lib/class/shorturl.class.php');
		$shorturl = new shorturl();
		$text = $shorturl->shorturl($text);
		/*
		 * 在这里对是否含有媒体信息进行判断
		 * */	
		$info = array(
			'member_id' => $memberid,
			'text' => $text,
			'location' => '地址',	
			'status' =>	$status,
			'source' => $source,			
			'bans' => $banwords,			
		);	
		return $info;			
	}

	
	/**
	 * 获取微博数目
	 */
	public function get_num()
	{
		$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "status";
		$r = $this->db->query_first($sql);
		
		echo $r['nums'];
	}
	
	/**
	 * 
	 * 添加同步关系
	 */
	public function add_syn_relation()
	{
		$id = $this->input['status_id'];
		$syn_id = $this->input['syn_id'];
		$type = $this->input['type'];
		$status_id = intval($id);
		$type = intval($type);
		$sql = "INSERT INTO " . DB_PREFIX . "member_syn_relation SET status_id = " . $status_id . " , syn_id = '" . $syn_id . "', type = " . $type;
		$this->db->query($sql);	
	}
}
$out = new updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>