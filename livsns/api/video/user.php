<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class userApi extends adminBase
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();
		$user = urldecode($_SERVER['PHP_AUTH_USER'] ? $_SERVER['PHP_AUTH_USER'] : hg_get_cookie('user'));
		$pass = urldecode($_SERVER['PHP_AUTH_PW'] ? $_SERVER['PHP_AUTH_PW'] : hg_get_cookie('pass'));
		$this->setUser($user, $pass);
	}

	function __destruct()
	{
		parent::__destruct();
	}
	

	public function setUser($user, $pass)
	{
		$this->mUserName = $user;
		$this->mPassword = $pass;
	}
	
	/**
	* 验证是否登录
	* @return array 返回用户名和密码 和权限
	*/
	public function verifyCredentials()
	{
		if ($this->input['innerTransKey'] == INNERTRANSKEY)
		{
			$this->setXmlNode('userinfo','user');
			$this->addItem(array('login' => true));
			$this->output();
		}
		if (!$this->mUserName || !$this->mPassword )
		{
			$this->errorOutput(FAILED);
		}

		$sql = "SELECT 
				id,
				username,
				avatar,
				register_time,
				group_id,
				email
				FROM " . DB_PREFIX . "user u 
				LEFT JOIN ".DB_PREFIX."user_extra e
				ON u.id = e.user_id 
				WHERE username = '{$this->mUserName}' AND password='{$this->mPassword}'";

		$r = $this->db->query_first($sql);
					
		if (!$r)
		{
			$this -> errorOutput(LOGIN_FAILED);//返回0x4000代码
		}
		else
		{
			if(strlen($r['avatar']) > 32)//qq同步的用户头像
			{
				$r['large_avatar']= hg_avatar($r['id'],"100",$r['avatar'],0);
				$r['middle_avatar']= hg_avatar($r['id'],"50",$r['avatar'],0);
				$r['small_avatar'] = hg_avatar($r['id'],"10",$r['avatar'],0);
			}
			else 
			{
				$r['larger_avatar'] = hg_avatar($r['id'],"larger",$r['avatar']);
				$r['middle_avatar'] = hg_avatar($r['id'],"middle",$r['avatar']);
				$r['small_avatar'] = hg_avatar($r['id'],"small",$r['avatar']);
			}

			if($r['group_id']==1)
			{
				$r['is_admin']=1;
			}
			else
			{
				$r['is_admin']=0;
			}
			$this->setXmlNode('userinfo','user');
			$this->addItem($r);
			$this->output();
		}
	}
	
	function show(){
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;

		$count = intval($this->input['count']?$this->input['count']:0);
		$page = intval($this->input['page']?$this->input['page']:0);
		$end = "";
		$offset = $page * $count;
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		
		$condition = $this->input['condition'] ? ' AND username LIKE "%' . urldecode($this->input['condition']) . '%" ' : '';
		
		$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "user WHERE 1 " . $condition;		
		$r = $this->db->query_first($sql);		
		$total_nums = $r['nums'];
		
				
		$sql = "SELECT id, username,avatar,register_time,email,ip,collect_count,comment_count 
		FROM ". DB_PREFIX ."user
		WHERE 1 " . $condition . "		
		ORDER BY collect_count DESC ,register_time DESC " . $end;
		$id = "";
		$space = " ";
				
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			if(strlen($array['avatar']) > 32)//qq同步的用户头像
			{
				$array['large_avatar']= hg_avatar($array['id'],"100",$array['avatar'],0);
				$array['middle_avatar']= hg_avatar($array['id'],"50",$array['avatar'],0);
				$array['small_avatar'] = hg_avatar($array['id'],"10",$array['avatar'],0);
			}
			else 
			{
				$array['larger_avatar'] = hg_avatar($array['id'],"larger",$array['avatar']);
				$array['middle_avatar'] = hg_avatar($array['id'],"middle",$array['avatar']);
				$array['small_avatar'] = hg_avatar($array['id'],"small",$array['avatar']);
			}

			$id .= $space.$array['id'];
			$space = ',';
			$info[] = $array;
		}
		
		$program = $this->getPrgramByUserid($id);
		if($info)
		{
			$sta_id = "";
			$space = "";
			foreach($info as $key => $value)
			{
				$info[$key]['program'] = hg_check_time($program[$value['id']]);
				if(is_array($program[$value['id']]))
				{
					$info[$key]['sta_id'] = $program[$value['id']][0]['sta_id'];					
				}
				else 
				{
					$info[$key]['sta_id'] = 0;
				}
				$sta_id .= $space.$info[$key]['sta_id'];
				$space = ",";
				
			}
			
			if($sta_id)
			{
				$re = $this->mVideo->get_concern_relevance($mInfo['id'],$sta_id);
				foreach ($info as $key => $value)
				{
					$info[$key]['relation'] = $re[$value['sta_id']]['relation'];
					$info[$key]['concern_id'] = $re[$value['sta_id']]['id'];
				}	
			}			
		}
		
		$info[] = $total_nums;
		
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();		
	}
	
	private function getPrgramByUserid($user_id){
		$program = array();
		if($user_id)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE user_id IN( ".$user_id.") ORDER BY end_time ASC";
			$query = $this->db->query($sql);
			while($array = $this->db->fetch_array($query))
			{
				$program[$array['user_id']][] = $array;
			}
		}
		return $program;
	}
	
	/**
	* 验证是否登录
	* @return array 返回用户名和密码 和权限
	*/
	function getUserById()
	{		
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		$mInfo = $this->mUser->verify_credentials();
		$user_id = $this->input['user_id']?$this->input['user_id']:1;
		if (!$user_id)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sql = "SELECT id, username,avatar,register_time,collect_count,comment_count FROM " . DB_PREFIX . "user WHERE id IN (".$user_id.")";
		$info = $this->db->query_first($sql);
		
		if(strlen($info['avatar']) > 32)//qq同步的用户头像
		{
			$info['large_avatar']= hg_avatar($info['id'],"100",$info['avatar'],0);
			$info['middle_avatar']= hg_avatar($info['id'],"50",$info['avatar'],0);
			$info['small_avatar'] = hg_avatar($info['id'],"10",$info['avatar'],0);
		}
		else 
		{
			$info['larger_avatar'] = hg_avatar($info['id'],"larger",$info['avatar']);
			$info['middle_avatar'] = hg_avatar($info['id'],"middle",$info['avatar']);
			$info['small_avatar'] = hg_avatar($info['id'],"small",$info['avatar']);
		}
		
		$re = $this->mVideo->get_collect_relevance($mInfo['id'],$user_id,2);
		$info['relation'] = $re[$user_id]['relation'];
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}
	

	/**
	 * 修改用户的收藏数目
	 * @param $video_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function favorite_count()
	{
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		$mInfo = $this->mUser->verify_credentials();
		$user_id = $this->input['user_id']? $this->input['user_id']:1;
		$type = $this->input['type']? $this->input['type']:1;//默认增加
		
		if(!$mInfo&&!$user_id)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
		
		$sql = "UPDATE " . DB_PREFIX . "user SET collect_count=";
		if($type)
		{
			$sql .="collect_count+1";
		}
		else 
		{
			$sql .="collect_count-1";
		}
		
		$sql .= " WHERE id = ".$user_id;
		$this->db->query($sql);
		$this->setXmlNode('user' , 'info');
		$this->addItem($user_id);
		$this->output();
	}
	
}

$out = new userApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>